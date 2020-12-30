<?php
namespace App\Services\Works;

use App\Exceptions\CommonException;
use App\Models\DmsAgentInfo;
use App\Models\SaasOrderSyncQueue;
use App\Models\SaasProjects;
use App\Models\SaasProjectsOrderTemp;
use App\Models\SaasTbOrderMessage;
use App\Repositories\DmsAgentInfoRepository;
use App\Repositories\SaasAreasRepository;
use App\Repositories\SaasOrdersRepository;
use App\Repositories\SaasProductsSkuRepository;
use App\Repositories\SaasProjectsOrderTempRepository;
use App\Repositories\SaasSalesChanelRepository;
use App\Services\ChanelUser;
use App\Services\Common\Mongo;
use App\Services\Helper;
use App\Services\Orders\SyncOrdersEntity;
use App\Services\Outer\TbApi;
use Illuminate\Support\Facades\Redis;

/**
 * 同步处理类
 *
 * 功能详细说明
 * @author: hlt <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/5/28
 */

class Sync
{
    //插入同步队列
    public function saveOrderSyncQueue($order_no,$agent_id)
    {

        $agentInfoModel = app(DmsAgentInfo::class);

        $syncQueueModel = app(SaasOrderSyncQueue::class);


        //获取所属商户id
        $mch_id = $agentInfoModel->where(['agent_info_id' => $agent_id])->value('mch_id');

        //判断订单号是否存在表中
        if ($syncQueueModel->where(['outer_order_no' => $order_no])->exists())
        {
            //存在，则更新记录
            $sync_status = $syncQueueModel->where(['outer_order_no' => $order_no])->value('sync_status');
            if ($sync_status == 'ready')
            {
                //该队列已处于准备状态，证明该订单各部分都正常，无须再做判断
                return false;
            }
        }

        $helper = app(Helper::class);
        $res = $helper->getSyncOrderIndo($order_no,$agent_id);
        /*$res = $this->getDemoData();
        $res = json_decode($res,true);*/

        if ($res['success'] == 'true' && isset($res['result']['trade']))
        {
            //成功获取订单
            //获取订单商品数目
            $prodCount = count($res['result']['trade']['orders']['order']);
            //走1对1流程
            $hanleRes = $this->insertSyncQueue($order_no,$res,$mch_id,$agent_id);
            return $hanleRes;
        }else{
            return false;
        }
    }
    /*public function getDemoData()
  {
      $res = '{
  "success": "true",
  "result": {
      "trade": {
          "alipay_no": "13265961649",
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
                      "num": "1",
                      "num_iid": "549924403733",
                      "oid": "1011032513942101325",
                      "oid_str": "1011032513942101325",
                      "order_from": "WAP,WAP",
                      "outer_sku_id": "TA017_A685",
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
                      "outer_sku_id": "TA01564",
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
          "tid_str": "13265961649",
          "total_fee": "296.00",
          "you_xiang": false
      },
      "request_id": "rswh8zlub2qs"
  }
}';
      return $res;
  }*/

    //插入同步队列流程处理
    public function insertSyncQueue($order_no,$data,$mch_id,$agent_id)
    {

        $syncQueueModel = app(SaasOrderSyncQueue::class);
        $tempRepository = app(SaasProjectsOrderTempRepository::class);

        $orderArr = $data['result']['trade']['orders']['order'];

        //判断订单状态
        if ($data['result']['trade']['status'] != 'WAIT_SELLER_SEND_GOODS')
        {
            //不是已付款状态不需要插入队列表
            return false;
        }
        $skuRepository = app(SaasProductsSkuRepository::class);

        //商品类型
        $good_type_arr = [];
        //获取错误信息
        $error = [];
        //作品数量
        $num = 0;
        foreach ($orderArr as $k => $v)
        {
            $skuArr = [];
            if (isset($v['outer_sku_id'])){
                //获取当前商品类型（实物不操作同步队列）
                //判断是否为合并商品
                $doubleStr = strstr($v['outer_sku_id'],DOUBLE_SN);
                if ($doubleStr)
                {
                    $skuArr = explode('_', $v['outer_sku_id']);
                }else{
                    $skuArr[] = $v['outer_sku_id'];
                }
                foreach ($skuArr as $kk => $vv)
                {
                    $firstStr = mb_substr($vv,0,1,'utf-8');
                    //判断是否有套餐商品的货号标识
                    if ($firstStr == PACKAGE_SN){
                        //含有套餐货号的商品，不自动同步
                        $error[] = "订单含有套餐类商品".$vv;
                    }
                    $prod_type_res = $skuRepository->getGoodstype($vv,$mch_id);
                    if ($prod_type_res['code'] == 1)
                    {
                        $good_type_arr[] = $prod_type_res['goods_type'];
                        if ($prod_type_res['goods_type']==GOODS_MAIN_CATEGORY_PRINTER)
                        {
                            //印品统计作品数量
                            $num += $v['num'];
                        }
                    }else{
                        $error[] = $prod_type_res['msg'];
                    }
                }
            }else{
                $error[] = "订单含有空货号商品";
            }



        }

        $printer_num = 0;
        $entity_num = 0;
        //判断该订单商品类型
        if (!empty($good_type_arr))
        {
            foreach ($good_type_arr as $k => $v){
                if ($v == GOODS_MAIN_CATEGORY_PRINTER)
                {
                    $printer_num++;
                }elseif($v == GOODS_MAIN_CATEGORY_ENTITY)
                {
                    $entity_num++;
                }
            }
        }
        if ($printer_num!=0&&$entity_num!=0){
            //混合类
            $goods_type = 3;
        }elseif ($printer_num!=0&&$entity_num==0){
            //印品类
            $goods_type = 1;
        }elseif ($printer_num==0&&$entity_num!=0){
            //实物类
            $goods_type = 2;
        }else{
            $goods_type = 0;
            $error[] = "商品类型错误";
        }



        //组织数据插入队列表
        $data = [
            'outer_order_no' => $order_no,
            'goods_type'     => $goods_type,
            'agent_id'       => $agent_id,
        ];
        if (!empty($error)){
            //出现错误情况
            $data['sync_status'] = 'error';
            $data['error_msg'] = implode(',',$error);

            //判断订单号是否存在表中
            if ($syncQueueModel->where(['outer_order_no' => $order_no])->exists())
            {
                //存在，则更新记录
                $data['updated_at']    = time();
                $syncQueueModel->where(['outer_order_no' => $order_no])->update($data);
            }else{
                $data['created_at']    = time();
                $syncQueueModel->insert($data);
            }
            return true;
        }
        $proj_num = $this->getPeddingProNum($order_no);

        //1.作品子表数量为零并且订单信息中印品数量为零
        if ($proj_num==0 && $num == 0){
            if ($data['goods_type'] == 2){
                //如果都是订单内都是实物则状态则直接为ready插入队列
                $data['sync_status'] = 'ready';
                $data['error_msg'] = '';
                //判断订单号是否存在表中
                if ($syncQueueModel->where(['outer_order_no' => $order_no])->exists())
                {
                    //存在，则更新记录
                    $data['updated_at']    = time();
                    $syncQueueModel->where(['outer_order_no' => $order_no])->update($data);
                }else{
                    $data['created_at']    = time();
                    $syncQueueModel->insert($data);
                }
                return true;
            }
            //货号正常,数量为0,接口订单信息里又含有印品类商品,数量有问题
            $data['sync_status'] = 'error';
            $data['error_msg'] = '作品子表数量为0';
            //判断订单号是否存在表中
            if ($syncQueueModel->where(['outer_order_no' => $order_no])->exists())
            {
                //存在，则更新记录
                $data['updated_at']    = time();
                $syncQueueModel->where(['outer_order_no' => $order_no])->update($data);
            }else{
                $data['created_at']    = time();
                $syncQueueModel->insert($data);
            }
            return true;
        }

        //1.作品子表数量不为零0
        if ($proj_num!=0 && $num>=$proj_num)
        {
            if ($proj_num == $num)
            {
                //判断是否有空图
                $t_res = $tempRepository->checkEmptyPictures($order_no);
                if ($t_res){
                    //货号正常,数量相等,无缺图,已付款
                    $data['sync_status'] = 'ready';
                    $data['error_msg'] = '';
                }else{
                    //货号正常,数量相等,已付款,有缺图情况
                    $data['sync_status'] = 'error';
                    $data['error_msg'] = $t_res['msg'];
                }
            }else{
                //货号正常,数量不相等,已付款,作品还未提交完
                $data['sync_status'] = 'prepare';
                $data['error_msg'] = '';
            }

            //判断订单号是否存在表中
            if ($syncQueueModel->where(['outer_order_no' => $order_no])->exists())
            {
                //存在，则更新记录
                $data['updated_at']    = time();
                $syncQueueModel->where(['outer_order_no' => $order_no])->update($data);
            }else{
                $data['created_at']    = time();
                $syncQueueModel->insert($data);
            }
            return true;

        }else{
            //订单信息内作品数量大于子表作品数量
            $data['sync_status'] = 'error';
            $data['error_msg'] = '接口或者作品子表作品数量异常';
            //判断订单号是否存在表中
            if ($syncQueueModel->where(['outer_order_no' => $order_no])->exists())
            {
                //存在，则更新记录
                $data['updated_at']    = time();
                $syncQueueModel->where(['outer_order_no' => $order_no])->update($data);
            }else{
                $data['created_at']    = time();
                $syncQueueModel->insert($data);
            }
            return true;
        }
    }



    //获取待确认作品所属外部订单订购数量
    public function getPeddingProNum($order_no,$status_where = [],$skuId=0,$page=0)
    {
        if (empty($status)){
            $status_where = [WORKS_DIY_STATUS_WAIT_CONFIRM,WORKS_DIY_STATUS_ORDER];
        }
        $skuWhere = [];
        if (!empty($skuId)){
            $skuWhere = ['saas_projects.sku_id' => $skuId];
        }
        //作品页数
        $page_where = [];
        if (!empty($page)){
            $page_where = ['saas_projects.prj_page_num' => $page];
        }

        $projectOrderTemp = app(SaasProjectsOrderTemp::class);
        $proj_num = $projectOrderTemp
            ->where(['saas_projects_order_temp.order_no' => $order_no])
            ->leftJoin('saas_projects', 'saas_projects_order_temp.prj_id', '=', 'saas_projects.prj_id')
            ->whereIn('saas_projects.prj_status',$status_where)
            ->where($skuWhere)
            ->where($page_where)
            ->whereNull('saas_projects_order_temp.deleted_at')
            ->whereNull('saas_projects.deleted_at')
            ->sum('saas_projects_order_temp.ord_quantity');
        return $proj_num;
    }
    //同步消息队列
    public function messageQueueSync()
    {
        //获取每次同步条数
        $limit = config("common.queue_limit.message_sync");
        $messageModel = app(SaasTbOrderMessage::class);
        $messageList = $messageModel->where(['msg_topic' => 'taobao_trade_TradeBuyerPay','sync_status' => 0])->limit($limit)->get()->toArray();
        foreach ($messageList as $k => $v)
        {
            $this->saveOrderSyncQueue($v['tb_order_no'],$v['agent_id']);
            $messageModel->where(['tb_msg_id' => $v['tb_msg_id']])->update(['sync_status' => 1]);
        }
        return true;
    }
    //获取即将跑同步队列创建订单的数组
    public function runReadySyncQueue()
    {
        //获取每次跑队列条数
        $limit = config("common.queue_limit.ready_sync_queue");
        $syncModel = app(SaasOrderSyncQueue::class);
        $waitingInfo = $syncModel->where(['sync_status' => 'ready'])->limit($limit)->get()->toArray();
        //将队列状态转为进行中
        foreach ($waitingInfo as $k => $v)
        {
            $syncModel->where(['sync_queue_id' => $v['sync_queue_id']])->update(['sync_status' => 'progress']);
        }

        $this->runSyncQueue($waitingInfo);
    }

    //跑同步队列
    public function runSyncQueue($waitingInfo)
    {
        $syncModel = app(SaasOrderSyncQueue::class);
        $tempModel = app(SaasProjectsOrderTemp::class);
        $projectModel = app(SaasProjects::class);
        $mongo = new Mongo();
        foreach ($waitingInfo as $k => $v)
        {
            $data = [];
            //获取该订单号的agent_id
            $agent_id = $v['agent_id'];
            if (empty($agent_id)){
                $data['error_msg'] = "用户id未找到";
                $data['times'] = ++$v['times'];
                $data['sync_status'] = "error";
                $data['updated_at'] = time();
                $syncModel->where(['outer_order_no' => $v['outer_order_no']])->update($data);
                //记录同步信息到作品日志中
                $workId = $tempModel->where(['order_no' => $v['outer_order_no']])->pluck('prj_id');
                $workInfo = $projectModel->whereIn('prj_id',$workId)->where(['prj_status' => WORKS_DIY_STATUS_WAIT_CONFIRM])->select('prj_id')->get()->toArray();
                foreach ($workInfo as $kk => $vv){
                    $workLog = [
                        'user_id'    => $v['agent_id'],
                        'works_id'   => $vv['prj_id'],
                        'action'     => "同步订单失败",
                        'note'       => "原因为【".$data['error_msg']."】",
                        'createtime' => time(),
                        'operator'   => "自动同步",
                    ];
                    $mongo->insert('diy_works_log',$workLog);
                }
                continue;
            }
            try{
                $res = $this->setOrdersData($v['outer_order_no'],$agent_id);
            }catch (CommonException $e){
                //生成订单失败
                $data['error_msg'] = $e->getMessage();
                $data['times'] = ++$v['times'];
                $data['sync_status'] = "error";
                $data['updated_at'] = time();
                $syncModel->where(['outer_order_no' => $v['outer_order_no']])->update($data);
                //记录同步信息到作品日志中
                $workId = $tempModel->where(['order_no' => $v['outer_order_no']])->pluck('prj_id');
                $workInfo = $projectModel->whereIn('prj_id',$workId)->where(['prj_status' => WORKS_DIY_STATUS_WAIT_CONFIRM])->select('prj_id')->get()->toArray();
                foreach ($workInfo as $kk => $vv){
                    $workLog = [
                        'user_id'    => $v['agent_id'],
                        'works_id'   => $vv['prj_id'],
                        'action'     => "同步订单失败",
                        'note'       => "原因为【".$data['error_msg']."】",
                        'createtime' => time(),
                        'operator'   => "自动同步",
                    ];
                    $mongo->insert('diy_works_log',$workLog);
                }
                continue;
            }

            if ($res['status'] == 'success')
            {
                //生成订单成功
                $data['error_msg'] = "";
                $data['times'] = ++$v['times'];
                $data['sync_status'] = "finish";
                $data['order_no'] = $res['data'];
                $data['updated_at'] = time();
                $syncModel->where(['outer_order_no' => $v['outer_order_no']])->update($data);
                //更新作品状态
                $workId = $tempModel->where(['order_no' => $v['outer_order_no']])->pluck('prj_id');
                $workInfo = $projectModel->whereIn('prj_id',$workId)->where(['prj_status' => WORKS_DIY_STATUS_WAIT_CONFIRM])->select('prj_id')->get()->toArray();

                foreach ($workInfo as $kk => $vv){
                    //记录同步信息到作品日志中
                    $workLog = [
                        'user_id'    => $v['agent_id'],
                        'works_id'   => $vv['prj_id'],
                        'action'     => "同步订单成功",
                        'note'       => "订单号为【".$data['order_no']."】",
                        'createtime' => time(),
                        'operator'   => "自动同步",
                    ];
                    $mongo->insert('diy_works_log',$workLog);
                }
                if (!empty($workId)){
                    //更新作品状态
                    $projectModel->whereIn('prj_id',$workId)->where(['prj_status' => WORKS_DIY_STATUS_WAIT_CONFIRM])->update(['prj_status' => WORKS_DIY_STATUS_ORDER,'updated_at'=>time()]);
                }
            }else{
                //生成订单失败
                $data['error_msg'] = $res['msg']??"程序出错";
                $data['times'] = ++$v['times'];
                $data['sync_status'] = "error";
                $data['updated_at'] = time();
                $syncModel->where(['outer_order_no' => $v['outer_order_no']])->update($data);
                //记录同步信息到作品日志中

                $workId = $tempModel->where(['order_no' => $v['outer_order_no']])->pluck('prj_id');
                $workInfo = $projectModel->whereIn('prj_id',$workId)->where(['prj_status' => WORKS_DIY_STATUS_WAIT_CONFIRM])->select('prj_id')->get()->toArray();
                foreach ($workInfo as $kk => $vv){
                    $workLog = [
                        'user_id'    => $v['agent_id'],
                        'works_id'   => $vv['prj_id'],
                        'action'     => "同步订单失败",
                        'note'       => "原因为【".$data['error_msg']."】",
                        'createtime' => time(),
                        'operator'   => "自动同步",
                    ];
                    $mongo->insert('diy_works_log',$workLog);
                }
            }
        }
    }

    /*
     * 标准化同步订单信息用于创建订单
     */
    public function setOrdersData($order_no,$agent_id,$isCreatedOrder = null)
    {
        //可能存在合单的情况下
        $orderNos = explode(",",$order_no);
        $tb_order_data_merge = [];
        $tb_buyer_nick = [];//淘宝买家旺旺号
        foreach ($orderNos as $ok => $ov){
            //判断是否是二次同步的订单号
            if(Redis::exists('tb'.$ov)==0){
                //判断订单号是否已被关联
                $ordersRepository = app(SaasOrdersRepository::class);
                $order_num = $ordersRepository->isoutOrderExists($ov);
                if($order_num>ZERO){
                    $return_data = [
                        'status'=>"failed",
                        'code'=>ONE,
                        'msg'=>$ov."已关联有效订单,无法同步"
                    ];
                    return $return_data;
                }
            }

            //请求淘宝接口获取订单信息
            $helper = app(Helper::class);
            $tb_order = $helper->getSyncOrderIndo($ov,$agent_id);

            if($tb_order['success']=='false'){
                $return_data = [
                    'status'=>"failed",
                    'code'=>ONE,
                    'msg'=>"该记录".$ov."不存在"
                ];
                return $return_data;
            }else if($tb_order['success']=="true" && empty($tb_order['result']['trade'])){
                $return_data = [
                    'status'=>"failed",
                    'code'=>ONE,
                    'msg'=>"该同步订单".$ov."不存在"
                ];
                return $return_data;
            }

            //淘宝订单信息
            $tb_order_data = $tb_order['result']['trade'];
            //不是二次同步的订单号才需要判断淘宝订单状态
            if(Redis::exists('tb'.$ov)==0) {
                //订单状态为买家已付款或者该订单已关闭
                if ($tb_order_data['status'] != 'WAIT_SELLER_SEND_GOODS' && $tb_order_data['status'] != 'PAID_FORBID_CONSIGN') {
                    $return_data = [
                        'status' => "failed",
                        'code' => ONE,
                        'msg' => "该订单号" . $ov . "的状态不是已付款状态"
                    ];
                    return $return_data;
                }
            }

            Redis::del('tb'.$ov);

            if(empty($tb_order_data_merge)){
                $tb_order_data_merge = $tb_order_data;
            }else{
                $tb_order_data_merge['orders']['order'] =array_merge($tb_order_data_merge['orders']['order'],$tb_order_data['orders']['order']);
                $tb_order_data_merge['post_fee'] += $tb_order_data['post_fee'];
                $tb_order_data_merge['payment'] += $tb_order_data['payment'];
                $tb_order_data_merge['total_fee'] += $tb_order_data['total_fee'];
                $tb_order_data_merge['tid'] = $tb_order_data_merge['tid'].",".$tb_order_data['tid'];
                $seller_memo = $tb_order_data['seller_memo']??"";
                if(isset($tb_order_data_merge['seller_memo'])){
                    if(isset($tb_order_data['seller_memo'])){
                        $tb_order_data_merge['seller_memo'] = $tb_order_data_merge['seller_memo'].",".$seller_memo;
                    }
                }
            }

            $tb_buyer_nick[] = $tb_order_data['buyer_nick'];
        }

        //买家旺旺号
        $tb_buyer_nick = array_unique($tb_buyer_nick);
        if(count($tb_buyer_nick)>=2){
            $return_data = [
                'status'=>"failed",
                'code'=>ONE,
                'msg'=>"合单单号中存在不同的买家信息，暂时不支持合单"
            ];
            return $return_data;
        }

        $items = [];
        $orderInfo = [];

        //子订单信息
        $tb_order_info = $tb_order_data_merge['orders']['order'];

        $prodSkuRepository = app(SaasProductsSkuRepository::class);
        $agentInfoModel = app(DmsAgentInfo::class);
        //获取所属商户id
        $mch_id = $agentInfoModel->where(['agent_info_id' => $agent_id])->value('mch_id');

        //判断是否存在相同货号的商品,如果相同，合并为一个，数量相加
        foreach ($tb_order_info as $kk => $vv){
//            if($tb_order_data_merge['tid']=='568225540660496092' && !isset($vv['outer_sku_id']))
//            {
//                $vv['outer_sku_id']='A108';
//            }
            if (isset($vv['outer_sku_id']))
            {
                //是否存在混合商品
                if(strstr($vv['outer_sku_id'],DOUBLE_SN)){
                    $snList = explode(DOUBLE_SN,$vv['outer_sku_id']);
                    foreach ($snList as $sk=>$sv){
                        if(isset($orderInfo[$sv])){
                            $orderInfo[$sv]['num'] += $vv['num'];
                        }else{
                            $vv['outer_sku_id'] = $sv;
                            $orderInfo[$sv] = $vv;
                        }
                    }
                }else{
                    if(isset($orderInfo[$vv['outer_sku_id']])){
                        $orderInfo[$vv['outer_sku_id']]['num'] += $vv['num'];
                    }else{
                        $orderInfo[$vv['outer_sku_id']] = $vv;
                    }
                }
            }
        }
        //重新整合数组键值
        $tb_order_info = array_values($orderInfo);
        foreach ($tb_order_info as $k => $v){
            if (isset($v['outer_sku_id']))
            {
                $goodsType = $prodSkuRepository->getGoodstype($v['outer_sku_id'],$mch_id);
                if($goodsType['code']==ZERO){
                    $return_data = [
                        'status'=>"failed",
                        'code'=>ONE,
                        'msg'=>$goodsType['msg']
                    ];
                    return $return_data;
                }
                //获取商品id和货品id
                $prodSku = $prodSkuRepository->getProdSkuId($v['outer_sku_id'],$mch_id);
                if(empty($prodSku)){
                    $return_data = [
                        'status'=>"failed",
                        'code'=>ONE,
                        'msg'=>"同步商品不存在"
                    ];
                    return $return_data;
                }
                //获取sku_id
                $tb_order_data_merge['orders']['order'][$k]['sku_id'] = $prodSku[0]['prod_sku_id'];
                //如果订单存在实物
                if($goodsType['code']==ONE && $goodsType['goods_type']==GOODS_MAIN_CATEGORY_ENTITY){
                    //实物信息
                    $items[] = [
                        'goods_id'   =>$prodSku[0]['prod_id'],
                        'product_id' =>$prodSku[0]['prod_sku_id'],
                        'works_id'   =>ZERO,
                        'file_type'  =>WORKS_FILE_TYPE_EMPTY,
                        'price_mod'  => 1,   //1正常按本/个计价 2按张数计价
                        'buy_num'    => $v['num'],  //购买数量 必须
                        'real_fee'   => $v['price']*$v['num'],  // 价格  商品单价*数量
                        'price'      => $v['payment'], //最终商品价格 非必须，如果有，则需要验证正确性 非必须
                    ];
                    //是否是实物,用于同步页面渲染时判断
                    $tb_order_data_merge['orders']['order'][$k]['entity'] = true;
                }else{
                    $tb_order_data_merge['orders']['order'][$k]['entity'] = false;
                    if(empty($isCreatedOrder)){
                        //购买数量
                        $tb_prod_num = $v['num'];

                        $prj_order_tempRespository = app(SaasProjectsOrderTempRepository::class);
                        //获取作品信息
                        $prj_info = $prj_order_tempRespository->getTbOrderWorksInfo($order_no,$prodSku[0]['prod_sku_id'],[WORKS_DIY_STATUS_WAIT_CONFIRM]);
                        if(empty($prj_info)){
                            $return_data = [
                                'status'=>"failed",
                                'code'=>ONE,
                                'msg'=>"获取不到对应的作品信息"
                            ];
                            return $return_data;
                        }
                        //判断买家信息是否一致
//                    if($tb_order_data['buyer_nick']!=$prj_info[0]['prj_outer_account']){
//                        $return_data = [
//                            'status'=>"failed",
//                            'code'=>2,
//                            'msg'=>"订单买家信息与作品买家信息不一致"
//                        ];
//                        return $return_data;
//                    }
                        //作品信息
                        $prj_num = 0;
                        foreach ($prj_info as $prj_k => $prj_v){

                            //作品如果异常，不能同步作品
                            if(!empty($prj_v['empty_mask_count'])){
                                $return_data = [
                                    'status'=>"failed",
                                    'code'=>ONE,
                                    'msg'=>"作品异常,缺少图片"
                                ];
                                return $return_data;
                            }

                            $prj_num +=$prj_v['ord_quantity'];
                            $items[] = [
                                'goods_id'   =>$prodSku[0]['prod_id'],
                                'product_id' =>$prodSku[0]['prod_sku_id'],
                                'works_id'   =>$prj_v['prj_id'],
                                'file_type'  =>WORKS_FILE_TYPE_DIY,
                                'price_mod'  => 1,   //1正常按本/个计价 2按张数计价
                                'buy_num'    => $prj_v['ord_quantity'],  //购买数量 必须
                                'real_fee'   => $v['price']*$prj_v['ord_quantity'],  // 价格  商品单价*数量
                                'price'      => $v['payment'], //最终商品价格 非必须，如果有，则需要验证正确性 非必须
                            ];
                        }
                        //判断淘宝购买商品的数量与待确认的作品数量是否一致
                        if($prj_num != $tb_prod_num){
                            $return_data = [
                                'status'=>"failed",
                                'code'=>ONE,
                                'msg'=>"作品数量与淘宝购买数量不一致"
                            ];
                            return $return_data;
                        }
                    }
                }
            }
        }


        //将区域名称转换为对应的区域编码
        try{
            $areasRespository = app(SaasAreasRepository::class);
            //省
            $province = $tb_order_data_merge['receiver_state'];
            $province_id = $areasRespository->provinceNameToCode($province);
            //市
            $city = $tb_order_data_merge['receiver_city'];
            $city_id = $areasRespository->cityNameToCode($city);
            //区
            $district = $tb_order_data_merge['receiver_district']??"";
            $district_id = $areasRespository->districtNameToCode($district);
        }catch (CommonException $e){
            if(empty($isCreatedOrder)){
                $return_data = [
                    'status'=>"failed",
                    'code'=>ONE,
                    'msg'=>$e->getMessage()
                ];
                return $return_data;
            }
        }

        //同步页面收货信息显示
        if(!empty($isCreatedOrder)){
            $receiver_data = [
                'outer_order_no'=>$tb_order_data_merge['tid'],
                'receiver_name'=>$tb_order_data_merge['receiver_name'],
                'receiver_zip'=>$tb_order_data_merge['receiver_zip'],
                'receiver_mobile'=>$tb_order_data_merge['receiver_mobile'],
                'receiver_province'=>$province_id??"",
                'receiver_city'=>$city_id??"",
                'receiver_district'=>$district_id??"",
                'receiver_address'=>$tb_order_data_merge['receiver_address']??"",
                'buyer_memo'=>$tb_order_data_merge['seller_memo']??"",
                //商品的数量
                'order_count' => count($orderNos)
            ];

            $return_data = [
                'status'=>"success",
                'data'=>$tb_order_data_merge,
                'receiver_data'=>$receiver_data,
                'items'=>$items
            ];
            return $return_data;
        }

        //渠道id
        $salesChanel = app(SaasSalesChanelRepository::class);
        $cha_id = $salesChanel->getAgentChannleId();
        //合作代码
        $dmsAgentInfoRepository = app(DmsAgentInfoRepository::class);
        $parent_code = $dmsAgentInfoRepository->getCodeById($agent_id);
        //创建订单收货信息
        $receiver_info = [
            'consignee'      => $tb_order_data_merge['receiver_name'],     //必须 收货人
            'ship_mobile'    => $tb_order_data_merge['receiver_mobile'],    //必须 收货人电话
            'province_code'  => $province_id,           //省id
            'city_code'      => $city_id,               //市id
            'district_code'  => $district_id,           //区id
            'ship_addr'      => $tb_order_data_merge['receiver_address']??"",    //收货地址
            'ship_tel'       => isset($tb_order_data_merge['receiver_phone'])?$tb_order_data_merge['receiver_phone']:"",           //电话
            'ship_zip'       => $tb_order_data_merge['receiver_zip'],            //邮编
        ];

        //快递模板id
        $delivery_temp_id = config('common.syn_delivery_temp_id');
        //快递id
        $delivery_id = config('common.syn_delivery_id');
        //支付方式id
        $order_pay_id = config('common.order_pay_id');

        //创建订单数据组装
        $post_data = [
            'items'            =>  $items,
            'receiver_info'    =>  $receiver_info,
            'outer_order_no'   =>  $tb_order_data_merge['tid'],     //关联的第三方单号 选填
            'shipping_temp_id' =>  $delivery_temp_id,          //快递模板id 必须
            'shipping_id'      =>  $delivery_id,          //快递id 必须
            'partner_code'     =>  $parent_code,    //合作代码，以些代码开头生成订单号

            'total_amount'     =>  $tb_order_data['payment'],      //订单总金额，实际价格 含运费 ,如果提交了，则会验算 非必填
            'post_fee'         =>  0,             //运费  选填
            'mch_id'           =>  $mch_id,          //商家id,必须
            'chanel_id'        =>  $cha_id,           //渠道id,必须
            'buyer_type'       =>  CHANEL_TERMINAL_AGENT,         // 终端用户类型 1代表分销 2代表会员，其他无效 必须
            'user_id'          =>  $agent_id,          //用户id,必须
            'note'             =>  $tb_order_data_merge['seller_memo']??"",  //用户备注  选填
            //支付信息
            'pay_info'         =>[  //支付信息 必填
                'pay_id' => $order_pay_id, //余额、支付宝、微信等支付对应的id 必须
            ],
        ];

        //请求订单创建接口
        $syncOrdersEntity = app(SyncOrdersEntity::class);
        $result = $syncOrdersEntity->create($post_data);

        $isUpdate = config('common.is_update_tb_memo');
        if($isUpdate){
            $nums = count($orderNos);
            foreach ($orderNos as $onk=>$onv){
                //备注信息回写到淘宝
                if ($result['status'] == 'success') {
                    //生成的订单号
                    $orderNo = $result['data'];
                    //淘宝返回数据的备注
                    $seller_memo = $tb_order_data['seller_memo'] ?? "";
                    //淘宝备注
                    $date_time = date('Y-m-d H:i:s');
                    if ($nums > 1) {
                        $new_seller_memo = $seller_memo . '  合单/' . $orderNo . '/' . $date_time;
                    } else {
                        $new_seller_memo = $seller_memo . '  一单/' . $orderNo . '/' . $date_time;
                    }
                    $data = [
                        'order_no' => $onv,
                        'agent_id' => $agent_id,
                        'new_seller_memo' => $new_seller_memo
                    ];

                    try {
                        $tbConfig = $helper->getTbConfig($agent_id);
                        $api = app(TbApi::class);
                        $api->request($tbConfig['sdk_cnf_domain'] . '/tb/order/update-memo', $data, 'POST');
                    } catch (CommonException $e) {

                    }
                }
            }

        }
        return $result;
    }

    //跑创建订单出错的队列
    public function runErrorSyncQueue()
    {
        //获取每次跑队列条数
        $limit = config("common.queue_limit.error_sync_queue");
        $syncModel = app(SaasOrderSyncQueue::class);
        $waitingInfo = $syncModel->where(['sync_status' => 'error'])->whereBetween('times',[1,2])->limit($limit)->get()->toArray();
        //将队列状态转为进行中
        foreach ($waitingInfo as $k => $v)
        {
            $syncModel->where(['sync_queue_id' => $v['sync_queue_id']])->update(['sync_status' => 'progress']);
        }

        $this->runSyncQueue($waitingInfo);
    }




}
