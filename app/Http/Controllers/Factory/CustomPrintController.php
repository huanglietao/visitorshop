<?php
namespace App\Http\Controllers\Factory;

use App\Exceptions\CommonException;
use App\Models\SaasAreas;
use App\Models\SaasCustomPrint;
use App\Models\SaasDiyAssistant;
use App\Models\SaasPrintLog;
use App\Repositories\AreasRepository;
use App\Repositories\SaasAreasRepository;
use App\Repositories\SaasOrdersRepository;
use App\Services\Exception;
use App\Services\Helper;
use App\Services\Prints;
use App\Services\Works\Sync;
use Illuminate\Http\Request;
use App\Repositories\SaasCustomPrintRepository;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpKernel\EventListener\ValidateRequestListener;

/**
 * 自定义打单列表
 *
 * @author: hlt
 * @version: 1.0
 * @date: 2020/08/12
 */

class CustomPrintController extends BaseController
{
    protected $viewPath = 'factory.customprint.importprint';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $mchID;

    public function __construct(SaasCustomPrintRepository $customPrintRepository)
    {
        parent::__construct();
        $this->repositories = $customPrintRepository;
        $this->company = [
            'yto' => '圆通快递',
            'sf' => '顺丰快递',
            'sto'=>'申通快递',
        ];
    }

    //列表展示页面
    public function index()
    {
        $type = \request()->route('type');
        $typeArr = config('print.custom_print');
        if (!in_array($type,$typeArr)){
            //默认为第一个类型
            $type = key($typeArr);
        }
            //获取公共头部tab视图
            return view("factory.customprint.importprint.index",['navList' => $typeArr,'default_key'=>$type,'express_list' => $this->company]);

    }

    //ajax方式获取列表
    public function table(Request $request)
    {
        $inputs = $request->all();
        if (isset($inputs['status'])){
            unset($inputs['status']);//不需要tab的状态的查询
        }
        $list = $this->repositories->getTableList($inputs)->toArray();
        $htmlContents = $this->renderHtml('',['list' => $list['data']]);
        $total = $list['total'];

        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
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
    //打单
    public function printData(Request $request)
    {
        $printLogModel = app(SaasPrintLog::class);
        $print_id = $request->post('print_id');
        $delivery_type = $request->post('delivery_type');
        $is_new = $request->post('is_new');

        $print_info = $this->repositories->getRow(['cus_pri_id'=>$print_id]);
        if(empty($print_info)){
            $this->returnJson(1,'需打印的快递单不存在');
        }

        if (!empty($delivery_type)){
            $delivery_type = strtoupper($delivery_type);
        }


        //判断该快递是否已被打过，打过则用旧单号打印
        if (!empty($print_info['log_id']) && empty($is_new))
        {

            $logInfo = $printLogModel->where('id',$print_info['log_id'])->first();
            if (!empty($logInfo) && $logInfo['company'] == $delivery_type){
                $tradeOrderNo = $logInfo['trade_order'];
            }else{
                //面单单号
                $tradeOrderNo = $this->generateTradeNo($print_info['pri_trade_no']);
            }
        }else{
            //面单单号
            $tradeOrderNo = $this->generateTradeNo($print_info['pri_trade_no']);
        }

        //面单备注
        $note = $print_info['pri_note'];

        //面单数据
        $print_data = [
            'id'            =>  0,
            'agent_id'      =>  0,
            'order_no'      =>  $print_info['pri_trade_no'],
            'province'      =>  $print_info['pri_rece_province'],
            'city'          =>  $print_info['pri_rece_city'],
            'area'          =>  $print_info['pri_rece_area'],
            'address'       =>  $print_info['pri_rece_address'],
            'mobile'        =>  $print_info['pri_rece_tel'],
            'consignee'     =>  $print_info['pri_rece_username'],
            'cus_pri_id'    =>  $print_info['cus_pri_id']
        ];

        $printService = app(Prints::class);

        //获取寄件人信息
        $sender = $printService->getDefaultMchSender();
        //修改寄件人信息
        if (!empty($print_info['pri_send_username']) && !empty($print_info['pri_send_tel'])){
            $sender[100]['name'] = $print_info['pri_send_username'];
            $sender[100]['mobile'] = $print_info['pri_send_tel'];
        }
        $new_sender = $sender[100];
        if($delivery_type == 'SF'){
            //顺风打单
            $result = $printService->sfPrinter($print_data,$tradeOrderNo,$delivery_type,0,SUPPLIER_DEFAULT_ID,$note,$new_sender);
        }else{
            //菜鸟打单
            $result = $printService->caiNiaoPrinter($print_data,$tradeOrderNo,$delivery_type,0,SUPPLIER_DEFAULT_ID,$note,$new_sender);
        }

        if($result['code'] == 1){
            //成功返回
            $this->returnJson(0,$result['data']);
        }else{
            //失败返回
            $this->returnJson(1,$result['msg']);
        }
    }

    //回写物流跟物流方式到打单表
    public function writeBack()
    {
        $printLogModel = app(SaasPrintLog::class);
        try{
            \DB::beginTransaction();

            $data = \request()->all();

            if (empty( $data['taskid'])) {
                $this->returnJson(1, '打印任务（taskID）不存在!');
            }

            $arrTask = explode('_', $data['taskid']);


            $taskId = $arrTask[3];

            //获取打印记录
            $printLogInfo = $printLogModel->where('id',$taskId)->first();
            if (empty($printLogInfo)) {
                $this->returnJson(1, '打单回写失败，请重新尝试打印');
            }
            //获取批打订单相关信息
            $printInfo = $this->repositories->getRow(['cus_pri_id'=>$printLogInfo['cus_pri_id']]);
            if (empty($printInfo)) {
                $this->returnJson(1, '打单回写失败，请重新尝试打印');
            }
            $printId = $printInfo['cus_pri_id'];
            //打印次数加1
            $printLogModel->where(['id'=>$taskId])->increment('print_times', 1);
            //获取快递名称
            if(isset($this->company[strtolower($printLogInfo['company'])]))
            {
                $express_str = $this->company[strtolower($printLogInfo['company'])];
            }else{
                $express_str = "未知快递";
            }


            //更新批量打单表记录为已打印
            $this->repositories->update(['cus_pri_id'=>$printId],['is_print'=>PUBLIC_YES,'log_id'=>$taskId,'express'=>$printLogInfo['company'],'express_str' => $express_str,'waybill_code'=>$printLogInfo['waybill_code']]);

            \DB::commit();
            $this->returnJson(0,'打单回写成功');

        }catch (\Exception $e){
            \DB::rollBack();
            $this->returnJson(1,'打单回写失败');
        }
    }

    //数据导入
    public function import()
    {
        try{
            \DB::beginTransaction();
            if (is_uploaded_file($_FILES['file']['tmp_name'])) {
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

                $sheetCount = $objPHPExcel->getSheetCount();
                $snum = 0;
                $total = 0;

                $customPrintRepository = app(SaasCustomPrintRepository::class);
                $areasRepository = app(AreasRepository::class);
                for ($i=0;$i<$sheetCount;$i++){
                    $sheet = $objPHPExcel->getSheet($i);   //excel中的第一张sheet
                    $highestRow = $sheet->getHighestRow();       // 取得总行数
                    $highestCol = $sheet->getHighestColumn(); //获取列数判断为精简格式还是完整格式
                    $format = 'simple';//默认为精简模式
                    if ($highestCol == 'AB'){
                        $format = 'complete'; //完整模式
                    }
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $personals = [];
                        //业务单号
                        $priTradeNo = $sheet->getCell('A'.$row)->getCalculatedValue();
                        if ($format == 'simple')
                        {
                            $receName     = $sheet->getCell('B'.$row)->getCalculatedValue(); //收件人姓名
                            $receTel      = $sheet->getCell('C'.$row)->getCalculatedValue(); //收件人手机
                            $receProv     = $sheet->getCell('D'.$row)->getCalculatedValue(); //收件人省
                            $receCity     = $sheet->getCell('E'.$row)->getCalculatedValue(); //收件人市
                            $receArea     = $sheet->getCell('F'.$row)->getCalculatedValue(); //收件人区/县
                            $receAddress  = $sheet->getCell('G'.$row)->getCalculatedValue(); //收件人地址
                            $prodName     = $sheet->getCell('H'.$row)->getCalculatedValue(); //商品名称
                            $num          = $sheet->getCell('I'.$row)->getCalculatedValue(); //数量
                            $note         = $sheet->getCell('J'.$row)->getCalculatedValue(); //备注
                            $weight       = $sheet->getCell('J'.$row)->getCalculatedValue(); //重量
                            $volume       = $sheet->getCell('K'.$row)->getCalculatedValue(); //体积
                        }else{
                            //完整模式
                            $sendCompany  = $sheet->getCell('B'.$row)->getCalculatedValue();  //寄件单位
                            $sendUsername = $sheet->getCell('C'.$row)->getCalculatedValue(); //寄件人姓名
                            $sendMobile   = $sheet->getCell('D'.$row)->getCalculatedValue(); //寄件人电话
                            $sendTel      = $sheet->getCell('E'.$row)->getCalculatedValue(); //寄件人手机
                            $sendProv     = $sheet->getCell('F'.$row)->getCalculatedValue(); //寄件人省
                            $sendCity     = $sheet->getCell('G'.$row)->getCalculatedValue(); //寄件人市
                            $sendArea     = $sheet->getCell('H'.$row)->getCalculatedValue(); //寄件人区/县
                            $sendAdress   = $sheet->getCell('I'.$row)->getCalculatedValue(); //寄件人地址
                            $sendZipCode  = $sheet->getCell('J'.$row)->getCalculatedValue(); //寄件人邮编
                            $receName     = $sheet->getCell('K'.$row)->getCalculatedValue(); //收件人姓名
                            $receMobile   = $sheet->getCell('L'.$row)->getCalculatedValue(); //收件人电话
                            $receTel      = $sheet->getCell('M'.$row)->getCalculatedValue(); //收件人手机
                            $receProv     = $sheet->getCell('N'.$row)->getCalculatedValue(); //收件人省
                            $receCity     = $sheet->getCell('O'.$row)->getCalculatedValue(); //收件人市
                            $receArea     = $sheet->getCell('P'.$row)->getCalculatedValue(); //收件人区/县
                            $receAddress  = $sheet->getCell('Q'.$row)->getCalculatedValue(); //收件人地址
                            $receZipCode  = $sheet->getCell('R'.$row)->getCalculatedValue(); //收件邮政编码
                            $deliveryFee  = $sheet->getCell('S'.$row)->getCalculatedValue(); //运费
                            $orderAmount  = $sheet->getCell('T'.$row)->getCalculatedValue(); //订单金额
                            $prodName     = $sheet->getCell('U'.$row)->getCalculatedValue(); //商品名称
                            $prodSn       = $sheet->getCell('V'.$row)->getCalculatedValue(); //商品编码
                            $saleAttr     = $sheet->getCell('W'.$row)->getCalculatedValue(); //销售属性
                            $prodAmount   = $sheet->getCell('X'.$row)->getCalculatedValue(); //商品金额
                            $num          = $sheet->getCell('Y'.$row)->getCalculatedValue(); //数量
                            $note         = $sheet->getCell('Z'.$row)->getCalculatedValue(); //备注
                            $weight       = $sheet->getCell('AA'.$row)->getCalculatedValue();//重量
                            $volume       = $sheet->getCell('AB'.$row)->getCalculatedValue();//体积
                        }
                        $importDate = [
                            'pri_trade_no'      => $priTradeNo,
                            'pri_send_company'  => $sendCompany??"",
                            'pri_send_username' => $sendUsername??"",
                            'pri_send_mobile'   => $sendMobile??"",
                            'pri_send_tel'      => $sendTel??"",
                            'pri_send_province' => $sendProv??"",
                            'pri_send_city'     => $sendCity??"",
                            'pri_send_area'     => $sendArea??"",
                            'pri_send_address'  => $sendAdress??"",
                            'pri_send_zip_code' => $sendZipCode??"",
                            'pri_rece_username' => $receName??"",
                            'pri_rece_mobile'   => $receMobile??"",
                            'pri_rece_tel'      => $receTel??"",
                            'pri_rece_province' => $receProv??"",
                            'pri_rece_city'     => $receCity??"",
                            'pri_rece_area'     => $receArea??"",
                            'pri_rece_address'  => $receAddress??"",
                            'pri_rece_zip_code' => $receZipCode??"",
                            'delivery_fee'      => $deliveryFee??0,
                            'order_amount'      => $orderAmount??0,
                            'prod_name'         => $prodName??"",
                            'prod_code'         => $prodSn??"",
                            'prod_attribute'    => $saleAttr??"",
                            'prod_amount'       => $prodAmount??0,
                            'prod_num'          => $num??0,
                            'pri_note'          => $note??"",
                            'pri_weight'        => $weight??0,
                            'pri_volume'        => $volume??0,

                        ];
                        if (empty($importDate['pri_rece_province']) && empty($importDate['pri_rece_city']) && empty($importDate['pri_rece_area'])){
                            //解析详细地址填入收件人省市区
                            $res = $areasRepository->parseDetailAddress($importDate['pri_rece_address']);
                            if (!empty($res)){
                                $importDate['pri_rece_province'] = $res['p']??"";
                                $importDate['pri_rece_city'] = $res['c']??"";
                                $importDate['pri_rece_area'] = $res['a']??"";
                                $importDate['pri_rece_address'] = $res['d']??"";
                            }
                        }
                        if (empty($importDate['pri_send_province']) && empty($importDate['pri_send_city']) && empty($importDate['pri_send_area'])){
                            //解析详细地址填入寄件人省市区
                            $res = $areasRepository->parseDetailAddress($importDate['pri_send_address']);
                            if (!empty($res)){
                                $importDate['pri_send_province'] = $res['p']??"";
                                $importDate['pri_send_city'] = $res['c']??"";
                                $importDate['pri_send_area'] = $res['a']??"";
                                $importDate['pri_send_address'] = $res['d']??"";
                            }
                        }
                        $srcet = md5(json_encode($importDate));
                        //对该数据做唯一性判断
                        $exist = $customPrintRepository->isExit($srcet);
                        if(!$exist) {
                            $importDate['md5_code'] = $srcet;
                            $importDate['created_at'] = time();
                            //存进数据表
                            $ret = $customPrintRepository->save($importDate);
                            if(!$ret){
                                \DB::rollBack();
                                return $this->jsonFailed("数据保存出错，已保存0条数据!");
                            }
                            $total+=1;
                        }

                    }
                    $snum += 1;
                }
                \DB::commit();
                return $this->jsonSuccess("已成功导入".$snum."张表格,共".$total."条数据！");
            }
        }catch(CommonException $e){
            \DB::rollBack();
            return $this->jsonFailed($e->getMessage());
        }
    }

    //数据导出
    public function export(Request $request)
    {
        $info = $request->get('info');
        $inputs = json_decode($info,true);
        $finish = $inputs['created_at'];
        $time = Helper::getTimeRangedata($finish);

        $list = $this->repositories->getExportTableList($inputs,"created_at desc");

        for($i=$time['start'];$i<=$time['end'];){
            $times[] = $i;
            $i +=24*60*60;
        }

        $newExcel = new Spreadsheet();//创建一个新的excel文档
        $objSheet = $newExcel->getActiveSheet();  //获取当前操作sheet的对象
        $objSheet->setTitle('快递单');  //设置当前sheet的标题

        //设置自动列宽
        $objSheet->setCellValue('A1', '业务单号')
            ->setCellValue('B1', '寄件单位')
            ->setCellValue('C1', '快递方式')
            ->setCellValue('D1', '物流单号')
            ->setCellValue('E1', '寄件人姓名')
            ->setCellValue('F1', '寄件人电话')
            ->setCellValue('G1', '寄件人手机')
            ->setCellValue('H1', '寄件人省')
            ->setCellValue('I1', '寄件人市')
            ->setCellValue('J1', '寄件人区/县')
            ->setCellValue('K1', '寄件人地址')
            ->setCellValue('L1', '寄件人邮编')
            ->setCellValue('M1', '收件人姓名')
            ->setCellValue('N1', '收件人电话')
            ->setCellValue('O1', '收件人手机')
            ->setCellValue('P1', '收件人省')
            ->setCellValue('Q1', '收件人市')
            ->setCellValue('R1', '收件人区/县')
            ->setCellValue('S1', '收件人地址')
            ->setCellValue('T1', '收件人邮政编码')
            ->setCellValue('U1', '运费')
            ->setCellValue('V1', '订单金额')
            ->setCellValue('W1', '商品名称')
            ->setCellValue('X1', '商品编码')
            ->setCellValue('Y1', '销售属性')
            ->setCellValue('Z1', '商品金额')
            ->setCellValue('AA1', '数量')
            ->setCellValue('AB1', '备注')
            ->setCellValue('AC1', '重量')
            ->setCellValue('AD1', '体积');

        $index = 2;
        foreach ($list as $key => $value) {
            $objSheet->setCellValue('A' . $index, $value['pri_trade_no'].' ')
                ->setCellValue('B' . $index, $value['pri_send_company'])
                ->setCellValue('C' . $index, $value['express_str'])
                ->setCellValue('D' . $index, $value['waybill_code'].' ')
                ->setCellValue('E' . $index, $value['pri_send_username'])
                ->setCellValue('F' . $index, $value['pri_send_mobile'])
                ->setCellValue('G' . $index, $value['pri_send_tel'])
                ->setCellValue('H' . $index, $value['pri_send_province'])
                ->setCellValue('I' . $index, $value['pri_send_city'])
                ->setCellValue('J' . $index, $value['pri_send_area'])
                ->setCellValue('K' . $index, $value['pri_send_address'])
                ->setCellValue('L' . $index, $value['pri_send_zip_code'])
                ->setCellValue('M' . $index, $value['pri_rece_username'])
                ->setCellValue('N' . $index, $value['pri_rece_mobile'])
                ->setCellValue('O' . $index, $value['pri_rece_tel'])
                ->setCellValue('P' . $index, $value['pri_rece_province'])
                ->setCellValue('Q' . $index, $value['pri_rece_city'])
                ->setCellValue('R' . $index, $value['pri_rece_area'])
                ->setCellValue('S' . $index, $value['pri_rece_address'])
                ->setCellValue('T' . $index, $value['pri_rece_zip_code'])
                ->setCellValue('U' . $index, $value['delivery_fee'])
                ->setCellValue('V' . $index, $value['order_amount'])
                ->setCellValue('W' . $index, $value['prod_name'])
                ->setCellValue('X' . $index, $value['prod_code'])
                ->setCellValue('Y' . $index, $value['prod_attribute'])
                ->setCellValue('Z' . $index, $value['prod_amount'])
                ->setCellValue('AA' . $index, $value['prod_num'])
                ->setCellValue('AB' . $index, $value['pri_note'])
                ->setCellValue('AC' . $index, $value['pri_weight'])
                ->setCellValue('AD' . $index, $value['pri_volume']);
            $index +=1;
        }

        $this->downloadExcel($newExcel, "快递单", 'Xls');

    }

    //公共文件，用来传入xls并下载
    public function downloadExcel($newExcel, $filename, $format)
    {
        // $format只能为 Xlsx 或 Xls
        if ($format == 'Xlsx') {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        } elseif ($format == 'Xls') {
            header('Content-Type: application/vnd.ms-excel');
        }

        header("Content-Disposition: attachment;filename=". $filename . date('Y-m-d') . '.' . strtolower($format));
        header('Cache-Control: max-age=0');
        $objWriter = IOFactory::createWriter($newExcel, $format);

        $objWriter->save('php://output');

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

    //生成唯一的交易单号
    public function generateTradeNo($tradeNo)
    {
        $printLogModel = app(SaasPrintLog::class);
        $tradeNo = $tradeNo . rand(100, 999);
        $isExist = $printLogModel->where(['trade_order' => $tradeNo])->exists();
        if ($isExist){
            self::generateTradeNo($tradeNo);
        }else{
            return $tradeNo;
        }
    }

    //调整收货人发件人信息
    public function infoEdit(Request $request)
    {
        $type = $request->route('type');
        $priId = $request->route('pri_id');
        try {

                $row = $this->repositories->getById($priId);
                $areaRepository = app(SaasAreasRepository::class);
                //获取省市区地区code
                $now_action = "area";//当前转化的地区标识
                //转换收货地址
                $address = trim($row['pri_rece_province']).' '.trim($row['pri_rece_city']).' '.trim($row['pri_rece_area']);

                $res = $areaRepository->parseAddressToCode($address);
                if ($res['code'] != 0){
                    $row['is_address'] = 1;
                    if ($res['code'] == 2){
                        //地址解析不匹配
                        $row['address_msg'] =  $res['msg'];
                    }
                    $row['rece_prov_code'] = $res['data']['p'];
                    $row['rece_city_code'] = $res['data']['c'];
                    $row['rece_area_code'] = $res['data']['a'];
                }else{
                    $row['is_address'] = 0;
                    $row['rece_prov_code'] = "";
                    $row['rece_city_code'] = -1;
                    $row['rece_area_code'] = -1;
                }



                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form,
                    [   'row' => $row,
                        'type' => $type,
                    ]);
                return $this->jsonSuccess(['html' => $htmlContents]);

        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }
    }

    //保存收货人发件人信息
    public function infoSave(Request $request)
    {
        $areaModel = app(SaasAreas::class);
        $param = $request->post();
        //去除字符串中左右两边的空格
        foreach ($param as $k=>$v)
        {
            $key = rtrim($k,'_');
            $post[$key] = trim($v);
        }
        //判断是收件信息还是寄件信息
        if ($post['pri_type'] == 'consignee'){
            //收件人
            $upData = [
                'pri_rece_username' => $post['pri_rece_username'],
                'pri_rece_mobile'   => $post['pri_rece_mobile'],
                'pri_rece_tel'      => $post['pri_rece_tel'],
                'pri_rece_address'  => $post['pri_rece_address'],
            ];
            //区分省
            if (!empty($post['province']) && $post['province']!=-1 && $post['province']!='省'){
                $upData['pri_rece_province'] = $areaModel->where('area_id',$post['province'])->value('area_name');
            }
            //区分市
            if (!empty($post['city']) && $post['city']!=-1 && $post['city']!='市'){
                $upData['pri_rece_city'] = $areaModel->where('area_id',$post['city'])->value('area_name');
            }
            //区分区
            if (!empty($post['district']) && $post['district']!=-1 && $post['district']!='区'){
                $upData['pri_rece_area'] = $areaModel->where('area_id',$post['district'])->value('area_name');
            }
        }else{
            $upData = [
                'pri_send_username' => $post['pri_send_username'],
                'pri_send_mobile'   => $post['pri_send_mobile'],
                'pri_send_tel'      => $post['pri_send_tel'],
            ];
        }
        //更新表
        $this->repositories->update(['cus_pri_id' => $post['id']],$upData);

        return $this->jsonSuccess([]);
    }
}