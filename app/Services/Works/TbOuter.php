<?php
namespace App\Services\Works;

use App\Exceptions\CommonException;
use App\Models\SaasTbDiyImage;
use App\Models\SaasTbOrderPicQueue;
use App\Repositories\SaasDiyAssistantRepository;
use App\Repositories\SaasInnerTemplatesPagesRepository;
use App\Repositories\SaasMainTemplatesPagesRepository;
use App\Repositories\SaasMainTemplatesRepository;
use App\Repositories\SaasProductsPrintRepository;
use App\Repositories\SaasProductsRepository;
use App\Repositories\SaasProductsSkuRepository;
use App\Services\Helper;
use App\Services\Outer\CommonApi;

/**
 *  天猫/淘宝外部定制图片处理
 *
 * 功能详细说明
 * @author: hlt <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/5/28
 */

class TbOuter
{
    //获取准备生成作品的图片列表
    public function runCreateWorksPic()
    {
        //获取每次跑队列条数
        $limit = config("common.queue_limit.create_works_pic_queue");
        $picQueueModel = app(SaasTbOrderPicQueue::class);
        $picQueueList = $picQueueModel->where(['status' => PIC_QUEUE_STATUS_DOWNLOAD,'queue_status' => 'finish','work_queue_status'=>'ready'])->limit($limit)->get()->toArray();
        $imageModel = app(SaasTbDiyImage::class);
        //将队列状态变为进行中
        foreach ($picQueueList as $k => $v)
        {
            $picQueueModel->where('pic_que_id',$v['pic_que_id'])->update(['work_queue_status' => 'progress']);
        }
        foreach ($picQueueList as $k => $v)
        {
            \DB::beginTransaction();
            try{
                $newTime = ++$v['times'];

                //获取图片列表
                $imageList = $imageModel->where(['tb_order_no'=>$v['tid'],'is_used'=>PUBLIC_NO])->get()->toArray();
                if (empty($imageList)){
                    //图片列表为空，直接更新图片队列表状态
                    $picQueueModel->where('pic_que_id',$v['pic_que_id'])->update(['work_queue_status' => 'error','times' => $newTime,'updated_at'=>time()]);
                    \DB::commit();
                    continue;
                }
                $arr_img = [];

                foreach ($imageList as $kk => $vv)
                {
                    //以子订单号区别不同作品
                    $orderItemNo = $vv['tb_item_order_no'];
                    $arr_img[$orderItemNo][] = $vv;
                }
                //判断作品是否成功创建
                $finish = 1;
                $work_num = count($arr_img);
                $num = 0;

                //循环图片列表创建作品
                foreach ($arr_img as $ko => $vo)
                {
                    ++$num;
                    try{
                        $res = $this->createWorks($vo,$v['tid'],$vo[0]['sku_id'],$v['agent_id']);
                        if ($res){
                            if ($num == $work_num && $finish == 1){
                                //创建成功，回写队列状态
                                $picQueueModel->where('pic_que_id',$v['pic_que_id'])->update(['work_queue_status' => 'finish','times' => $newTime,'updated_at'=>time(),'status' => PIC_QUEUE_STATUS_WORK,'works_created_at' => time()]);
                                continue;
                            }else{
                                continue;
                            }

                        }
                    }catch (CommonException $exception){
                        //创建作品失败,回写队列状态
                        $finish = 0;
                        $picQueueModel->where('pic_que_id',$v['pic_que_id'])->update(['work_queue_status' => 'error','times' => $newTime,'updated_at'=>time()]);
                        continue;

                    }

                }
                \DB::commit();
            } catch (\Exception $e) {
                \DB::rollBack();
                var_dump($e->getMessage());
            }
        }
        return true;
    }

    /**
     * @param $imageList 图片
     * @param $tbOrderNo 第三方平台订单号(淘宝/天猫/京东)
     * @param $skuId 货品id
     * @param $agentId 分销id
     * @return bool
     */
    public function createWorks($imageList,$tbOrderNo,$skuId,$agentId)
    {
        //通过sku获取商品规格
        $skuInfo = app(SaasProductsSkuRepository::class)->getById($skuId);
        $prodId = $skuInfo['prod_id'];

        //商品信息
        $prod_info = app(SaasProductsRepository::class)->getById($prodId);

        //获取规格id
        $printInfo = app(SaasProductsPrintRepository::class)->getRow(['prod_id' => $prodId]);

        $sizeId = $printInfo['prod_size_id'] ?? 0;
        if (empty ($sizeId)) {
            Helper::EasyThrowException('40015', __FILE__.__LINE__);
        }
        //获取模板
        $tempInfo = app(SaasMainTemplatesRepository::class)->getRow(['specifications_id' => $sizeId,'main_temp_check_status' => TEMPLATE_STATUS_VERIFYED], ['main_temp_id']);

        if (empty($tempInfo)) {
            Helper::EasyThrowException('50001', __FILE__.__LINE__);
        }
        //模板子页舞台
        $tempChildInfo = app(SaasMainTemplatesPagesRepository::class)->getRow(['main_temp_page_tid' => $tempInfo['main_temp_id'], 'main_temp_page_type' => GOODS_SIZE_TYPE_INNER], ['main_temp_page_id','main_temp_page_stage']);
        if (empty($tempChildInfo['main_temp_page_stage'])) {
            Helper::EasyThrowException('50002', __FILE__.__LINE__);
        }
        $arrStage = json_decode($tempChildInfo['main_temp_page_stage'], true);

        //淘宝订单数据
        $diyAssistantRepository = app(SaasDiyAssistantRepository::class);
        $info = $diyAssistantRepository->getOrderCacheData($tbOrderNo,$agentId);
        $tb_order_info = json_decode($info[0]['order_info'],true); //淘宝订单信息
        $tb_order_item_info = $tb_order_info['result']['trade']['orders']['order']; //淘宝子订单项

        if(isset($tb_order_info['code'])){
            //该订单记录不存在
            Helper::EasyThrowException('70030', __FILE__.__LINE__);
        }

        //淘宝子订单号
        $tb_item_order_no = $imageList[0]['tb_item_order_no'];

        //匹配子订单号,获取购买数量
        $num = 1;
        foreach ($tb_order_item_info as $k=>$v){
            if($v['oid'] == $tb_item_order_no){
                $num = $v['num'];
            }
        }
        $work_extra_data = [
            'user_name'     =>  $tb_order_info['result']['trade']['buyer_nick'], //旺旺昵称
            'order_id'      =>  $tbOrderNo, //淘宝订单号
            'buy_quantity'  =>  $num, //购买数量
        ];


        //创建作品api所需参数
        $work_api_data = $this->worksApiData($imageList, $arrStage, $tempChildInfo['main_temp_page_id']);

        //请求创建作品接口
        $apiService = app(CommonApi::class);
        $work_id = $apiService->request(env('API_URL').'/pcdiy/work/get-work-id',[],'POST');
        $wid = $work_id['result'][0]['wid'];

        if($work_id['success'] != "true"){
            //作品记录创建失败
            Helper::EasyThrowException('60042', __FILE__.__LINE__);
        }

        //组装保存作品数据
        $work_data = [
            'wid'                   =>  $wid, //作品id
            'uid'                   =>  $agentId,
            'agent_id'              =>  $agentId,
            'sp_id'                 =>  $prod_info['mch_id'], //商户id
            'tsid'                  =>  $tempInfo['main_temp_id'], //模板id
            'pid'                   =>  $skuInfo['prod_sku_id'], //货品id
            'work_name'             =>  $prod_info['prod_name'].'-'.$wid,   //商品名称+作品id
            'work_extra_info'       =>  json_encode($work_extra_data),
            'mask_total_count'      =>  count($work_api_data),
            'pages'                 =>  json_encode($work_api_data),
            'is_submit'             =>  0,
            'sub_system'            =>  'agent',
            'source'                =>  '',
            'sys_version'           =>  '',
            'is_mobile'             =>  '',
            'flag'                  =>  '',
        ];

        //请求作品保存接口
        $result = $apiService->request(env('API_URL').'/pcdiy/work/save-work-data',$work_data,'POST');

        if($result['success'] == 'true'){
            //作品创建成功更新diy_image图片状态,作品id
            app(SaasTbDiyImage::class)->where('tb_item_order_no',$tb_item_order_no)->update(['is_used'=>PUBLIC_YES,'prj_id'=>$wid]);
        }else{
            $code = isset($result['err_code']) ? $result['err_code'] : 60043;
            Helper::EasyThrowException($code,__FILE__.__LINE__);
        }

        return true;
    }

    /**
     * 创建作品api所需参数
     * @param $imageList 图片列表
     * @param $arrStage  模板舞台
     * @param $tid 模板id
     */
    public function worksApiData($imageList, $arrStage, $tid)
    {
        $pages = [];
        $j = 0;
        foreach ($imageList as $k=>$v) {
            for ($i = 0; $i< $v['print_nums']; $i++) {
                if ($v['print_nums'] > 1) {
                    $hz = $i+1;
                } else {
                    $hz = '';
                }

                $pages[$j]['id'] = 0;
                $pages[$j]['name'] = ($k+1).'-'.$hz;
                $pages[$j]['pid']  = 'PAGE_'.Helper::generateNo();
                $pages[$j]['spread'] = 0;
                $pages[$j]['type'] = 1;
                $pages[$j]['fid'] = 1;
                $pages[$j]['tid'] = $tid;
                $pages[$j]['index'] = $j;
                $pages[$j]['mask_count'] = 1;
                $pages[$j]['stage_content'] = json_encode($this->createStage($v,$arrStage));
                $j++;
            }
        }
        return $pages;
    }

    /**
     * 创建各页舞台
     * @param $imgInfo 图片信息
     * @param $arrStage 舞台信息
     */
    public function createStage($imgInfo,$arrStage)
    {
        $stage[] = $this->getPos($imgInfo,$arrStage[0]);
        return $stage;
    }

    /**
     * 获取图片在画框中的位置 x y phoX phoY
     * @param $imgInfo
     * @param $stage
     */
    public function getPos($imgInfo, $stage)
    {
        $imgScale = $imgInfo['origwidth']/$imgInfo['origheight']; //图片宽高比例
        $frameScale = $stage['width']/$stage['height']; //相框宽高比例

        if($imgScale < $frameScale){
            //图片等比例压缩
            $new_height =$stage['height'];
            $new_width = $new_height*$imgScale;

            //居中留白
            $ret_x = 0-(($new_width-$stage['width'])/2);
            $ret_y = 0;
            $ret_width = $new_width;
            $ret_height = $new_height;

        }else{
            $new_width =$stage['width'];
            $new_height = $new_width/$imgScale;

            //居中留白
            $ret_y = 0-(($new_height-$stage['height'])/2);
            $ret_x = 0;
            $ret_width = $new_width;
            $ret_height = $new_height;
        }

        $stage['x'] = $ret_x;
        $stage['y'] = $ret_y;
        $stage['phoX'] = $ret_width;
        $stage['phoY'] = $ret_height;

        return $stage;
    }
}