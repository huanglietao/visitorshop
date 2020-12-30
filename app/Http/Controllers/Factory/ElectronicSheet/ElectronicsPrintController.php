<?php
namespace App\Http\Controllers\Factory\ElectronicSheet;

use App\Models\SaasAreas;
use App\Models\SaasDelivery;
use App\Models\SaasExpress;
use App\Models\SaasOrderProducts;
use App\Models\SaasOrders;
use App\Models\SaasOrderStocked;
use App\Models\SaasPrintLog;
use App\Models\SaasSuppliersOrders;
use App\Repositories\SaasExpressRepository;
use App\Services\Logistics;
use App\Services\Outer\Tmall;
use App\Services\Prints;
use App\Services\Works\TbOuter;
use Illuminate\Support\Facades\Cookie;
use App\Exceptions\CommonException;
use App\Http\Controllers\Merchant\BaseController;
use App\Repositories\SaasDeliveryRepository;
use App\Repositories\SaasOrdersRepository;
use App\Repositories\SaasSuppliersOrderRepository;
use App\Services\Exception;
use Illuminate\Http\Request;

/**
 * 电子打单
 *
 * @author: hlt
 * @version: 1.0
 * @date: 2020/06/08
 */

class ElectronicsPrintController extends BaseController
{
    protected $viewPath = 'factory.print.electronicsprint.index';  //当前控制器所的view所在的目录
    protected $modules = 'factory';        //当前控制器所属模块
    protected $mchID;

    public function __construct(SaasExpressRepository $express)
    {
        $this->expressRepository = $express;

        $this->sheetTemplates = config('print.sheetTemplates');

        $this->sender = [
            '100' => [    //100 是商户号
                'province'   => '天津',
                'city'       => '天津市',
                'district'   => '北辰区',
                'detail'     => '永兴道102号',
                'mobile'     => '02787101355',
                'name'       => '李先生'
            ]
        ];
        $this->customerTemplates = config('print.customerTemplates');
        parent::__construct();
    }

    //列表展示页面
    public function index()
    {
        $type = \request()->get('type');
        $type = $type ?? 'all';
        $company = $this->expressRepository->getDeliveryName($type);
        if(empty($company)) {
            $company = "电子打单";
        }
        $username = \request()->get('username');
        $username = $username?$username:"";

        //判断有没有供货商id
        $sp_id = \request()->get('sp_id');
        if (!$sp_id){
            $sp_id = "";
        }

        Cookie::queue("type", $type, 3600*24*30);

        //判断是否是打印新单号的标识
        $is_new_code = \request()->get('is_new');
        $is_new_code = $is_new_code?$is_new_code:0;




        return view("factory.print.electronicsprint.index",['type' =>$type,'username' => $username,'company' => $company,'sp_id' => $sp_id,'is_new_code'=>$is_new_code]);
    }


    /**
     *第一步检查
     */
    public function check()
    {
        $type = Cookie::get('type');
        $key = \request()->post('key');  //扫码的订单号，订单项目号
        $stocked = \request()->post('stocked');
        //考虑供货商情况
        $spId = \request()->post('sp_id');
        if(empty($key)) {
            $this->returnJson(1,"订单号不能为空!");
        }
        //200200602175103408_1_1_1
        $spWhere = [];
        //解析订单项目号,删除最后一个_
        $projectNoArr = explode('_',$key);
        array_pop($projectNoArr);
        $projectNo = implode('-',$projectNoArr);

        //获取订单信息
        if ($spId) {
            $spWhere['sp_id'] = $spId;
        }
        $orderProductsModel = app(SaasOrderProducts::class);
        $orderModel = app(SaasOrders::class);
        //获取该订单id
        $orderItemInfo = $orderProductsModel->where('ord_prj_item_no',$projectNo)->where($spWhere)->first();
        if (!$orderItemInfo)
        {
            $this->returnJson(1,"订单".$key."不存在!");
        }

        $stockedModel = app(SaasOrderStocked::class);

        $stocked_status = $stockedModel->where('project_item_no',$projectNo)->value('is_stocked');

        if ($stocked_status == NOT_BE_STOCKED)
        {
            //未集货
            //若商品数量大于等于2，先抛出提示
            if ($orderItemInfo['prod_num'] >= 2 && $stocked == 0){
                $this->returnJson(2,$orderItemInfo['prod_num']);
            }else{
                //只有一件商品，直接更新为已集货
                $isStockedExist = $stockedModel->where('project_item_no',$projectNo)->exists();
                if ($isStockedExist)
                {
                    //更新为已集货
                    $stockedModel->where('project_item_no',$projectNo)->update(['is_stocked' => BE_STOCKED]);
                }else{
                    //插入集货记录
                    $data = [
                        'order_no'        => $orderItemInfo['order_no'],
                        'project_item_no' => $projectNo,
                        'is_stocked'      => BE_STOCKED,
                        'created_at'      => time(),
                    ];
                    $stockedModel->insert($data);
                }
            }
        }


        $orderId = $orderItemInfo['ord_id'];
        //获取订单信息
        $orderInfo = $orderModel->with(['item' =>function($query) use($projectNo,$spWhere){
            $query->where($spWhere);
        }])->where('order_id',$orderId)->first();
        if (!$orderInfo){
            $this->returnJson(1,"订单".$key."不存在!");
        }
        //判断订单状态，未付款的订单不能进行打单
        $canNotPrintOrderStatus = [ORDER_STATUS_WAIT_CONFIRM,ORDER_STATUS_WAIT_PAY];
        if (in_array($orderInfo['order_status'],$canNotPrintOrderStatus)){
            $this->returnJson(1,"订单".$key."还没付款,无法打单");
        }
        //获取快递方式
        $orderInfo = $orderInfo->toArray();
        $deliveryId = $orderInfo['order_delivery_id'];
        //获取快递方式包含的快递
        $expressModel = app(SaasExpress::class);
        $deliveryModel = app(SaasDelivery::class);
        $expressStr = $deliveryModel->where('delivery_id',$deliveryId)->value('delivery_express_list');
        if (empty($expressStr)){
            $expressArr = [];
        }else{
            $expressArr = explode(',',$expressStr);
        }
        $deliveryArr = [];

        //获取快递方式
        if ($type == "all"){
            //获取权重最高的快递方式为指定快递
            $expressList = $expressModel->whereIn('express_id',$expressArr)->orderBy('weight','desc')->get()->toArray();

            if (empty($expressList)){
                if (empty($expressArr)){
                    //快递数组为空时，可能设的是固定运费，这时候去权重最高的快递作为默认快递
                    $expressList = $expressModel->orderBy('weight','desc')->get()->toArray();
                    $dType = $expressList[0]['express_code'];
                }else{
                    $this->returnJson(1,"快递方式不存在");
                }
            }else{
                $dType = $expressList[0]['express_code'];
            }
        }else{
            $expressList = $expressModel->whereIn('express_id',$expressArr)->pluck('express_code')->toArray();
            if (in_array($type,$expressList)){
                //快递符合
                $dType = $type;
            }else{
                $company = $this->expressRepository->getDeliveryName($type);
                if (empty($company)){
                    $company = "异常快递方式";
                }
                $this->returnJson(1,"该订单不发".$company);
            }
        }
        $type = $dType;

        $company = $this->expressRepository->getDeliveryName($type);
        if (empty($company)){
            $company = "异常快递";
        }

        $print = 1;   //是否进行打印面单
        $hasPrint = 0; //是否已经打印过
        $canPrint = 1;  //这个好像没啥用，暂时放在这里写死
        //获取该订单的备货状态
        foreach ($orderInfo['item'] as $k=>$v){
            $stocked_status = $stockedModel->where('project_item_no',$v['ord_prj_item_no'])->value('is_stocked');
            if ($stocked_status){
                $stocked_status = "已集货";
            }else{
                $stocked_status = "未集货";
                $print = 0;
            }
            $orderInfo['item'][$k]['is_stocked'] = $stocked_status;
        }
        $isStocked = $stockedModel->where('project_item_no',$projectNo)->value('is_stocked');
        if (!$isStocked) {
            //未集货
            $print = 0;
        }
        $printLogModel = app(SaasPrintLog::class);

        //如果存在打印记录则不直接进行打印
        $isPrint =  $printLogModel->where('order_no',$orderInfo['order_no'])->where($spWhere)->get()->toArray();
        if (!empty($isPrint)){
            $hasPrint = 1;
            $print = 0;
        }
        //转换打印记录中的快递方式
        foreach ($isPrint as $k => $v){
            //大写转小写
            $cCode = strtolower($v['company']);
            $p_company = $this->expressRepository->getDeliveryName($cCode);
            $isPrint[$k]['company_name'] = $p_company;
        }

        $areasModel = app(SaasAreas::class);
        //获取收货地址
        $orderInfo['prod_name'] = $areasModel->where('area_id',$orderInfo['order_rcv_province'])->value('area_name');
        $orderInfo['city_name'] = $areasModel->where('area_id',$orderInfo['order_rcv_city'])->value('area_name');
        $orderInfo['dist_name'] = $areasModel->where('area_id',$orderInfo['order_rcv_area'])->value('area_name');

        //获取list视图
        $view=view('factory.print.electronicsprint.list')->with([
            'list' => $orderInfo,
            'plist' => $isPrint,
            'company' => $company,
        ]);
        $html=response($view)->getContent();


        $data = [
            'list'=>$html,
            'print' => $print,
            'has_print'=>$hasPrint,
            'can_print'=>$canPrint,
            'type'=>strtoupper($type),//小写转大写
            'works_tags'=>''
        ];
        return $this->returnJson(0,'', $data);
    }

    /**
     * @throws \App\Exceptions\CommonException
     */
    public function printData()
    {

        $data = \request()->all();
        $printerService = app(Prints::class);
        /*$orderInfo = ['id'=>'123','order_no'=>'3216548916181184','province' => '广东','city'=>'广州市','area'=>'天河区','address'=>'天盈创意园d1033','mobile'=>'1326544561','consignee'=>'黄某某'];
        $res = $printerService->sfPrinter($orderInfo,'20020060219280298479699','SF');
        if ($res['code'] == '1')
        {
            return $this->returnJson(3,'', $res['data']);
        }else{
            return $this->returnJson(1, $res['msg']);
        }*/

        $key = $data['key'];
        //解析订单项目号,删除最后一个_
        $projectNoArr = explode('_',$data['key']);
        array_pop($projectNoArr);
        $projectNo = implode('-',$projectNoArr);
        $orderProductsModel = app(SaasOrderProducts::class);
        $orderModel = app(SaasOrders::class);
        //获取该订单id
        $orderItemInfo = $orderProductsModel->where('ord_prj_item_no',$projectNo)->first();
        if (!$orderItemInfo)
        {
            $this->returnJson(1,"订单".$key."不存在!");
        }
        //获取供应商id
        $spId = $orderItemInfo['sp_id'];
        //获取订单号
        $orderId = $orderItemInfo['ord_id'];
        $orderInfo = $orderModel->where(['order_id' => $orderId])->first();
        if (!$orderInfo){
            $this->returnJson(1,"订单".$key."不存在!");
        }
        $orderInfo = $orderInfo->toArray();

        $logModel = app(SaasPrintLog::class);

        //生成交易单号
        if($data['is_old'] != '0') {
            $log = $logModel->where(['id' => $data['is_old']])->first();
            if (!$log){
                $tradeOrderNo = $orderItemInfo['order_no'];
            }else{
                $tradeOrderNo =  $log['trade_order'];
            }
        } else {
            $tradeOrderNo = $orderItemInfo['order_no'] . rand(100, 999);
        }
        //$tradeOrderNo = '200212213728992173';
        //组织数据
        $orderInfo['trader_no'] = $tradeOrderNo;
        $orderInfo['id'] = $orderInfo['order_id'];
        //获取收货地址
        $areasModel = app(SaasAreas::class);
        $orderInfo['province'] = $areasModel->where('area_id',$orderInfo['order_rcv_province'])->value('area_name');
        $orderInfo['city'] = $areasModel->where('area_id',$orderInfo['order_rcv_city'])->value('area_name');
        $orderInfo['area'] = $areasModel->where('area_id',$orderInfo['order_rcv_area'])->value('area_name');
        $orderInfo['address'] = $orderInfo['order_rcv_address'];
        $orderInfo['mobile'] = $orderInfo['order_rcv_phone'];
        $orderInfo['consignee'] = $orderInfo['order_rcv_user'];
        $orderInfo['agent_id'] = $orderInfo['user_id'];

        $note = $orderInfo['order_no'];
        //根据快递方式选择顺丰或者菜鸟
        if ($data['ctype'] == "SF")
        {
            //顺丰
            $res = $printerService->sfPrinter($orderInfo,$tradeOrderNo,$data['ctype'],$data['is_old'],$spId,$note);
            if ($res['code'] == '1')
            {
                return $this->returnJson(3,'', $res['data']);
            }else{
                return $this->returnJson(1, $res['msg']);
            }

        }else{
            $res = $printerService->caiNiaoPrinter($orderInfo,$tradeOrderNo,$data['ctype'],$data['is_old'],$spId,$note);
            if ($res['code'] == '1')
            {
                return $this->returnJson(0,'', $res['data']);
            }else{
                return $this->returnJson(1, $res['msg']);
            }
        }
    }
    //发货逻辑
    public function delivery()
    {
        $data = \request()->all();
        if (empty( $data['taskid'])) {
            $this->returnJson(1, '打印任务（taskID）不存在!');
        }

        $arrTask = explode('_', $data['taskid']);

        $sysOrderId = $arrTask[0];
        $taskId = $arrTask[3];

        $printLogModel = app(SaasPrintLog::class);
        $orderModel = app(SaasOrders::class);
        //获取订单相关信息
        $ordersInfo = $orderModel->where('order_id',$sysOrderId)->first();
        //获取打印记录
        $printLogInfo = $printLogModel->where('id',$taskId)->first();
        if (empty($ordersInfo) || empty($printLogInfo)) {
            $this->returnJson(1, '参数错误，请联系管理员!');
        }
        //打印次数加1
        $printLogInfo->increment('print_times', 1);
        //获取快递id
        $expressModel = app(SaasExpress::class);
        $delivery_id = $expressModel->where('express_code',strtolower($printLogInfo['company']))->value('express_id');
        //获取供应商id
        $spId = $printLogInfo['sp_id'];
        //获取供应商订单id
        $supplierOrderModel = app(SaasSuppliersOrders::class);
        $supOrderId = $supplierOrderModel->where(['ord_id' => $sysOrderId,'supplier_id'=>$spId])->value('sp_ord_id');
        //组织请求发货接口的数据
        $deliveryData = [
            'delivery_code'     => $printLogInfo['waybill_code'],
            'order_delivery_id' => $delivery_id,
            'order_exp_fee'     => $ordersInfo['order_exp_fee'],
            'order_remark_admin'=> '打印面单发货'
        ];
        //请求发货接口进行发货
        $orderRepository = app(SaasOrdersRepository::class);
        $logistics = app(Logistics::class);
        try{
            $orderRepository->delivery($sysOrderId,$deliveryData,'打单员',config('common.sys_abbreviation')['factory'],$supOrderId);
            //请求物流回写接口
            $logistics->deliveryWriteBack($ordersInfo['order_no'],$printLogInfo['waybill_code'],strtolower($printLogInfo['company']));
            $this->returnJson(0,'发货回调成功');
        }catch (CommonException $e){
            $this->returnJson(1, $e->getMessage());
        }


    }
    /**
     * @param int $status 状态码
     * @param string $msg 返回信息
     * @param string $data 数据
     */
    private function returnJson($status = 0, $msg = '', $data = '')
    {
        echo json_encode(['status' => $status, 'msg' => $msg, 'content' => $data]);
        exit();
    }

}