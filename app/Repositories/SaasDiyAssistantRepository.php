<?php
namespace App\Repositories;
use App\Http\Controllers\Api\Editor\WorksController;
use App\Models\DmsAgentInfo;
use App\Models\SaasCategory;
use App\Models\SaasDiyAssistant;
use App\Models\SaasProducts;
use App\Models\SaasProductsSku;
use App\Models\SaasProjectsOrderTemp;
use App\Services\Helper;
use App\Services\Works\Sync;

/**
 * 仓库模板
 * diy在线助手仓库
 * @author: hlt
 * @version: 1.0
 * @date: 2020/5/27
 */
class SaasDiyAssistantRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存

    public function __construct(SaasDiyAssistant $model)
    {
        $this->model =$model;
    }

    /**
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getTableList($where=null, $order=null)
    {
        //处理状态值为0的情况把转化成int
        if(empty($where['cms_adm_status'])&&isset($where['cms_adm_status'])){
            $where['cms_adm_status'] = intval($where['cms_adm_status']);
        }
        //时间转时间戳
        if(!empty($where['created_at'])){
            $aa = Helper::getTimeRangedata($where['created_at']);
        }

        $limit = isset($where['limit']) ? $where['limit']:config('common.page_limit');  //这个10取配置里的
        $where = $this->parseWhere($where);

        //order 必须以 'id desc'这种方式传入.
        $orderBy = [];
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }
        $query = $this->model;
        if(!empty ($where)) {
            $query =  $query->where($where);
            if(!empty($where['created_at'])){
                $query = $query->whereBetween('created_at',[$aa['start'],$aa['end']]);
            }

        }

        if(!empty($order)) {
            $query =  $query->orderBy($orderBy[0],$orderBy[1]);
        }

        $list = $query->paginate($limit);
        return $list;
    }


    /**
     * 新增/修改
     * @param $data
     * @return boolean
     */
    public function save($data)
    {
        if($data['_token']){
            unset($data['_token']);
        }
       // dump($data);die;
        $salt = Helper::build();
        if(empty($data['id'])) {
            unset($data['id']);
            //$data['cms_adm_password'] = md5(md5($data['cms_adm_password']).$salt); //密码加密规定原则
            $data['cms_adm_password'] = $this->setPassword($data['cms_adm_password'],$salt);
            $data = $data+['cms_adm_salt'=>$salt,'created_at'=>time()];
            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;
        } else {
            // 如果没有修改密码就不更改密码盐
            if($data['cms_adm_password']){
                $data['cms_adm_salt'] = $salt;
                $data['cms_adm_password'] = $this->setPassword($data['cms_adm_password'],$salt);
            }else{
                unset($data['cms_adm_password']);unset($data['cms_adm_salt']);
            }
            $data['updated_at'] = time(); //修改时更新时间
            $priKeyValue = $data['id'];
            unset($data['id']);
            $ret =$this->model->where('cms_adm_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
         if (isset($this->isCache)&&$this->isCache === true){
             $table_name = $this->model->getTable();
             $redis = app('redis.connection');
             $data['cms_adm_id'] = $priKeyValue;
             //将数据写入缓存
             $redis->set($table_name.'_'.$priKeyValue , json_encode($data));
         }
        return $ret;

    }

    /**
     * 删除(软删除)
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $model = $this->model->find($id);
        $model->delete();

        //删除缓存数据
        if (isset($this->isCache)&&$this->isCache === true){
            $table_name = $this->model->getTable();
            $redis = app('redis.connection');
            $data['cms_adm_id'] = $id;
            $redis->del($table_name.'_'.$id);
        }

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }
    //获取订单详情信息
    public function getWorkInfo($order_no,$agent_id)
    {
        var_dump(1111);
        die;

        $dmsAgentInfo = app(DmsAgentInfo::class);
        //获取所属商户id
        $mch_id = $dmsAgentInfo->where(['agent_info_id' => $agent_id])->value('mch_id');
        //查看本地表是否有该订单信息
        $orderDiyInfo = $this->model->where(['order_no' => $order_no,'agent_id'=>$agent_id])->get()->toArray();
        var_dump($orderDiyInfo);
        die;
        $prodAllInfo = [];
        if (empty($orderDiyInfo))
        {
            //通过接口获取订单信息并存进diy助手表
            $helper = app(Helper::class);
            $orderInfo = $helper->getSyncOrderIndo($order_no,$agent_id);
            /*$orderInfo = $this->getDemoData();
            $orderInfo = json_decode($orderInfo,true);*/
            //再次查询订单信息
            $orderDiyInfo = $this->model->where(['order_no' => $order_no,'agent_id'=>$agent_id])->get()->toArray();

            if (!empty($orderDiyInfo))
            {
                $prodAllInfo = $orderDiyInfo;
            }else{
                $reData = [
                    'code' => 0,
                    'msg'  => "该订单号不存在"
                ];
                return $reData;
            }

        }else{
            $prodAllInfo = $orderDiyInfo;
        }
        $sku_id = [];
        $same_sku_id = [];
        //判断是否有多商品同个货品
        foreach ($prodAllInfo as $k=>$v)
        {
            if (array_key_exists($v['sku_sn'],$sku_id)){
                //有同个货品
                $same_sku_id[]=$v['sku_sn'];
                $sku_id[$v['sku_sn']] += $v['prod_num'];
            }else{
                $sku_id[$v['sku_sn']]=$v['prod_num'];
            }
        }
        $mediaRepository = app(SaasProductsMediaRepository::class);
        $relationRepository = app(SaasProductsRelationAttrRepository::class);
        $projectOrderTempRepository = app(SaasProjectsOrderTempRepository::class);
        $productsModel = app(SaasProducts::class);
        $categoryModel = app(SaasCategory::class);


        foreach ($prodAllInfo as $k => $v){
            //获取商品名称
            $prodAllInfo[$k]['prod_name'] = $prodAllInfo[$k]['order_prod_name'];
            //获取商品图片
            $prodAllInfo[$k]['prod_image'] = $prodAllInfo[$k]['order_prod_photo'];
            //获取货品属性
            $prodAllInfo[$k]['prod_attr'] = $prodAllInfo[$k]['order_prod_attr'];
            //获取货品属性
            $prodAllInfo[$k]['prod_attr_comb'] = app(SaasProductsSku::class)->where(['prod_sku_id' => $v['sku_id']])->value('prod_attr_comb');

            //判断系统有无该货品
            if ($v['sku_id'])
            {
                //有该货品
                $prodAllInfo[$k]['isset_sku'] = 1;
            }else{
                //无该货品
                $prodAllInfo[$k]['isset_sku'] = 0;
            }

            if ($v['prod_cate_flag'] == GOODS_MAIN_CATEGORY_PRINTER)
            {
                //印刷类商品有作品属性
                $workCounts = $projectOrderTempRepository->getTbOrderWorksCount($order_no,$v['sku_id'],$v['prod_num']);
                $prodAllInfo[$k]['waiting_make']            = $workCounts['waiting_make'];
                $prodAllInfo[$k]['already_submit']          = $workCounts['already_submit'];
                $prodAllInfo[$k]['making']                  = $workCounts['making'];

                //判断商品是否是冲印类商品
                $prodCate = $productsModel->where(['prod_id' => $v['prod_id']])->value('prod_cate_uid');
                //获取分类
                $cateName = $categoryModel->where(['cate_id' => $prodCate])->value('cate_flag');

                $prodAllInfo[$k]['is_single'] = 0;
                $prodAllInfo[$k]['temp_id'] = '';
                if ($cateName == GOODS_DIY_CATEGORY_SINGLE)
                {
                    //冲印类
                    $prodAllInfo[$k]['is_single'] = 1;
                    //冲印类商品的编辑器地址不同

                    //获取规格id
                    $printInfo = app(SaasProductsPrintRepository::class)->getRow(['prod_id' => $v['prod_id']]);

                    $sizeId = $printInfo['prod_size_id'] ?? 0;
                    if (!empty ($sizeId)) {
                        //获取模板id
                        $tempInfo = app(SaasMainTemplatesRepository::class)->getRow(['specifications_id' => $sizeId,'main_temp_check_status' => TEMPLATE_STATUS_VERIFYED], ['main_temp_id']);
                        if (!empty($tempInfo)) {
                            $prodAllInfo[$k]['temp_id'] = $tempInfo['main_temp_id'];
                        }
                    }
                    //判断淘宝货号是否带有冲印标识
                    $firstStr = strstr($v['sku_sn'],SINGLE_SN);
                    //判断是否有冲印商品的货号标识'-'
                    if ($firstStr){
                        //含有冲印货号标识的商品，截取货号标识后面的字符串作为p数
                        $pc = substr($v['sku_sn'],strripos($v['sku_sn'],SINGLE_SN)+1);
                    }
                    if (!isset($pc) || empty($pc)){
                        $pc = ZERO;
                    }

                    $prodAllInfo[$k]['pc'] = $pc;

                    //冲印商品作品数量需要重新计算
                    $singleWorkCounts = $projectOrderTempRepository->getTbOrderWorksCount($order_no,$v['sku_id'],$v['prod_num'],$pc);
                    $prodAllInfo[$k]['waiting_make']            = $singleWorkCounts['waiting_make'];
                    $prodAllInfo[$k]['already_submit']          = $singleWorkCounts['already_submit'];
                    $prodAllInfo[$k]['making']                  = $singleWorkCounts['making'];




                }



            }



        }
        //有多个商品同货号的情况
        if (!empty($same_sku_id))
        {
            foreach ($prodAllInfo as $k => $v)
            {
                if (in_array($v['sku_sn'],$same_sku_id))
                {
                    //获取该货品已制作的作品数量,重新分配
                    $allMakedWorks[$v['sku_sn']] = $v['already_submit']+ $v['making'];

                    if ($v['waiting_make'] <= 0 && $allMakedWorks[$v['sku_sn']]<$sku_id[$v['sku_sn']])
                    {
                        $waitingMake = $sku_id[$v['sku_sn']]-$allMakedWorks[$v['sku_sn']];
                        $prodAllInfo[$k]['waiting_make'] = $waitingMake;


                    }

                }
            }
        }

        return $prodAllInfo;
    }

    public function getDemoData()
    {
        $res = '{
    "success": "true",
    "result": {
        "trade": {
            "alipay_no": "2020052022001185461445496954",
            "buyer_alipay_no": "136********",
            "buyer_email": " ",
            "buyer_nick": "甘泉1950",
            "buyer_open_uid": "AAEiWuy9AEPLhyj6Hb81_Sjm",
            "created": "2020-05-20 11:11:30",
            "modified": "2020-05-20 11:11:38",
            "new_presell": false,
            "orders": {
                "order": [
                    {
                        "adjust_fee": "0.00",
                        "buyer_rate": false,
                        "cid": "50003463",
                        "combo_id": "27170012",
                        "discount_fee": "104.00",
                        "divide_order_fee": "55.62",
                        "is_daixiao": false,
                        "is_oversold": false,
                        "num": "4",
                        "num_iid": "549924403733",
                        "oid": "1011032513942101325",
                        "oid_str": "1011032513942101325",
                        "order_from": "WAP,WAP",
                        "outer_sku_id": "A008",
                        "part_mjz_discount": "8.38",
                        "payment": "64.00",
                        "pic_path": "https://img.alicdn.com/bao/uploaded/i1/3230326467/O1CN01GsUnmt1xdvxQiKwnl_!!3230326467-0-pixelsss.jpg",
                        "price": "168.00",
                        "propoint": "192",
                        "refund_status": "NO_REFUND",
                        "seller_rate": false,
                        "seller_type": "B",
                        "sku_id": "4120248730389",
                        "sku_properties_name": "颜色分类:精装照片书12寸竖;页数:套餐（56P套餐，请拍套餐组合，单拍发一本）",
                        "snapshot_url": "r:1011032513942101325_1",
                        "status": "WAIT_SELLER_SEND_GOODS",
                        "title": "【告白价】照片书制作diy旅行创意相册制作照片生日礼物定制相片情侣纪念册",
                        "total_fee": "64.00"
                    },
                    {
                        "adjust_fee": "0.00",
                        "buyer_rate": false,
                        "cid": "50003463",
                        "combo_id": "27170012",
                        "discount_fee": "93.00",
                        "divide_order_fee": "30.41",
                        "is_daixiao": false,
                        "is_oversold": false,
                        "num": "2",
                        "num_iid": "587980015928",
                        "oid": "1011032513943101325",
                        "oid_str": "1011032513943101325",
                        "order_from": "WAP,WAP",
                        "outer_sku_id": "651",
                        "part_mjz_discount": "4.59",
                        "payment": "35.00",
                        "pic_path": "https://img.alicdn.com/bao/uploaded/i1/3230326467/O1CN01HbbYiW1xdvxPsLgbG_!!3230326467-0-pixelsss.jpg",
                        "price": "128.00",
                        "propoint": "105",
                        "refund_status": "NO_REFUND",
                        "seller_rate": false,
                        "seller_type": "B",
                        "sku_id": "4023785093587",
                        "sku_properties_name": "颜色分类:锁线精装书;页数:56P套餐（请拍套餐组合，单拍发一本）",
                        "snapshot_url": "r:1011032513943101325_1",
                        "status": "WAIT_SELLER_SEND_GOODS",
                        "title": "照片书定制毕业纪念册情侣相册制作diy大容量相册本女教师节礼物",
                        "total_fee": "35.00"
                    }
                ]
            },
            "pay_time": "2020-05-20 11:11:38",
            "payment": "86.03",
            "post_fee": "0.00",
            "receiver_address": "左**街道**路鑫天鑫**栋703",
            "receiver_city": "长沙市",
            "receiver_district": "雨花区",
            "receiver_mobile": "136********",
            "receiver_name": "张**",
            "receiver_phone": "0731-********",
            "receiver_state": "湖南省",
            "receiver_zip": "410007",
            "seller_nick": "爱美印家居旗舰店",
            "shipping_type": "express",
            "status": "WAIT_SELLER_SEND_GOODS",
            "tid": "1011032513941101325",
            "tid_str": "1011032513941101325",
            "total_fee": "296.00",
            "you_xiang": false
        },
        "request_id": "rswh8zlub2qs"
    }
 }';
        return $res;
    }


    //获取淘宝订单缓存数据
    public function getOrderCacheData($order_no,$agent_id)
    {
        //查看本地表是否有该订单信息
        $orderDiyInfo = $this->model->where(['order_no' => $order_no,'agent_id'=>$agent_id])->get()->toArray();
        $prodAllInfo = [];
        if (empty($orderDiyInfo))
        {
            //通过接口获取订单信息并存进diy助手表
            $helper = app(Helper::class);
            $helper->getSyncOrderIndo($order_no,$agent_id);
            //再次查询订单信息
            $orderDiyInfo = $this->model->where(['order_no' => $order_no,'agent_id'=>$agent_id])->get()->toArray();

            if (!empty($orderDiyInfo))
            {
                $prodAllInfo = $orderDiyInfo;
            }else{
                $reData = [
                    'code' => 0,
                    'msg'  => "该订单号不存在"
                ];
                return $reData;
            }

        }else{
            $prodAllInfo = $orderDiyInfo;
        }
        return $prodAllInfo;
    }
    //修改作品数量
    public function changeWorksNum($post)
    {
        $tempOrderRepository = app(SaasProjectsOrderTempRepository::class);
        $tempOrderModel = app(SaasProjectsOrderTemp::class);
        $workEditor = app(WorksController::class);
        \DB::beginTransaction();
        if (!isset($post['project_info']))
        {
            //还没开始做作品，返回1直接关闭弹窗
            return [
                'code' => 1,
                'msg'  => 'ok'
            ];
        }
        //数量插入作品子表，等待验证订单数量
        foreach ($post['project_info'] as $k => $v){
            $tempOrderModel->where('prj_info_id',$k)->update(['ord_quantity' => $v]);
        }
        //验证作品数量
        $res = $workEditor->checkProjectNum($post['order_no'],$post['agent_id']);
        if ($res['code'] == 1){
            //修改成功
            \DB::commit();
            /*\DB::rollBack();*/
            return $res;
        }else if ($res['code'] == 4){
            //作品到达了最大数量，需确认是否修改，
            if (empty($post['is_confirm'])){
                //需要提示用户已达到最大数量，创建订单后将无法修改，
                \DB::rollBack();
                return [
                    'code' => 0,
                    'msg'  => "该订单作品数量达到最大值，待成功创建订单后将再无法修改数量，确认修改作品数量吗？"
                ];
            }else{
                //客户已确认，可以准备创建订单
                \DB::commit();
                /*\DB::rollBack();*/
                //更新同步队列状态
                $syncService = app(Sync::class);
                $syncService->saveOrderSyncQueue($post['order_no'],$post['agent_id']);
                return [
                    'code' => 1,
                    'msg'  => 'ok'
                ];
            }
        }else{
            //异常情况
            \DB::rollBack();
            return [
                'code' => 3,
                'msg'  => $res['msg']
            ];
        }

    }
}
