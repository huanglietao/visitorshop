<?php
/**
 * 工厂、erp相关的逻辑
 *
 * erp对商品或渠道的相关配置信息
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/6/1
 */

namespace App\Services;


use App\Exceptions\CommonException;
use App\Models\DmsAgentInfo;
use App\Models\SaasAreas;
use App\Models\SaasCategory;
use App\Models\SaasCompoundService;
use App\Models\SaasExpress;
use App\Models\SaasOrderErpPushQueue;
use App\Models\SaasOrderLog;
use App\Models\SaasOrderProducts;
use App\Models\SaasOrders;
use App\Models\SaasProducts;
use App\Models\SaasProductsSku;
use App\Models\SaasSpDownloadQueue;
use App\Repositories\DmsAgentInfoRepository;
use App\Repositories\OmsMerchantInfoRepository;
use App\Repositories\SaasCategoryRepository;
use App\Repositories\SaasProductsRepository;
use App\Services\Common\Mongo;
use App\Services\Outer\Erp\Api;

class Factory
{
    protected $repoAgent; //分销仓库
    protected $repoMch;   //oms商户仓库
    protected $coverFlag = '_cover';
    public function __construct(DmsAgentInfoRepository $agent, OmsMerchantInfoRepository $merchant, SaasOrderLog $orderLog)
    {
        $this->repoAgent = $agent;
        $this->repoMch   = $merchant;
        $this->orderLog   = $orderLog;
    }
    /**
     * @param int $agentId 分销商id,如果为0,mchId必须。取商户配置
     * @param int $mchId
     * @return string
     */
    public function getErpName($agentId = 0, $mchId = 0)
    {

        $erpName = '';
        if (!empty($agentId)) {
            $info = $this->repoAgent->getRow(['agent_info_id' => $agentId], ['erp_name', 'mch_id']);
            if (empty($info)) { //如果不存在分销抛出错误
                Helper::EasyThrowException('11001',__FILE__.__LINE__);
            } else {
                $mchId = $info['mch_id'];
            }
        } else {
            if (empty($mchId)) {
                Helper::EasyThrowException('20030',__FILE__.__LINE__);
            }
        }

        //如果分销商未设置取商户的
        if (empty($info['erp_name'])) {
            $info = $this->repoMch->getRow(['mch_id' => $mchId], ['erp_name']);
        }

        $erpName = $info['erp_name'];
        return $erpName;
    }

    /**
     * 判断url是否是封面
     */
    public function urlIsCover()
    {

    }

    /**
     * @param $goodsId
     * @return bool|mixed
     */
    public function getGoodsCompoundSetting($goodsId)
    {
        $mongo = new Mongo();
        $info = $mongo->select('compound_setting', ['goods_id' => strval($goodsId)]);

        if(!isset($info[0]['info'])){
            return false;
        }

        $setting = $info[0]['info'];
        return json_decode($setting,true);
    }

    public function getGoodsOutputType($goodId)
    {
        $setting = $this->getGoodsCompoundSetting($goodId);
        if (empty($setting))
            return 'pdf';
        return strtolower($setting['outputFileType']);
    }

    /**
     *  通过商品获取稿件路径规则
     * @param $goodsId
     * @return array
     */
    public function getDirNameRule($goodsId)
    {
        return $this->getCommonDirRule();
        $mongo = new Mongo();
        $info = $mongo->select('compound_setting', ['goods_id' => strval($goodsId)]);

        if(!isset($info[0]['info'])){
            return $this->getCommonDirRule();
        }
        $setting = $info[0]['info'];
        $dir = urldecode(json_decode($setting,true)['outputPath']);
        return $dir;
    }

    /**
     * @param $goodsId
     * @param $info
     * @return string
     */
    public function generateDirName($goodsId,$info)
    {
        $goodsInfo = app(SaasProductsRepository::class)->getRow(['prod_id' => $goodsId], ['prod_cate_uid']);
        if (empty($goodsInfo)) {
            Helper::EasyThrowException('40014',__FILE__.__LINE__);
        }

        $arrSearch = [
            "{erp_sp_name}",
            "{date}",
            "{factory_pro_name}" ,
            '{project_sn}',
        ];

        $arrReplace = [
            $info['erp_name'],date('Y-m-d'),$info['factory_code'], $info['project_sn']
        ];
        $rule = $this->getDirNameRule($goodsId);
        $dir = str_replace($arrSearch,$arrReplace, $rule);

        $online_create_dir = config('order.online_create_dir');
        $pos = strrpos($dir, $online_create_dir);
        if (!empty($pos)) {
            $len = strlen($online_create_dir);
            $dir = substr($dir,$pos+$len+1);
        }

        $dir = str_replace("\\", "/",$dir);
        return $dir;
    }
    /**
     * 通过商品获取稿件规则
     * @param $goodsId
     * @return array
     */
    public function getFileNameRule($goodsId)
    {
        $mongo = new Mongo();
        $info = $mongo->select('compound_setting', ['goods_id' => strval($goodsId)]);

        if(!isset($info[0]['info'])){
            return $this->getCommonFileNameRule();
        }
        $setting = $info[0]['info'];
        return urldecode(json_decode($setting,true)['outputFileName']);
    }

    /**
     * @param $goodsId
     * @param $info  erp_name erp商户名称 order_no 订单号 factory_code 工厂代码 project_sn 项目号   quantity 商品购买数量 page_count 冲印页数
     * @param boolean $isEntity 是否是实物
     * @param boolean $isCover 是否为封面
     * @return string
     */
    public function generateFileName($goodsId, $info,$isCover = false,$isEntity = false)
    {
        if (empty($info['erp_name']) || empty($info['order_no']) || empty($info['factory_code'])|| empty($info['project_sn'])) {
            Helper::EasyThrowException('10023',__FILE__.__LINE__);
        }

        $goodsInfo = app(SaasProductsRepository::class)->getRow(['prod_id' => $goodsId], ['prod_cate_uid']);
        if (empty($goodsInfo)) {
            Helper::EasyThrowException('40014',__FILE__.__LINE__);
        }
        $cateId = $goodsInfo['prod_cate_uid'];
        $cateInfo = app(SaasCategoryRepository::class)->getRow(['cate_id' => $goodsInfo['prod_cate_uid']], ['cate_flag']);

        //实物流程
        if ($cateInfo['cate_flag'] == GOODS_MAIN_CATEGORY_ENTITY || $isEntity) {
            $rule = $this->getEntityRule();
        } else {
            $rule = $this->getFileNameRule($goodsId);

            if (empty($rule)) {
                $rule = $this->getCommonFileNameRule();
            }

        }
        $projectSn = str_replace('-','_',$info['project_sn']);
        $tmp = explode('-', $info['project_sn']);
        $arrSearch = [
            "{erp_sp_name}",
            "{factory_pro_name}" ,
            "{order_id}",
            '{project_sn}',
            '{project_count}',
            '{quantity}',
            '{total_page_count}'
        ];
        $pageCount = $info['page_count']??1;
        $quantity = $info['quantity']?? 1;
        $arrReplace = [
            $info['erp_name'],$info['factory_code'],$info['order_no'], $projectSn,$tmp[1],$quantity,$pageCount,
        ];
        $fileName = str_replace($arrSearch,$arrReplace, $rule);
        if ($isCover) {
            $fileName.=$this->coverFlag;
        }
        return $fileName;
    }


    /**
     * 通用规则
     * @return string
     */
    public function getCommonFileNameRule()
    {
        return '{erp_sp_name}-{factory_pro_name}-[{order_id}]{{project_sn}}-{project_count}X{quantity}w';
    }

    public function getEntityRule()
    {
        return '{erp_sp_name}-{factory_pro_name}-[{order_id}]{{project_sn}_{quantity}}-{project_count}X{quantity}w';
    }

    public function getCommonDirRule()
    {
        return "{erp_sp_name}/{date}/{factory_pro_name}/";
    }

    public function runReadyPushErpOrderQueue()
    {
        //获取每次跑队列条数
        $limit = config("common.queue_limit.ready_push_erp_order_queue");
        $pushModel = app(SaasOrderErpPushQueue::class);
        //获取已准备队列
        $orderArr = $pushModel
            ->where(['order_push_status' => 'ready'])
            ->limit($limit)
            ->get()
            ->toArray();
        //将队列状态转化为进行中
        foreach ($orderArr as $k => $v){
            $pushModel->where(['order_erp_push_id' => $v['order_erp_push_id']])->update(['order_push_status' => 'progress']);
        }

        $this->runPushErpOrderQueue($orderArr);
    }
    //对接工厂的推送队列
    public function runPushErpOrderQueue($orderArr)
    {
        $pushModel = app(SaasOrderErpPushQueue::class);
        $downModel = app(SaasSpDownloadQueue::class);
        $orderProductsModel = app(SaasOrderProducts::class);
        $skuModel = app(SaasProductsSku::class);
        $productsModel = app(SaasProducts::class);
        $categoryModel = app(SaasCategory::class);
        $helper = app(Helper::class);

        foreach ($orderArr as $ko => $vo)
        {
            $pushInfo = $orderProductsModel
                ->with(['spDownload'])
                ->where('ord_id',$vo['order_id'])
                ->select('ord_prod_id','prod_num','ord_id','ord_prj_item_no','sku_id','prod_id','order_no','created_at','delivery_id')
                ->get()
                ->toArray();
            $post_data = [];
            $isMask = 0;//判断是否为口罩订单标识
            $orderItemCount = 0;
            $times = ++$vo['times'];
            $error = [];

            foreach ($pushInfo as $k=>$v)
            {
                //判断是否是口罩订单，是口罩订单则跑另一套逻辑
                $prodCate = $productsModel->where('prod_id',$v['prod_id'])->value('prod_cate_uid');
                /*$categorySign = $categoryModel->where('cate_id',$prodCate)->value('cate_flag');*/
                if ($prodCate == MASK_CATEGORY){
                    $isMask = 1;
                    //口罩订单
                    try{
                        //先判断订单是否都是口罩订单，如果是混合订单则不推送
                       $res = $this->checkIsMaskOrder($pushInfo);
                       if ($res){
                         $maskRes = $this->pushMaskOrder($v,$vo);
                         //只有
                           if($maskRes['code'] == '200' || $maskRes['code'] == 1) {  //成功
                               ++$orderItemCount;
                           } else {
                               $error[] =  $maskRes['message'];
                           }
                       }
                    }catch (CommonException $e){
                        $error[] =  $e->getMessage();
                    }
                    continue;
                }

                $post_data['file_lines'][$k]['serial_number'] = str_replace('-', '_',$v['ord_prj_item_no'])."_".$v['prod_num'];
                $product_name = $skuModel->where(['prod_sku_id' => $v['sku_id']])->value('prod_supplier_sn');
                //定义所需返回的字段
                $inner_file_name = '';   //内页文件名
                $inener_down_url = '';  //内页下载地址
                $cover_file_name = '';  //封面文件名
                $cover_down_url = '';   //封面下载地址
                $file_md5 = '';     //内页md5值
                $file_md5_cover = '';  //封面md5值
                foreach ($v['sp_download'] as $kk=>$vv) {
                    $servModel = app(SaasCompoundService::class);
                    $down_ip = $servModel->where(['comp_serv_id' => $vv['service_id']])->value('public_ip');

                    if($vv['filetype'] == 1 || $vv['filetype'] == 2) {  //封面/封底
                        //判断文件名是否含有http
                        if (strstr($vv['filename'], 'http') || strstr($vv['filename'], 'https'))
                        {
                            $cover_down_url = $vv['filename'];
                            $cover_file_name_str = explode('/', $vv['filename']);
                            $cover_file_name = end($cover_file_name_str);
                        }else{
                           /* $cover_down_url = 'http://'.$down_ip.'/'.$vv['path'].'/'.$vv['filename'];*/
                            $cover_down_url = $helper->getRealUrl($vv['path'].'/'.$vv['filename'],'http://'.$down_ip);
                            $cover_file_name = $vv['filename'];

                        }
                        $file_md5 = md5('123456');
                    }

                    if($vv['filetype'] == 3) {  //内页
                        //判断文件名是否含有http
                        if (strstr($vv['filename'], 'http') || strstr($vv['filename'], 'https'))
                        {
                            $inener_down_url = $vv['filename'];
                            $inner_file_name_str = explode('/', $vv['filename']);
                            $inner_file_name = end($inner_file_name_str);
                        }else{
                            /*$inener_down_url = 'http://'.$down_ip.'/'.$vv['path'].'/'.$vv['filename'];*/
                            $inener_down_url = $helper->getRealUrl($vv['path'].'/'.$vv['filename'],'http://'.$down_ip);
                            $inner_file_name = $vv['filename'];

                        }
                        $file_md5 = md5('123456');
                    }

                }
                $post_data['file_lines'][$k]['file_path'] =$inner_file_name;
                $post_data['file_lines'][$k]['file_down_url'] = $inener_down_url;
                $post_data['file_lines'][$k]['file_path_cover'] = $cover_file_name;
                $post_data['file_lines'][$k]['file_down_url_cover'] = $cover_down_url;
                $post_data['file_lines'][$k]['file_md5'] = $file_md5;
                $post_data['file_lines'][$k]['file_md5_cover'] = $file_md5_cover;
                $post_data['file_lines'][$k]['product_name'] = $product_name;
            }
            if (empty($isMask)){
                //不为口罩订单
                try{
                    \DB::beginTransaction();
                    $pushModel->where(['order_erp_push_id' => $vo['order_erp_push_id']])->update(['start_time' => time()]);


                    $post_data['file_lines'] = json_encode(array_values($post_data['file_lines']));
                    $res_arr = new Api();
                    file_put_contents('/tmp/erp_push_order_other.log',var_export($post_data,true),FILE_APPEND);
                    $arr_ret  = $res_arr->request(config('erp.interface_url').config('erp.push_erp_order'),$post_data);
                    file_put_contents('/tmp/erp_push_order_other.log',var_export($arr_ret,true),FILE_APPEND);
                    //如果成功，则标识订单推送状态为已推送

                    if($arr_ret['code'] == '200' || $arr_ret['code'] == '1' ) { //反馈成功
                        $pushModel->where(['order_erp_push_id' => $vo['order_erp_push_id']])->update(['times' => $times,'end_time' => time(),'order_push_status' => 'finish']);

                        //记录订单日志
                        $this->insertOrderLog($vo['order_id'],true);
                        \DB::commit();
                    } else {  //反馈失败
                        //记录失败日志
                        $pushModel->where(['order_erp_push_id' => $vo['order_erp_push_id']])->update(['times' => $times,'end_time' => time(),'order_push_status' => 'error','err_msg'=>$arr_ret['message']]);

                        //记录订单日志
                        $this->insertOrderLog($vo['order_id'],false);
                        \DB::commit();
                    }
                } catch (\Exception $exception) {
                    \DB::rollBack();
                    //记录订单日志
                    $this->insertOrderLog($vo['order_id'],false);

                    $pushModel->where(['order_erp_push_id' => $vo['order_erp_push_id']])->update(['err_msg'=>$exception->getMessage()]);
                }
            }else{
                //口罩订单
                $count = $orderProductsModel->where('ord_id',$vo['order_id'])->count();
                //判断是否全部推送成功
                if ($orderItemCount == $count){
                    //推送成功
                    $pushModel->where(['order_erp_push_id' => $vo['order_erp_push_id']])->update(['times' => $times,'end_time' => time(),'order_push_status' => 'finish']);

                    //记录订单日志
                    $this->insertOrderLog($vo['order_id'],true);
                }else{
                    //推送失败
                    $error_msg = implode(',',$error);
                    $pushModel->where(['order_erp_push_id' => $vo['order_erp_push_id']])->update(['times' => $times,'end_time' => time(),'order_push_status' => 'error','err_msg'=>$error_msg]);

                    //记录订单日志
                    $this->insertOrderLog($vo['order_id'],false);
                }
            }



        }
    }

    //推送工厂口罩订单处理逻辑
    public function pushMaskOrder($orderItem)
    {
        $orderModel = app(SaasOrders::class);
        $skuModel = app(SaasProductsSku::class);
        $expressModel = app(SaasExpress::class);
        $areasModel = app(SaasAreas::class);
        $api = app(Api::class);
        $orderProductModel = app(SaasOrderProducts::class);
        $pushModel = app(SaasOrderErpPushQueue::class);
        $omsMerchantRepository = app(OmsMerchantInfoRepository::class);


        //获取订单信息，准备组织数据
        $orderInfo = $orderModel->where('order_no',$orderItem['order_no'])->get()->toArray();
        if (!empty($orderInfo)){
            //获取产品名称
            $product_name = $skuModel->where('prod_sku_id',$orderItem['sku_id'])->value('prod_supplier_sn');
            //获取物流方式
            $delivery_short = $expressModel->where('express_id',$orderItem['delivery_id'])->value('express_code');
            //获取收货地址
            $province_name = $areasModel->where('area_id',$orderInfo[0]['order_rcv_province'])->value('area_name');
            $city_name     = $areasModel->where('area_id',$orderInfo[0]['order_rcv_city'])->value('area_name');
            $area_name = $areasModel->where('area_id',$orderInfo[0]['order_rcv_area'])->value('area_name');

            //组装接口所需要的数据
            $data['partner_number'] = $orderItem['order_no'];
            //判断是否需要集货
            $orderItemCount = $orderProductModel->where('order_no',$orderItem['order_no'])->count();
            if($orderItemCount > 1) {
                $data['is_collect'] = '1';
            } else {
                $data['is_collect'] = '0';
            }
            //获取客户名称
            $partner_real_name = $this->getErpName($orderInfo[0]['user_id'],$orderInfo[0]['mch_id']);
            //获取发件人信息
            $senderInfo = $omsMerchantRepository->getMerchantSender($orderInfo[0]['mch_id']);
            //获取
            $data['partner_real_name']        = $partner_real_name;
            $data['product_name']             = $product_name;
            $data['single_num']               = $orderItem['prod_num'];
            $data['assign_express_type']      = $delivery_short;
            $data['recipient_person']         = $orderInfo[0]['order_rcv_user'];
            $data['recipient_phone']          = $orderInfo[0]['order_rcv_phone'];
            $data['recipient_address']        = $province_name.' ' .$city_name.' ' .$area_name." ".$orderInfo[0]['order_rcv_address'];
            $data['sender_person']            = $senderInfo['mch_sender_person'];
            $data['sender_phone']             = $senderInfo['mch_sender_phone'];
            $data['sender_address']           = '';
            $data['note']                     = '';
            $data['is_hurry']                 = '0';
            $data['partner_order_date']       = date("Y-m-d H:i:s",$orderItem['created_at']);

            //请求接口

            $arr[] = $data;
            $post_data['trade_order_lines'] = json_encode($arr);
            $url = config('erp.interface_url').config('erp.trade_order');

            \DB::beginTransaction();
            file_put_contents('/tmp/erp_push_order_mask.log',var_export($post_data,true),FILE_APPEND);
            $resultApi  = $api->request($url,$post_data);
            file_put_contents('/tmp/erp_push_order_mask.log',var_export($resultApi,true),FILE_APPEND);
            \DB::commit();
            return $resultApi;

        }else{
            Helper::EasyThrowException(24001,__FILE__.__LINE__);
        }

    }

    //检查订单是否都为口罩订单
    public function checkIsMaskOrder($pushInfo)
    {
        $productsModel = app(SaasProducts::class);
        $isMask = 1;//判断口罩订单标识
        foreach ($pushInfo as $k => $v){
            $prodCate = $productsModel->where('prod_id',$v['prod_id'])->value('prod_cate_uid');
            if ($prodCate != MASK_CATEGORY){
                $isMask = 0;
                break;
            }
        }
        if (empty($isMask)){
            //混合订单，不走推送
            Helper::EasyThrowException(24002,__FILE__.__LINE__);
            return false;
        }else{
            return true;
        }
    }

    //推送工厂队列的订单操作日志
    public function insertOrderLog($ord_id,$success)
    {
        if($success){
            $note = '订单推送工厂成功';
        }else{
            $note = '订单推送工厂失败，请及时反馈处理';
        }
        $ord_log_data = [
            'ord_id'        =>      $ord_id,
            'operater'      =>      'admin',
            'platform'      =>      config('common.sys_abbreviation')['backend'],
            'action'        =>      '推送工厂',
            'note'          =>      $note,
        ];
        $this->orderLog->create($ord_log_data);
    }
}