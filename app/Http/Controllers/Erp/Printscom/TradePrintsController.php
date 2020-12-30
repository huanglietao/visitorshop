<?php
/**
 * Created by sass.
 * Author: HLT
 * Date: 2020/2/12
 * Time: 9:48
 */
namespace App\Http\Controllers\Erp\Printscom;

use App\Http\Controllers\Erp\BaseController;
use App\Repositories\AreasRepository;
use App\Repositories\ErpPrintLogRepository;
use App\Repositories\TempOrdersRepository;
use App\Services\Outer\Erp\Api;
use App\Services\Outer\TbApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redis;
use PhpOffice\PhpSpreadsheet\IOFactory;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Services\Common\CallWayBillPrinter\BillPrinter;


class TradePrintsController extends BaseController
{
    protected $viewPath = 'erp.print';  //当前控制器所的view所在的目录
    protected $modules = 'sys';//当前控制器所属模块

    protected $company;
    protected $areaRepository;  //地址库仓库
    protected $sheetTemplates;  //电子面单模板
    protected $customerTemplates; //自定义区域模板
    protected $sender = [];       //发件人信息

    public function __construct(TempOrdersRepository $repository, AreasRepository $areasRepository,ErpPrintLogRepository $logRepository)
    {
        $this->company = [
            'yto' => '圆通快递',
            'sf' => '顺丰快递',
            'yunda' => '韵达快递',
            'sto'=>'申通快递',
            'eyb' => '中国邮政快递包裹',
            'htky' => '百世快递'
        ];
        $this->sheetTemplates = [
            'YTO' => 'http://cloudprint.cainiao.com/template/standard/290659/31',
            'SF' => 'http://cloudprint.cainiao.com/template/standard/1501/54',
            'YUNDA' => 'http://cloudprint.cainiao.com/template/standard/401/165',
            'STO'=>'http://cloudprint.cainiao.com/template/standard/288948/33',
            'EMS' => 'http://cloudprint.cainiao.com/template/standard/701/127',
            'EYB' => 'http://cloudprint.cainiao.com/template/standard/801/147',
            'HTKY' => 'http://cloudprint.cainiao.com/template/standard/501/147',
            'POSTB' => 'http://cloudprint.cainiao.com/template/standard/801/147',
        ];

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
        $this->customerTemplates = 'http://cloudprint.cainiao.com/print/resource/getResource.json?resourceId=1502530&status=0';
        $this->repositories = $repository;
        $this->areaRepository = $areasRepository;
        $this->logRepository = $logRepository;
        parent::__construct();
    }

    public function index()
    {



        $type = \request()->get('type');
        $type = $type ?? 'yto';
        if(!isset($this->company[$type])) {
            $company = $this->company['yto'];
        } else {
            $company = $this->company[$type];
        }


        $username = \request()->get('username');
        $username = $username??"";

        Cookie::queue("type", $type, time()+3600*24*30);
        /*Cookie::queue("type", $type, 3600*24*30);*/

        //获取接口返回的数据
        return view('erp.printscom.trade_print',['type' =>$type,'username' => $username,'company' => $company]);
    }


    /**
     *第一步检查
     */
    public function check()
    {



        $type = Cookie::get('type');
        $key = \request()->post('key');  //扫码的订单号，erp订单号或行项目号
        $stocked = \request()->post('stocked');


        if(empty($key)) {
            $this->returnJson(1,"订单号不能为空!");
        }

        //通过接口获取订单数据
        $apiOrderDataArr = $this->repositories->getTradeOrdersInfoByApi($key);

        $apiOrderData = $apiOrderDataArr['data'];
        $msg = $apiOrderDataArr['msg'];

        if ($msg!=""){
            $this->returnJson(1,$msg);
        }

        if(empty($apiOrderData)) {
            $this->returnJson(1,"订单".$key."不存在!");
        }

        //是否需要创建新订单数据
        $orderItemInfo = $this->repositories->orderItemInfo($key);


        if(empty($orderItemInfo)) {
            //创建新的订单记录
            $this->repositories->createOrders($apiOrderData);
        } else {
            //更新主记录数据并把子表记录更正为已集货
            $address = $this->areaRepository->parseDetailAddress($apiOrderData['address']);
            if (empty($address)) {
                $this->returnJson(1,"收货地址解析失败!");
            }
            $orderData = [
                'consignee'           => $apiOrderData['consignee'],
                'mobile'              => $apiOrderData['mobile'],
                'assign_express_type' => $apiOrderData['assign_express_type'],
                'province'            => $address['p'],
                'city'                => $address['c'],
                'area'                => $address['a'],
                'address'             => $address['d'],
                'sender_person'       => $apiOrderData['sender_person'],
                'sender_phone'        => $apiOrderData['sender_phone'],
                'sender_address'      => $apiOrderData['sender_address'],
            ];
            $this->repositories->updateOrders($key, $orderData);
        }




        //判断集货逻辑(份数)
        $repOrderItem = $this->repositories->orderItemInfo($key);

        if(empty($repOrderItem['is_stocked'])) {
            if($orderItemInfo['print_nums'] >1 && $stocked == '0') {
                returnJson('2',$orderItemInfo['print_nums']);
            } else {
                //更新为已集货状态
                $this->repositories->updateOrderItems($repOrderItem['id'], ['is_stocked'=>1]);
            }
        }

        //判断集货逻辑(稿件数)
        $isStocked = $this->repositories->isStocked($repOrderItem['sys_order_no']);


        $print = 1;   //是否进行打印面单
        $hasPrint = 0; //是否已经打印过
        $canPrint = 1;  //这个好像没啥用，暂时放在这里写死
        if (!$isStocked) {
            $print = 0;
        }


        //获取集货单记录
        $order_list = $this->repositories->orderItemAll($repOrderItem['sys_order_no']);

        //获取主订单记录
        $main_order = $this->repositories->getOrder($repOrderItem['sys_order_no']);
        if (!$main_order){
            $this->returnJson(1,"该订单不存在");
        }

        if ($main_order['assign_express_type'] == 'sfd'){
            $this->returnJson(1," '顺丰到'订单不能打印电子面单");
        }


        //获取快递方式
        $companyArr = $this->repositories->transLogistics($this->company,$main_order['assign_express_type'],$type);
        $company = $companyArr['describe'];


        if ($companyArr['describe'] == "查无此快递方式"){
            $this->returnJson(1,'该快递方式不存在');
        }

        if ($companyArr['label'] != $type){
            $this->returnJson(1,'该订单快递为'.$companyArr['describe'].'，请到'.$companyArr['describe'].'专用打印链接打印');
        }




        //如果存在打印记录则不直接进行打印
        $isPrint = json_decode($this->logRepository->getPrintLog($repOrderItem['sys_order_no']),true);

        if (!empty($isPrint)){
            $hasPrint = 1;
            $print = 0;
        }





        //获取list视图
        $view=view('erp.printscom.list')->with([
            'list' => $order_list,
            'plist' => $isPrint,
            'company' => $company,
            'main' => $main_order,

        ]);
        $html=response($view)->getContent();


        $data = [
            'list'=>$html,
            'print' => $print,
            'has_print'=>$hasPrint,
            'can_print'=>$canPrint,
            'type'=>strtoupper($type),
            'works_tags'=>''
        ];


        return $this->returnJson(0,'', $data);







    }

    /**
     * @param ErpPrintLogRepository $printLogRepository
     * @throws \App\Exceptions\CommonException
     */
    public function printData(ErpPrintLogRepository $printLogRepository)
    {

        $data = \request()->all();

        if(empty($data['key'])) {
            $this->returnJson(1,'请输入正确的订单号！');
        }

        $erpOrderNo = $data['key'];
        //获取订单详情信息
        $orderItems = $this->repositories->orderItemInfo($erpOrderNo);

        if (empty($orderItems)) {
            $this->returnJson(1,'订单号不存在！');
        }
        //获取订单信息
        $orders = $this->repositories->orderInfo($orderItems['sys_order_no']);

        //获取订单快递方式
        $compStr = $orders['assign_express_type'];


        //获取快递方式
        $companyArr = $this->repositories->transLogistics($this->company,$orders['assign_express_type'],strtolower($data['ctype']));
        $company = $companyArr['label'];

        if ($company == "" || (strtoupper($company) != $data['ctype'])){
            $this->returnJson(1,'快递方式不匹配！无法打单');
        }




        //生成交易单号
        if($data['is_old'] != '0') {
            $log = $printLogRepository->getById($data['is_old']);
            $tradeOrderNo =  $log['trade_order'] ? $log['trade_order'] : $orderItems['sys_order_no'];
        } else {
            $tradeOrderNo = $orderItems['sys_order_no'] . rand(10, 99);

        }
        //$tradeOrderNo = '200212213728992173';
        $orders['trader_no'] = $tradeOrderNo;

        //组合面单数据
        $deliveryType = $data['ctype'];



        $sender = $this->sender;

        //获取发件人和电话
        //获取发件人和电话
        if ($orders['sender_phone']!=""){
            $sender['100']['mobile'] = $orders['sender_phone'];
        }
        if ($orders['sender_person']!=""){
            $sender['100']['name'] = $orders['sender_person'];
        }
        
        $sheetData = $this->combSheetData($deliveryType , $sender, $orders);

        //打单添加自定义内容
        $extraData = "";
        $orderItemsAll = $this->repositories->orderItemAll($orderItems['sys_order_no']);
        if (!empty($orderItemsAll))
        {
            foreach ($orderItemsAll as $k => $v){
                $extraData .= $v['erp_order_no']."\n";
            }
            $extraData = ltrim($extraData);
        }



        //当快递为顺丰时，不走菜鸟打单接口
        if ($orders['assign_express_type'] == "sf"){
            //组织数据
            $pData = $orders->toArray();
            $pData['sender_province'] = $sender['100']['province'];
            $pData['sender_city'] = $sender['100']['city'];
            $pData['sender_area'] = $sender['100']['district'];
            $pData['sender_address'] = $sender['100']['detail'];
            $pData['sender_phone'] = $sender['100']['mobile'];
            $pData['sender_person'] = $sender['100']['name'];

            //添加交易单号，避免顺丰报重复下单的错误
            $pData['order_id'] = $tradeOrderNo;

            $pData['extra_data'] = $extraData;

            $bill_printer = new BillPrinter($pData);


            //判断是否需要重新下单
            if($data['is_old'] != '0') {
                //旧单号打单
                $logData = $printLogRepository->getById($data['is_old']);
                //无须下单直接进行重新打单
                $bill_printer->post_data['mailNo'] = $logData['waybill_code'];

            } else {
                //新单号打单，需重新下单
               $bill_data = $bill_printer->place_order();

               if (isset($bill_data['origincode'])&&$bill_data['origincode'] == "022"){
                   //下单成功，获取运单号
                   $bill_printer->post_data['mailNo'] = $bill_data['mailno'];
               }else{
                   $this->returnJson(1,"顺丰面单打印出错!");
               }

            }
            //获取顺丰打印所需数据
            $bill_res = $bill_printer->re_data();
            $face_info = json_decode($bill_res['post_json_data'],true);



            //先记入日志
            $waybillCode = $face_info[0]['mailNo'];
            $taskId = $orders['id'].'_'.$data['ctype'].'_'.rand(0, 1000);

            $logInfo = $printLogRepository->getRow(['trade_order'=>$tradeOrderNo]);

            if(empty($logInfo)) {
                $printLogData = [
                    'taskid' => $taskId,
                    'sys_order_no' => $orders['sys_order_no'],
                    'waybill_code' => $waybillCode,
                    'company' => $data['ctype'],
                    'created_at' => time(),
                    'updated_at' => time(),
                    'print_times' => 0,
                    'trade_order' => $tradeOrderNo,
                ];
                $logModel = $printLogRepository->insert($printLogData);
                $logId = $logModel->id;
            } else {
                $logId = $logInfo['id'];
            }

            $taskId .= '_'.$logId;

            //顺丰快递返回数据组织
            $sf_data = [
                'taskID' => $taskId,
                'reqURL' => $bill_res['reqURL'],
                'post_json_data' => $bill_res['post_json_data']
            ];
            return $this->returnJson(3,'', $sf_data);

        }else{
            //请求菜鸟打单接口
            $postCnUrl = 'http://fxapi.meiin.com/tb/cainiao/print-data';
            $postData = [
                'mid' => 46, //$mid,
                'server_flag' => 'amy',
                'data' => json_encode($sheetData)
            ];
            $tbApi = new TbApi();
            $tbApiRes = $tbApi->request($postCnUrl,$postData);

            if($tbApiRes['success'] == 'false') {
                $this->returnJson(1,$tbApiRes['err_msg']['sub_msg']);
            }

            $resp = $tbApiRes['result'][0];
            $waybillCode = $resp['modules']['waybill_cloud_print_response'][0]['waybill_code'];

        }

        $taskId = $orders['id'].'_'.$data['ctype'].'_'.rand(0, 1000);

        $logInfo = $printLogRepository->getRow(['trade_order'=>$tradeOrderNo]);

        if(empty($logInfo)) {
            $printLogData = [
                'taskid' => $taskId,
                'sys_order_no' => $orders['sys_order_no'],
                'waybill_code' => $waybillCode,
                'company' => $data['ctype'],
                'created_at' => time(),
                'updated_at' => time(),
                'print_times' => 0,
                'trade_order' => $tradeOrderNo,
            ];
            $logModel = $printLogRepository->insert($printLogData);
            $logId = $logModel->id;
        } else {
            $logId = $logInfo['id'];
        }

        $taskId .= '_'.$logId;



        if ($orders['assign_express_type'] == "sf"){
            //顺丰快递返回数据组织
            $sf_data = [
                'taskID' => $taskId
            ];
            return $this->returnJson(3,'', $sf_data);
        }else{
            //发送到打印机的数据
            $printerData = $this->combPrinterData($taskId, $resp,$extraData);
            return $this->returnJson(0,'', $printerData);
        }

    }

    //发货逻辑
    public function delivery(ErpPrintLogRepository $printLogRepository)
    {

        $data = \request()->all();

        if (empty( $data['taskid'])) {
            $this->returnJson(1, '打印任务（taskID）不存在!');
        }

        $arrTask = explode('_', $data['taskid']);

        $sysOrderId = $arrTask[0];
        $taskId = $arrTask[3];

        //获取订单相关信息
        $ordersInfo = $this->repositories->getById($sysOrderId);
        //获取打印记录
        $printLogInfo = $printLogRepository->getById($taskId);

        if (empty($ordersInfo) || empty($printLogInfo)) {
            $this->returnJson(1, '参数错误，请联系管理员!');
        }
        //判断是否为第一次打印
        $isPrintLog = $printLogRepository->getList(['sys_order_no'=>$printLogInfo['sys_order_no']],'print_times','desc');
        if (count($isPrintLog)>=1&&$isPrintLog[0]['print_times']>=1)
        {
            $is_update_express = '1';
        }else{
            $is_update_express = '0';
        }

        //打印次数加1
        $printLogInfo->increment('print_times', 1);

        $printLogInfo['is_update_express'] = $is_update_express;
        //请求发货接口进行发货
        $printLogInfo['company_zn'] = $this->company[strtolower($printLogInfo['company'])];
        $printLogInfo['order_no'] = $ordersInfo['cus_order_no'];  //客户传来的订单号
        $deliveryInfo = $this->repositories->deliveryByTradeApi($printLogInfo);
        if(empty($deliveryInfo)) {
            $this->returnJson(1, '发货失败!');
        }

        $redis = app('redis.connection');
        //回写爱美印接口
        $amy_order_no = $redis->get('partner_number')??"";
        $company_name = $printLogInfo['company_zn']??"";
        $logistics_code = $printLogInfo['waybill_code']??"";
        //组织url
        $url = 'http://backend.meiin.com/index.php?controller=simple&action=deliveryfromfactory&order_no='.$amy_order_no.'&company_name='.$company_name.'&logistics_code='.$logistics_code;
        //回写接口
        file_get_contents($url);
        $this->returnJson(0,'发货回调成功');
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

    /**
     * @param string $deliveryType  快递类型 如YTO
     * @param array $sender          发件人信息
     * @param array $traderOrderInfo  订单相关数据信息
     * @return array $sheetData       返回请求面单的数据
     */
    private function combSheetData($deliveryType , $sender, $traderOrderInfo)
    {
        $sheetData = [
            'cp_code'                           => $deliveryType,
            'sender' => [
                'address' => [
                    'province'                  => $sender['100']['province'],
                    'city'                      => $sender['100']['city'],
                    'district'                  => $sender['100']['district'],
                    'detail'                    => $sender['100']['detail'],
                    'town'                      => '',
                ],
                'mobile'                        =>  $sender['100']['mobile'],
                'name'                          => $sender['100']['name'],
            ],

            'trade_order_info_dtos' => [   //请求面单信息
                'logistics_services'        => '',  //如不需要特殊服务，该值为空
                'object_id'                 => '1',

                'order_info'        => [    //订单信息
                    'order_channels_type' => "OTHERS",
                    'trade_order_list'    => $traderOrderInfo['trader_no']
                ],

                'package_info'      => [   //包裹信息
                    'id'                    => '1',
                    'item'          =>[
                        'count'                     => '1',
                        'name'                      => "订单编号：".$traderOrderInfo['sys_order_no'],
                    ],
                    'volume'                    => '1',
                    'weight'                    => '1',
                ],
                'recipient'              => [  //收件人信息
                    'address'                       => [
                        'province'                  =>  $traderOrderInfo['province'],
                        'city'                      =>  $traderOrderInfo['city'],
                        'district'                  =>  $traderOrderInfo['area'],
                        'detail'                    =>  $traderOrderInfo['address'],
                        'town'                      => ''
                    ],
                    'mobile'                        => $traderOrderInfo['mobile'],
                    'name'                          => $traderOrderInfo['consignee'],
                    'phone'                         => $traderOrderInfo['mobile'],
                ],
                'template_url'                      =>$this->sheetTemplates[$deliveryType],
                'user_id'                           => '3230326467'
            ],

            'store_code'                            => '',
            'resource_code'                         => '',
            'dms_sorting'                           => 'false'
        ];

        return $sheetData;
    }

    /**
     * @param string $taskId 任务id
     * @param array $resp   菜鸟接口返回数据
     * @param  string $extraData 额外信息
     * @return array
     */
    private function combPrinterData($taskId, $resp, $extraData = '')
    {
        return [
            'cmd' => 'print',
            'requetID' => $resp['request_id'],
            'version' => '1.0',
            'task' =>
                [
                    'taskID' => "$taskId",
                    'preview' => false,
                    'documents' =>
                        [
                            0 =>
                                [
                                    'documentID' => $resp['modules']['waybill_cloud_print_response'][0]['waybill_code'],
                                    'contents' =>
                                        [
                                            0 => json_decode($resp['modules']['waybill_cloud_print_response'][0]['print_data'],true),
                                            1 => [
                                                'templateURL' => $this->customerTemplates,
                                                'data' => ['item_name'=>$extraData],
                                            ],
                                        ],
                                ],
                        ],
                ]
        ];
    }

}
