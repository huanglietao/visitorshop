<?php
namespace App\Repositories;

use App\Models\DmsAgentInfo;
use App\Models\DmsMerchantAccount;
use App\Models\OmsCoupon;
use App\Models\OmsMerchantInfo;
use App\Models\SaasAreas;
use App\Models\SaasCart;
use App\Models\SaasCompoundQueue;
use App\Models\SaasCompoundService;
use App\Models\SaasDelivery;
use App\Models\SaasDeliveryDoc;
use App\Models\SaasDeliveryLog;
use App\Models\SaasDeliveryTemplate;
use App\Models\SaasDownloadQueue;
use App\Models\SaasExpress;
use App\Models\SaasInvoice;
use App\Models\SaasManuscript;
use App\Models\SaasNewSuppliersOrders;
use App\Models\SaasOrderBarter;
use App\Models\SaasOrderException;
use App\Models\SaasOrderLog;
use App\Models\SaasOrderPayLog;
use App\Models\SaasOrderProduceQueue;
use App\Models\SaasOrderProducts;
use App\Models\SaasOrderRefund;
use App\Models\SaasOrders;
use App\Models\SaasOrderTag;
use App\Models\SaasPayment;
use App\Models\SaasProducts;
use App\Models\SaasProductsSku;
use App\Models\SaasProjectPage;
use App\Models\SaasProjects;
use App\Models\SaasSalesChanel;
use App\Models\SaasSpDownloadQueue;
use App\Models\SaasSuppliers;
use App\Models\SaasSuppliersOrderProduct;
use App\Models\SaasSuppliersOrders;
use App\Models\SaasUser;
use App\Presenters\CommonPresenter;
use App\Services\ChanelUser;
use App\Services\Goods\Info;
use App\Services\Goods\Price;
use App\Services\Helper;
use App\Services\Logistics;
use App\Services\Orders\LogisticsInfo;
use App\Services\Orders\Production;
use App\Services\Orders\Status;
use App\Exceptions\CommonException;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


/**
 * 订单仓库
 * @author: cjx
 * @version: 1.0
 * @date: 2020/04/14
 */

class SaasOrdersRepository extends BaseRepository
{
    protected $mch_id;
    protected $agent_id;

    public function __construct(SaasOrders $orders,SaasProducts $products,DmsAgentInfo $agentInfo,SaasSalesChanel $chanel,
                                SaasExpress $express,SaasAreasRepository $areasRepository,SaasOrderProducts $orderProducts,
                                SaasProductsSku $productsSku,OmsCoupon $omsCoupon,SaasProjects $projects,SaasManuscript $manuscript,
                                SaasSuppliers $suppliers,DmsMerchantAccount $dmsMerchantAccount,SaasUser $user,SaasInvoice $saasInvoice,
                                SaasOrderLog $orderLog,SaasOrderPayLog $orderPayLog,SaasDeliveryDoc $deliveryDoc,
                                SaasProductsRelationAttrRepository $productsRelationAttrRepository,SaasPayment $payment,
                                SaasOrderProduceQueue $orderProduceQueue,SaasOrderTag $orderTag,SaasCompoundQueue $compoundQueue,
                                SaasCompoundService $compoundService,SaasProjectPage $projectPage,SaasDownloadQueue $downloadQueue,
                                SaasProductsMediaRepository $mediaRepository,SaasDelivery $saasDelivery,SaasDeliveryLog $deliveryLog,
                                SaasSuppliersOrderProduct $suppliersOrderProduct,SaasSuppliersOrders $suppliersOrders,SaasOrderException $orderException,
                                SaasOrderProduceQueueRepository $orderProduceQueueRepository,SaasPaymentRepository $paymentRepository,
                                DmsAgentInfoRepository $dmsAgentInfoRepository,SaasDeliveryTemplate $saasDeliveryTemplate,SaasOrderRefund $orderRefund,
                                SaasCustomerBalanceLogRepository $balanceLogRepository,SaasSpDownloadQueue $spDownloadQueue,OmsMerchantInfo $merchantInfo,
                                SaasNewSuppliersOrders $newSuppliersOrders,SaasProductsSkuRepository $skuRepository,SaasDiyAssistantRepository $assistantRepository,
                                SaasProjectsRepository $projectsRepository,SaasOrderBarter $orderBarter)
    {
        $this->mch_id = isset(session("admin")['mch_id']) ? session("admin")['mch_id'] : PUBLIC_CMS_MCH_ID;
        $this->agent_id = isset(session('admin')['agent_info_id']) ? session('admin')['agent_info_id'] : '';

        $this->model =$orders;
        $this->prodMoel = $products;
        $this->agentInfoModel = $agentInfo;
        $this->chanelModel = $chanel;
        $this->expressModel = $express;
        $this->orderProductModel = $orderProducts;
        $this->skuModel = $productsSku;
        $this->omsCouponModel = $omsCoupon;
        $this->projectModel = $projects;
        $this->manuscriptModel = $manuscript;
        $this->supplierModel = $suppliers;
        $this->dmsAccountModel = $dmsMerchantAccount;
        $this->userModel = $user;
        $this->invoiceModel = $saasInvoice;
        $this->logModel = $orderLog;
        $this->payLogModel = $orderPayLog;
        $this->deliveryDocModel = $deliveryDoc;
        $this->paymentModel = $payment;
        $this->orderProduceQueueModel = $orderProduceQueue;
        $this->orderTagModel = $orderTag;
        $this->compoundQueueModel = $compoundQueue;
        $this->compoundServiceModel = $compoundService;
        $this->projectPageModel = $projectPage;
        $this->downloadQueueModel = $downloadQueue;
        $this->deliveryModel = $saasDelivery;
        $this->deliveryLogModel = $deliveryLog;
        $this->spOrderProductModel = $suppliersOrderProduct;
        $this->spOrderModel = $suppliersOrders;
        $this->ordExceptionModel = $orderException;
        $this->deliveryTemplateModel = $saasDeliveryTemplate;
        $this->ordRefundModel = $orderRefund;
        $this->spDownloadModel = $spDownloadQueue;
        $this->merchantInfoModel = $merchantInfo;
        $this->newSpOrderModel = $newSuppliersOrders;
        $this->orderBarter = $orderBarter;

        $this->areaRepository = $areasRepository;
        $this->prodRelationAttrRepository = $productsRelationAttrRepository;
        $this->mediaRepository = $mediaRepository;
        $this->ordProdQueueRepository = $orderProduceQueueRepository;
        $this->paymentRepository = $paymentRepository;
        $this->agentInfoRepository = $dmsAgentInfoRepository;
        $this->balanceLogRepository = $balanceLogRepository;
        $this->skuRepository = $skuRepository;
        $this->diyAssistantRepository = $assistantRepository;
        $this->projectsRepository = $projectsRepository;

    }

    /**
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getTableList($where=null, $order='created_at desc')
    {
        $limit = isset($where['limit']) ? $where['limit']:config('common.page_limit');  //这个10取配置里的

        $where['mch_id'] = isset(session("admin")['mch_id']) ? session("admin")['mch_id'] : '';
        $where = $this->parseWhere($where);

        $orwhere = [];
        if(isset($where['status'])){
            //按订单状态查询
            $order_status = [
                'ALL'                                 =>          ZERO,   //全部()
                'ORDER_STATUS_WAIT_CONFIRM'           =>          ORDER_STATUS_WAIT_CONFIRM,   //待确认
                'ORDER_STATUS_WAIT_PAY'               =>          ORDER_STATUS_WAIT_PAY,       //待付款  已确认
                'ORDER_STATUS_WAIT_PRODUCE'           =>          ORDER_STATUS_WAIT_PRODUCE,   //待生产  已付款
                'ORDER_STATUS_WAIT_DELIVERY'          =>          ORDER_STATUS_WAIT_DELIVERY,  //待发货  已生产
                'ORDER_STATUS_WAIT_RECEIVE'           =>          ORDER_STATUS_WAIT_RECEIVE,   //待收货  已发货
                'ORDER_STATUS_CANCEL'                 =>          ORDER_STATUS_CANCEL,         //交易取消
                'ORDER_STATUS_AFTERSALE'              =>          ORDER_STATUS_AFTERSALE,      //售后
                'ORDER_STATUS_FINISH'                 =>          ORDER_STATUS_FINISH,         // 交易完成  已收货
            ];
            if($order_status[$where['status']] != ZERO){
                $where['order_status'] = $order_status[$where['status']];
            }
            unset($where['status']);
        }

        //按渠道查询
        $where_info = [];
        if(isset($where['chanel'])){
            if($where['chanel'] != 'all'){
                $where_info['cha_id'] = $where['chanel'];
            }
            unset($where['chanel']);
        }

        if(!empty($this->agent_id)){
            //分销订单
            $where['user_id'] = $this->agent_id;
        }

        //order 必须以 'id desc'这种方式传入.
        $orderBy = [];
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }

        $where_sku = [];
        $sys_flag = PUBLIC_YES; //标识cms与oms、cms按货号查询时情况，1为oms、oms，0为cms
        if(isset($where['sku_sn'])){
            if($this->mch_id == PUBLIC_CMS_MCH_ID){
                //cms系统mid=0时情况
                $sys_flag = PUBLIC_NO;
                //cms下取符合货号的所有货品id
                $sku_ids = $this->skuModel->where('prod_sku_sn',$where['sku_sn'])->pluck('prod_sku_id')->toArray();
                $where_sku = $sku_ids;
            }else{
                //货号转换货号id
                $sku_data = $this->skuRepository->getGoodstype($where['sku_sn'],$this->mch_id);
                if($sku_data['code'] == PUBLIC_YES){
                    $where_sku['sku_id'] = $sku_data['sku_id'];
                }else{
                    //未找到货号对应货品id则用空值
                    $where_sku['sku_id'] = '';
                }
            }
            unset($where['sku_sn']);
        }

        //店铺来源查询
        if(isset($where['user_id'])){
            if($where['user_id']  == PUBLIC_NO){
                unset($where['user_id']);
            }
        }

        $query = $this->model->orWhereHas(
            'item',function($query) use ($where_sku,$sys_flag) {
                if (!empty($where_sku)) {
                    if($sys_flag != PUBLIC_YES){
                        //cms货号查询
                        return $query->whereIn('sku_id',$where_sku);
                    }else{
                        //oms、cms货号查询
                        return $query->where($where_sku);
                    }
                }
            })->where(function($query) use ($where_info) {
            if (!empty($where_info)) {
                return $query->where($where_info);
            }
        })->with(['item','chanel']);
        

        //查询时间
        if(isset($where['created_at'])){
            $created_at = $where['created_at'];
            $time_list = Helper::getTimeRangedata($created_at);
            if((!isset($where['order_status'])) || (isset($where['order_status']) && $where['order_status'] != ORDER_STATUS_FINISH)){
                $query = $query->whereBetween("created_at",[$time_list['start'],$time_list['end']]);
                unset($where['created_at']);
            }
        }

        //已完成包括已发货+已完成
        if(isset($where['order_status']) && $where['order_status'] == ORDER_STATUS_FINISH){
            $index = 0;
            $where_data = $where;
            $where_data['order_status'] = ORDER_STATUS_WAIT_RECEIVE;
            foreach ($where_data as $k=>$v){
                if($k == 'created_at'){
                    $orwhere[$index] = ['created_at','>=',$time_list['start']];
                    $index++;
                    $orwhere[$index] = ['created_at','<=',$time_list['end']];

                    $where[] = ['created_at','>=',$time_list['start']];
                    $where[] = ['created_at','<=',$time_list['end']];
                    unset($where['created_at']);
                }else{
                    $orwhere[$index] = [$k,$v];
                }
                $index++;
            }
        }

        if(!empty ($where)) {
            $query =  $query->where($where)->orWhere($orwhere);
        }

        if(!empty($order)) {
            $query =  $query->orderBy($orderBy[0],$orderBy[1]);
        }

        $list = $query->paginate($limit);

        $arr[] = '';
        foreach ($list as $k=>$v){
            $arr[$k] = $v->toArray();
            $arr[$k]['total'] = count($v->item); //item数量
            $nums = 0; //总件数
            foreach ($v->item as $key=>$val){
                $nums += $val->prod_num;

                //商品信息
                $prod_info = $this->prodMoel->where('prod_id',$val->prod_id)->select("prod_name")->first();
                $arr[$k]['item'][$key]['prod_name'] = isset($prod_info->prod_name) ? $prod_info->prod_name : '';
                $arr[$k]['item'][$key]['prod_main_thumb'] = $this->mediaRepository->getProductPhoto($val->prod_id)[0]['prod_md_path'];
                $arr[$k]['item'][$key]['attr_str'] = $this->prodRelationAttrRepository->getProductAttr($val->sku_id);

                //货品信息
                $sku_info = $this->skuModel->where('prod_sku_id',$val->sku_id)->select('prod_sku_price','prod_supplier_sn')->first();
                $arr[$k]['item'][$key]['prod_sku_price'] = $sku_info['prod_sku_price'];
                $arr[$k]['item'][$key]['prod_supplier_sn'] = $sku_info['prod_supplier_sn'];
            }
            $arr[$k]['nums'] = $nums;

            //店铺名称
            $agentInfo = $this->agentInfoModel->where('agent_info_id',$v['user_id'])->select("agent_name")->first();
            $arr[$k]['agent_name'] = !empty($agentInfo) ? $agentInfo->agent_name : '';

            $mch_name = $this->merchantInfoModel->where('mch_id',$v['mch_id'])->value('mch_name');
            $arr[$k]['mch_name'] = !empty($mch_name) ? $mch_name : '';


            //渠道
            $chanel = $this->chanelModel->where('cha_id',$v['cha_id'])->select("cha_name")->first();
            $arr[$k]['cha_name'] = !empty($chanel) ? $chanel->cha_name : '';

            //订单标签
            $tag_arr = explode(",",$v['order_tag_id']);
            foreach ($tag_arr as $kk=>$vv){
                $tag_info = $this->orderTagModel->where('tag_id',$vv)->select('tag_name')->first();
                $arr[$k]['tag_name'][$kk] = $tag_info['tag_name'];
            }

            //物流转换处理
            if($v['order_status'] == ORDER_STATUS_WAIT_RECEIVE || $v['order_shipping_status'] == ORDER_SHIPPED){
                //已发货状态下
                $delivery_id = $this->deliveryDocModel->where("delivery_code",$v['delivery_code'])->value("delivery_id");
                $delivery_info = $this->expressModel->where('express_id',$delivery_id)->select('express_name')->first();
                $arr[$k]['delivery_name'] = $delivery_info['express_name'];
            }else{
                //未发货状态下
                $delivery_info = $this->deliveryModel->where("delivery_id",$v['order_delivery_id'])->select("delivery_name")->first();
                $arr[$k]['delivery_name'] = $delivery_info['delivery_name'];
            }

        }
        $arr = $arr[0] == '' ? [] : $arr;
        $list = $list->toArray();
        $list['data'] = $arr;
//dd($list['data']);
        return $list;
    }


    /**
     * 新增/修改
     * @param $data
     * @return boolean
     */
    public function save($data)
    {
        if(empty($data['id'])) {
            unset($data['id']);
            $ret = $this->model->create($data);
        } else {
            $priKeyValue = $data['id'];
            unset($data['id']);
            $ret =$this->model->where('order_id',$priKeyValue)->update($data);
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

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }

    /**
     *  各状态订单数量统计
     * @return array
     */
    public function orderStatusCount()
    {
//        if(!empty($this->agent_id)){
//            //分销订单
//            $where['user_id1'] = $this->agent_id;
//        }
        $where = [];
        if(!empty($this->mch_id)){
            $where['mch_id'] = $this->mch_id;
            $orwhere = [['order_status',ORDER_STATUS_WAIT_RECEIVE],['mch_id',$this->mch_id]];
        }else{
            $orwhere = [['order_status',ORDER_STATUS_WAIT_RECEIVE]];
        }

        $all = count($this->model->where($where)->get());                                                                  //全部
        $wait_confirm = count($this->model->where('order_status',ORDER_STATUS_WAIT_CONFIRM)->where($where)->get());        //待确认
        $wait_pay = count($this->model->where('order_status',ORDER_STATUS_WAIT_PAY)->where($where)->get());                //待支付
        $wait_produce = count($this->model->where('order_status',ORDER_STATUS_WAIT_PRODUCE)->where($where)->get());        //待配货
        $wait_delivery = count($this->model->where('order_status',ORDER_STATUS_WAIT_DELIVERY)->where($where)->get());      //待发货
        $cancel = count($this->model->where('order_status',ORDER_STATUS_CANCEL)->where($where)->get());                    //取消
        $aftersale = count($this->model->where('order_status',ORDER_STATUS_AFTERSALE)->where($where)->get());              //退换货
        $finish = count($this->model->where('order_status',ORDER_STATUS_FINISH)->where($where)->orWhere($orwhere)->get()); //已完成

        return [$all,$wait_confirm,$wait_pay,$wait_produce,$wait_delivery,$finish,$cancel,$aftersale];
    }

    /**
     * 订单是否存在
     * @param $orderNo 订单号
     */
    public function isOrderExists($orderNo)
    {
        return $this->model->where('order_no',$orderNo)->count();
    }

    /**
     *  获取订单详情
     *  param $oid 订单id
     * @return array
     */
    public function orderInfo($oid)
    {
        $data = $this->model->where('order_id',$oid)->first();

        //物流转换处理
        if($data['order_status'] == ORDER_STATUS_WAIT_RECEIVE || $data['order_shipping_status'] == ORDER_SHIPPED){
            //已发货状态下
            $delivery_id = $this->deliveryDocModel->where("delivery_code",$data['delivery_code'])->value("delivery_id");
            $delivery_info = $this->expressModel->where('express_id',$delivery_id)->select('express_name')->first();
            $data['delivery_name'] = $delivery_info['express_name'];
        }else{
            //未发货状态下
            $delivery_info = $this->deliveryModel->where("delivery_id",$data['order_delivery_id'])->select("delivery_name")->first();
            $data['delivery_name'] = $delivery_info['delivery_name'];
        }

        //店铺名称
        $agentInfo = $this->agentInfoModel->where('agent_info_id',$data['user_id'])->select("agent_name")->first();
        $data['agent_name'] = $agentInfo['agent_name'];

        //渠道
        $chanel = $this->chanelModel->where('cha_id',$data['cha_id'])->select("cha_name")->first();
        $data['cha_name'] = $chanel['cha_name'];

        //省市区转换
        $province = $this->areaRepository->getAreaIdList($data['order_rcv_province']);
        $city = $this->areaRepository->getAreaIdList($data['order_rcv_city']);
        $area = $this->areaRepository->getAreaIdList($data['order_rcv_area']);

        $data['province_name'] = !empty($province) ? $province['area_name'] : '';
        $data['city_name'] = !empty($city) ? $city['area_name'] : '';
        $data['area_name'] = !empty($area) ? $area['area_name'] : '';

        //支付流水号
        $pay_log_info = $this->payLogModel->where("order_no",$data['order_no'])->select("outer_trade_no")->first();
        $data['outer_trade_no'] = $pay_log_info['outer_trade_no'];

        //支付方式
        $payment_info = $this->paymentModel->where('pay_id',$data['order_pay_id'])->select('pay_name')->first();
        $data['pay_name'] = $payment_info['pay_name'];

        //商品信息数据组装
        $prod_info = $this->orderProductModel->where("ord_id",$data['order_id'])->select('ord_prod_id','prod_id','prod_sale_price','prod_num','sku_id','coupon_id','prj_type','prj_id','sp_id','pro_handel_type','ord_prj_item_no')->get()->toArray();

        $discount = 0; //折扣金额
        $prod_amount = 0; //商品总金额

        foreach ($prod_info as $k=>$v){

            //商品信息
            $info = $this->prodMoel->where('prod_id',$v['prod_id'])->select('prod_name','prod_sn')->first();
            $prod_info[$k]['prod_name'] = $info['prod_name'];
            $prod_info[$k]['prod_main_thumb'] = $this->mediaRepository->getProductPhoto($v['prod_id'])[0]['prod_md_path'];
            $prod_info[$k]['prod_sn'] = $info['prod_sn'];
            $prod_amount += $v['prod_sale_price'];

            //商品属性
            $prod_info[$k]['prod_attr_str'] = $this->prodRelationAttrRepository->getProductAttr($v['sku_id']);

            //货品信息
            $sku_info = $this->skuModel->where('prod_sku_id',$v['sku_id'])->select('prod_sku_sn','prod_sku_weight','prod_sku_price','prod_sku_cost')->first();
            $prod_info[$k]['sku_sn'] = $sku_info['prod_sku_sn'];
            $prod_info[$k]['prod_sku_weight'] = $sku_info['prod_sku_weight'];
            $prod_info[$k]['prod_sku_price'] = $sku_info['prod_sku_price'];
            $prod_info[$k]['prod_sku_cost'] = $sku_info['prod_sku_cost'];

            //优惠券信息
            $cou_info = $this->omsCouponModel->where('cou_id',$v['coupon_id'])->select('cou_name','cou_denomination')->first();
            $prod_info[$k]['cou_name'] = $cou_info['cou_name'];
            $prod_info[$k]['cou_denomination'] = $cou_info['cou_denomination'];
            $discount += $cou_info['cou_denomination'];

            //小计
            $prod_info[$k]['subtotal'] = $v['prod_sale_price'];

            //供应商
            $supplier_info = $this->supplierModel->where('sup_id',$v['sp_id'])->select('sup_name')->first();
            $prod_info[$k]['sp_name'] = $supplier_info['sup_name'];

            $user_id = 0;
            $cha_id = 0;
            if($v['prj_type'] == WORKS_FILE_TYPE_DIY){
                //DIY类
                $project_info = $this->projectModel->where('prj_id',$v['prj_id'])->select('prj_name','prj_sn','prj_page_num','prj_image','user_id','cha_id')->first();
                $prod_info[$k]['prj_name'] = $project_info['prj_name'];
                $prod_info[$k]['prj_sn'] = $project_info['prj_sn'];
                $prod_info[$k]['prj_image'] = $project_info['prj_image'];
                $prod_info[$k]['prj_page_num'] = $project_info['prj_page_num'];
                $user_id = $project_info['user_id'];
                $cha_id = $project_info['cha_id'];

                //作品处理状态
                if($v['pro_handel_type'] == WORKS_HANDEL_TYPE_PROCESSED){
                    //已处理
                    $prod_info[$k]['pro_type'] = '合成完成';
                }elseif($v['pro_handel_type'] == WORKS_HANDEL_TYPE_PROCESSING){
                    //处理中
                    $prod_info[$k]['pro_type'] = '合成中';
                }else{
                    //未处理
                    $prod_info[$k]['pro_type'] = '未合成';
                }

                //拼接封面内页下载url
//                $sp_download_info = $this->spDownloadModel->where(['ord_prod_id'=>$v['ord_prod_id']])->select('service_id','filename','filetype','path')->get()->toArray();
//                $prod_info[$k]['manuscript'] = [];
//                if(!empty($sp_download_info)){
//                    foreach ($sp_download_info as $key=>$val){
//                        $public_id = $this->compoundServiceModel->where('comp_serv_id',$val['service_id'])->value('public_ip');
//                        $prod_info[$k]['manuscript'][$key]['url'] = 'http://'.$public_id.'/'.$val['path'].'/'.$val['filename'];
//                        $prod_info[$k]['manuscript'][$key]['filetype'] = $val['filetype'];
//                    }
//                }

                //获取封面、内页下载url
                $prod_info[$k]['manuscript'] = [];
                $compound_queue_info = $this->compoundQueueModel->where(['order_prod_id'=>$v['ord_prod_id'],'comp_queue_status'=>'finish'])->first();
                if(!empty($compound_queue_info)){
                    $file_info = json_decode($compound_queue_info['comp_queue_file_info'],true);
                    if(strpos($file_info[0], 'http://') !== false){
                        //商务印刷文件
                        $urls_arr = $file_info;
                    }else{
                        //diy文件
                        $public_id = $this->compoundServiceModel->where('comp_serv_id',$compound_queue_info['comp_queue_serv_id'])->value('public_ip');
                        $urls_arr = app(Production::class)->getDownUrl($file_info);
                    }

                    $cover_flag = config('order.cover_flag');
                    foreach ($urls_arr as $key=>$val){
                        //判断是否为封面
                        if(strstr($val,$cover_flag )) {
                            //封面
                            $filetype = GOODS_SIZE_TYPE_COVER;
                        } else {
                            //内页
                            $filetype = GOODS_SIZE_TYPE_INNER;
                        }
                        $prod_info[$k]['manuscript'][$key]['filetype'] = $filetype;


                        if(strpos($file_info[0], 'http://') !== false){
                            //商务印刷文件
                            $prod_info[$k]['manuscript'][$key]['url'] = $val;
                        }else{
                            //diy文件
                            $prod_info[$k]['manuscript'][$key]['url'] = 'http://'.$public_id.'/'.$val;
                        }
                    }
                }

            }elseif($v['prj_type'] == WORKS_FILE_TYPE_UPLOAD){
                //稿件类
                $manuscript_info = $this->manuscriptModel->where('script_id',$v['prj_id'])->select('prj_page_num','user_id','cha_id')->first();
                $prod_info[$k]['prj_page_num'] = $manuscript_info['prj_page_num'];
                $user_id = $manuscript_info['user_id'];
                $cha_id = $manuscript_info['cha_id'];

                //作品处理状态
                if($v['pro_handel_type'] == WORKS_HANDEL_TYPE_PROCESSED){
                    //已处理
                    $prod_info[$k]['pro_type'] = '已处理';
                }elseif($v['pro_handel_type'] == WORKS_HANDEL_TYPE_PROCESSING){
                    //处理中
                    $prod_info[$k]['pro_type'] = '处理中';
                }else{
                    //未处理
                    $prod_info[$k]['pro_type'] = '未处理';
                }

            }

            //买家信息
            $prod_info[$k]['buyer_nickname'] = '';
            $chanel_info = $this->chanelModel->where('cha_id',$cha_id)->select('cha_flag')->first();
            if($chanel_info['cha_flag'] == CHANEL_TERMINAL_AGENT && $cha_id != 0){
                //分销会员
                $user_info = $this->dmsAccountModel->where('dms_adm_id',$user_id)->select('dms_adm_nickname')->first();
                $prod_info[$k]['buyer_nickname'] = $user_info['dms_adm_nickname'];

            }elseif($chanel_info['cha_flag'] == CHANEL_TERMINAL_USER && $cha_id != 0){
                //一般会员
                $user_info = $this->userModel->where('user_id',$user_id)->select('user_nickname')->first();
                $prod_info[$k]['buyer_nickname'] = $user_info['user_nickname'];
            }

        }

        $data['discount_amount'] = $discount;
        $data['prod_amount'] = $prod_amount;

        //发票信息
        if($data['order_bill_id'] != 0){
            $invoice_info = $this->invoiceModel->where('invoice_id',$data['order_bill_id'])->first();
            $data['inv_info'] = $invoice_info;
        }

        //订单日志
        $log_info = $this->logModel->where("ord_id",$data['order_id'])->orderBy('created_at')->select('created_at','action','note')->get();

        //旺旺号
        $data['wangwang'] = '';
        if(!empty($data['order_relation_no'])){
            $tb_info = $this->diyAssistantRepository->getOrderCacheData($data['order_relation_no'],$data['user_id']);
            if(!isset($tb_info['code'])){
                $tb_ord_info = json_decode($tb_info[0]['order_info'],true);
                $data['wangwang'] = $tb_ord_info['result']['trade']['buyer_nick'];
            }
        }

        $data['prod_info'] = $prod_info;
        $data['log_info'] = $log_info;
//dd($data->toArray());
        return $data;
    }

    /**
     *  取消订单
     *  param $oid 订单id $username 操作人 $platform 平台 $operater_id 操作人id
     * @return array
     */
    public function cancelOrder($oid,$username,$platform,$operater_id)
    {
        $data = $this->model->where("order_id",$oid)->first();

        if (empty($data)){
            //该订单记录不存在
            Helper::EasyThrowException('70030',__FILE__.__LINE__);
        }
        if($data['order_status'] == ORDER_STATUS_WAIT_CONFIRM || $data['order_status'] == ORDER_STATUS_WAIT_PAY || $data['order_status'] == ORDER_STATUS_WAIT_PRODUCE) {

            //更新订单状态
            $this->model->where("order_id", $oid)->update(['order_status' => ORDER_STATUS_CANCEL]);

            //新增订单操作日志
            $ord_log_data = [
                'ord_id'    => $oid,
                'user_id'   => $operater_id,
                'operater'  => $username,
                'platform'  => $platform,
                'action'    => '取消订单',
                'note'      => $platform . '管理员' . $username . '操作取消订单，' . '订单号【' . $data['order_no'] . '】',
            ];
            $this->logModel->create($ord_log_data);

            //订单金额退款到账户余额
            $this->refundToBalance($oid, $username, $platform, $operater_id);

            //更新作品状态为制作中
            $this->projectsRepository->changeProjectStatus($oid,$data['user_id'],$username);

            //删除订单对应作品合成队列或稿件下载队列
            $this->compoundQueueModel->where(['order_no'=>$data['order_no']])->update(['deleted_at'=>time()]);
            $this->downloadQueueModel->where(['order_no'=>$data['order_no']])->update(['deleted_at'=>time()]);

            //删除订单对应的生产队列
            $this->orderProduceQueueModel->where(['order_id'=>$oid])->update(['produce_queue_status'=>'error','deleted_at'=>time()]);

            if($data['is_exchange'] == PUBLIC_YES){
                //若是换货订单操作取消则删除对应换货单列表记录
                $this->orderBarter->where(['exchange_order_no'=>$data['order_no']])->update(['deleted_at'=>time()]);
            }

            return true;
        }elseif ($data['order_status'] == ORDER_STATUS_CANCEL){
            //该订单已被取消交易，请勿重复操作
            Helper::EasyThrowException('70087', __FILE__ . __LINE__);
        }else{
            //订单已提交生产，不可取消
            Helper::EasyThrowException('70031',__FILE__.__LINE__);
        }

    }

    /**
     *  获取单条订单信息
     *  param $oid 订单id
     * @return array
     */
    public function getOrderInfo($oid=null,$order_no=null)
    {
        $where_id['order_id'] = $oid;
        $where_no['order_no'] = $order_no;
        $where = empty($oid) ? $where_no :$where_id;
        $data = $this->model->where($where)->first();
        return $data;
    }


    /**
     *  获取订单商品、作品信息
     *  param $oid 订单id
     * @return array
     */
    public function productAndWork($oid)
    {
        $info = $this->orderProductModel->where("ord_id",$oid)->select('ord_id','prod_id','prod_sale_price','sku_id','prod_num','prod_pages','prj_type','prj_id','sp_id')->get()->toArray();

        foreach ($info as $k=>$v){
            //商品信息
            $product_info = $this->prodMoel->where("prod_id",$v['prod_id'])->select('prod_name')->first();
            $sku_info = $this->skuModel->where("prod_sku_id",$v['sku_id'])->select('prod_sku_sn')->first();

            $info[$k]['prod_name'] = $product_info['prod_name'];
            $info[$k]['prod_main_thumb'] = $this->mediaRepository->getProductPhoto($v['prod_id'])[0]['prod_md_path'];
            $info[$k]['prod_sku_sn'] = $sku_info['prod_sku_sn'];

            //作品信息
            if($v['prj_type'] == WORKS_FILE_TYPE_DIY) {
                //DIY类
                $work_info = $this->projectModel->where("prj_id",$v['prj_id'])->select('prj_name','prj_sn','prj_image')->first();
                $info[$k]['prj_name'] = $work_info['prj_name'];
                $info[$k]['prj_sn'] = $work_info['prj_sn'];
                $info[$k]['prj_image'] = $work_info['prj_image'];
            }

            //小计
            $info[$k]['subtotal'] = $v['prod_sale_price'] * $v['prod_num'];
        }

       return $info;
    }

    /**
     *  配货处理
     *  param $oid 订单id
     * @return array
     */
    public function distribution($oid)
    {
        $order_info = $this->model->where("order_id",$oid)->select('order_status')->first();

        if(empty($order_info)){
            //该订单记录不存在
            Helper::EasyThrowException(70030,__FILE__.__LINE__);
        }

        if($order_info['order_status'] == ORDER_STATUS_WAIT_PRODUCE){
            //待生产，已付款订单才可配货
            $res = app(Status::class)->updateToProducing($oid);
            if ($res){
                return true;
            }
        }else{
            //订单未满足配货条件
            Helper::EasyThrowException(70032,__FILE__.__LINE__);
        }
    }

    /**
     *  发货处理
     *  param $oid 订单id $data 发货数据 $operater 操作人 $platform 平台
     * @return array
     */
    public function delivery($oid,$data,$operater,$platform,$sp_ord_id=null)
    {
        $order_info = $this->model->where("order_id",$oid)->select('order_id','order_status','order_pay_status','order_prod_status','order_comf_status','mch_id','user_id','order_no','cha_id')->first();

        if(empty($order_info)){
            //该订单记录不存在
            Helper::EasyThrowException(70030,__FILE__.__LINE__);
        }

        if(empty($data['delivery_code'])){
            //请填写物流单号
            Helper::EasyThrowException(70033,__FILE__.__LINE__);
        }

        if(empty($sp_ord_id)){
            //商户、CMS发货，发货条件(总状态4，支付状态1，确认状态1,生产状态无法判断)
            if($order_info['order_status'] != ORDER_STATUS_WAIT_DELIVERY || $order_info['order_pay_status'] != ORDER_PAYED || $order_info['order_comf_status'] != ORDER_CONFIRMED){
                //订单未满足发货条件
                Helper::EasyThrowException(70034,__FILE__.__LINE__);
            }
        }


        $exist_code = $this->deliveryDocModel->where('delivery_code',$data['delivery_code'])->first();

        if(!empty($exist_code)){
            //物流单号已存在，请重新输入
            Helper::EasyThrowException(70035,__FILE__.__LINE__);
        }

        //组织供货商订单数据
        $sp_ord_data = [
            'express_id'                =>      $data['order_delivery_id'],
            'sp_delivery_code'          =>      $data['delivery_code'],
            'sp_order_status'           =>      SP_ORDER_STATUS_SEND,           //供货商订单状态(已发货)
            'new_sp_order_status'       =>      ORDER_STATUS_WAIT_RECEIVE,      //订单总状态(待收货、已发货)
            'new_sp_delivery_status'    =>      ORDER_SHIPPED,                  //发货状态(已发货)
        ];

        if(!empty($sp_ord_id)){
            //供货商操作发货(现流程弃用)
            //更新订单详情表，针对订单详情ID更新物流
            $ord_prod_ids = $this->spOrderProductModel->where('sp_ord_id',$sp_ord_id)->select('ord_prod_id')->get()->toArray();
            foreach ($ord_prod_ids as $k=>$v){
                $this->orderProductModel->where('ord_prod_id',$v['ord_prod_id'])->update(['delivery_id'=>$data['order_delivery_id'],'delivery_code'=>$data['delivery_code']]);
            }

            $ord_data = [
                'express_id'                =>      $data['order_delivery_id'],
                'sp_delivery_code'          =>      $data['delivery_code'],
                'sp_order_status'           =>      SP_ORDER_STATUS_SEND,           //供货商订单状态(已发货)
            ];

            //更新旧供货商订单表
            $this->spOrderModel->where('sp_ord_id',$sp_ord_id)->update($ord_data);

        }else{
            //商户、CMS操作发货，需同时更新供货商订单的状态为已发货

            //更新订单详情表，针对订单ID更新物流
//            $ord_item_data = [
//                'delivery_id'       =>  $data['order_delivery_id'],
//                'delivery_code'     =>  $data['delivery_code'],
//            ];
//            $this->orderProductModel->where("ord_id",$oid)->update($ord_item_data);
        }

        //更新新供货商订单表
        $this->newSpOrderModel->where('ord_id',$oid)->update($sp_ord_data);

        //订单表只记录第一次发货物流单号、快递方式
        $ord_delivery_code = $this->model->where("order_id",$oid)->value('delivery_code');
        if(empty($ord_delivery_code)){
            //更新订单表物流单号、订单状态
            $order_data = [
                'delivery_code'                 =>      $data['delivery_code'],
                'order_shipping_time'           =>      time(),
            ];
            $this->model->where("order_id",$oid)->update($order_data);
            app(Status::class)->updateToDelivery($oid);
        }

        //新增订单操作日志
        $ord_log_data = [
                'ord_id'        =>      $oid,
                'operater'      =>      $operater,
                'platform'      =>      $platform,
                'action'        =>      '订单发货',
                'note'          =>      $platform.'管理员【'.$operater.'】操作订单号【'.$order_info['order_no'].'】发货，物流单号【'.$data['delivery_code'].'】',
        ];
        $this->logModel->create($ord_log_data);

        //新增发货记录
        $is_delivery = $this->deliveryDocModel->where('ord_id',$oid)->first();
        if(empty($is_delivery)){
            //同一订单只记录第一次的发货记录
            $doc_data = [
                'ord_id'            =>    $oid,
                'mch_id'            =>    $order_info['mch_id'],
                'user_id'           =>    $order_info['user_id'],
                'delivery_id'       =>    $data['order_delivery_id'],
                'delivery_code'     =>    $data['delivery_code'],
                'freight'           =>    $data['order_exp_fee'],
                'note'              =>    $data['order_remark_admin']
            ];
            $this->deliveryDocModel->create($doc_data);
        }

        //记录发货日志
        $delivery_log_data = [
            'ord_id'                    =>  $oid,
            'order_no'                  =>  $order_info['order_no'],
            'sp_ord_id'                 =>  $sp_ord_id,
            'delivery_code'             =>  $data['delivery_code'],
            'express_id'                =>  $data['order_delivery_id'],
            'delivery_log_operater'     =>  $operater,
            'delivery_log_platform'     =>  $platform,
            'delivery_log_action'       =>  '订单发货',
            'delivery_log_note'         =>  $platform.'管理员【'.$operater.'】操作订单【'.$order_info['order_no'].'】发货，物流单号【'.$data['delivery_code'].'】',
            'created_at'                =>  time(),
        ];
        $this->deliveryLogModel->create($delivery_log_data);

        //分销商编号存在则做物流回写相关处理
        $this->logistics($order_info,$data['delivery_code'],$data['order_delivery_id']);
        return true;
    }

    /**
     * 创建订单日志
     * @param $data
     */
    public function recordOrderLog($data)
    {
        return $this->logModel->create($data);
    }

    /**
     * 获取所有销售渠道
     */
    public function getSalesChanel()
    {
        $data = $this->chanelModel->select('cha_id','cha_name')->get()->toArray();
        return $data;
    }

    /**
     * 设置标签
     * @param $order_id
     */
    public function setTag($order_id,$param)
    {
        unset($param['_token']);

        $str = '';
        $queue_flag = 0;
        if (!empty($param)){
            $queue_flag = 1;
            foreach ($param as $k=>$v){
                if(end($param) == $v){
                    $str .=$v;
                }else{
                    $str .=$v.",";
                }
            }
        }

        if(strpos($order_id,'[') !== false){
            //批量标记
            foreach (json_decode($order_id,true) as $k=>$v){
                //更新订单表标签
                $this->model->where('order_id',$v)->update(['order_tag_id'=>$str]);

                //更新生产队列表
                $this->orderProduceQueueModel->where('order_id',$v)->update(['produce_queue_flag'=>$queue_flag]);
            }
        }else{
            //更新订单表标签
            $this->model->where('order_id',$order_id)->update(['order_tag_id'=>$str]);

            //更新生产队列表
            $this->orderProduceQueueModel->where('order_id',$order_id)->update(['produce_queue_flag'=>$queue_flag]);
        }
        return true;
    }

    /**
     * 订单手动提交生产
     * @param $order_id
     */
    public function submitProduction($order_id,$operater=null,$platform=null,$user_id=null)
    {
        $operater = empty($operater) ? session("admin")['oms_adm_username'] : $operater;
        $platform = empty($platform) ? config('common.sys_abbreviation')['merchant'] : $platform;
        $user_id = empty($user_id) ? session("admin")['oms_adm_id'] : $user_id;

        //订单信息
        $order_info = $this->getOrderInfo($order_id);

        $produceClass = app(Production::class);

        //检查订单
        $produceClass->checkOrder($order_id);

        $produce_queue_info = $this->ordProdQueueRepository->getRow([['order_id','=',$order_id],['produce_queue_status','!=','progress'],['produce_queue_status','!=','finish']]);
        if(empty($produce_queue_info)){
            //该订单已提交生产，请勿重复提交
            Helper::EasyThrowException(70071,__FILE__.__LINE__);
        }

        //更新生产队列状态(改为手动提交方式，统一走自动提交生产流程)
        $produceClass->updateProduceQueue($order_id,ORDER_PRODUCE_TYPE_HAND,'ready');

        //添加订单日志
        $log_data = [
            'ord_id'        =>      $order_id,
            'user_id'       =>      $user_id,
            'operater'      =>      $operater,
            'platform'      =>      $platform,
            'action'        =>      '提交生产',
            'note'          =>      '订单号【'.$order_info['order_no'].'】由'.$operater.'提交生产',
        ];
        $this->recordOrderLog($log_data);

        //手动提交后改变订单状态(已确认，已支付，生产中，总状态:待发货、已生产)
        app(Status::class)->updateToProducing($order_id);

        return true;
    }

    /**
     * 审核文件处理
     * @param $project_no 订单作品号
     */
    public function checkFile($project_no)
    {
        $compound_info = $this->compoundQueueModel->where('project_sn',$project_no)->select('comp_queue_serv_id','works_id','comp_queue_file_info')->first();
        if(empty($compound_info)){
            //作品文件出错了
            Helper::EasyThrowException(70074,__FILE__.__LINE__);
        }

        $online_path = config('order.online_create_root');

        //将查出来的路径中的\\转换为linus中的/
        $path = dirname(preg_replace('/\\\\{1,}/', '/', str_replace($online_path,'',json_decode($compound_info['comp_queue_file_info'],true)[0])));

        //取出图片放置的域名id并组合到路径前面
        $sev_id = $compound_info['comp_queue_serv_id'];
        $public_id = $this->compoundServiceModel->where('comp_serv_id',$sev_id)->select('public_ip')->first();
        $sev_id = $public_id['public_ip'];
        $path = 'http://'.$sev_id.'/'.$path.'/check/';

        //获取作品的页面和图片
        $work_id = $compound_info['works_id'];
        $pagelist = $this->projectPageModel->where('prj_id',$work_id)->orderBy('prj_page_sort')->get();

        $sort = 1;
        foreach ($pagelist as $k=>$v){
            if($pagelist[0]['prj_page_type'] == TEMPLATE_PAGE_PAGE){
                //带封面从0开始
                $sort_img = $k;
            }else{
                //只有内页从1开始
                $sort_img = $sort;
                $sort++;
            }
            if($k < 10){
                if($k == 9 && $pagelist[0]['prj_page_type'] != TEMPLATE_PAGE_PAGE){
                    //照片冲印处理(prj_page_type都是2且有多张)
                    $pagelist[$k]['path']=$path.$sort_img.'.jpg';
                }else{
                    $pagelist[$k]['path']=$path.'0'.$sort_img.'.jpg';
                }
            }else{
                $pagelist[$k]['path']=$path.$sort_img.'.jpg';
            }
        }
        $count = count($pagelist);

        if($compound_info){
            $workInfo = $this->projectModel->where('prj_id',$compound_info['works_id'])->first();
        }

        return ['pageList'=>$pagelist->toArray(),'count'=>$count,'workInfo'=>$workInfo->toArray()];
    }

    /**
     * 检查下载文件
     * @param $ord_prod_id 订单详情ID
     */
    public function downloadFileCheck($ord_prod_id)
    {
        $download_queue_info = $this->downloadQueueModel->where('order_prod_id',$ord_prod_id)->get();
        foreach ($download_queue_info as $k=>$v){
            if($v['down_status'] != 'finish'){
                //文件未处理完成，无法下载
                Helper::EasyThrowException(70075,__FILE__.__LINE__);
            }

            $public_id = $this->compoundServiceModel->where('comp_serv_id',$v['down_serv_id'])->select('public_ip')->first();
            if(empty($v['down_local_path']) || empty($v['down_local_file_name'])){
                $url[$k] = urlencode($v['down_url']);
            }else{
                $url[$k] = urlencode('http://'.$public_id['public_ip'].'/'.$v['down_local_path'].'/'.$v['down_local_file_name']);
            }
        }
//        $url[] = 'https://img.alicdn.com/imgextra/i3/2030501916/TB2jUBkcDIlyKJjSZFrXXXn2VXa_!!2030501916-0-dingzhi.jpg';
//        $url[] = 'https://img.alicdn.com/imgextra/i1/191762161/TB23.24A5lnpuFjSZFgXXbi7FXa_!!191762161-0-dingzhi.jpg';
        return $url;
    }

    /**
     * 通过url进行下载
     * @param $url
     */
    public function startDownload($url)
    {
        $filename = basename($url);
        if (strpos($filename,'.') == false){
            //链接跳转下载(如：http://order.ele007.com/api/artfiles?uid=b5474ee6-f326-4396-b4fd-93ee2fcef57a)
            header('location:'.$url);
            exit();
        }else{
            //带文件名下载（如:http://itbour-generate.itbour.com/image/U779438/2020/07/03/130358170_p3MUy5nBJM9uiu3D1EbI/0.pdf）
            $url = $this->linkUrldecode($url);

            $header = get_headers($url, 1);
            $size = $header['Content-Length'];

            header("Content-Type: application/force-download;"); //告诉浏览器强制下载
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: ".$size);
            header("Content-Disposition: attachment; filename=$filename"); //attachment表明不在页面输出打开，直接下载
            header("Expires: 0");
            header("Cache-control: private");
            header("Pragma: no-cache"); //不缓存页面
            ob_clean();
            flush();
            readfile($url);
        }

    }

    /**
     * 对url中文编码处理
     * @param $url
     */
    public function linkUrldecode($url) {
        $uri = '';
        $cs = unpack('C*', $url);
        $len = count($cs);
        for ($i=1; $i<=$len; $i++) {
            $uri .= $cs[$i] > 127 ? '%'.strtoupper(dechex($cs[$i])) : $url{$i-1};
        }
        return $uri;
    }

    //获取作品信息
    public function getProjectInfo($projectArr,$cart_id,$isFast=0)
    {
        $relationRepositories = app(SaasProductsRelationAttrRepository::class);
        $info = app(Info::class);
        $price = app(Price::class);
        $dmsAgentInfoRepository = app(DmsAgentInfoRepository::class);
        $cartModel = app(SaasCart::class);

        $projectInfo = [];
        $total_price = 0;
        $cartInfo = [];
        if (!$isFast)
        {
            //获取购物车信息
            $cartInfoArr = $cartModel->find($cart_id);
            if ($cartInfoArr)
            {
                $cartInfoArr = $cartInfoArr->toArray();
                $cartInfo = json_decode($cartInfoArr['cart_info'],true);
            }else{
                echo "获取购物车信息失败";
                die;
            }
        }
        foreach ($projectArr['sku_id'] as $k=>$v){

            //获取sku信息
            $skuProject = $this->skuModel->find($v);
            if ($skuProject)
            {
                $skuProject = $skuProject->toArray();
            }else{
                $skuProject = [];
                $projectInfo[$k] = $skuProject;
                continue;
            }

            $project = $skuProject;
            $project['prod_num'] = 0;
            $project['proj_id'] = $projectArr['proj_id'][$k];
            $project['sku_id'] = $v;

            if (!$isFast)
            {
                //获取该商品数量
                foreach ($cartInfo as $kk => $vv)
                {
                    if ($vv['sku_id'] == $v && $vv['projects_id'] == $projectArr['proj_id'][$k]){
                        $project['prod_num'] = $vv['num'];
                    }
                }
            }else{
                $project['prod_num'] = $projectArr['num'][$k];
            }


            //获取作品信息
            $project_arr = $this->projectModel->find($projectArr['proj_id'][$k]);
            if ($project_arr)
            {
                $project_arr = $project_arr->toArray();
            }else{
                $project_arr = [];
            }

            $project['works'] =[];
            if (!empty($project_arr)){
                $project['works']['prj_page_num'] = $project_arr['prj_page_num'];
                $project['works']['prj_name'] = $project_arr['prj_name'];
                $project['works']['prj_sn'] = $project_arr['prj_sn'];
            }

            //获取作品中货品的属性
            $sku_attr = $relationRepositories->getProductAttr($v);
            $project['sku_attr'] = explode("，",$sku_attr);

            //如果属性存在P数的话，转换成作品信息中的p数
            foreach ($project['sku_attr'] as $sk=>$sv){
                if(strpos($sv,"P数")!==false){
                    if (!empty($project_arr))
                    {
                        $project['sku_attr'][$sk] = "P数：".$project['works']['prj_page_num']."P";
                    }else{
                        $project['sku_attr'][$sk] = "P数：".$project['prod_p_num']."P";
                    }


                }
            }

            //获取商品名称
            $prod_arr = $this->prodMoel->find($project['prod_id'])->toArray();
            $project['prod_name'] = $prod_arr['prod_name'];

            //获取商品物流方式
            $project['prod_temp_id'] = $prod_arr['prod_express_tpl_id'];

            //获取商品图片
            $prod_photo = $this->mediaRepository->getProductPhoto($project['prod_id']);

            if (empty($prod_photo))
            {
                $project['prod_photo'] = "";
            }else{
                $project['prod_photo'] = $prod_photo[0]['prod_md_path'];
            }

            //获取货品的重量，转换为kg
            if (!empty($project_arr))
            {
                $sku_weight = $info->getGoodsWeight($project['prod_sku_id'],$project['works']['prj_page_num']);
            }else{
                $sku_weight = $info->getGoodsWeight($project['prod_sku_id'],$project['prod_p_num']??0);
            }

            $project['sku_weight'] = $sku_weight;
            //获取货品的价格
            $cust_lv_id = $dmsAgentInfoRepository->getCustLvId($this->agent_id);

            if (!empty($project_arr))
            {
                $sku_price = $price->getChanelPrice($project['prod_sku_id'],$cust_lv_id,$project['works']['prj_page_num']);
            }else{
                $sku_price = $price->getChanelPrice($project['prod_sku_id'],$cust_lv_id,$project['prod_p_num']??0);
            }
            $project['sku_price'] = $sku_price;

            $project['ord_quantity'] = 1;

            $project['total_price'] = number_format($project['sku_price'] * $project['prod_num'],2);

            $projectInfo[$k] = $project;
        }
        return $projectInfo;
    }

    //获取物流方式及运费
    public function getCreateDeliveryPrice($post,$mch_id)
    {
        try {
            $params = $post;
            //作品id
            $prod_ids = explode(",", $params['prod_id']);

            //快递模板id
            $temp_ids = explode(",", $params['temp_id']);
            $temp_id = array_unique($temp_ids);
            $deliveryList = [];

            if (count($prod_ids) == 1) {
                $prodInfo = $this->prodMoel->where('prod_id', $prod_ids[0])->select('prod_express_type', 'prod_express_fee')->first();
            }

            //如果模板id不存在的话，商品为固定运费取值
            if (count($temp_id) == 1 && empty($temp_id[0])) {
                $prodInfo = $this->prodMoel->where('prod_id', $prod_ids[0])->select('prod_express_type', 'prod_express_fee')->first();
                if (empty($prodInfo['prod_express_fee'])) {
                    return [
                        'code' => 0,
                        'msg' => "快递模板不存在!",
                    ];
                }
                $deliveryInfo = [
                    'delivery_id' => 0,
                    'delivery_name' => '固定运费',
                    'delivery_show_name' => '固定运费',
                    'delivery_desc' => '运费为固定值',
                    'deli_price' => $prodInfo['prod_express_fee'],
                    'del_temp_id' => 0
                ];
                $deliveryList[0] = $deliveryInfo;
                return [
                    'code' => 1,
                    'deliveryList' => $deliveryList,
                ];
            }

            $templateRepository = app(SaasDeliveryTemplateRepository::class);
            $deliveryRepository = app(SaasDeliveryRepository::class);
            $logistics = app(Logistics::class);

            //省id
            $pro_id = $params['pro_id'];

            //市id
            $city_id = $params['city_id'];

            //区id
            $area_id = $params['area_id'];

            //商品重量
            $weight = $params['total_weight'];
            $deli_temp = $templateRepository->getTemplate($temp_id,$mch_id);

            //获取快递运送方式
            $delivery_list = explode(",", $deli_temp['del_temp_delivery_list']);
            $deliveryList = [];

            //获取运送方式的运费
            foreach ($delivery_list as $k => $v) {
                $deli_list = $deliveryRepository->getTableList(['delivery_id' => $v])->toArray();
                $deliveryInfo = $deli_list['data'][0];
                $deli_price = $logistics->getDeliveryFee($deli_temp['del_temp_id'], $v, $pro_id, $city_id, $area_id, $weight);
                if (count($prod_ids) == 1 && $prodInfo['prod_express_type'] == LOGISTICS_PRICE_BY_FIXED) {
                    $deliveryInfo['deli_price'] = $prodInfo['prod_express_fee'];
                } else {
                    $deliveryInfo['deli_price'] = $deli_price;
                }
                $deliveryList[$k] = $deliveryInfo;
                $deliveryList[$k]['del_temp_id'] = $deli_temp['del_temp_id'];
            }
            return [
                'code' => 1,
                'deliveryList' => $deliveryList,
            ];
        }catch (CommonException $e){
            return [
                'code' => 0,
                'msg' => $e->getMessage(),
            ];
        }
    }

    //组织创建订单数据
    public function getCreateData($param,$workInfo)
    {
        $channleRepository = app(SaasSalesChanelRepository::class);
        $projectModel = app(SaasProjects::class);
        $cha_id = $channleRepository->getAgentChannleId();
        //合作代码
        $dmsAgentInfoRepository = app(DmsAgentInfoRepository::class);
        $parent_code = $dmsAgentInfoRepository->getCodeById(session('admin')['agent_info_id']);

        foreach ($workInfo as $k =>$v){
            $file_type =1;
            //获取作品类型
            if ($v['proj_id'] == 0)
            {
                $file_type = 0;
            }else{
                //获取作品文件类型
                $prjArr = $projectModel->where(['prj_id' => $v['proj_id']])->select('prj_file_type','manuscript_id')->first();
                if (!empty($prjArr)){
                    $prjArr = $prjArr->toArray();
                    if ($prjArr['prj_file_type'] == WORKS_FILE_TYPE_UPLOAD)
                    {
                        $file_type = 2;
                        $v['proj_id'] = $prjArr['manuscript_id'];
                     }else{
                        $file_type = 1;
                    }
                }
            }

            $items[$k] = [
                'goods_id'   =>$v['prod_id'],
                'product_id' =>$v['prod_sku_id'],
                'works_id'   =>$v['proj_id'],
                'file_type'  =>$file_type,
                'file_info'  => [
//                    'file_url' => 'http://xxxxx/xxx.pdf||http://xxxxx/xxx.pdf',  //封面||内页||封底这样排
                    'pages_num'  => $v['works']['prj_page_num']??""     //冲印张数或照片书内页数
                ],
                'price_mod'  => 1,                                      //1正常按本/个计价 2按张数计价
                'buy_num'    => $v['prod_num'],                         //购买数量 必须
                'real_fee'   => $v['sku_price']*$v['prod_num'],         // 价格  商品单价*数量
                'price'      => $v['sku_price'],                        //最终商品价格 非必须，如果有，则需要验证正确性 非必须
            ];
        }

        $receiver_info = [
            'consignee'      => $param['address']['consignee'],         //必须 收货人
            'ship_mobile'    => $param['address']['ship_mobile'],       //必须 收货人电话
            'province_code'  => $param['address']['province_code'],     //省id
            'city_code'      => $param['address']['city_code'],         //市id
            'district_code'  => $param['address']['district_code'],     //区id
            'ship_addr'      => $param['address']['ship_addr'],         //收货地址
            'ship_tel'       => $param['address']['ship_tel'],          //电话
            'ship_zip'       => $param['address']['ship_zip'],          //邮编
        ];
        $post_data = [
            'items'            =>$items,
            'receiver_info'    =>$receiver_info,
            'outer_order_no'   => "",                                                   //关联的第三方单号 选填
            'shipping_temp_id' =>  $param['shipping_temp_id'],                          //快递模板id 必须
            'shipping_id'      =>  $param['shipping_id'],                               //快递id 必须
            'partner_code'     =>  $parent_code,                                        //合作代码，以些代码开头生成订单号
            'payment'          =>  $param['prod_total_price']+$param['post_fee'],       //订单总金额，实际价格 含运费 ,如果提交了，则会验算 非必填
            'post_fee'         =>  $param['post_fee'],                                  //运费  选填
            'mch_id'           =>  session("admin")['mch_id'],                     //商家id,必须
            'chanel_id'        =>  $cha_id,                                             //渠道id,必须
            'buyer_type'       =>  CHANEL_TERMINAL_AGENT,                               // 终端用户类型 1代表分销 2代表会员，其他无效 必须
            'user_id'          =>  session('admin')['agent_info_id'],              //用户id,必须
            'note'             => "",                                                   //用户备注  选填
            //支付信息
            'pay_info'         =>[                                                      //支付信息 必填
                'pay_id' => $param['pay_id'],                                           //余额、支付宝、微信等支付对应的id 必须
            ],
        ];
        return $post_data;
    }

    /**
     * 同步的订单号是否存在
     * @param $outorderNo 外部订单号
     */
    public function isoutOrderExists($outorderNo)
    {
        return $this->model->where('order_relation_no',$outorderNo)->whereNotIn('order_status',[ORDER_STATUS_CANCEL])->count();
    }

    /**
     * 记录订单异常日志
     * @param $data
     */
    public function recordOrderException($data)
    {
        return $this->ordExceptionModel->create($data);
    }

    /**
     * 稿件创建订单数据解析
     * @param $data
     */
    public function manuscriptCreateOrder($data)
    {
        $result = [];
        foreach ($data as $k=>$v){

            $data[$k]['works_url'] = explode('|',$v['works_url']);
            $data[$k]['md5_works_url_arr'] = explode('|',$v['md5_works_url']);
            $data[$k]['product_id'] = explode(',',$v['product_id']);
            $data[$k]['goods_number'] = explode(',',$v['goods_number']);
            $data[$k]['folio_number'] = explode(',',$v['folio_number']);

            //稿件数量校验
            if(count($data[$k]['md5_works_url_arr']) != count($data[$k]['goods_number'])){
                //稿件数量与商品数量不符
                Helper::EasyThrowException(70080,__FILE__.__LINE__);
            }

            foreach ($data[$k]['works_url'] as $key=>$val){

                if(stripos($v['md5_works_url'],',')){
                    $sku_id = array_shift($data[$k]['product_id']);
                }else{
                    $sku_id = $data[$k]['product_id'][$key];
                }

                //货品信息
                $prod_info = $this->skuModel->where('prod_sku_id',$sku_id)->select('prod_id')->first();
                if(empty($prod_info)){
                    //货品ID无效
                    Helper::EasyThrowException(70081,__FILE__.__LINE__);
                }

                //订单详情
                $result[$k]['items'][$key] = [
                    'goods_id'      => $prod_info['prod_id'],                                     //商品id  必须
                    'product_id'    => $sku_id,                                                   //货品id  必须
                    'works_id'      => PUBLIC_NO,                                                 //0表示无作品(实物或需下载的)
                    'file_type'     => WORKS_FILE_TYPE_UPLOAD,                                    // 1,diy文件 2,稿件url 0,无  可为0 ，0表示无文件  必须
                    'file_info'     =>  [                                                         //文件信息 works_id为0并且无文件表示实物，works_id为0但有文件信息表示文件信息需要下载或转移
                        'file_url'      =>  str_replace(',','||',$val),                           //封面||内页||封底这样排
                        'pages_num'     =>  $data[$k]['folio_number'][$key]??PUBLIC_NO,           //冲印张数或照片书内页数
                    ],
                    'price_mod'     => PUBLIC_YES,                                                //1正常按本/个计价 2按张数计价
                    'buy_num'       => $data[$k]['goods_number'][$key],                           //购买数量 必须
                ];
            }

            //收货人信息
            $result[$k]['receiver_info'] = [
                'consignee'         =>      $v['consignee'],
                'ship_mobile'       =>      $v['ship_mobile'],
                'province_name'     =>      $v['province'],
                'city_name'         =>      $v['city'],
                'district_name'     =>      $v['district'],
                'ship_addr'         =>      $v['ship_addr'],
                'ship_tel'          =>      isset($v['ship_tel']) ? $v['ship_tel'] : '',
                'ship_zip'          =>      isset($v['ship_zip']) ? $v['ship_zip'] : '',
            ];

            //余额支付id
            $pay_info = $this->paymentRepository->getPayByName(PAYMENT_FLAG_BALANCE);
            if(empty($pay_info)){
                //未配置余额支付
                Helper::EasyThrowException(70078,__FILE__.__LINE__);
            }

            //支付信息
            $result[$k]['pay_info'] = [
                'pay_id'    =>  $pay_info['pay_id'],
            ];

            //合作代码取商户id、分销id
            $id_info = $this->agentInfoRepository->getMchIdAndAgentIdByCode($v['coop_code']);
            if(empty($id_info)){
                //未配置合作编号
                Helper::EasyThrowException(70079,__FILE__.__LINE__);
            }

            //渠道
            $chanel_info = $this->chanelModel->where('short_name',AGENT_CHANNEL)->select('cha_id')->first();

            //快递模板
            $del_temp_id = $this->prodMoel->where('prod_id',$prod_info['prod_id'])->value('prod_express_tpl_id');
            if(empty($del_temp_id)){
                $delivery_info = $this->deliveryTemplateModel->whereRaw("FIND_IN_SET(".$v['shipping_id'].",del_temp_delivery_list)",true)->where('del_temp_status',PUBLIC_ENABLE)->select('del_temp_id')->get()->toArray();
                if(empty($delivery_info)){
                    //未找到快递模板
                    Helper::EasyThrowException(70082,__FILE__.__LINE__);
                }
                $del_temp_id = $delivery_info[0]['del_temp_id'];
            }

            $parentDel = [
                '301'  => 6,
                '208'  => 8,
                '108'  => 8,
            ];

            $del_temp_id = isset($parentDel[$v['coop_code']])  ?$parentDel[$v['coop_code']] : $del_temp_id;

            $result[$k]['order_no']         =       $v['order_sn'];                     //系统订单号
            $result[$k]['outer_order_no']   =       $v['order_sn'];                     //关联的第三方单号 选填
            $result[$k]['shipping_id']      =       $v['shipping_id'];                  //快递id 必须
            $result[$k]['shipping_temp_id'] =       $del_temp_id;                       //快递模板id 必须
            $result[$k]['partner_code']     =       $v['coop_code'];                    //合作代码，以些代码开头生成订单号
            $result[$k]['mch_id']           =       $id_info['mch_id'];                 //商家id,必须
            $result[$k]['user_id']          =       $id_info['agent_info_id'];          //用户id,必须
            $result[$k]['chanel_id']        =       $chanel_info['cha_id'];             //渠道id,必须
            $result[$k]['buyer_type']       =       CHANEL_TERMINAL_AGENT;              // 终端用户类型 1代表分销 2代表会员，其他无效 必须
        }
        return array_shift($result);
    }




    /**
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getOrderTableList($where=null, $order='created_at desc',$isExport=null)
    {
        $limit = isset($where['limit']) ? $where['limit']:config('common.page_limit');  //这个10取配置里的
        $where = $this->parseWhere($where);
        $where['mch_id'] = $this->mch_id;

        if(isset($where['status'])){
            //按订单状态查询
            $order_status = [
                'ALL'                                 =>          0,   //全部(自定义,非常量)
                'ORDER_STATUS_WAIT_CONFIRM'           =>          ORDER_STATUS_WAIT_CONFIRM,   //待确认
                'ORDER_STATUS_WAIT_PAY'               =>          ORDER_STATUS_WAIT_PAY,       //待付款  已确认
                'ORDER_STATUS_WAIT_PRODUCE'           =>          ORDER_STATUS_WAIT_PRODUCE,   //待生产  已付款
                'ORDER_STATUS_WAIT_DELIVERY'          =>          ORDER_STATUS_WAIT_DELIVERY,  //待发货  已生产
                'ORDER_STATUS_WAIT_RECEIVE'           =>          ORDER_STATUS_WAIT_RECEIVE,   //待收货  已发货
                'ORDER_STATUS_CANCEL'                 =>          ORDER_STATUS_CANCEL,         //交易取消
                'ORDER_STATUS_AFTERSALE'              =>          ORDER_STATUS_AFTERSALE,      //售后
                'ORDER_STATUS_FINISH'                 =>          ORDER_STATUS_FINISH,         // 交易完成  已收货
            ];
            if($order_status[$where['status']] != 0){
                $where['order_status'] = $order_status[$where['status']];
            }
            unset($where['status']);
        }

        //按渠道查询
        $where_info = [];
        if(isset($where['chanel'])){
            if($where['chanel'] != 'all'){
                $where_info['cha_id'] = $where['chanel'];
            }
            unset($where['chanel']);
        }

        if(!empty($this->agent_id)){
            //分销订单
            $where['user_id'] = $this->agent_id;
        }

        //order 必须以 'id desc'这种方式传入.
        $orderBy = [];
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }

        $query = $this->model->orWhereHas(
            'chanel',function($query) use ($where_info) {
            if (!empty($where_info)) {
                return $query->where($where_info);
            }
        })->with(['item','chanel']);


        if(isset($where['order_status']) && is_array($where['order_status'])){
            $query = $query->whereIn('order_status',$where['order_status']);
            unset($where['order_status']);
        }

        if(isset($where['order_shipping_status'])){
            $query = $query->whereIn('order_shipping_status',$where['order_shipping_status']);
            unset($where['order_shipping_status']);
        }

        //下单时间查询或者发货时间查询
        if(isset($where['prod_time'])&&$where['prod_time']==1){
            if(isset($where['search_time'])){
                $order_shipping_time = $where['search_time'];
                $time_list = Helper::getTimeRangedata($order_shipping_time);
                $query = $query->whereBetween("order_shipping_time",[$time_list['start'],$time_list['end']]);
            }
            unset($where['search_time']);
            unset($where['prod_time']);
        }
        if(isset($where['prod_time'])&&$where['prod_time']==2){
            if(isset($where['search_time'])){
                $where['created_at'] = $where['search_time'];
            }
            unset($where['search_time']);
            unset($where['prod_time']);
        }

        //查询时间
        if(isset($where['created_at'])){
            $created_at = $where['created_at'];
            $time_list = Helper::getTimeRangedata($created_at);
            $query = $query->whereBetween("created_at",[$time_list['start'],$time_list['end']]);
            unset($where['created_at']);
        }

        if(isset($where['user_ids'])){
            $query = $query->whereIn('user_id',$where['user_ids']);
            unset($where['user_ids']);
        }

        if(!empty ($where)) {
            $query =  $query->where($where);
        }

        if(!empty($order)) {
            $query =  $query->orderBy($orderBy[0],$orderBy[1]);
        }

        if(empty($isExport)){
            $list = $query->paginate($limit);
        }else{
            $list = $query->get();
        }


        $arr[] = '';
        foreach ($list as $k=>$v){
            $arr[$k] = $v->toArray();

            $arr[$k]['total'] = count($v->item); //item数量
            $nums = 0; //总件数
            foreach ($v->item as $key=>$val){
                $nums += $val->prod_num;

                //商品信息
                $prod_info = $this->prodMoel->where('prod_id',$val->prod_id)->select("prod_name")->first();
                $arr[$k]['item'][$key]['prod_name'] = $prod_info->prod_name;
                $arr[$k]['item'][$key]['prod_main_thumb'] = $this->mediaRepository->getProductPhoto($val->prod_id)[0]['prod_md_path'];
                $arr[$k]['item'][$key]['attr_str'] = $this->prodRelationAttrRepository->getProductAttr($val->sku_id);

                //货品信息
                $sku_info = $this->skuModel->where('prod_sku_id',$val->sku_id)->select('prod_sku_price')->first();
                $arr[$k]['item'][$key]['prod_sku_price'] = $sku_info['prod_sku_price'];
            }
            $arr[$k]['nums'] = $nums;

            //店铺名称
            $agentInfo = $this->agentInfoModel->where('agent_info_id',$v['user_id'])->select("agent_name")->first();
            $arr[$k]['agent_name'] = !empty($agentInfo) ? $agentInfo->agent_name : '';

            //渠道
            $chanel = $this->chanelModel->where('cha_id',$v['cha_id'])->select("cha_name")->first();
            $arr[$k]['cha_name'] = !empty($chanel) ? $chanel->cha_name : '';

            //订单标签
            $tag_arr = explode(",",$v['order_tag_id']);
            foreach ($tag_arr as $kk=>$vv){
                $tag_info = $this->orderTagModel->where('tag_id',$vv)->select('tag_name')->first();
                $arr[$k]['tag_name'][$kk] = $tag_info['tag_name'];
            }

        }
        $arr = $arr[0] == '' ? [] : $arr;
        $list = $list->toArray();
        $list['data'] = $arr;
//dd($list);
        return $list;
    }


    /**
     *  订单退款到余额
     *  param $oid 订单id $operater操作人 $platform操作平台 $operater_id 操作人id
     * @return
     */
    public function refundToBalance($oid, $operater, $platform, $operater_id)
    {
        $order_data = $this->model->where("order_id",$oid)->first();

        //余额写法
        $userModel = app(ChanelUser::class)->getUserInfo($order_data['user_id'], $order_data['cha_id']);

        if(empty($userModel)){
            //账号不存在
            Helper::EasyThrowException(70086,__FILE__.__LINE__);
        }

        if ($order_data['cha_id'] == CHANEL_TERMINAL_AGENT) {
            //分销
            $balance = $userModel['agent_balance'];
        } else {
            //会员
            $balance = $userModel['balance'];
        }

        //更新余额
        $new_balance = $balance + $order_data['order_real_total'];
        $return = app(ChanelUser::class)->updateBalance($order_data['user_id'], $order_data['cha_id'], $order_data['order_real_total']);
        if(empty($return)){
            //余额退款出错
            Helper::EasyThrowException(70085,__FILE__.__LINE__);
        }

        //资金变动日志
        $balanceLogData = [
            'mch_id'                     => $order_data['mch_id'],
            'user_id'                    => $order_data['user_id'],
            'user_type'                  => $order_data['cha_id'],
            'operate_type'               => OPERATE_TYPE_ADMIN,
//            'operater'                   => $operater,
            'operate_id'                 => $operater_id,
            'cus_balance_type'           => FINANCE_INCOME,
            'cus_balance_type_detail'    => FINANCE_CHANGE_TYPE_REFUND,
            'cus_balance_change'         => $order_data['order_real_total'],
            'cus_balance'                => $new_balance,
            'cus_balance_frozen_change'  => 0,
            'cus_balance_frozen'         => 0,
            'cus_balance_business_no'    => $order_data['order_no'],
            'pay_id'                     => $order_data['order_pay_id'],
            'remark'                     => '',
            'created_at'                 => time()
        ];
        $this->balanceLogRepository->insert($balanceLogData);

        //生成退款单
        $refund_data = [
                'mch_id'            =>      $order_data['mch_id'],
                'order_id'          =>      $order_data['order_id'],
                'order_no'          =>      $order_data['order_no'],
                'user_id'           =>      $order_data['user_id'],
                'refund_amount'     =>      $order_data['order_real_total'],
                'operater'          =>      $operater,
                'platform'          =>      $platform,
                'refund_status'    =>       REFUND_STATUS_SUCCESS, //0退款申请，1退款成功,2退款失败
                'created_at'        =>      time(),
        ];
        $this->ordRefundModel->insert($refund_data);

        //订单日志
        $log_data = [
            'ord_id'        =>      $order_data['order_id'],
            'user_id'       =>      $operater_id,
            'operater'      =>      $operater,
            'platform'      =>      $platform,
            'action'        =>      '订单退款',
            'note'          =>      '订单号【'.$order_data['order_no'].'】由'.$operater.'操作退款至余额',
        ];
        $this->recordOrderLog($log_data);

    }

    /**
     *  物流回写相关处理
     *  param $order_info 订单信息 $delivery_code 物流单号 $delivery_id 快递id
     * @return
     */
    public function logistics($order_info,$delivery_code,$delivery_id)
    {
        //存在agent_code则做物流回写处理
        $chanel_info = $this->chanelModel->where('cha_id',$order_info['cha_id'])->select('cha_flag')->first();
        if($chanel_info['cha_flag'] == CHANEL_TERMINAL_AGENT && $order_info['cha_id'] != 0){
            //分销会员
            $user_info = app(ChanelUser::class)->getUserInfo($order_info['user_id'],$order_info['cha_id']);
            if(!empty($user_info['agent_code'])){
                //插入物流回写队列
                $delivery_name = $this->expressModel->where('express_id',$delivery_id)->value('express_code');
                if(empty($delivery_name)){
                    //指定快递模板下快递不存在
                    Helper::EasyThrowException(80002,__FILE__.__LINE__);
                }

                $queue_data = [
                    'mch_id'            =>  $order_info['mch_id'],
                    'agent_code'        =>  $user_info['agent_code'],
                    'order_id'          =>  $order_info['order_id'],
                    'delivery_name'     =>  $delivery_name,
                    'delivery_code'     =>  $delivery_code,
                ];
                $res = app(Logistics::class)->insertDeliveryQueue($queue_data);
                if($res['status'] == 'fail'){
                    //发货物流队列出错
                    Helper::EasyThrowException(70089,__FILE__.__LINE__);
                }
            }
        }
    }


    //获取订单去年今年每月的销售额
    public function salesAmount($mid=null,$user_id=null)
    {
        if (empty($mid)){
            $midWhere = [];
        }else{
            $midWhere = ['mch_id' => $mid];
         }
        $last_year = date("Y",strtotime("-1 year"));
        $this_year = date("Y");
        $next_year = date("Y",strtotime("+1 year"));

        for ($y=1;$y<3;$y++){
            for($i=1;$i<=12;$i++){
                $k = $i+1;
                if($y==1){
                    //去年
                    if($i == 12){
                        $start_time = $last_year.'-'.$i;
                        $end_time = $this_year.'-1';
                    }else{
                        $start_time = $last_year.'-'.$i;
                        $end_time = $last_year.'-'.$k;
                    }
                }else{
                    //今年
                    if($i == 12){
                        $start_time = $this_year.'-'.$i;
                        $end_time = $next_year.'-1';
                    }else{
                        $start_time = $this_year.'-'.$i;
                        $end_time = $this_year.'-'.$k;
                    }
                }
                $start_time = strtotime($start_time);
                $end_time = strtotime($end_time);
                if(empty($user_id)){
                    $order_data[$y][] = $this->model
                        ->where($midWhere)
                        ->whereNotIn('order_status',[ORDER_STATUS_WAIT_CONFIRM,ORDER_STATUS_WAIT_PAY,ORDER_STATUS_CANCEL])
                        ->whereBetween('created_at', [$start_time,$end_time])
                        ->select("order_real_total")
                        ->get()
                        ->toArray();
                }
                else{
                    $order_data[$y][] = $this->model
                        ->where($midWhere)
                        ->where('user_id',$user_id)
                        ->whereNotIn('order_status',[ORDER_STATUS_WAIT_CONFIRM,ORDER_STATUS_WAIT_PAY,ORDER_STATUS_CANCEL])
                        ->whereBetween('created_at', [$start_time,$end_time])
                        ->select("order_real_total")
                        ->get()
                        ->toArray();
                }

            }
        }

        //计算销售额
        foreach ($order_data[1] as $k=>$v){
            if(count($v) <1){
                $order_data[1][$k] = 0;
            }else{
                $amount = 0;
                foreach ($v as $kk=>$vv){
                    $amount += $vv['order_real_total'];
                }
                if ($order_data[1][$k] != 0)
                {
                    //将销售额转化为以万为单位
                    $order_data[1][$k] = number_format($amount/10000,2);
                }else{
                    $order_data[1][$k] = 0;
                }

            }
        }

        foreach ($order_data[2] as $k=>$v){
            if(count($v) <1){
                $order_data[2][$k] = 0;
            }else{
                $amount = 0;
                foreach ($v as $kk=>$vv){
                    $amount += $vv['order_real_total'];
                }
                if ($order_data[2][$k] != 0)
                {
                    //将销售额转化为以万为单位
                    $order_data[2][$k] = number_format($amount/10000,2);
                }else{
                    $order_data[2][$k] = 0;
                }
            }
        }
        return $order_data;
    }

    //获取订单某一天的销售额
    public function getTodayAmount($mid=null,$starttime = null,$endtime = null,$agent_id=null)
    {
        //获得当日凌晨的时间戳
        if (empty($starttime)){
            $starttime = strtotime(date("Y-m-d"),time());
            $endtime = time();
        }
        $amount = $this->model;

        if (!is_null($mid) && !is_array($mid))
        {
            $midArr = explode(',',$mid);
            $amount = $amount->whereIn('mch_id',$midArr);
        }

        //分销商订单销售额
        if(!empty($agent_id)){
            $amount = $amount->where('user_id',$agent_id);
        }

        $amount= $amount->whereNotIn('order_status',[ORDER_STATUS_WAIT_CONFIRM,ORDER_STATUS_WAIT_PAY,ORDER_STATUS_CANCEL])
            ->whereBetween('created_at', [$starttime,$endtime])
            ->sum('order_real_total');

        return number_format($amount,2);
    }
    //获取某一天创建的订单数
    public function getTodayOrderCount($mid=null,$starttime = null,$endtime = null,$agent_id=null)
    {
        //获得当日凌晨的时间戳
        if (empty($starttime)){
            $starttime = strtotime(date("Y-m-d"),time());
            $endtime = time();
        }
        $count = $this->model;
        if (!is_null($mid) && !is_array($mid))
        {
            $midArr = explode(',',$mid);
            $count = $count->whereIn('mch_id',$midArr);
        }

        //分销商订单数
        if(!empty($agent_id)){
            $count = $count->where('user_id',$agent_id)->whereNotIn('order_status',[ORDER_STATUS_WAIT_CONFIRM,ORDER_STATUS_WAIT_PAY,ORDER_STATUS_CANCEL]);
        }

        $count= $count->whereBetween('created_at', [$starttime,$endtime])
            ->count();
        return $count;
    }
    //获取某一天的订单发货数
    public function getTodayOrderShipping($mid=null,$starttime = null,$endtime = null)
    {
        //获得当日凌晨的时间戳
        if (empty($starttime)){
            $starttime = strtotime(date("Y-m-d"),time());
            $endtime = time();
        }
        $count = $this->model;
        if (!is_null($mid) && !is_array($mid))
        {
            $midArr = explode(',',$mid);
            $count = $count->whereIn('mch_id',$midArr);
        }
        $count= $count->whereBetween('order_shipping_time', [$starttime,$endtime])
            ->count();
        return $count;
    }


    //获取控制台订单状态数量
    public function getOrderCount($mid = null)
    {
        if (empty($mid)){
            $midWhere = [];
        }else{
            $midWhere = ['mch_id' => $mid];
        }
        //待确认订单
        $data['wait_confirm_count'] = $this->model->where(['order_status' => ORDER_STATUS_WAIT_CONFIRM])->where($midWhere)->count();
        //待支付订单
        $data['wait_pay_count'] = $this->model->where(['order_status' => ORDER_STATUS_WAIT_PAY])->where($midWhere)->count();
        //待生产订单
        $data['order_wait_produce'] = $this->model->where(['order_status' => ORDER_STATUS_WAIT_PRODUCE])->where($midWhere)->count();
        //待发货订单
        $data['wait_delivery_count'] = $this->model->where(['order_status' => ORDER_STATUS_WAIT_DELIVERY])->where($midWhere)->count();
        //待确认收货订单
        $data['wait_receive_count'] = $this->model->where(['order_status' => ORDER_STATUS_WAIT_RECEIVE])->where($midWhere)->count();
        //待评价订单
        $data['wait_evaluate_count'] = $this->model->where(['order_evaluate_status' => ORDER_UNEVALUATE])->where($midWhere)->count();

        return $data;
    }

    //DMS系统--销售分析  获取最近30天交易金额，成交订单数，作品数
    public function getChartInfo()
    {
        //获取渠道id
        $cha_id = app(SaasSalesChanelRepository::class)->getAgentChannleId();
        $projectModel = app(SaasProjects::class);
        $chartInfo = [];
        $data = [];
        $days = [];
        $realTotals = [];
        $orderNums = [];
        $worksNums = [];
        for($i=31;$i>=0;$i--){
            $time = date('m/d', strtotime('-'.$i.' days'));
            $days[] = $time;
            $start = strtotime($time . " 00:00:00");
            $end = strtotime($time . " 23:59:59");

            //交易金额
            $realTotal = $this->model
                ->where(['user_id' => $this->agent_id, 'cha_id'=>$cha_id])
                ->whereNotIn('order_status',[ORDER_STATUS_WAIT_CONFIRM,ORDER_STATUS_WAIT_PAY,ORDER_STATUS_CANCEL])
                ->whereBetween("created_at", [$start, $end])
                ->orderBy('order_id', 'desc')
                ->sum(DB::raw('order_real_total'));

            //成交订单数
            $orderNum = $this->model
                ->where(['user_id' => $this->agent_id, 'cha_id'=>$cha_id])
                ->whereNotIn('order_status',[ORDER_STATUS_WAIT_CONFIRM,ORDER_STATUS_WAIT_PAY,ORDER_STATUS_CANCEL])
                ->whereBetween("created_at", [$start, $end])
                ->orderBy('order_id', 'desc')
                ->count();

            //作品数
            $worksNum = $projectModel
                        ->where(['user_id'=>$this->agent_id,'cha_id'=>$cha_id,])
                        ->whereBetween("created_at", [$start, $end])
                        ->orderBy('order_id', 'desc')
                        ->count();

            $realTotals[] = $realTotal;
            $orderNums[] = $orderNum;
            $worksNums[] = $worksNum;


        }

        $chartInfo['realTotals'] = 0;
        $chartInfo['orderNums'] = 0;
        $chartInfo['worksNums'] = 0;

        foreach ($realTotals as $rtk =>$rtv){
            $chartInfo['realTotals'] += $rtv;
        }
        foreach ($orderNums as $onk =>$onv){
            $chartInfo['orderNums'] += $onv;
        }
        foreach ($worksNums as $wnk =>$wnv){
            $chartInfo['worksNums'] += $wnv;
        }

        $chartInfo['realTotals'] = number_format($chartInfo['realTotals'],2);

        $data['totals'] = $realTotals;
        $data['orders'] = $orderNums;
        $data['works'] = $worksNums;
        $data['days'] = $days;

        $chartInfo['data'] = $data;

        return $chartInfo;

    }


    /**
     *  DMS系统 -- 订单统计
     *  各状态订单数量统计
     * @return array
     */
    public function agentOrderStatusCount()
    {
        $where['mch_id'] = $this->mch_id;
        $where['user_id'] = $this->agent_id;

        //销售总额
        $totals = $this->model
                    ->whereNotIn('order_status',[ORDER_STATUS_WAIT_CONFIRM,ORDER_STATUS_WAIT_PAY,ORDER_STATUS_CANCEL])
                    ->where($where)
                    ->sum(DB::raw('order_real_total'));

        //异常单总额
        $afterTotals = $this->model
            ->where('order_status',ORDER_STATUS_AFTERSALE)
            ->where($where)
            ->sum(DB::raw('order_real_total'));

        $all = count($this->model->where($where)->get());                                                                  //全部
        $wait_pay = count($this->model->where('order_status',ORDER_STATUS_WAIT_PAY)->where($where)->get());                //待支付
        $wait_produce = count($this->model->where('order_status',ORDER_STATUS_WAIT_PRODUCE)->where($where)->get());        //待配货
        $wait_delivery = count($this->model->where('order_status',ORDER_STATUS_WAIT_DELIVERY)->where($where)->get());      //待发货
        $wait_receive = count($this->model->where('order_status',ORDER_STATUS_WAIT_RECEIVE)->where($where)->get());        //已发货
        $finish = count($this->model->where('order_status',ORDER_STATUS_FINISH)->where($where)->get()); //已完成
        $aftersale = count($this->model->where('order_status',ORDER_STATUS_AFTERSALE)->where($where)->get());              //退换货
        if($finish==0){
            $per = number_format(0,2);
        }else{
            $per = number_format($totals/$finish,2);
        }


        return [$totals,$afterTotals,$per,$all,$wait_pay,$wait_produce,$wait_delivery,$wait_receive,$finish,$aftersale];
    }

    //DMS系统--商品统计
    //获取属于该分销商的订单
    public function getOrderId($user_id)
    {
        $order_ids = $this->model->where(['mch_id'=>$this->mch_id,'user_id'=>$user_id])->select('order_id')->get()->toArray();
        $ids = [];
        foreach ($order_ids as $k=>$v){
            $ids[] = $v['order_id'];
        }
        return $ids;
    }

    /*
     * DMS系统--地区统计
     */
    public function getAreasStatisticsInfo()
    {
        $where['mch_id'] = $this->mch_id;
        $where['user_id'] = $this->agent_id;

        //销售总额
        $totals = $this->model
            ->whereNotIn('order_status',[ORDER_STATUS_WAIT_CONFIRM,ORDER_STATUS_WAIT_PAY,ORDER_STATUS_CANCEL])
            ->where($where)
            ->sum(DB::raw('order_real_total'));

        //订单总量
        $ordNums = count($this->model->where($where)->get());
        //地区数量
        $proNums = count($this->model->where($where)->get()->groupBy('order_rcv_province'));

        //占比最高
        $provinceList = $this->model->where($where)->select(DB::raw('count(*) as count, order_rcv_province'))->groupBy('order_rcv_province')->orderBy('count','desc')->get()->toArray();
        $maxPro = $provinceList[0]??0;
        if($ordNums==0){
            $mix = number_format(0,2);
        }else{
            $mix = number_format($maxPro['count']/$ordNums*100,2);
        }

        $areaModel = app(SaasAreas::class);
        $proName = $areaModel->where(['area_id'=>$maxPro['order_rcv_province']])->select('area_name')->first();

        $mixList = [
            'mix'=>$mix,
            'proName'=>$proName['area_name']
        ];

        $data=[
            'proNums'=>$proNums,
            'ordNums'=>$ordNums,
            'totals'=>$totals,
            'mixList'=>$mixList
        ];

        return $data;
    }


    //地区统计列表
    public function getAreaTableList($where)
    {
        $where = $this->parseWhere($where);
        $where['mch_id'] = $this->mch_id;
        $where['user_id'] = $this->agent_id;

        //order 必须以 'id desc'这种方式传入.
        $query = $this->model;
        if(isset($where['order_status'])){
            $order_status = explode(",",$where['order_status']);
            $query = $query->whereIn('order_status',$order_status);
            unset($where['order_status']);
        }

        if(isset($where['deli_status'])){
            $deli_status = explode(",",$where['deli_status']);
            $query = $query->whereIn('order_shipping_status',$deli_status);
            unset($where['deli_status']);
        }

        //下单时间查询或者发货时间查询
        if(isset($where['prod_time'])&&$where['prod_time']==1){
            if(isset($where['search_time'])){
                $order_shipping_time = $where['search_time'];
                $time_list = Helper::getTimeRangedata($order_shipping_time);
                $query = $query->whereBetween("order_shipping_time",[$time_list['start'],$time_list['end']]);
                unset($where['search_time']);
            }
            unset($where['prod_time']);
        }
        if(isset($where['prod_time'])&&$where['prod_time']==2){
            if(isset($where['search_time'])){
                $where['created_at'] = $where['search_time'];
                unset($where['search_time']);
            }
            unset($where['prod_time']);
        }

        //查询时间
        if(isset($where['created_at'])){
            $created_at = $where['created_at'];
            $time_list = Helper::getTimeRangedata($created_at);
            $query = $query->whereBetween("created_at",[$time_list['start'],$time_list['end']]);
            unset($where['created_at']);
        }

        if(!empty ($where)) {
            $query =  $query->where($where);
        }
        $list = $query->get()->groupBy('order_rcv_province')->toArray();
        $ordNums = count($this->model->where(['mch_id'=>$this->mch_id,'user_id'=>$this->agent_id])->get());
        $data=[
            'data'=>$list,
            'ordNums'=>$ordNums
        ];
        return $data;
    }



    /*
     * DMS系统--物流统计
     */
    public function getLogisticsStatisticsInfo()
    {
        $where['mch_id'] = $this->mch_id;
        $where['user_id'] = $this->agent_id;

        //总运费
        $totals = $this->model
            ->where($where)
            ->sum(DB::raw('order_exp_fee'));

        //订单总量
        $ordNums = count($this->model->where($where)->get());
        //地区数量
        $deliNums = count($this->model->where($where)->get()->groupBy('order_delivery_id'));

        //占比最高
        $expressList = $this->model->where($where)->select(DB::raw('count(*) as count, order_delivery_id'))->groupBy('order_delivery_id')->orderBy('count','desc')->get()->toArray();
        $maxPro = $expressList[0]??0;
        if($ordNums==0){
            $mix = number_format(0,2);
        }else{
            $mix = number_format($maxPro['count']/$ordNums*100,2);
        }
        $areaModel = app(SaasDelivery::class);
        $proName = $areaModel->where(['delivery_id'=>$maxPro['order_delivery_id']])->select('delivery_name')->first();

        $mixList = [
            'mix'=>$mix,
            'deliName'=>$proName['delivery_name']
        ];

        $data=[
            'deliNums'=>$deliNums,
            'ordNums'=>$ordNums,
            'totals'=>$totals,
            'mixList'=>$mixList
        ];

        return $data;
    }

    //物流统计列表
    public function getLogisticsTableList($where)
    {
        $where = $this->parseWhere($where);
        $where['mch_id'] = $this->mch_id;
        $where['user_id'] = $this->agent_id;

        //order 必须以 'id desc'这种方式传入.
        $query = $this->model;
        if(isset($where['order_status'])){
            $order_status = explode(",",$where['order_status']);
            $query = $query->whereIn('order_status',$order_status);
            unset($where['order_status']);
        }

        if(isset($where['deli_status'])){
            $deli_status = explode(",",$where['deli_status']);
            $query = $query->whereIn('order_shipping_status',$deli_status);
            unset($where['deli_status']);
        }

        //下单时间查询或者发货时间查询
        if(isset($where['prod_time'])&&$where['prod_time']==1){
            if(isset($where['search_time'])){
                $order_shipping_time = $where['search_time'];
                $time_list = Helper::getTimeRangedata($order_shipping_time);
                $query = $query->whereBetween("order_shipping_time",[$time_list['start'],$time_list['end']]);
                unset($where['search_time']);
            }
            unset($where['prod_time']);
        }
        if(isset($where['prod_time'])&&$where['prod_time']==2){
            if(isset($where['search_time'])){
                $where['created_at'] = $where['search_time'];
                unset($where['search_time']);
            }
            unset($where['prod_time']);
        }

        //查询时间
        if(isset($where['created_at'])){
            $created_at = $where['created_at'];
            $time_list = Helper::getTimeRangedata($created_at);
            $query = $query->whereBetween("created_at",[$time_list['start'],$time_list['end']]);
            unset($where['created_at']);
        }

        if(!empty ($where)) {
            $query =  $query->where($where);
        }

        $list = $query->get()->groupBy('order_delivery_id')->toArray();
        $ordNums = count($this->model->where(['mch_id'=>$this->mch_id,'user_id'=>$this->agent_id])->get());
        $data=[
            'data'=>$list,
            'ordNums'=>$ordNums
        ];
        return $data;
    }

    //物流统计列表
    public function getLogisticsDetailTableList($where,$isExport=null)
    {
        $limit = isset($where['limit']) ? $where['limit']:config('common.page_limit');  //这个10取配置里的
        $where = $this->parseWhere($where);
        $where['mch_id'] = $this->mch_id;
        $where['user_id'] = $this->agent_id;
        //order 必须以 'id desc'这种方式传入.
        $query = $this->model;
        if(isset($where['order_status'])){
            $order_status = explode(",",$where['order_status']);
            $query = $query->whereIn('order_status',$order_status);
            unset($where['order_status']);
        }

        if(isset($where['deli_status'])){
            $deli_status = explode(",",$where['deli_status']);
            $query = $query->whereIn('order_shipping_status',$deli_status);
            unset($where['deli_status']);
        }

        //下单时间查询或者发货时间查询
        if(isset($where['prod_time'])&&$where['prod_time']==1){
            if(isset($where['search_time'])){
                $order_shipping_time = $where['search_time'];
                $time_list = Helper::getTimeRangedata($order_shipping_time);
                $query = $query->whereBetween("order_shipping_time",[$time_list['start'],$time_list['end']]);
                unset($where['search_time']);
            }
            unset($where['prod_time']);
        }
        if(isset($where['prod_time'])&&$where['prod_time']==2){
            if(isset($where['search_time'])){
                $where['created_at'] = $where['search_time'];
                unset($where['search_time']);
            }
            unset($where['prod_time']);
        }

        //查询时间
        if(isset($where['created_at'])){
            $created_at = $where['created_at'];
            $time_list = Helper::getTimeRangedata($created_at);
            $query = $query->whereBetween("created_at",[$time_list['start'],$time_list['end']]);
            unset($where['created_at']);
        }

        if(!empty ($where)) {
            $query =  $query->where($where);
        }

        if(empty($isExport)){
            $list = $query->paginate($limit);
        }else{
            $list = $query->get();
        }

        $expFees = $this->model->where(['mch_id'=>$this->mch_id,'user_id'=>$this->agent_id,'order_delivery_id'=>$where['order_delivery_id']])->sum(DB::raw('order_exp_fee'));
        $data=[
            'data'=>$list->toArray(),
            'expFees'=>$expFees
        ];
        return $data;
    }

    /**
     * 导出处理
     * @param $param 创建时间
     */
    public function export($param)
    {
        $commonPresenter = app(CommonPresenter::class);

        //获取数据
        $list = $this->getOrderExportData($param);
        $result = $list['data'];

        if(empty($result)){
            echo '暂无记录';
            die;
        }

        $data = [];
        foreach ($result as $k=>$v){

            //快递、发货时间
            if (!empty($v['delivery_code'])){
                $delivery_time = $this->deliveryDocModel->where(['delivery_code'=>$v['delivery_code']])->value('created_at');
                $order_delivery = $this->expressModel->where(['express_id'=>$v['order_delivery_id']])->value('express_name');
            }

            //渠道
            $data[$k]['chanel'] = $this->chanelModel->where('cha_id',$v['cha_id'])->value("cha_name");

            $detail = '';
            $data[$k]['order_no'] = $v['order_no'];
            $data[$k]['order_relation_no'] = $v['order_relation_no'];
            $data[$k]['order_real_total'] = $v['order_real_total'];
            $data[$k]['order_rcv_user'] = $v['order_rcv_user'];
            $data[$k]['order_rcv_phone'] = $v['order_rcv_phone'];
            $data[$k]['created_at'] = $v['created_at'];
            $data[$k]['order_status'] = $v['order_status'];
            $data[$k]['order_delivery'] = isset($order_delivery) ? $order_delivery : '';
            $data[$k]['delivery_code'] = $v['delivery_code'];
            $data[$k]['delivery_time'] = isset($delivery_time) ? date('Y-m-d H:i:s',strtotime($delivery_time)) : '';
            foreach ($v['item'] as $key=>$val){
                if(count($v['item']) == $key + 1){
                    $detail .= '商品名称：'.$val['prod_name'].' 工厂码：'.$val['prod_supplier_sn'].' 数量：'.$val['prod_num'].' 价格：'.$val['prod_sku_price'];
                }else{
                    $detail .= '商品名称：'.$val['prod_name'].' 工厂码：'.$val['prod_supplier_sn'].' 数量：'.$val['prod_num'].' 价格：'.$val['prod_sku_price'].'，';
                }
            }
            $data[$k]['detail'] = $detail;
        }

        $spreadsheet= new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        //设置sheet的名字  两种方法
        $spreadsheet->getActiveSheet()->setTitle('订单导出');

        //设置自动列宽
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setWidth(40);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(20);
        $sheet->getColumnDimension('K')->setWidth(20);
        $sheet->getColumnDimension('L')->setWidth(10);

        //设置第一行小标题
        $k = 1;
        $sheet->setCellValue('A'.$k, '订单号');
        $sheet->setCellValue('B'.$k, '交易关联单号');
        $sheet->setCellValue('C'.$k, '商品详情');
        $sheet->setCellValue('D'.$k, '订单金额');
        $sheet->setCellValue('E'.$k, '状态');
        $sheet->setCellValue('F'.$k, '快递方式');
        $sheet->setCellValue('G'.$k, '快递单号');
        $sheet->setCellValue('H'.$k, '收货人');
        $sheet->setCellValue('I'.$k, '电话');
        $sheet->setCellValue('J'.$k, '创建时间');
        $sheet->setCellValue('K'.$k, '发货时间');
        $sheet->setCellValue('L'.$k, '渠道');

        $k = 2;
        foreach ($data as $key => $value) {
            $sheet->setCellValue('A' . $k, "\t".$value['order_no']);
            $sheet->setCellValue('B' . $k, "\t".$value['order_relation_no']);
            $sheet->setCellValue('C' . $k, $value['detail']);
            $sheet->setCellValue('D' . $k, $value['order_real_total']);
            $sheet->setCellValue('E' . $k, $commonPresenter->exchangeOrderStatus($value['order_status']));
            $sheet->setCellValue('F' . $k, $value['order_delivery']);
            $sheet->setCellValue('G' . $k, $value['delivery_code']);
            $sheet->setCellValue('H' . $k, $value['order_rcv_user']);
            $sheet->setCellValue('I' . $k, $value['order_rcv_phone']);
            $sheet->setCellValue('J' . $k, date('Y-m-d H:i:s',$value['created_at']));
            $sheet->setCellValue('K' . $k, $value['delivery_time']);
            $sheet->setCellValue('L' . $k, $value['chanel']);

            $k++;
        }

        $file_name = '订单导出'.date('Y-m-d H:i:s',time());
        $file_name = $file_name . ".xlsx";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$file_name.'"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');

    }

    /**
     * 导出订单数据组装
     * @param $param 创建时间
     */
    public function getOrderExportData($where,$order='created_at desc')
    {
        $where['mch_id'] = isset(session("admin")['mch_id']) ? session("admin")['mch_id'] : '';
        $where = $this->parseWhere($where);

        $orwhere = [];
        if(isset($where['status'])){
            //按订单状态查询
            $order_status = [
                'ALL'                                 =>          ZERO,   //全部
                'ORDER_STATUS_WAIT_CONFIRM'           =>          ORDER_STATUS_WAIT_CONFIRM,   //待确认
                'ORDER_STATUS_WAIT_PAY'               =>          ORDER_STATUS_WAIT_PAY,       //待付款  已确认
                'ORDER_STATUS_WAIT_PRODUCE'           =>          ORDER_STATUS_WAIT_PRODUCE,   //待生产  已付款
                'ORDER_STATUS_WAIT_DELIVERY'          =>          ORDER_STATUS_WAIT_DELIVERY,  //待发货  已生产
                'ORDER_STATUS_WAIT_RECEIVE'           =>          ORDER_STATUS_WAIT_RECEIVE,   //待收货  已发货
                'ORDER_STATUS_CANCEL'                 =>          ORDER_STATUS_CANCEL,         //交易取消
                'ORDER_STATUS_AFTERSALE'              =>          ORDER_STATUS_AFTERSALE,      //售后
                'ORDER_STATUS_FINISH'                 =>          ORDER_STATUS_FINISH,         // 交易完成  已收货
            ];
            if($order_status[$where['status']] != ZERO){
                $where['order_status'] = $order_status[$where['status']];
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

        $where_sku = [];
        $sys_flag = PUBLIC_YES; //标识cms与oms、cms按货号查询时情况，1为oms、oms，0为cms
        if(isset($where['sku_sn'])){
            if($this->mch_id == PUBLIC_CMS_MCH_ID){
                //cms系统mid=0时情况
                $sys_flag = PUBLIC_NO;
                //cms下取符合货号的所有货品id
                $sku_ids = $this->skuModel->where('prod_sku_sn',$where['sku_sn'])->pluck('prod_sku_id')->toArray();
                $where_sku = $sku_ids;
            }else{
                //货号转换货号id
                $sku_data = $this->skuRepository->getGoodstype($where['sku_sn'],$this->mch_id);
                if($sku_data['code'] == PUBLIC_YES){
                    $where_sku['sku_id'] = $sku_data['sku_id'];
                }else{
                    //未找到货号对应货品id则用空值
                    $where_sku['sku_id'] = '';
                }
            }
            unset($where['sku_sn']);
        }

        //店铺来源查询
        if(isset($where['user_id'])){
            if($where['user_id']  == PUBLIC_NO){
                unset($where['user_id']);
            }
        }

        $query = $this->model->orWhereHas(
            'item',function($query) use ($where_sku,$sys_flag) {
            if (!empty($where_sku)) {
                if($sys_flag != PUBLIC_YES){
                    //cms货号查询
                    return $query->whereIn('sku_id',$where_sku);
                }else{
                    //oms、cms货号查询
                    return $query->where($where_sku);
                }
            }
        })->with(['item']);


        //查询时间
        if(isset($where['created_at'])){
            $created_at = $where['created_at'];
            $time_list = Helper::getTimeRangedata($created_at);
            if((!isset($where['order_status'])) || (isset($where['order_status']) && $where['order_status'] != ORDER_STATUS_FINISH)){
                $query = $query->whereBetween("created_at",[$time_list['start'],$time_list['end']]);
                unset($where['created_at']);
            }
        }

        //已完成包括已发货+已完成
        if(isset($where['order_status']) && $where['order_status'] == ORDER_STATUS_FINISH){
            $index = 0;
            $where_data = $where;
            $where_data['order_status'] = ORDER_STATUS_WAIT_RECEIVE;
            foreach ($where_data as $k=>$v){
                if($k == 'created_at'){
                    $orwhere[$index] = ['created_at','>=',$time_list['start']];
                    $index++;
                    $orwhere[$index] = ['created_at','<=',$time_list['end']];

                    $where[] = ['created_at','>=',$time_list['start']];
                    $where[] = ['created_at','<=',$time_list['end']];
                    unset($where['created_at']);
                }else{
                    $orwhere[$index] = [$k,$v];
                }
                $index++;
            }
        }

        if(!empty ($where)) {
            $query =  $query->where($where)->orWhere($orwhere);
        }

        if(!empty($order)) {
            $query =  $query->orderBy($orderBy[0],$orderBy[1]);
        }

        $list = $query->get();

        $arr[] = '';
        foreach ($list as $k=>$v){
            $arr[$k] = $v->toArray();
            foreach ($v->item as $key=>$val){

                //商品信息
                $prod_info = $this->prodMoel->where('prod_id',$val->prod_id)->select("prod_name")->first();
                $arr[$k]['item'][$key]['prod_name'] = $prod_info->prod_name;
                $arr[$k]['item'][$key]['prod_main_thumb'] = $this->mediaRepository->getProductPhoto($val->prod_id)[0]['prod_md_path'];
                $arr[$k]['item'][$key]['attr_str'] = $this->prodRelationAttrRepository->getProductAttr($val->sku_id);

                //货品信息
                $sku_info = $this->skuModel->where('prod_sku_id',$val->sku_id)->select('prod_sku_price','prod_supplier_sn')->first();
                $arr[$k]['item'][$key]['prod_sku_price'] = $sku_info['prod_sku_price'];
                $arr[$k]['item'][$key]['prod_supplier_sn'] = $sku_info['prod_supplier_sn'];
            }
        }
        $arr = $arr[0] == '' ? [] : $arr;
        $list = $list->toArray();
        $list['data'] = $arr;

        return $list;
    }


    //获取各个阶段的订单趋势
    public function getOrderTrendInfo($timeArr,$mid=null)
    {
        if (empty($mid) || $mid=='all'){
            $midWhere=[];
        }else{
            $midWhere = ['mch_id' => $mid];
        }

        //获取每个阶段的订单量
        foreach ($timeArr as $k => $v)
        {
            $orderCount = $this->model->where($midWhere)->whereBetween('created_at', [$v['start_time'], $v['end_time']])->count();
            $timeArr[$k]['order_count'] = $orderCount;
        }
        return $timeArr;
    }
    //获取各商家时间段的订单量
    public function getTrendOrderCount($startTime,$endTime,$mid)
    {
        $merchantInfo = app(OmsMerchantInfo::class);
        if ($mid=='all'){
            $midInfo = $merchantInfo->select('mch_id','mch_name')->get()->toArray();
        }else{
            $midInfo = $merchantInfo->where('mch_id',$mid)->select('mch_id','mch_name')->get()->toArray();
        }

        //获取各个商家在这段时间的订单量
        foreach ($midInfo as $k => $v){
            $orderCount = $this->model->where('mch_id',$v['mch_id'])->whereBetween('created_at', [$startTime, $endTime])->count();
            $midInfo[$k]['order_count'] = $orderCount;
        }

        return $midInfo;
    }

    /**
     *  获取订单详情
     *  param $oid 订单id
     * @return array
     */
    public function orderDetailInfo($oid)
    {
        $data = $this->model->where('order_id',$oid)->first();

        return $data;
    }

    /**
     *  获取快递、物流信息
     *  param $oid 订单id
     * @return array
     */
    public function getLogistics($oid)
    {
        //订单信息
        $order_info = $this->getOrderInfo($oid,'');

        //快递信息
        $delivery_doc = $this->deliveryDocModel->where("delivery_code",$order_info['delivery_code'])->first()->toArray();
        $express_info = $this->expressModel->where('express_id',$delivery_doc['delivery_id'])->select('express_name','express_code')->first()->toArray();
        $data['delivery']['express_name'] = $express_info['express_name'];
        $data['delivery']['delivery_code'] = $delivery_doc['delivery_code'];
        $data['delivery']['freight'] = $delivery_doc['freight'];
        $data['delivery']['note'] = $delivery_doc['note'];
        $data['delivery']['created_at'] = $delivery_doc['created_at'];

        //物流信息
        $data['logistics'] = app(LogisticsInfo::class)->search(strtoupper($express_info['express_code']),$delivery_doc['delivery_code']);
        return $data;

    }


    /**
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getInviterTableList($where=null, $order='created_at desc')
    {
        $limit = isset($where['limit']) ? $where['limit']:config('common.page_limit');  //这个10取配置里的

        $where['mch_id'] = isset(session("admin")['mch_id']) ? session("admin")['mch_id'] : '';
        $where = $this->parseWhere($where);

        $orwhere = [];
        if(isset($where['status'])){
            //按订单状态查询
            $order_status = [
                'ALL'                                 =>          ZERO,   //全部()
                'ORDER_STATUS_WAIT_CONFIRM'           =>          ORDER_STATUS_WAIT_CONFIRM,   //待确认
                'ORDER_STATUS_WAIT_PAY'               =>          ORDER_STATUS_WAIT_PAY,       //待付款  已确认
                'ORDER_STATUS_WAIT_PRODUCE'           =>          ORDER_STATUS_WAIT_PRODUCE,   //待生产  已付款
                'ORDER_STATUS_WAIT_DELIVERY'          =>          ORDER_STATUS_WAIT_DELIVERY,  //待发货  已生产
                'ORDER_STATUS_WAIT_RECEIVE'           =>          ORDER_STATUS_WAIT_RECEIVE,   //待收货  已发货
                'ORDER_STATUS_CANCEL'                 =>          ORDER_STATUS_CANCEL,         //交易取消
                'ORDER_STATUS_AFTERSALE'              =>          ORDER_STATUS_AFTERSALE,      //售后
                'ORDER_STATUS_FINISH'                 =>          ORDER_STATUS_FINISH,         // 交易完成  已收货
            ];
            if($order_status[$where['status']] != ZERO){
                $where['order_status'] = $order_status[$where['status']];
            }
            unset($where['status']);
        }

        //按渠道查询
        $where_info = [];
        if(isset($where['chanel'])){
            if($where['chanel'] != 'all'){
                $where_info['cha_id'] = $where['chanel'];
            }
            unset($where['chanel']);
        }

        if(!empty($this->agent_id)){
            //分销订单
            $where['user_id'] = $this->agent_id;
        }

        //order 必须以 'id desc'这种方式传入.
        $orderBy = [];
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }

        $where_sku = [];
        $sys_flag = PUBLIC_YES; //标识cms与oms、cms按货号查询时情况，1为oms、oms，0为cms
        if(isset($where['sku_sn'])){
            if($this->mch_id == PUBLIC_CMS_MCH_ID){
                //cms系统mid=0时情况
                $sys_flag = PUBLIC_NO;
                //cms下取符合货号的所有货品id
                $sku_ids = $this->skuModel->where('prod_sku_sn',$where['sku_sn'])->pluck('prod_sku_id')->toArray();
                $where_sku = $sku_ids;
            }else{
                //货号转换货号id
                $sku_data = $this->skuRepository->getGoodstype($where['sku_sn'],$this->mch_id);
                if($sku_data['code'] == PUBLIC_YES){
                    $where_sku['sku_id'] = $sku_data['sku_id'];
                }else{
                    //未找到货号对应货品id则用空值
                    $where_sku['sku_id'] = '';
                }
            }
            unset($where['sku_sn']);
        }

        //店铺来源查询
        if(isset($where['user_id'])){
            if($where['user_id']  == PUBLIC_NO){
                unset($where['user_id']);
            }
        }

        $query = $this->model->orWhereHas(
            'item',function($query) use ($where_sku,$sys_flag) {
            if (!empty($where_sku)) {
                if($sys_flag != PUBLIC_YES){
                    //cms货号查询
                    return $query->whereIn('sku_id',$where_sku);
                }else{
                    //oms、cms货号查询
                    return $query->where($where_sku);
                }
            }
        })->where(function($query) use ($where_info) {
            if (!empty($where_info)) {
                return $query->where($where_info);
            }
        })->with(['item','chanel']);

        //查询时间
        if(isset($where['created_at'])){
            $created_at = $where['created_at'];
            $time_list = Helper::getTimeRangedata($created_at);
            if((!isset($where['order_status'])) || (isset($where['order_status']) && $where['order_status'] != ORDER_STATUS_FINISH)){
                $query = $query->whereBetween("created_at",[$time_list['start'],$time_list['end']]);
                unset($where['created_at']);
            }
        }

        //已完成包括已发货+已完成
        if(isset($where['order_status']) && $where['order_status'] == ORDER_STATUS_FINISH){
            $index = 0;
            $where_data = $where;
            $where_data['order_status'] = ORDER_STATUS_WAIT_RECEIVE;
            foreach ($where_data as $k=>$v){
                if($k == 'created_at'){
                    $orwhere[$index] = ['created_at','>=',$time_list['start']];
                    $index++;
                    $orwhere[$index] = ['created_at','<=',$time_list['end']];

                    $where[] = ['created_at','>=',$time_list['start']];
                    $where[] = ['created_at','<=',$time_list['end']];
                    unset($where['created_at']);
                }else{
                    $orwhere[$index] = [$k,$v];
                }
                $index++;
            }
        }

        if(isset($where['user_ids'])){
            $query = $query->whereIn('user_id',$where['user_ids']);
            unset($where['user_ids']);
        }

        if(!empty ($where)) {
            $query =  $query->where($where)->orWhere($orwhere);
        }

        if(!empty($order)) {
            $query =  $query->orderBy($orderBy[0],$orderBy[1]);
        }

        $list = $query->paginate($limit);

        $arr[] = '';
        foreach ($list as $k=>$v){
            $arr[$k] = $v->toArray();
            $arr[$k]['total'] = count($v->item); //item数量
            $nums = 0; //总件数
            foreach ($v->item as $key=>$val){
                $nums += $val->prod_num;
            }
            $arr[$k]['nums'] = $nums;

            //店铺名称
            $agentInfo = $this->agentInfoModel->where('agent_info_id',$v['user_id'])->select("agent_name")->first();
            $arr[$k]['agent_name'] = !empty($agentInfo) ? $agentInfo->agent_name : '';

            $mch_name = $this->merchantInfoModel->where('mch_id',$v['mch_id'])->value('mch_name');
            $arr[$k]['mch_name'] = !empty($mch_name) ? $mch_name : '';

            //渠道
            $chanel = $this->chanelModel->where('cha_id',$v['cha_id'])->select("cha_name")->first();
            $arr[$k]['cha_name'] = !empty($chanel) ? $chanel->cha_name : '';

        }
        $arr = $arr[0] == '' ? [] : $arr;
        $list = $list->toArray();
        $list['data'] = $arr;
//dd($list['data']);
        return $list;
    }





}
