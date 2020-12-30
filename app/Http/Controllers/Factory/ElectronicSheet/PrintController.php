<?php
namespace App\Http\Controllers\Factory\ElectronicSheet;

use App\Exceptions\CommonException;
use App\Http\Controllers\Factory\BaseController;
use App\Models\SaasExpress;
use App\Models\SaasOrderProducts;
use App\Models\SaasPrintLog;
use App\Models\ScmBatchPrint;
use App\Repositories\SaasOrdersRepository;
use App\Repositories\ScmBatchPrintRepository;
use App\Services\Exception;
use App\Services\Outer\CommonApi;
use App\Services\Prints;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * 供货商订单列表
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020/06/09
 */

class PrintController extends BaseController
{
    protected $viewPath = 'factory.ElectronicSheet.print';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $mchID;

    public function __construct(ScmBatchPrintRepository $batchPrintRepository,SaasPrintLog $printLog,SaasOrdersRepository $ordersRepository,SaasExpress $express)
    {
        parent::__construct();
        $this->repositories = $batchPrintRepository;
        $this->orderRepositories = $ordersRepository;
        $this->printLogModel = $printLog;
        $this->expressModel = $express;
        $this->mchID = session('admin')['mch_id'];
    }

    public function lists(Request $request)
    {
        $limit = $request->get('limit');
        $search_list = $this->repositories->getSupplierSnList();
        $sku_ids = $request->get('sku') ?? json_encode($search_list[0]['sku_ids']);
        $tab = $request->get('tab') ?? 'all';
        $res = $this->repositories->getTableList($limit,'order_create_time asc',$sku_ids,$tab);
        $statusCount = $this->repositories->getCount(json_decode($sku_ids,true));

        return view("factory.print.batchPrint",['data'=>$res,'searchList'=>$search_list,'skuIds'=>$sku_ids,'tab'=>$tab,'statusCount'=>$statusCount]);
    }

    //获取打印数据
    public function printData(Request $request)
    {
        $order_no = $request->post('order_no');
        $delivery_type = $request->post('delivery_type');

        if(empty($order_no)) {
            $this->returnJson(1,'请输入正确的订单号');
        }

        $print_info = $this->repositories->getRow(['order_no'=>$order_no]);
        if(empty($print_info)){
            $this->returnJson(1,'订单号不存在');
        }

        //面单单号
        $tradeOrderNo = $print_info['order_no'] . rand(10, 99);

        //面单备注
        $note = $print_info['order_no'].'-'.$print_info['buy_num'];

        //地区id转换
        $address = $this->repositories->exchangeArea($print_info['print_rcv_province'],$print_info['print_rcv_city'],$print_info['print_rcv_area']);

        //面单数据
        $print_data = [
            'id'            =>  $print_info['order_id'],
            'agent_id'      =>  $print_info['user_id'],
            'order_no'      =>  $print_info['order_no'],
            'province'      =>  !empty($address['p']) ? $address['p'] : '',
            'city'          =>  !empty($address['c']) ? $address['c'] : '',
            'area'          =>  !empty($address['a']) ? $address['a'] : '',
            'address'       =>  $print_info['print_rcv_address'],
            'mobile'        =>  $print_info['print_rcv_phone'],
            'consignee'     =>  $print_info['print_rcv_user'],
        ];
        file_put_contents('/tmp/order_print_data.log',var_export($print_data,true),FILE_APPEND);

        $printService = app(Prints::class);
        if($delivery_type == 'SF'){
            //顺风打单
            $result = $printService->sfPrinter($print_data,$tradeOrderNo,$delivery_type,0,SUPPLIER_DEFAULT_ID,$note);
        }else{
            //菜鸟打单
            $result = $printService->caiNiaoPrinter($print_data,$tradeOrderNo,$delivery_type,0,SUPPLIER_DEFAULT_ID,$note);
        }

        if($result['code'] == 1){
            //成功返回
            $this->returnJson(0,$result['data']);
        }else{
            //失败返回
            Redis::setex( 'error'.$print_info['order_no'] , 259200 , $result['msg']);
            $this->returnJson(1,$result['msg']);
        }

    }

    //打单发货
    public function delivery()
    {
        try{
            $data = \request()->all();

            if (empty( $data['taskid'])) {
                $this->returnJson(1, '打印任务（taskID）不存在!');
            }

            $arrTask = explode('_', $data['taskid']);

            $sysOrderId = $arrTask[0];
            $taskId = $arrTask[3];

            //获取批打订单相关信息
            $ordersInfo = $this->repositories->getRow(['order_id'=>$sysOrderId]);

            //获取打印记录
            $printLogInfo = $this->printLogModel->where('id',$taskId)->first();

            if (empty($ordersInfo) || empty($printLogInfo)) {
                Redis::setex( 'error'.$printLogInfo['order_no'] , 259200 , '打印参数错误，请联系管理员!');
                $this->returnJson(1, '打印参数错误，请联系管理员!');
            }

            \DB::beginTransaction();

            //打印次数加1
            $this->printLogModel->where(['id'=>$taskId])->increment('print_times', 1);
            /*if($ordersInfo['batch_print_id'] >= 1545 && $ordersInfo['batch_print_id'] <= 4070) {*/
            if(false) {
                //2020-08-27 针对6寸小插页2526单特殊处理
                //获取淘宝订单号
                $tb_order_no = $this->orderRepositories->getById($ordersInfo['order_id']);
                //触发更新淘宝物流单号接口
                $post_data = [
                    'order_no'      =>$tb_order_no['order_relation_no'],
                    'agent_id'      =>18,
                    'out_sid'       =>$printLogInfo['waybill_code'],
                    'company_code'  =>'yto',
                ];
                $apiService = app(CommonApi::class);
                file_put_contents('/tmp/order_print_data.log',var_export($post_data,true),FILE_APPEND);
                $result = $apiService->request('http://fxmy_api.meiin.com/tb/logistics/resend',$post_data,'POST');
                file_put_contents('/tmp/order_print_data.log',var_export($result,true),FILE_APPEND);
            }else{
                //获取快递id
                $express_id = $this->expressModel->where('express_code',strtolower($printLogInfo['company']))->value('express_id');
                if(empty($express_id)){
                    Redis::setex( 'error'.$printLogInfo['order_no'] , 259200 , '未配置相应快递信息');
                    $this->returnJson(1, '未配置相应快递信息');
                }

                //发货流程处理(统一手动发货流程)
                $delivery_data = [
                    'delivery_code'         =>  $printLogInfo['waybill_code'],
                    'order_delivery_id'     =>  $express_id,
                    'order_exp_fee'         =>  $ordersInfo['order_exp_fee'],
                    'order_remark_admin'    =>  '实物打单发货',
                ];
                $this->orderRepositories->delivery($sysOrderId,$delivery_data,'实物打单员',config('common.sys_abbreviation')['factory']);
            }

            //更新批量打单表记录为已打印
            $this->repositories->update(['order_id'=>$sysOrderId],['is_print'=>PUBLIC_YES]);

            \DB::commit();
            $this->returnJson(0,'发货成功');

        }catch (\Exception $e){
            \DB::rollBack();

            if(!empty($e->getMessage())){
                Redis::setex( 'error'.$printLogInfo['order_no'] , 259200 , $e->getMessage());
                $this->returnJson(1,$e->getMessage());
            }else{
                Redis::setex( 'error'.$printLogInfo['order_no'] , 259200 , '打单发货失败');
                $this->returnJson(1,'打单发货失败');
            }
        }
    }

    //数据导入
    public function import()
    {
        try{
            if (is_uploaded_file($_FILES['file']['tmp_name'])) {

                $batchPrint = app(ScmBatchPrint::class);
                $ordProduct = app(SaasOrderProducts::class);

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
                //$filename可以是上传的表格，或者是指定的表格

                $objPHPExcel = $objReader->load($fileTmpname);
                //excel中的第一张sheet

                $sheet = $objPHPExcel->getSheet(0);

                // 取得总行数
                $highestRow = $sheet->getHighestRow();
                $total = 0;

                \DB::beginTransaction();
                //循环读取excel表格，整合成数组。如果是不指定key的二维，就用$data[i][j]表示。
                for ($j = 3; $j <= $highestRow; $j++) {
                    //订单流水号
                    $project_sn = $objPHPExcel->getActiveSheet()->getCell("C" . $j)->getValue();

                    //截取订单编号
                    $order_no = explode('_',$project_sn)[0];

                    //判断该条记录是否已经保存到数据库
                    $isExit = $batchPrint->where(['order_no'=>$order_no])->first();

                    //如果存在则不再操作
                    if(!empty($isExit)){
                        continue;
                    }

                    //查询订单信息
                    $order_info = $this->orderRepositories->getOrderInfo('',$order_no);
                    if(empty($order_info)){
                        continue;
                    }

                    //订单详情信息
                    $order_prod_info = $ordProduct->where(['order_no'=>$order_no])->select('sku_id','prod_num')->first();
                    if(empty($order_prod_info)){
                        continue;
                    }

                    $data = [
                        'order_no'              => trim($order_no),
                        'order_id'              => $order_info['order_id'],
                        'mch_id'                => $order_info['mch_id'],
                        'user_id'               => $order_info['user_id'],
                        'order_delivery_id'     => $order_info['order_delivery_id'],
                        'order_exp_fee'         => $order_info['order_exp_fee'],
                        'sku_id'                => $order_prod_info['sku_id'],
                        'print_rcv_user'        => trim($order_info['order_rcv_user']),
                        'print_rcv_phone'       => $order_info['order_rcv_phone'],
                        'print_rcv_province'    => $order_info['order_rcv_province'],
                        'print_rcv_city'        => $order_info['order_rcv_city'],
                        'print_rcv_area'        => $order_info['order_rcv_area'],
                        'print_rcv_address'     => $order_info['order_rcv_address'],
                        'print_rcv_zipcode'     =>$order_info['order_rcv_zipcode'],
                        'buy_num'               =>$order_prod_info['prod_num'],
                        'order_create_time'     =>strtotime($order_info['created_at']),
                        'created_at'            =>time(),
                    ];

                    $ret = $batchPrint->create($data);
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