<?php
namespace App\Http\Controllers\Backend;


use App\Services\Outer\TbApi;
use Illuminate\Support\Facades\Redis;
use App\Models\SaasBatchPrint;
use Illuminate\Http\Request;
use App\Exceptions\CommonException;
use App\Repositories\SaasBatchPrintRepository;
use PhpOffice\PhpSpreadsheet\IOFactory;
/**
 * 临时电子面单批打
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/5/26
 */

class Printer extends BaseController
{
    protected $viewPath = 'backend.printer';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $sysId = 'backend';        //当前控制器所属模块
    protected $noNeedRight = ['*'];

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
                'mobile'     => '18998373952',
                'name'       => '小美'
            ]
        ];

        $this->customerTemplates = 'http://cloudprint.cainiao.com/print/resource/getResource.json?resourceId=1502530&status=0';
        parent::__construct();
    }
    public function index()
    {

        //获取产品名称
        $product_name = app(Request::class)->get('product_name') ?? "six_inches";

        //查询条数
        $limit = app(Request::class)->get('limit_num') ?? 20;

        //获取客户单号
        $order_no = app(Request::class)->get('order_no');

        if($product_name == 'six_inches'){
            $where['print_status'] = 0;
        }elseif ($product_name == 'printed'){
            $where['print_status'] = 1;
        }else{
            $where['print_status'] = 2;
        }

        if(!empty($order_no)){
            $where['order_no'] = $order_no;
        }

        $result = SaasBatchPrint::where($where)->limit($limit)->orderBy('order_time','desc')->get()->toArray();

        foreach ($result as $k=>$v){
            $result[$k]['receiver_info'] = $this->parseDetailAddress($v['order_no']);
            $result[$k]['error_msg'] = Redis::get("error".$v['order_no']);
        }

        return view('backend.printer.print_deliver',['data'=>$result,'product_name'=>$product_name]);

    }

    //打单
    public function printData()
    {
        try{
            $order_no = \request()->post("order_no");
            if(empty($order_no)) {
                $this->returnJson(1,'请输入正确的订单号');
            }
            //获取订单详情信息
            \DB::beginTransaction();
            $res = \DB::connection('ishop_mysql')->table('is_order')->where('status', '<>', 6)->where(['order_no' => $order_no])->get()->toArray();
            if (empty($res)){
                Redis::setex( 'error'.$order_no , 259200 , '订单号不存在或已经取消!');
                $this->returnJson(1,'订单号不存在');
            }
            $orderInfo = json_decode(json_encode($res[0]),true);
            //组合面单数据
            $tradeOrderNo = $orderInfo['order_no'] . rand(10, 99);
            //收货人省市区
            $send_pca = $this->parseDetailAddress($orderInfo['order_no']);

            if (empty($send_pca)) {
                Redis::setex( 'error'.$order_no , 259200 , '收货地址解析失败');
                $this->returnJson(1,"收货地址解析失败!");
            }

            //写死圆通
            $ctype = 'YTO';

            //菜鸟打单
            $sheetData = $this->combSheetData($orderInfo, $tradeOrderNo, $ctype, $this->sender, $send_pca);

            //请求菜鸟打单接口
            $postCnUrl = 'http://fxapi.meiin.com/tb/cainiao/print-data';
            $postData = [
                'mid' => 46, //$mid,
                'server_flag' => 'amy',
                'data' => json_encode($sheetData)
            ];
            $tbApi = app(TbApi::class);
            $tbApiRes = $tbApi->request($postCnUrl,$postData);

            if($tbApiRes['success'] == 'false') {
                Redis::setex( 'error'.$order_no , 259200 , $tbApiRes['err_msg']['sub_msg']);
                $this->returnJson(1,$tbApiRes['err_msg']['sub_msg']);
            }

            $resp = $tbApiRes['result'][0];

            $taskId = $orderInfo['id'].'_'.$ctype.'_'.rand(0, 1000);
            $waybillCode = $resp['modules']['waybill_cloud_print_response'][0]['waybill_code'];


            $logInfo = \DB::connection('ishop_mysql')->table('is_print_log')->where(['trade_order' => $tradeOrderNo])->get()->toArray();

            if(empty($logInfo)) {
                $printLogData = [
                    'taskid' => $taskId,
                    'order_id' => $orderInfo['id'],
                    'order_no' => $orderInfo['order_no'],
                    'waybill_code' => $waybillCode,
                    'company' => $ctype,
                    'created_at' => date("Y/m/d H:i:s"),
                    'updated_at' => date("Y/m/d H:i:s"),
                    'print_times' => 0,
                    'trade_order' => $tradeOrderNo,
                ];
                $logId = \DB::connection('ishop_mysql')->table('is_print_log')->insertGetId($printLogData);
            } else {
                $logId = $logInfo[0]->id;
            }
            $taskId .= '_'.$logId;

            //发送到打印机的数据
            $printerData = $this->combPrinterData($taskId, $resp, $order_no."\n");
            return $this->returnJson(0,'', $printerData);

            \DB::commit();

        }catch (\Exception $e){
            Redis::setex( 'error'.$order_no , 259200 , $e->getMessage());
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

    public function parseDetailAddress($order_no)
    {
        $res = \DB::connection('ishop_mysql')->table('is_order')->where(['order_no' => $order_no])->get()->toArray();
        if (empty($res)){
            $return = [
                'code' => 0,
                'msg'  => "该订单号不存在"
            ];
            return $return;
        }
        $orderInfo = json_decode(json_encode($res[0]),true);
        $return = [
            'p' => "",
            'c' => "",
            'a' =>"",
            'd' =>  $orderInfo['address'],
            'accept_name' => $orderInfo['accept_name'],
            'mobile' => $orderInfo['mobile'],
        ];
        //获取省
        $addressPArr = \DB::connection('ishop_mysql')->table('is_areas')->where(['area_id' => $orderInfo['province']])->get()->toArray();
        if (!empty($addressPArr))
        {
            $return['p'] = $addressPArr[0]->area_name;
        }
        //获取市
        $addressCArr = \DB::connection('ishop_mysql')->table('is_areas')->where(['area_id' => $orderInfo['city']])->get()->toArray();
        if (!empty($addressPArr))
        {
            $return['c'] = $addressCArr[0]->area_name;
        }
        //获取区
        $addressAArr = \DB::connection('ishop_mysql')->table('is_areas')->where(['area_id' => $orderInfo['area']])->get()->toArray();
        if (!empty($addressPArr))
        {
            $return['a'] = $addressAArr[0]->area_name;
        }
        if($return['p']=="" || $return['a']=="") {
            return false;
        }
        return $return;
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
                        'name'                      => "订单编号：".$orderInfo['order_no'],
                    ],
                    'volume'                    => '1',
                    'weight'                    => '1',
                ],
                'recipient'              => [  //收件人信息
                    'address'                       => [
                        'province'                  =>  $send_pca['p'],
                        'city'                      =>  $send_pca['c'],
                        'district'                  =>  $send_pca['a'],
                        'detail'                    =>  $send_pca['d'],
                        'town'                      => ''
                    ],
                    'mobile'                        => $send_pca['mobile'],
                    'name'                          => $send_pca['accept_name'],
                    'phone'                         => $send_pca['mobile'],
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
            $content = $this->renderHtml("backend.printer.tips",['data' =>$data]);
        }else if($surplus == 0 && ($success != 0 || $fail !=0)){
            $layre_flag = 1;//展示打印完成弹窗
            $content = $this->renderHtml("backend.printer.tips",['data' =>$data]);
        }else{
            $content = $data;
        }
        return response()->json(['status' => 200, 'html' => $content, 'layre_flag' => $layre_flag]);
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

        //获取订单相关信息
        $ordersInfo = \DB::connection('ishop_mysql')->table('is_order')->where(['id' => $sysOrderId])->get()->toArray();
        //获取打印记录
        $printLogInfo = \DB::connection('ishop_mysql')->table('is_print_log')->where(['id' => $taskId])->get()->toArray();


        if (empty($ordersInfo) || empty($printLogInfo)) {
            Redis::setex( 'error'.$printLogInfo['sys_order_no'] , 259200 , '打印参数错误，请联系管理员!');
            $this->returnJson(1, '打印参数错误，请联系管理员!');
        }
        //打印次数加1
        \DB::connection('ishop_mysql')->table('is_print_log')->where(['id' => $taskId])->increment('print_times');
        //更改为已打印
        app(SaasBatchPrint::class)->where(['order_no'=>$ordersInfo[0]->order_no])->update(['print_status'=>1]);
        $postData['express_type'] = strtolower($printLogInfo[0]->company);
        $postData['express_num'] = $printLogInfo[0]->waybill_code;

        //加入广州电商发货回写流程
        $url = "http://backend.meiin.com/index.php?controller=simple&action=deliveryfromfactory&order_no=".$ordersInfo[0]->order_no."&company_name=". $this->company[$postData['express_type']]."&logistics_code=". $postData['express_num'];
        file_get_contents($url);
        $this->returnJson(0,'发货回调成功');
    }

    //数据导入
    public function import()
    {
        try{
            \DB::beginTransaction();
            if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                $batchprintRepository = app(SaasBatchPrintRepository::class);
                $filename = $_FILES['file']['name'];
                $ext_list = explode(".",$filename);
                $ext = $ext_list[count($ext_list)-1];
                if($ext=='xls'){
                    $ext = 'Xls';
                }elseif($ext=='xlsx'){
                    $ext = 'Xlsx';
                }
                // 有Xls和Xlsx格式两种
                $objReader = IOFactory::createReader($ext);

                $fileTmpname = $_FILES['file']['tmp_name'];
                $objPHPExcel = $objReader->load($fileTmpname);  //$filename可以是上传的表格，或者是指定的表格
                $sheet = $objPHPExcel->getSheet(0);   //excel中的第一张sheet
                $highestRow = $sheet->getHighestRow();       // 取得总行数
                $total = 0;
                //循环读取excel表格，整合成数组。如果是不指定key的二维，就用$data[i][j]表示。
                for ($j = 3; $j <= $highestRow; $j++) {
                    //erp订单号
                    $erp_order_no = $objPHPExcel->getActiveSheet()->getCell("A" . $j)->getValue();
                    //订单流水号
                    $project_sn = $objPHPExcel->getActiveSheet()->getCell("C" . $j)->getValue();
                    //判断该条记录是否已经保存到数据库
                    $isExit = $batchprintRepository->isExit($project_sn);
                    //如果存在则不再操作
                    if($isExit){
                        continue;
                    }
                    //订单号
                    $order_no_list = explode("_",$project_sn);
                    $order_no = $order_no_list[0];
                    //商品分类
                    $goods_type = $objPHPExcel->getActiveSheet()->getCell("D" . $j)->getValue();
                    //产品名称
                    $goods_name = $objPHPExcel->getActiveSheet()->getCell("G" . $j)->getValue();
                    //印件内容
                    $print_contents = $objPHPExcel->getActiveSheet()->getCell("H" . $j)->getValue();
                    //订购人数
                    $buyer_num = $objPHPExcel->getActiveSheet()->getCell("I" . $j)->getValue();
                    //购买数量
                    $goods_num = $objPHPExcel->getActiveSheet()->getCell("J" . $j)->getValue();
                    //单双面
                    $ds = $objPHPExcel->getActiveSheet()->getCell("K" . $j)->getValue();
                    //价格
                    $price = $objPHPExcel->getActiveSheet()->getCell("L" . $j)->getValue();
                    //订单总价
                    $order_total_price = $objPHPExcel->getActiveSheet()->getCell("M" . $j)->getValue();
                    //订单状态
                    $order_status = $objPHPExcel->getActiveSheet()->getCell("N" . $j)->getValue();
                    //订单时间
                    $order_time =$objPHPExcel->getActiveSheet()->getCell("O" . $j)->getValue();

                    //格式化客户订单时间
                    if($order_time){
                        if (is_float($order_time))
                        {
                            $n = intval(($order_time - 25569) * 3600 * 24); //转换成1970年以来的秒数
                            $order_time = gmdate('Y-m-d H:i:s',$n);//格式化时间,不是用date哦, 时区相差8小时的
                        } else {
                            $order_time = date('Y-m-d H:i:s', strtotime($order_time));
                        }
                    }
                    $order_time = strtotime($order_time);

                    $data = [
                        'erp_order_no' => trim($erp_order_no),
                        'order_no' => trim($order_no),
                        'project_sn' => trim($project_sn),
                        'goods_type' => trim($goods_type),
                        'goods_name' => trim($goods_name),
                        'print_contents' => trim($print_contents),
                        'buyer_num' => trim($buyer_num),
                        'goods_num' => trim($goods_num),
                        'ds' => trim($ds),
                        'price' => trim($price),
                        'order_total_price' => trim($order_total_price),
                        'order_status' => trim($order_status),
                        'order_time' => trim($order_time),
                        'created_at'=>time()
                    ];

                    $ret = $batchprintRepository->save($data);
                    if(!$ret){
                        \DB::rollBack();
                        return $this->jsonFailed("数据保存出错，已保存0条数据!");
                    }
                    $total +=1;
                }
                \DB::commit();
                return $this->jsonSuccess("已成功导入".$total."条数据！已存在的订单不会再次保存");
            }
        }catch(CommonException $e){
            \DB::rollBack();
            return $this->jsonFailed($e->getMessage());
        }
    }

    //发货回调
    public function testDelivery()
    {
        //获取临时打印的订单
        $arr = app(SaasBatchPrint::class)->where('created_at','>=','1590595200')->where('print_status',1)->get()->toArray();
        $error = [];
        foreach ($arr as $k => $v){
            $res = \DB::connection('ishop_mysql')->table('is_print_log')->where(['order_no' => $v['order_no']])->get()->toArray();
            if (empty($res)){
                $error[] = $v['order_no'];
            }else{
                $logArr = $res[0];
                file_put_contents('/tmp/test_delivery_hlt.log','正在进行转换:'.$logArr->order_no,FILE_APPEND);
                $url = "http://backend.meiin.com/index.php?controller=simple&action=deliveryfromfactory&order_no=".$logArr->order_no."&company_name=". $this->company[strtolower($logArr->company)]."&logistics_code=".$logArr->waybill_code;
                file_get_contents($url);
            }
        }
        var_dump($error);
        die;
    }

    //清除异常订单项
    public function clearOrder()
    {
        $list = SaasBatchPrint::where('print_status',0)->get()->toArray();
        foreach ($list as $k=>$v){
            $error_msg = Redis::get("error".$v['order_no']);
            if(!empty($error_msg)){
                SaasBatchPrint::where('order_no',$v['order_no'])->update(['print_status'=>2]);
            }
        }
        $this->returnJson(0, '正在清除，请稍等');
    }
}