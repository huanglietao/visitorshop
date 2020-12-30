<?php
namespace App\Http\Controllers\Agent\Finance;

use App\Exceptions\CommonException;
use App\Http\Controllers\Agent\BaseController;
use App\Models\SaasOrders;
use App\Repositories\SaasAreasRepository;
use App\Repositories\SaasDeliveryRepository;
use App\Repositories\SaasExpressRepository;
use App\Repositories\SaasOrderProductsRepository;
use App\Repositories\SaasOrdersRepository;
use App\Repositories\SaasProductsRelationAttrRepository;
use App\Services\Helper;
use Illuminate\Http\Request;

/**
 * 销售分析
 *
 * @author: hlt <1013488674@qq.com>
 * @version: 1.0
 * @date: 2019/8/29
 */

class SalesAnalysisController extends BaseController
{
    protected $viewPath = 'agent.finance.sales_analysis';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块

    public function __construct(SaasOrdersRepository $ordersRepository)
    {
        parent::__construct();
        $this->ordersRepository = $ordersRepository;
        $this->merchantID = empty(session('admin')) == false ? session('admin')['mch_id'] : ' ';
        $this->agentID = empty(session('admin')) == false ? session('admin')['agent_info_id'] : ' ';
    }

    public function index()
    {
        return view('agent.finance.sales_analysis.index');
    }

    //销售分析统计图
    public function salesAnalysis(Request $request)
    {
        if($request->ajax())
        {
            //获取订单销售额
            $orderData = $this->ordersRepository->salesAmount($this->merchantID,$this->agentID);
            $ordersData['order_amount']['last_year'] = $orderData[1]; //去年每个月的销售额
            $ordersData['order_amount']['this_year'] = $orderData[2]; //今年每个月的销售额

            $chartData = $this->ordersRepository->getChartInfo();
            $staData = [
                'realTotals'=>$chartData['realTotals'],
                'orderNums'=>$chartData['orderNums'],
                'worksNums'=>$chartData['worksNums']
            ];

            $chartInfo = $chartData['data'];


            $htmlContents = $this->renderHtml("agent.finance.sales_analysis._salesAnalysis",['ordersData'=>json_encode($ordersData),'staData'=>$staData,'chartInfo'=>json_encode($chartInfo)]);
            return response()->json(['status' => 200, 'html' => $htmlContents]);
        }else{
            return view("agent.finance.sales_analysis._salesAnalysis");
        }
    }

    //订单统计
    public function orderStatistics()
    {
        //状态栏信息
        $orderStatusCount = $this->ordersRepository->agentOrderStatusCount();
        $htmlContents = $this->renderHtml("agent.finance.sales_analysis.order.index",['orderStatus'=>$orderStatusCount]);
        return response()->json(['status' => 200, 'html' => $htmlContents]);
    }

    public function orderTable(Request $request)
    {
        $params = $request->all();
        if(isset($params['status'])){
            unset($params['status']);
        }
        unset($params['o_status']);
        unset($params['l_status']);
        //订单状态
        if(isset($params['order_status'])){
            $order_status = explode(",",$params['order_status']);
            $params['order_status'] = $order_status;
        }
        //物流状态
        if(isset($params['deli_status'])){
            $deli_status = explode(",",$params['deli_status']);
            $params['order_shipping_status'] = $deli_status;
            unset($params['deli_status']);
        }

        $Info =  $this->ordersRepository->getOrderTableList($params);
        $orderShippingList = [];
        foreach ($Info['data'] as $key=>$value) {
            $orderProductInfo = $this->ordersRepository->orderDetailInfo($value['order_id'])->toArray();
            //订单号
            $orderInfo['order_id'] = $value['order_id'];
            $orderInfo['order_no'] = $orderProductInfo['order_no'];
            //订单状态
            $orderInfo['order_status'] = $orderProductInfo['order_status'];
            //订单金额
            $orderInfo['order_real_total'] = $orderProductInfo['order_real_total'];
            //运费
            $orderInfo['order_exp_fee'] = $orderProductInfo['order_exp_fee'];
            //下单时间
            $orderInfo['created_at'] = $orderProductInfo['created_at'];
            //数量
            $orderInfo['prod_nums'] = 0;
            foreach ($value['item'] as $prod_key => $prod_val) {
                $orderInfo['prod_nums'] +=$prod_val['prod_num'];
            }
            //订单发货信息
            $orderShippingList[$key] = $orderInfo;
            $orderInfo = [];
        }

        $total = $Info['total'];
        $htmlContents = $this->renderHtml('agent.finance.sales_analysis.order._table',['list'=>$orderShippingList]);
        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
    }
    //订单统计 详情
    public function orderForm(Request $request)
    {
        try{
            $params = $request->all();
            $Info =  $this->ordersRepository->getOrderTableList($params);
            $orderInfo = [];
            foreach ($Info['data'] as $key=>$value){
                $orderProductInfo = $this->ordersRepository->orderInfo($value['order_id'])->toArray();
                //订单号
                $orderInfo['order_no'] = $orderProductInfo['order_no'];
                //外部订单号
                $orderInfo['order_relation_no'] = $orderProductInfo['order_relation_no'];
                //下单时间
                $orderInfo['created_at'] = $orderProductInfo['created_at'];
                //收货人
                $orderInfo['buyer_nickname'] = $orderProductInfo['order_rcv_user'];
                //收货地址
                $orderInfo['order_rcv_address'] = $orderProductInfo['province_name'] . $orderProductInfo['city_name'] . $orderProductInfo['area_name'] . $orderProductInfo['order_rcv_address'];
                //手机
                $orderInfo['order_rcv_phone'] = $orderProductInfo['order_rcv_phone'];
                //订单金额
                $orderInfo['order_real_total'] = $orderProductInfo['order_real_total'];
                //支付方式
                $orderInfo['pay_name'] = $orderProductInfo['pay_name'];
                //支付状态
                $orderInfo['order_pay_status'] = $orderProductInfo['order_pay_status'];
                //物流方式
                $orderInfo['delivery_name'] = $orderProductInfo['delivery_name'];
                //物流单号
                $orderInfo['delivery_code'] = $orderProductInfo['delivery_code'];
                //发货日期
                $orderInfo['order_shipping_time'] = $orderProductInfo['order_shipping_time'];
                //店铺来源
                $orderInfo['agent_name'] = $orderProductInfo['agent_name'];
                //渠道来源
                $orderInfo['cha_name'] = $orderProductInfo['cha_name'];
                //供应商
                $orderInfo['sp_name'] = '';
                //商品信息
                $orderInfo['prod_info'] = '';
                foreach ($orderProductInfo['prod_info'] as $prod_key => $prod_val){

                    //供应商
                    if(!empty($orderInfo['sp_name'])){
                        $orderInfo['sp_name']  = $orderInfo['sp_name'].",".$prod_val['sp_name'];
                    }else{
                        $orderInfo['sp_name']  = $prod_val['sp_name'];
                    }
                    //商品信息
                    if(!empty($orderInfo['prod_info'])){
                        $orderInfo['prod_info']  = $orderInfo['prod_info']."  商品编号:".$prod_val['prod_sn']." 商品名称:".$prod_val['prod_name']." 规格:".$prod_val['prod_attr_str']." 数量:".$prod_val['prod_num'];
                    }else{
                        $orderInfo['prod_info']  = "商品编号:".$prod_val['prod_sn']." 商品名称:".$prod_val['prod_name']." 规格:".$prod_val['prod_attr_str']." 数量:".$prod_val['prod_num'];
                    }
                }
            }

            $htmlContents = $this->renderHtml('agent.finance.sales_analysis.order._form',['row' =>$orderInfo]);

            return $this->jsonSuccess(['html' => $htmlContents]);
        }catch (CommonException $e){
            $this->jsonFailed($e->getMessage());
        }
    }



    //商品统计列表
    public function goodsStatistics(SaasOrderProductsRepository $orderProductsRepository)
    {

        $totalNum = $orderProductsRepository->getOrderProducts($this->merchantID,$this->agentID);
        $htmlContents = $this->renderHtml("agent.finance.sales_analysis.goods.index",['totalNum'=>$totalNum]);
        return response()->json(['status' => 200, 'html' => $htmlContents]);

    }
    // 商品统计数据
    public function goodsTable(Request $request,SaasOrderProductsRepository $orderProductsRepository,
                                SaasProductsRelationAttrRepository $productsRelationAttrRepository)
    {
        try{
            $params = $request->all();
            if(isset($params['status'])){
                unset($params['status']);
            }
            $limit = isset($params['limit']) ? $params['limit']:config('common.page_limit');  //这个10取配置里的
            $curPage = isset($params['page']) ? $params['page']:1;
            unset($params['o_status']);
            unset($params['l_status']);
            $totalNum = $orderProductsRepository->getOrderProducts($this->merchantID,$this->agentID);

            $params['mch_id'] = $this->merchantID;
            $params['user_id'] = $this->agentID;
            if(isset($params['deli_status']) && $params['deli_status']=='0'){
                $params['deli_status']=0;
            }
            $list = $orderProductsRepository->getOrderProdTableList($params);
            $offset = ($curPage-1)*$limit;
            if($limit>count($list)-$offset){
                $limit = count($list);
            };
            $total = count($list);
            $list = array_slice($list,$offset,$limit);
            $product_info_list = [];
            foreach ($list as $key=>$value) {
                //货品号
                $product_info['prod_sku_sn'] = $value[0]['prod_sku']['prod_sku_sn'];
                //商品名称
                $product_info['prod_name'] = $value[0]['prod']['prod_name'];
                //货品的属性值
                $prod_attr_str = $productsRelationAttrRepository->getProductAttr($value[0]['sku_id']);
                $product_info['prod_attr'] = $prod_attr_str;
                $product_info['prod_num'] = 0;
                //货品售价
                $product_info['prod_sku_price'] = $value[0]['prod_sku']['prod_sku_price'];
                //货品实收金额
                $product_info['prod_sale_price'] = 0;
                foreach ($value as $k=>$v){
                    //订单中购买的货品的数量
                    $product_info['prod_num'] += $v['prod_num'];
                    //货品实收金额
                    $product_info['prod_sale_price'] += $v['prod_sale_price'];
                }
                $product_info_list[$key] = $product_info;
            }

            foreach ($product_info_list as $key =>$value){
                $product_info_list[$key]['prod_sale_price'] = round($value['prod_sale_price'],2);
                if($totalNum['prod_num']==0){
                    $product_info_list[$key]['percentage'] = round(0,2);
                }else{
                    $product_info_list[$key]['percentage'] = round($value['prod_num']/$totalNum['prod_num']*100,2);
                }

            }

            $htmlContents = $this->renderHtml('agent.finance.sales_analysis.goods._table',['list' =>$product_info_list]);
            return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
        }catch (CommonException $e){
            $this->jsonFailed($e->getMessage());
        }

    }



    //地区统计列表
    public function areasStatistics(SaasAreasRepository $areasRepository)
    {
        $navData = $this->ordersRepository->getAreasStatisticsInfo();
        //获取直辖市
        $proData = $areasRepository->getAreaByLevel(ONE);
        $htmlContents = $this->renderHtml("agent.finance.sales_analysis.areas.index",['navData'=>$navData,'proData'=>$proData]);
        return response()->json(['status' => 200, 'html' => $htmlContents]);
    }
    // 地区统计数据
    public function areasTable(Request $request,SaasAreasRepository $areasRepository)
    {
        try{
            $params = $request->all();
            if(isset($params['status'])){
                unset($params['status']);
            }
            $limit = isset($params['limit']) ? $params['limit']:config('common.page_limit');  //这个10取配置里的
            $curPage = isset($params['page']) ? $params['page']:1;
            unset($params['o_status']);
            unset($params['l_status']);
            unset($params['limit']);

            $list = $this->ordersRepository->getAreaTableList($params);
            $area_info_list = [];
            foreach ($list['data'] as $key=>$value) {
                $proName = $areasRepository->getById($key);
                $area_info_list[$key]['area_name'] = $proName['area_name'];
                $area_info_list[$key]['ordersNum'] = count($value);
                $area_info_list[$key]['totals'] = 0;
                foreach ($value as $vk=>$vv){
                    $area_info_list[$key]['totals'] += $vv['order_real_total'];
                }
                $area_info_list[$key]['per'] = number_format($area_info_list[$key]['totals']/$area_info_list[$key]['ordersNum'],2);
                $area_info_list[$key]['mix'] = number_format(count($value)/$list['ordNums']*100,2);
            }

            $offset = ($curPage-1)*$limit;
            if($limit>count($area_info_list)-$offset){
                $limit = count($area_info_list);
            };
            $total = count($area_info_list);
            $area_info_list = array_slice($area_info_list,$offset,$limit);


            $htmlContents = $this->renderHtml('agent.finance.sales_analysis.areas._table',['list' =>$area_info_list]);
            return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
        }catch (CommonException $e){
            $this->jsonFailed($e->getMessage());
        }
    }



    //物流统计列表
    public function logisticsStatistics(SaasDeliveryRepository $deliveryRepository)
    {
        $navData = $this->ordersRepository->getLogisticsStatisticsInfo();
        //获取快递方式
        $deliData = $deliveryRepository->getDeliveryByMid($this->merchantID);
        $htmlContents = $this->renderHtml("agent.finance.sales_analysis.logistics.index",['navData'=>$navData,'deliData'=>$deliData]);
        return response()->json(['status' => 200, 'html' => $htmlContents]);

    }
    // 物流统计数据
    public function logisticsTable(Request $request,SaasDeliveryRepository $deliveryRepository)
    {
        try{
            $params = $request->all();
            if(isset($params['status'])){
                unset($params['status']);
            }
            $limit = isset($params['limit']) ? $params['limit']:config('common.page_limit');  //这个10取配置里的
            $curPage = isset($params['page']) ? $params['page']:1;
            unset($params['o_status']);
            unset($params['l_status']);
            unset($params['limit']);

            $list = $this->ordersRepository->getLogisticsTableList($params);
            $express_info_list = [];
            foreach ($list['data'] as $key=>$value) {
                $expName = $deliveryRepository->getById($key);
                $express_info_list[$key]['express_id'] = $expName['delivery_id'];
                $express_info_list[$key]['express_name'] = $expName['delivery_name'];
                $express_info_list[$key]['ordersNum'] = count($value);
                $express_info_list[$key]['exp_fee'] = 0;
                foreach ($value as $vk=>$vv){
                    $express_info_list[$key]['exp_fee'] += $vv['order_exp_fee'];
                }
                $express_info_list[$key]['mix'] = number_format(count($value)/$list['ordNums']*100,2);
            }

            $offset = ($curPage-1)*$limit;
            if($limit>count($express_info_list)-$offset){
                $limit = count($express_info_list);
            };
            $total = count($express_info_list);
            $express_info_list = array_slice($express_info_list,$offset,$limit);


            $htmlContents = $this->renderHtml('agent.finance.sales_analysis.logistics._table',['list' =>$express_info_list]);
            return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
        }catch (CommonException $e){
            $this->jsonFailed($e->getMessage());
        }

    }


    //物流明细统计列表
    public function logisticsDetailStatistics(Request $request,SaasDeliveryRepository $deliveryRepository)
    {
        $express_id = $request->get('express_id');
        $str = $express_id;
        $navData = $this->ordersRepository->getLogisticsStatisticsInfo();
        //获取快递方式
        $deliData = $deliveryRepository->getDeliveryByMid($this->merchantID);
        //获取table视图
        $htmlContents = $this->renderHtml("agent.finance.sales_analysis.logisticsDetail.index",['navData'=>$navData,'deliData'=>$deliData,'str'=>$str]);
        return response()->json(['status' => 200, 'html' => $htmlContents,'total' => 56]);

    }

    // 物流明细统计数据
    public function logisticsDetailTable(Request $request,SaasDeliveryRepository $deliveryRepository,
                                         SaasAreasRepository $areasRepository,SaasOrderProductsRepository $orderProductsRepository)
    {
        try{
            $params = $request->all();
            if(isset($params['status'])){
                unset($params['status']);
            }
            unset($params['o_status']);
            unset($params['l_status']);

            $list = $this->ordersRepository->getLogisticsDetailTableList($params);

            $express_info_list = [];
            foreach ($list['data']['data'] as $key=>$value) {
                $express_info_list[$key]['order_no'] = $value['order_no'];
                $express_info_list[$key]['created_at'] = $value['created_at'];
                $expName = $deliveryRepository->getById($value['order_delivery_id']);
                $express_info_list[$key]['express_name'] = $expName['delivery_name'];
                $express_info_list[$key]['delivery_code'] = $value['delivery_code'];
                $express_info_list[$key]['order_shipping_time'] = $value['order_shipping_time'];

                $products = $orderProductsRepository->getList(['ord_id'=>$value['order_id']])->toArray();
                $express_info_list[$key]['num'] = count($products);
                $express_info_list[$key]['nums'] = 0;
                foreach ($products as $pk =>$pv){
                    $express_info_list[$key]['nums'] += $pv['prod_num'];
                }

                //省市区转换
                $province = $areasRepository->getAreaIdList($value['order_rcv_province'])->toArray();
                $city = $areasRepository->getAreaIdList($value['order_rcv_city'])->toArray();
                $area = $areasRepository->getAreaIdList($value['order_rcv_area'])->toArray();
                $province = !empty($province) ? $province['area_name'] : '';
                $city = !empty($city) ? $city['area_name'] : '';
                $area_name = !empty($area) ? $area['area_name'] : '';

                $express_info_list[$key]['area'] = $province.'-'.$city.'-'.$area_name;
                $express_info_list[$key]['order_status'] = $value['order_status'];
                $express_info_list[$key]['order_exp_fee'] = $value['order_exp_fee'];
                $express_info_list[$key]['order_real_total'] = $value['order_real_total'];
                $express_info_list[$key]['mix_exp_fee'] = 0;

                if($value['order_exp_fee']!='0.00'){
                    $express_info_list[$key]['mix_exp_fee'] = number_format($value['order_exp_fee']/$list['expFees'],2);
                }

            }

            $total = $list['data']['total'];

            $htmlContents = $this->renderHtml('agent.finance.sales_analysis.logisticsDetail._table',['list' =>$express_info_list]);
            return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
        }catch (CommonException $e){
            $this->jsonFailed($e->getMessage());
        }
    }


}