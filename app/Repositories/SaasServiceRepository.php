<?php
namespace App\Repositories;

use App\Models\DmsAgentInfo;
use App\Models\DmsMerchantAccount;
use App\Models\OmsMerchantInfo;
use App\Models\SaasDeliveryDoc;
use App\Models\SaasExpress;
use App\Models\SaasManuscript;
use App\Models\SaasOrderBarter;
use App\Models\SaasOrderProduceQueue;
use App\Models\SaasOrderProducts;
use App\Models\SaasOrders;
use App\Models\SaasOrderServiceReason;
use App\Models\SaasProducts;
use App\Models\SaasProductsSku;
use App\Models\SaasProjects;
use App\Models\SaasSalesChanel;
use App\Models\SaasService;
use App\Models\SaasSuppliers;
use App\Models\SaasUser;
use App\Presenters\CommonPresenter;
use App\Services\Helper;
use App\Services\Orders\AfterCreate;
use App\Services\Queue;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * 售后列表仓库模板
 * @author: cjx
 * @version: 1.0
 * @date:  2020/04/22
 */
class SaasServiceRepository extends BaseRepository
{
    protected $mch_id;

    public function __construct(SaasService $model,SaasOrders $orders,SaasOrderProducts $orderProducts,
                                SaasExpress $express,SaasProductsSku $productsSku,SaasProjects $projects,
                                SaasManuscript $manuscript,SaasProducts $products,SaasProductsRelationAttrRepository $productsRelationAttrRepository,
                                SaasOrdersRepository $ordersRepository,OmsMerchantInfo $merchantInfo,SaasOrderBarter $orderBarter,
                                SaasProductsMediaRepository $mediaRepository,DmsMerchantAccount $merchantAccount,SaasUser $user,
                                SaasOrderProduceQueue $orderProduceQueue, SaasOrderServiceReasonRepository $orderServiceReasonRepository,
                                SaasSuppliers $suppliers,SaasOrderServiceReason $orderServiceReason,SaasDeliveryDoc $deliveryDoc,SaasSalesChanel $chanel,
                                DmsAgentInfo $agentInfo,DmsAgentInfoRepository $dmsAgentInfoRepository)
    {
        $this->mch_id = isset(session("admin")['mch_id']) ? session("admin")['mch_id'] : '';
        $this->model =$model;
        $this->ordModel = $orders;
        $this->ordProductModel = $orderProducts;
        $this->expressModel = $express;
        $this->skuModel = $productsSku;
        $this->projectModel = $projects;
        $this->manuscriptModel = $manuscript;
        $this->productModel = $products;
        $this->mchInfoModel = $merchantInfo;
        $this->barterModel = $orderBarter;
        $this->dmsAccountModel = $merchantAccount;
        $this->userModel = $user;
        $this->ordPushQueueModel = $orderProduceQueue;
        $this->suppliersModel = $suppliers;
        $this->ordReasonModel = $orderServiceReason;
        $this->deliveryDocModel = $deliveryDoc;
        $this->chanelModel = $chanel;
        $this->agentInfoModel = $agentInfo;

        $this->productsRelationAttrRepository = $productsRelationAttrRepository;
        $this->orderRepository = $ordersRepository;
        $this->mediaRepository = $mediaRepository;
        $this->serviceReasonRepository = $orderServiceReasonRepository;
        $this->dmsAgentInfoRepository = $dmsAgentInfoRepository;
    }

    /**
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getTableList($where=null, $order='created_at desc')
    {
        $limit = isset($where['limit']) ? $where['limit']:config('common.page_limit');  //这个10取配置里的
        $where = $this->parseWhere($where);
        $where['mch_id'] =$this->mch_id;

        if(isset($where['status'])){
            if ($where['status'] != 'all'){
                $where['job_status'] = $where['status'];
            }
            unset($where['status']);
        }

        //order 必须以 'id desc'这种方式传入.
        $orderBy = [];
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }
        $query = $this->model->with('orderInfo');

        //查询时间
        if(isset($where['time'])){
            if(!empty($where['created_at'])){
                if($where['time'] == 'apply'){
                    $where_field = 'created_at';
                }else{
                    $where_field = 'handle_time';
                }
                $created_at = $where['created_at'];
                $time_list = Helper::getTimeRangedata($created_at);
                $query = $query->whereBetween($where_field,[$time_list['start'],$time_list['end']]);
                unset($where['created_at']);
            }
           unset($where['time']) ;


        }

        if(!empty ($where)) {
            $query =  $query->where($where);
        }

        if(!empty($order)) {
            $query =  $query->orderBy($orderBy[0],$orderBy[1]);
        }

        $list = $query->paginate($limit);

        //商品数量
        foreach ($list as $k=>$v){
            $prod_info = $this->ordProductModel->where("ord_id",$v['orderInfo']['order_id'])->select("prod_num")->get();
            foreach ($prod_info as $key=>$val){
                $list[$k]['nums'] += $val['prod_num'];
            }
        }

        return $list;
    }


    /**
     * 新增/修改
     * @param $data $platform 平台简称 $adm_username操作人 $is_examine审核归档
     * @return boolean
     */
    public function save($data, $platform=null, $adm_username=null,$user_id=null,$is_examine=false)
    {
        if(empty($data['job_id'])) {
            unset($data['job_id']);
            $data['created_at'] = time();

            //检查订单状态
            $order_info = $this->orderRepository->getOrderInfo('',$data['order_no']);
            if($order_info['order_status'] == ORDER_STATUS_WAIT_RECEIVE || $order_info['order_status'] == ORDER_STATUS_FINISH){

                //检查订单是否已有售后工单
                $exist_job = $this->model->where('order_no',$data['order_no'])->get();
                if(count($exist_job) > 0){
                    //该订单已创建过售后工单
                    Helper::EasyThrowException('70042',__FILE__.__LINE__);
                }

                //订单日志
                $ord_log_data = [
                    'ord_id'    => $order_info['order_id'],
                    'user_id'   => $user_id,
                    'operater'  => $adm_username,
                    'platform'  => $platform,
                    'action'    => '订单售后',
                    'note'      => $platform . '管理员' . $adm_username . '创建售后订单，' . '售后单号【' . $data['service_order_no'] . '】',
                ];
                $this->orderRepository->recordOrderLog($ord_log_data);

                //添加售后单
                $ret = $this->model->create($data);

                //修改订单状态为售后
                $this->ordModel->where('order_no',$data['order_no'])->update(['order_status'=>ORDER_STATUS_AFTERSALE]);
            }else{
                //订单为满足售后条件
                Helper::EasyThrowException('70044',__FILE__.__LINE__);
            }

        }else{
            $job_info = $this->model->where('job_id',$data['job_id'])->first();

            //分销商修改售后单前先检查售后单是否被处理
            if($platform == config('common.sys_abbreviation')['agent']){
                if($job_info['job_status'] != ORDER_AFTER_STATUS_UNPROCESSED){
                    //该售后单已被处理或归档，不可修改
                    Helper::EasyThrowException('70088',__FILE__.__LINE__);
                }
            }

            $priKeyValue = $data['job_id'];
            unset($data['job_id']);
            $ret =$this->model->where('job_id',$priKeyValue)->update($data);

        }
        return $ret;

    }

    /**
     * 审核归档
     * @param $id
     * @return
     */
    public function examine($param,$platform,$adm_username)
    {
        $job_info = $this->model->where('job_id',$param['job_id'])->first();

        if($param['job_handle'] == ORDER_AFTER_REFUND_MONEY || $param['job_handle'] == ORDER_AFTER_REFUND_GOODS){
            //退款、退货退款情况，需处理余额退款
            $order_info = $this->orderRepository->getOrderInfo('',$job_info['order_no']);

            //新增订单操作日志
            $ord_log_data = [
                'ord_id' => $order_info['order_id'],
                'operater' => $adm_username,
                'platform' => $platform,
                'action' => '售后处理',
                'note' => $platform . '管理员' . $adm_username . '操作处理售后订单退款，' . '订单号【' . $order_info['order_no'] . '】',
            ];
            $this->orderRepository->recordOrderLog($ord_log_data);

            //订单金额退款到账户余额
            $this->orderRepository->refundToBalance($order_info['order_id'],$adm_username,$platform,session("admin")['oms_adm_id']);
        }
        //更新售后单状态
        $data = $this->serviceHandle($param,true);  //得到组装数据
        $ret =$this->model->where('job_id',$data['job_id'])->update($data);

        return $ret;

    }

    /**
     * 删除(软删除)
     * @param $id 售后单id $operater 操作人 $platform 平台 $user_id 操作人id
     * @return bool
     */
    public function deleteJob($id,$operater,$platform,$user_id)
    {
        $job_info = $this->model->where('job_id',$id)->first();
        $order_data['order_status'] = ORDER_STATUS_WAIT_RECEIVE; //待收货、已发货
        if ($job_info['job_good_status'] == ORDER_AFTER_GOOD_STATUS_RECEIVER){
            //已收到货
            $order_data['order_status'] = ORDER_STATUS_FINISH; //交易完成、已收货
        }

        //回撤订单状态
        $this->ordModel->where('order_no',$job_info['order_no'])->update($order_data);

        $model = $this->model->find($id);
        $model->delete();

        //订单信息
        $order_info = $this->orderRepository->getOrderInfo('',$job_info['order_no']);

        //操作日志
        $ord_log_data = [
            'ord_id' => $order_info['order_id'],
            'user_id' => $user_id,
            'operater' => $operater,
            'platform' => $platform,
            'action' => '删除售后订单',
            'note' => $platform . '管理员' . $operater . '删除售后订单，' . '订单号【' . $job_info['order_no'] . '】',
        ];
        $this->orderRepository->recordOrderLog($ord_log_data);

        if($model->trashed()){
            return true;
        }else{
            return true;
        }

    }

    /**
     * 处理保存数据
     * @param $param
     * @return array
     */
    public function getSaveData($data)
    {
        $ord_info = $this->ordModel->where('order_no',$data['order_no'])->select('order_id','order_relation_no','order_real_total','user_id','cha_id','is_exchange')->first();
        if(empty($ord_info)){
            //订单不存在
            Helper::EasyThrowException('70030',__FILE__.__LINE__);
        }
        if($data['refund_money'] > $ord_info['order_real_total'] && $ord_info['is_exchange'] == PUBLIC_NO){
            //退款金额不能超过订单金额
            Helper::EasyThrowException('70039',__FILE__.__LINE__);
        }

        if($ord_info['cha_id'] == CHANEL_TERMINAL_AGENT){
            //分销
            $user_info = $this->dmsAccountModel->where(['agent_info_id'=>$ord_info['user_id'],'is_main'=>PUBLIC_YES])->select('dms_adm_username')->first();
            $name = $user_info['dms_adm_username'];
        }else{
            //会员
            $user_info =$this->userModel->where(['user_id'=>$ord_info['user_id']])->select('user_name')->first();
            $name = $user_info['user_name'];
        }

        //申请人
        $data['operator'] = isset($name) ? $name : '';

        $data['service_order_no'] = Helper::generateNo('03');
        $data['outer_order_no'] = $ord_info['order_relation_no'];

        return $data;
    }

    /**
     * 售后单详情
     * @id $id 售后单id
     * @return array
     */
    public function serviceInfo($id)
    {
        $data = $this->model->with('orderInfo')->where(['job_id'=>$id,'mch_id'=>$this->mch_id])->first();

        //快递方式
        $info = $data;
        $delivery_code = $info->toArray()['order_info']['delivery_code'];
        $delivery_id = $this->deliveryDocModel->where("delivery_code",$delivery_code)->value("delivery_id");
        $delivery_info = $this->expressModel->where('express_id',$delivery_id)->select('express_name')->first();
        $data['express_name'] = $delivery_info['express_name'];

        //凭证图处理
        $data['job_service_voucher'] = explode(",",$data['job_service_voucher']);
        $data['job_handel_voucher'] = explode(",",$data['job_handel_voucher']);

        //售后原因
        $reason =  $this->serviceReasonRepository->getRow(['service_reason_id'=>$data['job_reason']]);
        $data['job_reason_text'] = $reason['reason'];

        //责任认定
        $responsibility =  $this->serviceReasonRepository->getRow(['service_reason_id'=>$data['job_responsibility']]);
        $data['job_responsibility_text'] = $responsibility['reason'];


        //订单详情
       $prod_info = $this->ordProductModel->where("ord_id",$data['orderInfo']['order_id'])->select('ord_prod_id','sku_id','prod_id','prod_pages','prod_num','prod_sale_price','coupon_id','prj_type','prj_id','prod_cost')->orderBy("prj_type")->get();

       foreach ($prod_info as $k=>$v){
           //货品信息
           $sku_info = $this->skuModel->where('prod_sku_id',$v['sku_id'])->select('prod_sku_price','prod_sku_sn')->first();
           $prod_info[$k]['prod_sku_price'] = $sku_info['prod_sku_price'];
           $prod_info[$k]['prod_sku_sn'] = $sku_info['prod_sku_sn'];

           //作品信息
           if($v['prj_type'] == WORKS_FILE_TYPE_DIY) {
               //DIY类
               $proj_info = $this->projectModel->where('prj_id',$v['prj_id'])->select("prj_page_num","prj_image","prj_name","prj_sn")->first();
               $prod_info[$k]['prj_image'] = $proj_info['prj_image'];
               $prod_info[$k]['prj_name'] = $proj_info['prj_name'];
               $prod_info[$k]['prj_sn'] = $proj_info['prj_sn'];
               $prod_info[$k]['prj_page_num'] = $proj_info['prj_page_num'];

           }elseif($v['prj_type'] == WORKS_FILE_TYPE_UPLOAD){
               //稿件类
                $proj_info = $this->manuscriptModel->where('script_id',$v['prj_id'])->select("prj_page_num")->first();
               $prod_info[$k]['prj_page_num'] = $proj_info['prj_page_num'];

           }

           //商品信息
           $goods_info = $this->productModel->where('prod_id',$v['prod_id'])->select('prod_name')->first();
           $prod_info[$k]['prod_name'] = $goods_info['prod_name'];
           $prod_info[$k]['prod_main_thumb'] = $this->mediaRepository->getProductPhoto($v['prod_id'])[0]['prod_md_path'];
           $prod_info[$k]['prod_att_str'] = $this->productsRelationAttrRepository->getProductAttr($v['sku_id']);

           //总数量
           $data['nums'] += $v['prod_num'];

           //订单成本
           $data['prod_cost'] += $v['prod_cost'];

       }
        $data['prod_info'] = $prod_info;
//       dd($data->toArray());
        return $data;
    }

    /**
     * 售后处理
     * @param $param
     * @return array
     */
    public function serviceHandle($param,$is_examine=null)
    {
        //订单信息
        $job_info = $this->model->where('job_id',$param['job_id'])->select('order_no')->first();
        $order_info = $this->ordModel->where('order_no',$job_info['order_no'])->select('order_real_total','order_id','order_no')->first();

        $param['job_status'] = ORDER_AFTER_STATUS_PROCESSED; //已处理
        if($param['job_handle'] == ORDER_AFTER_PREFERENTIAL){
            //协商优惠
            $param['discount_money'] = $param['input'];

        }elseif($param['job_handle'] == ORDER_AFTER_REFUND_MONEY){
            //仅退款
            $param['refund_money'] = $param['input'];
            if($param['refund_money'] > $order_info['order_real_total']){
                //退款金额不能超过订单金额
                Helper::EasyThrowException('70039',__FILE__.__LINE__);
            }

        }elseif($param['job_handle'] == ORDER_AFTER_REFUND_GOODS){
            //退货退款
            $param['refund_order_no'] = $param['input'];

        }elseif ($param['job_handle'] == ORDER_AFTER_OTHERS){
            //其他
            $param['job_remarks'] = $param['input'];

        }
        $param['handle_time'] = time();
        $param['handler'] = session("admin")["oms_adm_username"];
        unset($param['input']);

        if(!empty($is_examine)){
            //审核归档
            $param['job_status'] = ORDER_AFTER_STATUS_FILE;

            if(empty($param['refund_order_no'])){
                unset($param['refund_order_no']);
            }
            return $param;
        }else{
            $this->save($param);
            return true;
        }
    }

    /**
     * 下换货单
     * @param $param
     * @return array
     */
    public function exchangeOrder($param)
    {
        //售后单信息
        $job_info = $this->model->where('job_id',$param['job_id'])->first();

        //订单信息
        $order_info = $this->orderRepository->getOrderInfo('',$job_info['order_no']);

        //取换货商品对应订单详情并替换数量
        foreach ($param['exchange_item'] as $k=>$v){
            $ord_prod_info[$k] = $this->ordProductModel->where('ord_prod_id',$v)->first()->toArray();
            foreach ($param['item_num'] as $key=>$val){
                $arr = json_decode($val,true);
                if(isset($arr[$v])){
                    $ord_prod_info[$k]['prod_num'] = $arr[$v];
                }
            }
        }

        //订单状态为待收货、已发货或者交易完成、已收货，发货状态为已发货才可下换货单
        if($order_info['order_status'] == ORDER_STATUS_WAIT_RECEIVE || $order_info['order_status'] == ORDER_STATUS_FINISH || $order_info['order_status'] == ORDER_STATUS_AFTERSALE){
            $res = $this->createExchangeOrder($order_info['order_id'],$param,$ord_prod_info);
            if($res){
                return true;
            }
        }else{
            //订单为满足换货条件
            Helper::EasyThrowException('70037',__FILE__.__LINE__);
        }
    }

    /**
     * 创建换货单
     * @param $order_id订单id $param收货人信息 $item订单详情表数据
     * @return array
     */
    public function createExchangeOrder($order_id,$param,$item)
    {
        //订单信息
        $order_info = $this->orderRepository->getOrderInfo($order_id);

        $exist_barter = $this->barterModel->where('old_order_no',$order_info['order_no'])->get();
        if(count($exist_barter) > 0){
            //创建失败,该订单已有换货单
            Helper::EasyThrowException('70040',__FILE__.__LINE__);
        }

        //获取合作代码
        $code = $this->dmsAgentInfoRepository->getCodeById($order_info['user_id']);

        //生成换货订单号
        $exchange_oreder_no = Helper::generateNo($code);

        //订单主表数据
        $order_data = [
            'mch_id'                =>      $this->mch_id,
            'user_id'               =>      $order_info['user_id'],
            'cha_id'                =>      $order_info['cha_id'],
            'order_no'              =>      $exchange_oreder_no,
            'order_status'          =>      ORDER_STATUS_WAIT_PRODUCE, //待生产 已付款
            'order_prod_status'     =>      ORDER_NO_PRODUCE, //未生产
            'order_comf_status'     =>      ORDER_CONFIRMED, //已确认
            'order_pay_status'      =>      ORDER_PAYED , //已付款
            'order_shipping_status' =>      ORDER_UNSHIPPED, //未发货
            'order_conf_time'       =>      time(),
            'order_pay_time'        =>      time(),
            'order_real_total'      =>      0.00,
            'order_exp_fee'         =>      0.00,
            'is_exchange'           =>      PUBLIC_YES,//1为换货单
            'order_discount'        =>      $order_info['order_discount'],
            'order_tax_fee'         =>      $order_info['order_tax_fee'],
            'order_delivery_id'     =>      $order_info['order_delivery_id'],
            'order_bill_id'         =>      $order_info['order_bill_id'],
            'order_relation_no'     =>      $order_info['order_relation_no'],
            'order_pay_id'          =>      $order_info['order_pay_id'],
            'order_remark_user'     =>      $order_info['order_remark_user'],
            'order_remark_admin'    =>      $order_info['order_remark_admin'],
            'order_rcv_country'     =>      $order_info['order_rcv_country'],
            'order_rcv_user'        =>      !empty($param['order_rcv_user']) ? $param['order_rcv_user'] : $order_info['order_rcv_user'],
            'order_rcv_phone'       =>      !empty($param['order_rcv_phone']) ? $param['order_rcv_phone'] : $order_info['order_rcv_phone'],
            'order_rcv_province'    =>      !empty($param['province']) ? $param['province'] : $order_info['order_rcv_province'],
            'order_rcv_city'        =>      !empty($param['city']) ? $param['city'] : $order_info['order_rcv_city'],
            'order_rcv_area'        =>      !empty($param['district']) ? $param['district'] : $order_info['order_rcv_area'],
            'order_rcv_address'     =>      !empty($param['order_rcv_address']) ? $param['order_rcv_address'] : $order_info['order_rcv_address'],
            'order_rcv_zipcode'     =>      !empty($param['order_rcv_zipcode']) ? $param['order_rcv_zipcode'] : $order_info['order_rcv_zipcode'],
            'created_at'            =>      time()
        ];
        $ord_id = $this->ordModel->insertGetId($order_data);

        $product_id_str = '';
        //订单详情表
        foreach ($item as $k=>$v){
            $new_ord_prj_item_no = $exchange_oreder_no."-".count($item)."-".($k+1);
            $item[$k]['ord_id'] = $ord_id;
            $item[$k]['order_no'] = $exchange_oreder_no;
            $item[$k]['ord_prj_item_no'] = $new_ord_prj_item_no;
            $item[$k]['prod_sale_price'] = 0.00;
            $item[$k]['created_at'] = time();
            $item[$k]['pro_handel_type'] = WORKS_HANDEL_TYPE_UNPROCESSED;

            unset($item[$k]['ord_prod_id']);
            unset($item[$k]['delivery_code']);
            unset($item[$k]['updated_at']);
            unset($item[$k]['deleted_at']);

            if($k == count($item)-1){
                $product_id_str .= $v['prod_id'];
            }else{
                $product_id_str .= $v['prod_id'].',';
            }

            $order_prod_id = $this->ordProductModel->insertGetId($item[$k]);

            //添加作品队列处理信息
            if ($v['prj_type'] == WORKS_FILE_TYPE_DIY) {
                //合成队列
                $compound_data = [
                    'mch_id'                => $this->mch_id,
                    'works_id'              => $v['prj_id'],
                    'order_no'              => $exchange_oreder_no,
                    'order_prod_id'         => $order_prod_id,
                    'comp_queue_serv_id'    => app(Queue::class)->dispatch(),
                    'project_sn'            => $new_ord_prj_item_no,
                    'comp_queue_status'     => 'ready',
                    'created_at'            => time()
                ];
                app(AfterCreate::class)->createCompoundQueue($compound_data);

            }elseif ($v['prj_type'] == WORKS_FILE_TYPE_UPLOAD) {
                //下载队列
                $manuscript_info = $this->manuscriptModel->where('script_id',$v['prj_id'])->first();

                $arrFilePath = explode('||', $manuscript_info['script_url']);

                if(count($arrFilePath) > 1) {//地址多个被认定为是pdf形式
                    $type = WORKS_MANUSCRIPT_TYPE_PDF;
                } else {  //其他暂时都被认定为zip打包的形式
                    $type = WORKS_MANUSCRIPT_TYPE_ZIP;
                }

                foreach ($arrFilePath as $kk=>$vv) { //最多支持  封面||内页||封底的情况
                    if ($kk == 1) {
                        $pageType = GOODS_SIZE_TYPE_INNER;
                    } else if($kk == 0) {
                        $pageType = GOODS_SIZE_TYPE_COVER;
                    } else {
                        $pageType = GOODS_SIZE_TYPE_BACK;
                    }
                    //只有一个稿件则标识为整体的
                    if (count($arrFilePath) == 1) {
                        $pageType = ZERO; //0代表不分封面内页
                    }
                    $queueData = [
                        'mch_id'            => $this->mch_id,
                        'manuscript_id'     => $v['prj_id'],
                        'down_serv_id'      => app(Queue::class)->dispatch(),
                        'order_no'          => $exchange_oreder_no,
                        'order_prod_id'     => $order_prod_id,
                        'down_file_type'    => $type,  //稿件类型
                        'down_page_type'    => $pageType, //页面类型 封面 内页 封底
                        'down_url'          => $vv,
                        'down_status'       => 'ready',
                        'created_at'        => time()
                    ];
                    app(AfterCreate::class)->createDownloadQueue($queueData);
                }
            }
        }

        if(count($item) == 1 && $item[0]['prj_type'] == WORKS_FILE_TYPE_EMPTY){
            //订单只有一个商品且是实物，则队列状态为ready
            $queue_status = 'ready';
        }else{
            $queue_status = 'prepare';
        }

        //创建生产队列
        $produce_queue_data = [
            'mch_id'                        =>      $this->mch_id,
            'produce_queue_type'            =>      ORDER_PRODUCE_TYPE_AUTO,
            'produce_queue_status'          =>      $queue_status,
            'order_id'                      =>      $ord_id,
            'created_at'                    =>      time(),
        ];
        $this->ordPushQueueModel->create($produce_queue_data);

        //创建日志
        $log_data_exchange = [
            'ord_id'        =>  $ord_id,
            'operater'      =>  session("admin")['oms_adm_username'],
            'platform'      =>  config('common.sys_abbreviation')['merchant'],
            'action'        =>  '换货订单',
            'note'          =>  '原订单号【'.$order_info['order_no'].'】换货订单号【'.$exchange_oreder_no.'】 创建成功',
        ];
        $this->orderRepository->recordOrderLog($log_data_exchange);

        $log_data_create = [
            'ord_id'        =>  $ord_id,
            'operater'      =>  session("admin")['oms_adm_username'],
            'platform'      =>  config('common.sys_abbreviation')['merchant'],
            'action'        =>  '创建订单',
            'note'          =>  '【商户】 '.session("admin")['oms_adm_username'].'创建订单成功',
        ];
        $this->orderRepository->recordOrderLog($log_data_create);

        //修改售后单状态
        $job_data = [
            'job_responsibility'    => $param['job_responsibility'],
            'job_handel_voucher'    => $param['job_handel_voucher'],
            'job_handle'            => $param['job_handle'],
            'job_status'            => ORDER_AFTER_STATUS_PROCESSED, //已处理
            'handle_time'           => time(),
            'handler'               => session("admin")['oms_adm_username'],
        ];
        $this->model->where('job_id',$param['job_id'])->update($job_data);

        //记录到换货单列表
        $job_info = $this->model->where('job_id',$param['job_id'])->select('job_reason')->first();
        $exchange_data = [
            'old_order_no'              =>      $order_info['order_no'],
            'exchange_order_no'         =>      $exchange_oreder_no,
            'prod_id'                   =>      $product_id_str,
            'mch_id'                    =>      $this->mch_id,
            'job_id'                    =>      $param['job_id'],
            'admin_id'                  =>      session('admin')['oms_adm_id'],
            'bart_reason'               =>      $job_info['job_reason'],
            'bart_explain'              =>      $param['bart_explain'],
        ];
        $this->barterModel->create($exchange_data);

        return true;

    }

    /**
     * 售后单撤回
     * @param $job_id
     * @return array
     */
    public function withdraw($job_id)
    {
        $job_info = $this->model->where('job_id',$job_id)->first();
        $order_info = $this->orderRepository->getOrderInfo('',$job_info['order_no']);

        if(empty($job_info)){
            //该售后单记录不存在
            Helper::EasyThrowException('70046',__FILE__.__LINE__);
        }

        if($job_info['job_status'] != ORDER_AFTER_STATUS_UNPROCESSED){
            //售后单为已处理、已归档情况下，不可再撤回
            Helper::EasyThrowException('70047',__FILE__.__LINE__);
        }

        if($job_info['job_good_status'] == ORDER_AFTER_GOOD_STATUS_NOT_RECEIVER){
            //未收到货
            $order_data['order_status'] = ORDER_STATUS_WAIT_RECEIVE; //待收货、已发货
        }elseif ($job_info['job_good_status'] == ORDER_AFTER_GOOD_STATUS_RECEIVER){
            //已收到货
            $order_data['order_status'] = ORDER_STATUS_FINISH; //交易完成、已收货
        }

        //回撤订单状态
        $this->ordModel->where('order_no',$job_info['order_no'])->update($order_data);

        //修改售后单状态
        $this->model->where('job_id',$job_id)->update(['job_status'=>ORDER_AFTER_STATUS_WITHDRAW]);

        //订单日志
        $platform = config('common.sys_abbreviation')['agent'];
        $operater = session("admin")['dms_adm_username'];
        $ord_log_data = [
            'ord_id' => $order_info['order_id'],
            'operater' => $operater,
            'platform' => $platform,
            'action' => '撤回售后订单',
            'note' => $platform . '管理员' . $operater . '撤回售后订单，' . '订单号【' . $order_info['order_no'] . '】',
        ];
        $this->orderRepository->recordOrderLog($ord_log_data);

        return true;
    }

    //获取售后原因
    public function getServiceReason()
    {
        $arr = [];
        $list = $this->serviceReasonRepository->getType();
        foreach ($list as $k=>$v){
            $arr[$k] = [];
            $child_content = $this->serviceReasonRepository->getRows(['reason_pid'=>$k],'created_at')->toArray();
            foreach ($child_content as $key=>$val){
                $arr[$k][$key]['service_reason_id'] = $val['service_reason_id'];
                $arr[$k][$key]['reason'] = $val['reason'];
            }
        }
        return $arr;
    }

    /**
     * 导出处理
     * @param $param 创建时间
     */
    public function export($param)
    {
        //获取数据
        $result = $this->getJobExportData($param);

        if(empty($result)){
            echo '暂无记录';
            die;
        }

        $spreadsheet= new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        //设置sheet的名字  两种方法
        $spreadsheet->getActiveSheet()->setTitle('售后工单导出');

        //设置自动列宽
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(20);
        $sheet->getColumnDimension('C')->setWidth(10);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setAutoSize(25);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(25);
        $sheet->getColumnDimension('H')->setWidth(30);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(30);
        $sheet->getColumnDimension('K')->setWidth(15);
        $sheet->getColumnDimension('L')->setWidth(20);
        $sheet->getColumnDimension('M')->setWidth(30);
        $sheet->getColumnDimension('N')->setWidth(30);
        $sheet->getColumnDimension('O')->setWidth(15);
        $sheet->getColumnDimension('P')->setWidth(10);
        $sheet->getColumnDimension('Q')->setWidth(20);

        //设置第一行小标题
        $k = 1;
        $sheet->setCellValue('A'.$k, '日期');
        $sheet->setCellValue('B'.$k, '订单来源');
        $sheet->setCellValue('C'.$k, '订单渠道');
        $sheet->setCellValue('D'.$k, '供应商');
        $sheet->setCellValue('E'.$k, '订单号');
        $sheet->setCellValue('F'.$k, '原订单号');
        $sheet->setCellValue('G'.$k, '同步订单号');
        $sheet->setCellValue('H'.$k, '产品信息');
        $sheet->setCellValue('I'.$k, '售后类型');
        $sheet->setCellValue('J'.$k, '售后原因');
        $sheet->setCellValue('K'.$k, '快递方式');
        $sheet->setCellValue('L'.$k, '快递单号');
        $sheet->setCellValue('M'.$k, '售后说明');
        $sheet->setCellValue('N'.$k, '处理说明');
        $sheet->setCellValue('O'.$k, '退款金额');
        $sheet->setCellValue('P'.$k, '数量');
        $sheet->setCellValue('Q'.$k, '处理人');

        $k = 2;
        foreach ($result as $key => $value) {
            $e_value = $value['job_handle']==ORDER_AFTER_EXCHANGE ? $value['exchange_order_no'] : $value['order_no'];
            $f_value = $value['job_handle']==ORDER_AFTER_EXCHANGE ? $value['order_no'] : '';
            $sheet->setCellValue('A' . $k, date('Y-m-d H:i:s',$value['created_at']));
            $sheet->setCellValue('B' . $k, $value['agent_name']);
            $sheet->setCellValue('C' . $k, $value['cha_name']);
            $sheet->setCellValue('D' . $k, $value['suppliers']);
            $sheet->setCellValue('E' . $k, "\t".$e_value);
            $sheet->setCellValue('F' . $k, "\t".$f_value);
            $sheet->setCellValue('G' . $k, "\t".$value['outer_order_no']);
            $sheet->setCellValue('H' . $k, $value['product_info']);
            $sheet->setCellValue('I' . $k, $value['job_handle_text']);
            $sheet->setCellValue('J' . $k, $value['job_reason_text']);
            $sheet->setCellValue('K' . $k, $value['delivery_name']);
            $sheet->setCellValue('L' . $k, $value['order_info']['delivery_code']);
            $sheet->setCellValue('M' . $k, $value['job_note']);
            $sheet->setCellValue('N' . $k, $value['job_remarks']);
            $sheet->setCellValue('O' . $k, $value['refund']);
            $sheet->setCellValue('P' . $k, $value['total_num']);
            $sheet->setCellValue('Q' . $k, $value['handler']);
            $k++;
        }

        $file_name = '售后工单导出'.date('Y-m-d H:i:s',time());
        $file_name = $file_name . ".xlsx";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$file_name.'"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    /**
     * 导出售后工单数据组装
     * @param $param 创建时间
     */
    public function getJobExportData($where,$order='created_at desc')
    {
        $where = $this->parseWhere($where);
        $where['mch_id'] =$this->mch_id;

        if(isset($where['status'])){
            if ($where['status'] != 'all'){
                $where['job_status'] = $where['status'];
            }
            unset($where['status']);
        }

        //order 必须以 'id desc'这种方式传入.
        $orderBy = [];
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }
        $query = $this->model->with('orderInfo');

        //查询时间
        if(isset($where['time'])){
            if(!empty($where['created_at'])){
                if($where['time'] == 'apply'){
                    $where_field = 'created_at';
                }else{
                    $where_field = 'handle_time';
                }
                $created_at = $where['created_at'];
                $time_list = Helper::getTimeRangedata($created_at);
                $query = $query->whereBetween($where_field,[$time_list['start'],$time_list['end']]);
                unset($where['created_at']);
            }
            unset($where['time']) ;
        }

        if(!empty ($where)) {
            $query =  $query->where($where);
        }

        if(!empty($order)) {
            $query =  $query->orderBy($orderBy[0],$orderBy[1]);
        }

        $list = $query->get();

        $arr[] = '';
        foreach ($list as $k=>$v){
            $arr[$k] = $v->toArray();
            $prod_info = $this->ordProductModel->where("ord_id",$v['orderInfo']['order_id'])->select('prod_num','prod_id','sku_id','sp_id')->get()->toArray();
            $arr[$k]['total_num'] = 0;
            $arr[$k]['suppliers'] = '';
            $arr[$k]['product_info'] = '';
            foreach ($prod_info as $key=>$val){
                //商品总数量
                $arr[$k]['total_num'] += $val['prod_num'];

                $arr[$k]['order_item'][$key] = $val;
                if(count($prod_info) == $key + 1){
                    //供货商信息
                    $arr[$k]['suppliers'] .= $this->suppliersModel->where(['sup_id'=>$val['sp_id']])->value('sup_name');

                    //商品信息
                    $prod_name = $this->productModel->where(['prod_id'=>$val['prod_id']])->value('prod_name');
                    $sku_no = $this->skuModel->where(['prod_sku_id'=>$val['sku_id']])->value('prod_sku_sn');
                    $arr[$k]['product_info'] .= '产品名称：'.$prod_name.'，商品货号'.$sku_no;
                }else{
                    //供货商信息
                    $sp_name = $this->suppliersModel->where(['sup_id'=>$val['sp_id']])->value('sup_name');
                    $arr[$k]['suppliers'] .= $sp_name.'，';

                    //商品信息
                    $sku_no = $this->skuModel->where(['prod_sku_id'=>$val['sku_id']])->value('prod_sku_sn');
                    $prod_name = $this->productModel->where(['prod_id'=>$val['prod_id']])->value('prod_name');
                    $arr[$k]['product_info'] .= '产品名称：'.$prod_name.'，商品货号：'.$sku_no.'；';
                }

            }

            //售后处理方式
            $job_handle_arr = config('order.service_handle_type');
            $arr[$k]['job_handle_text'] = isset($v['job_handle']) ? $job_handle_arr[$v['job_handle']] : "";

            //售后原因
            $arr[$k]['job_reason_text'] = $this->ordReasonModel->where(['service_reason_id'=>$v['job_reason']])->value('reason');

            //快递
            $info = $v;
            $info = $info->toArray();
            $delivery_id = $this->deliveryDocModel->where("delivery_code",$info['order_info']['delivery_code'])->value("delivery_id");
            $delivery_info = $this->expressModel->where('express_id',$delivery_id)->select('express_name')->first();
            $arr[$k]['delivery_name'] = $delivery_info['express_name'];

            //状态
            $job_status_arr = config('order.service_status');
            $arr[$k]['job_status_text'] = $job_status_arr[$v['job_status']];

            $arr[$k]['refund'] = 0;
            $arr[$k]['exchange_order_no'] = '';
            if($v['job_handle'] == ORDER_AFTER_PREFERENTIAL){
                //协商优惠金额
                $arr[$k]['refund'] = $v['discount_money'];
            }elseif($v['job_handle'] == ORDER_AFTER_REFUND_MONEY || $v['job_handle'] == ORDER_AFTER_REFUND_GOODS){
                //退款、退货退款金额
                $arr[$k]['refund'] = $v['refund_money'];
            }else if($v['job_handle'] == ORDER_AFTER_EXCHANGE){
                //换货，取换货单号
                $arr[$k]['exchange_order_no'] = $this->barterModel->where(['old_order_no'=>$v['order_no']])->value('exchange_order_no');
            }

            //渠道
            $chanel = $this->chanelModel->where('cha_id',$info['order_info']['cha_id'])->select("cha_name")->first();
            $arr[$k]['cha_name'] = !empty($chanel) ? $chanel->cha_name : '';

            //店铺名称
            $agentInfo = $this->agentInfoModel->where('agent_info_id',$info['order_info']['user_id'])->select("agent_name")->first();
            $arr[$k]['agent_name'] = !empty($agentInfo) ? $agentInfo->agent_name : '';

        }
        $arr = $arr[0] == '' ? [] : $arr;

        return $arr;
    }
}
