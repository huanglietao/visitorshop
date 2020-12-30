<?php
/**
 * Created by sass.
 * Author: cjx
 * Date: 2020/03/07
 * Time: 9:48
 */
namespace App\Http\Controllers\Erp\Printscom;

use App\Http\Controllers\Erp\BaseController;
use App\Repositories\AreasRepository;
use App\Repositories\ErpPrintLogRepository;
use App\Repositories\ErpPrintsDeliverOrderRepository;
use App\Services\Common\CallWayBillPrinter\BillPrinter;
use App\Services\Outer\Erp\Api;
use App\Services\Outer\Erp\PrintsDeliver;
use App\Services\Outer\TbApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;


class PrintsDeliverController extends BaseController
{
    protected $viewPath = 'erp.print';  //当前控制器所的view所在的目录
    protected $modules = 'sys';//当前控制器所属模块
    protected $printsFactory;
    protected $sheetTemplates;  //电子面单模板
    protected $company;

    public function __construct()
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
                'mobile'     => '13610500434',
                'name'       => '李先生'
            ]
        ];

        $this->customerTemplates = 'http://cloudprint.cainiao.com/print/resource/getResource.json?resourceId=1502530&status=0';
        parent::__construct();
        $this->printsFactory = app(PrintsDeliver::class);
    }


    public function index()
    {
        //获取产品名称
        $product_name = app(Request::class)->get('product_name')??"20MASK";

        //获取读取条数
        $limit_num = app(Request::class)->get('limit_num')??"20";

        //快递打单方式
        $delivery_type = app(Request::class)->get('delivery_type');

        if($delivery_type == 'sf'){
            $delivery_type = 'sf';
        }elseif ($delivery_type == 'yto'){
            $delivery_type = 'yto';
        }else{
            $delivery_type = 'cn';
        }

        //验证条数
        if (intval($limit_num)==0)
        {
            $limit_num = "20";
        }
        $result_20MASK =  [];
        $result_50MASK =  [];
        $result_MEAL = [];

        //获取数据
       if ($product_name=='all' || $product_name == 'sf' || $product_name == 'since' || $product_name == 'sfd'){
           $result_20MASK =  $this->printsFactory->requestApi('20MASK',$limit_num);
           $result_50MASK =  $this->printsFactory->requestApi('50MASK',$limit_num);
           $result_meal_A = $this->printsFactory->requestApi('得力套餐A',$limit_num);
           $result_meal_B = $this->printsFactory->requestApi('得力套餐B',$limit_num);
           $result_meal_C = $this->printsFactory->requestApi('得力套餐C',$limit_num);
           $result_meal_D = $this->printsFactory->requestApi('洗手液套餐A',$limit_num);
           $result_meal_E = $this->printsFactory->requestApi('洗手液套餐B',$limit_num);
           $result_MEAL = array_merge($result_meal_A,$result_meal_B,$result_meal_C,$result_meal_D,$result_meal_E);
       }elseif($product_name == "20MASK"){
           $result_20MASK =  $this->printsFactory->requestApi('20MASK',$limit_num);
       }elseif($product_name == "50MASK") {
           $result_50MASK =  $this->printsFactory->requestApi('50MASK',$limit_num);
       }elseif ($product_name == "MEAL"){
           $result_meal_A = $this->printsFactory->requestApi('得力套餐A',$limit_num);
           $result_meal_B = $this->printsFactory->requestApi('得力套餐B',$limit_num);
           $result_meal_C = $this->printsFactory->requestApi('得力套餐C',$limit_num);
           $result_meal_D = $this->printsFactory->requestApi('洗手液套餐A',$limit_num);
           $result_meal_E = $this->printsFactory->requestApi('洗手液套餐B',$limit_num);
           $result_MEAL = array_merge($result_meal_A,$result_meal_B,$result_meal_C,$result_meal_D,$result_meal_E);
       }

//        $result_20MASK = [
//       [
//           'trade_order_name'   =>      'MYCH202003100020',
//           'assign_express_type'   =>      'sfd',
//           'recipient_person'   =>      '张三',
//           'recipient_phone'   =>      '15912345678',
//           'recipient_address'   =>      '广东省广州市天河区 1101号',
//           'sender_person'   =>      '王园',
//           'sender_phone'   =>      '18802083636',
//           'sender_address'   =>      '广东省广州市增城区荔城街城大鹏路3号（博文幼儿园）',
//           'partner_order_date'   =>      '2020-03-05 10:00:00',
//           'is_hurry'              => '否',
//           'product_name'         => "20MASK"
//
//
//       ],
//       [
//           'trade_order_name'   =>      '200306171204119667',
//           'assign_express_type'   =>      'sfj',
//           'recipient_person'   =>      '张三',
//           'recipient_phone'   =>      '15912345678',
//           'recipient_address'   =>      '广东省广州市天河区 1101号',
//           'sender_person'   =>      '王园',
//           'sender_phone'   =>      '18802083636',
//           'sender_address'   =>      '广东省广州市增城区荔城街城大鹏路3号（博文幼儿园）',
//           'partner_order_date'   =>      '2020-03-05 10:00:00',
//           'is_hurry'              => '否',
//           'product_name'         => "20MASK"
//
//       ]
//   ];
//
//       $result_50MASK = [
//           [
//               'trade_order_name'   =>      '300306171204119667',
//               'assign_express_type'   =>      'sf',
//               'recipient_person'   =>      '张三',
//               'recipient_phone'   =>      '15912345678',
//               'recipient_address'   =>      '广东省广州市天河区 1101号',
//               'sender_person'   =>      '王园',
//               'sender_phone'   =>      '18802083636',
//               'sender_address'   =>      '广东省广州市增城区荔城街城大鹏路3号（博文幼儿园）',
//               'partner_order_date'   =>      '2020-03-05 10:00:00',
//               'is_hurry'              => '否',
//               'product_name'         => "50MASK"
//
//           ],
//       ];

        $result = array_unique(array_merge($result_20MASK,$result_50MASK,$result_MEAL), SORT_REGULAR);

        if(!empty($result)){
            //取到记录存入erp_prints_deliver_order表
            $this->printsFactory->saveData($result);
        }
		
		if($product_name == "20MASK" || $product_name == "50MASK" || $product_name == "MEAL")
		{
		    if($delivery_type != 'sf'){
                $result = array_filter($result, function($val){
                    if ($val['assign_express_type'] != "sf" && $val['assign_express_type'] != "since" && $val['assign_express_type'] != "sfd" && $val['assign_express_type'] != "sfj") {
                        return true;
                    } else {
                        return false;
                    }
                });
            }
		}

		if($delivery_type == 'cn'){
            //url地址不带delivery_tyep情况
            if($product_name == 'sf'){
                //顺丰单归类
                $res_sf = [];
                foreach ($result as $k=>$v){
                    if($v['assign_express_type'] == 'sf' || $v['assign_express_type'] == 'sfj'){
                        $res_sf[] = $v;
                    }
                }
                $result = $res_sf;
            }elseif ($product_name == 'since'){
                //自提单归类
                $res_since = [];
                foreach ($result as $k=>$v){
                    if($v['assign_express_type'] == 'since' || $v['assign_express_type'] == 'sfd'){
                        $res_since[] = $v;
                    }
                }
                $result = $res_since;
            }
        }else{
            //带delivery_type根据快递类型归类
            if($delivery_type == 'sf' && $product_name == 'all') {
                //顺丰订单(全部)归类
                $res_sf = [];
                foreach ($result as $k => $v) {
                    if ($v['assign_express_type'] == 'sf' || $v['assign_express_type'] == 'sfj'|| $v['assign_express_type'] == 'sfd') {
                        $res_sf[] = $v;
                    }
                }
                $result = $res_sf;
            }else if($delivery_type == 'sf' && $product_name == 'sfd') {
                //顺丰到(自提)订单归类
                $res_sf = [];
                foreach ($result as $k => $v) {
                    if ($v['assign_express_type'] == 'sfd') {
                        $res_sf[] = $v;
                    }
                }
                $result = $res_sf;
            }else if($delivery_type == 'sf'){
                //顺丰(除全部、自提外)订单归类
                $res_sf = [];
                foreach ($result as $k => $v) {
                    if ($v['assign_express_type'] == 'sf' || $v['assign_express_type'] == 'sfj') {
                        $res_sf[] = $v;
                    }
                }
                $result = $res_sf;
            }else{
                //圆通订单归类(url带delivery_type=yto)
                $res_since = [];
                foreach ($result as $k=>$v){
                    if($v['assign_express_type'] == 'yto' && $product_name != 'sfd'){
                        $res_since[] = $v;
                    }
                }
                $result = $res_since;
            }
        }
        //匹配打印错误信息
        foreach ($result as $k=>$v){
            $result[$k]['error_msg'] = Redis::get("error".$v['trade_order_name']);
        }

        return view('erp.printscom.print_deliver',['data'=>$result,'product_name'=>$product_name,'delivery_type'=>$delivery_type]);
    }

    public function printData(ErpPrintLogRepository $printLogRepository,AreasRepository $areasRepository)
    {
        try{

            $order_no = \request()->post("order_no");
            $ctype = \request()->post("ctype");
            $delivery_type = \request()->post("delivery_type");

            if(empty($order_no)) {
                $this->returnJson(1,'请输入正确的订单号');
            }

            //获取订单详情信息
            $orderInfo = $this->printsFactory->getOrderInfo($order_no);

            if (empty($orderInfo)) {
                $this->returnJson(1,'订单号不存在');
            }

            //组合面单数据
            $tradeOrderNo = $orderInfo['trade_order_name'] . rand(10, 99);

            //收货人省市区
            $send_pca = $areasRepository->parseDetailAddress($orderInfo['recipient_address']);

            if (empty($send_pca)) {
                Redis::setex( 'error'.$order_no , 259200 , '收货地址解析失败');
                $this->returnJson(1,"收货地址解析失败!");
            }

            $this->sender[100]['name'] = empty($orderInfo['sender_person']) ? $this->sender[100]['name'] : $orderInfo['sender_person'];
            $this->sender[100]['mobile'] = empty($orderInfo['sender_phone']) ? $this->sender[100]['mobile']:$orderInfo['sender_phone'];


            if($delivery_type == 'sf'){
                //顺丰打单数据处理
                $data = [
                    'province'          => $send_pca['p'],
                    'city'              => $send_pca['c'],
                    'area'              => $send_pca['a'],
                    'address'           => $orderInfo['recipient_address'],
                    'mobile'            => $orderInfo['recipient_phone'],
                    'consignee'          => $orderInfo['recipient_person'],
                    'sender_province'   => $this->sender['100']['province'],
                    'sender_city'       => $this->sender['100']['city'],
                    'sender_area'       => $this->sender['100']['district'],
                    'sender_address'    => $this->sender['100']['detail'],
                    'sender_person'     => $this->sender['100']['name'],
                    'sender_phone'      => $this->sender['100']['mobile'],
                    'extra_data'        => $orderInfo['product_name']."\n".$orderInfo['trade_order_name']
                ];

                //添加交易单号，避免顺丰报重复下单的错误
                $data['order_id'] = $tradeOrderNo;

                $sf = new BillPrinter($data);

                //新单号打单，需重新下单
                $bill_data = $sf->place_order();

                if (isset($bill_data['origincode'])&&$bill_data['origincode'] == "022"){
                    //下单成功，获取运单号
                    $sf->post_data['mailNo'] = $bill_data['mailno'];
                }else{
                    Redis::setex( 'error'.$orderInfo['trade_order_name'] , 259200 , '顺丰面单下单出错');
                    $this->returnJson(1,"顺丰面单下单出错!");
                }

                //获取顺丰打印所需数据
                $res = $sf->re_data();
                $face_info = json_decode($res['post_json_data'],true);

                //组装打印日志数据
                $taskId = $orderInfo['id'].'_SF_'.rand(0, 1000);
                $waybillCode = $face_info[0]['mailNo'];

                $printLogData = [
                    'taskid' => $taskId,
                    'sys_order_no' => $orderInfo['trade_order_name'],
                    'waybill_code' => $waybillCode,
                    'company' => strtoupper($orderInfo['assign_express_type']),
                    'created_at' => time(),
                    'updated_at' => time(),
                    'print_times' => 0,
                    'trade_order' => $tradeOrderNo,
                ];

                $logModel = $printLogRepository->insert($printLogData);
                $logId = $logModel->id;
                $taskId .= '_'.$logId;

                $return_data = [
                    'taskID' => $taskId,
                    'reqURL' => $res['reqURL'],
                    'post_json_data' => $res['post_json_data']
                ];
                $this->returnJson(0,'',$return_data);

            }else{
                //菜鸟打单
                $sheetData = $this->combSheetData($orderInfo, $tradeOrderNo, $ctype, $this->sender, $send_pca);

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
                    Redis::setex( 'error'.$order_no , 259200 , $tbApiRes['err_msg']['sub_msg']);
                    $this->returnJson(1,$tbApiRes['err_msg']['sub_msg']);
                }

                $resp = $tbApiRes['result'][0];

                $taskId = $orderInfo['id'].'_'.$ctype.'_'.rand(0, 1000);
                $waybillCode = $resp['modules']['waybill_cloud_print_response'][0]['waybill_code'];
                $logInfo = $printLogRepository->getRow(['trade_order'=>$tradeOrderNo]);

                if(empty($logInfo)) {
                    $printLogData = [
                        'taskid' => $taskId,
                        'sys_order_no' => $orderInfo['trade_order_name'],
                        'waybill_code' => $waybillCode,
                        'company' => $ctype,
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

                //发送到打印机的数据
                $printerData = $this->combPrinterData($taskId, $resp, $order_no."\n".$orderInfo['product_name']);
                return $this->returnJson(0,'', $printerData);

            }

        }catch (\Exception $e){

            $this->returnJson(1,$e->getMessage());
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

    private function combSheetData($orderInfo, $tradeOrderNo, $ctype, $sender, $send_pca)
    {
        $sheetData = [
            'cp_code'                           => $ctype,
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
                    'trade_order_list'    => $tradeOrderNo
                ],

                'package_info'      => [   //包裹信息
                    'id'                    => '1',
                    'item'          =>[
                        'count'                     => '1',
                        'name'                      => "订单编号：".$orderInfo['trade_order_name'],
                    ],
                    'volume'                    => '1',
                    'weight'                    => '1',
                ],
                'recipient'              => [  //收件人信息
                    'address'                       => [
                        'province'                  =>  $send_pca['p'],
                        'city'                      =>  $send_pca['c'],
                        'district'                  =>  $send_pca['a'],
                        'detail'                    =>  $orderInfo['recipient_address'],
                        'town'                      => ''
                    ],
                    'mobile'                        => $orderInfo['recipient_phone'],
                    'name'                          => $orderInfo['recipient_person'],
                    'phone'                         => $orderInfo['recipient_phone'],
                ],
                'template_url'                      =>$this->sheetTemplates[$ctype],
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

    //发货逻辑
    public function delivery(ErpPrintsDeliverOrderRepository $erpPrintsDeliverOrderRepository, ErpPrintLogRepository $printLogRepository)
    {
        $data = \request()->all();

        if (empty( $data['taskid'])) {

            $this->returnJson(1, '打印任务（taskID）不存在!');
        }

        $arrTask = explode('_', $data['taskid']);

        $sysOrderId = $arrTask[0];
        $taskId = $arrTask[3];

        //获取订单相关信息
        $ordersInfo = $erpPrintsDeliverOrderRepository->getById($sysOrderId);

        //获取打印记录
        $printLogInfo = $printLogRepository->getById($taskId);


        if (empty($ordersInfo) || empty($printLogInfo)) {
            Redis::setex( 'error'.$printLogInfo['sys_order_no'] , 259200 , '打印参数错误，请联系管理员!');
            $this->returnJson(1, '打印参数错误，请联系管理员!');
        }
        //打印次数加1
        $printLogInfo->increment('print_times', 1);

        //请求发货接口进行发货
        $postData['express_type'] = strtolower($printLogInfo['company']);
        $postData['express_num'] = $printLogInfo['waybill_code'];
        $postData['trade_order_name'] = $printLogInfo['sys_order_no'];  //客户传来的订单号
        $deliveryInfo = $erpPrintsDeliverOrderRepository->deliveryByApi($postData);

        if(empty($deliveryInfo)) {
            Redis::setex( 'error'.$printLogInfo['sys_order_no'] , 259200 , '发货失败');
            $this->returnJson(1, '发货失败!');
        }

        if($postData['express_type'] == 'sfd' || $postData['express_type'] == 'sfj'){
            $postData['express_type'] = 'sf';
        }
        
        //加入广州电商发货回写流程
        $url = "http://backend.meiin.com/index.php?controller=simple&action=deliveryfromfactory&order_no=".$ordersInfo['partner_number']."&company_name=". $this->company[$postData['express_type']]."&logistics_code=". $postData['express_num'];
        file_get_contents($url);
        $this->returnJson(0,'发货回调成功');
    }

    //顺丰和自提发货
    public function otherDelivery(Request $request)
    {
        $data = $request->all();

        $requestApi = new Api();

        $requestUrl = config("erp.interface_url").config("erp.write_back_delivery");

        $res = $requestApi->request($requestUrl,$data);
        if(empty($res)) {
            echo json_encode(['success' => 'false','msg' =>"请求接口错误！请联系管理员!" ]);
            exit;
        }

        if($res['code'] ==  1) {
            echo json_encode(['success' => 'true','msg' =>'发货成功']);
        } else {
            echo json_encode(['success' => 'false','msg' =>$res['message']]);
        }
        exit;
    }

    //打单tips
    public function tips(Request $request)
    {
        $post = $request->post();
        $total = $post['total'];
        $success = $post['success'];
        $fail = $post['fail'];
        $surplus = $post['surplus'];

        $layre_flag = 0;

        if($success == 0 && $fail == 0){
            //展示打印中弹窗
            $layre_flag = 1;
        }

        $data = [
            "total"     =>  $total,
            "success"   =>  $success,
            "fail"      =>  $fail,
            "surplus"   =>  $surplus
        ];

        if($layre_flag == 1){
            $content = $this->renderHtml("erp.printscom.tips",['data' =>$data]);
        }else if($surplus == 0 && ($success != 0 || $fail !=0)){
            $layre_flag = 1;//展示打印完成弹窗
            $content = $this->renderHtml("erp.printscom.tips",['data' =>$data]);
        }else{
            $content = $data;
        }
        return response()->json(['status' => 200, 'html' => $content, 'layre_flag' => $layre_flag]);
    }

}
