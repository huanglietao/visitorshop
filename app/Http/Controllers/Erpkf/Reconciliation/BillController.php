<?php
namespace App\Http\Controllers\Erpkf\Reconciliation;

use App\Http\Controllers\Erpkf\BaseController;
use App\Repositories\BillRepository;
use App\Services\Outer\Erp\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use PhpOffice\PhpSpreadsheet\IOFactory;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
/**
 * 项目说明
 * 详细说明
 * @author:
 * @version: 1.0
 * @date:
 */
class BillController extends BaseController
{
    protected $viewPath = 'erpkf.reconciliation.bill';  //当前控制器所的view所在的目录
    protected $modules = 'sys';//当前控制器所属模块
    public function __construct(BillRepository $Repository)
    {
        parent::__construct();
        $this->repositories = $Repository;
    }

    public function index()
    {
        $list = DB::table('erp_vip_customer')->select('partner_code')->get();
        $partner_codeList = json_decode($list,true);
        $setting = $this->getSetting();
        $pageLimit = $setting['default_pages_limit'];
        return view('erpkf.reconciliation.bill.index',compact('pageLimit','partner_codeList'));
    }


    //ajax方式获取列表
    public function table(Request $request)
    {
        $id = $request->post("cate_id");                                //标识ID
        $search = $request->post("search");                             //查询的时间
        $limit = $request->post('limit');                               // 每页显示数量
        $curPage = $request->post('curPage');                           // 当前页
        $partner_code = $request->post('partner_code');                 //查询的客户编号
        $partner_real_name = $request->post('partner_real_name');       //查询的客户名称
        $list = [];                                                         //设置空数组
        if(!$partner_code && !$partner_real_name){
            $htmlContents = $this->renderHtml('',['list' =>$list]);
            return response()->json(['status' => 200, 'html' => $htmlContents,'total' => 0]);
        }
        //切割时间段
        $array = explode(" - ",$search);
        $end_date = $array[1];
        $start_date = $array[0];
        //转换时间戳
        $data = $this->changeTime($start_date,$end_date);

        //redis索引值
        $redisKey = "KF_".$partner_code."_".$data['start']."_".$data['end'];
        if(!$partner_code){
            $redisKey = "KF_".$partner_real_name."_".$data['start']."_".$data['end'];
        }

        //判断redis是否有缓存
        if(Redis::exists($redisKey)==0)
        {
            //请求接口
            $result = $this->getPortList($partner_code,$partner_real_name,$start_date,$end_date);
            foreach ($result as $k=>$v){
                if($v['order_state']=="done"){
                    $result[$k]['order_state']="未出货";
                }elseif ($v['order_state']=="gather" || $v['order_state']=="send"){
                    $result[$k]['order_state']="已出货";
                }
            }
            //存入redis
            Redis::setex($redisKey,86400,serialize($result));
        }else{
            //取redis缓存的数据
            $result = unserialize(Redis::get($redisKey));
        }

        $i=0;
        if($id==1){
//            $list = $result;
            foreach ($result as $k =>$v){
                if($v['order_state']=="已出货"){
                    $list[$i] = $v;
                    $i++;
                }
            }
        }
//        else if($id==2){
//            foreach ($result as $k =>$v){
//                if($v['order_state']=="已出货"){
//                    $list[$i] = $v;
//                    $i++;
//                }
//            }
//        }
//        else{
//            foreach ($result as $k =>$v){
//                if($v['order_state']=="未出货"){
//                    $list[$i] = $v;
//                    $i++;
//                }
//            }
//        }
        $offset = ($curPage-1)*10;
        if($limit>count($list)-$offset){
            $limit = count($list);
        };
        $total = count($list);
        $list = array_slice($list,$offset,$limit);
        $htmlContents = $this->renderHtml('',['list' =>$list]);
        return response()->json(['status' => 200, 'html' => $htmlContents,'total' => $total]);
    }

    //获取接口数据
    public function getPortList($partner_code,$partner_real_name,$start_date,$end_date)
    {
        $data = [
            'partner_code' =>$partner_code,
            'partner_real_name'     =>$partner_real_name,
            'start_date'   =>$start_date,
            'end_date'     =>$end_date
        ];
        $res_arr = new Api();
        $result = [];
        $result_arr  = $res_arr->request(config('erp.interface_url').config('erp.sale_order'),$data);
        if($result_arr['code'] == 1){
            $result = $result_arr['data'];
        }
        return $result;
    }

    //转换时间戳
    public function changeTime($start,$end)
    {
        $data['start'] = strtotime($start);
        $data['end'] = strtotime($end);
        return $data;
    }


    //导出客户对账单表格
    public function export(Request $request)
    {

        $newExcel = new Spreadsheet();//创建一个新的excel文档
        $objSheet = $newExcel->getActiveSheet();  //获取当前操作sheet的对象
        $objSheet->setTitle('客户对账表');  //设置当前sheet的标题

        //设置自动列宽
        $objSheet->getColumnDimension('A')->setAutoSize(true);
        $objSheet->getColumnDimension('B')->setAutoSize(true);
        $objSheet->getColumnDimension('C')->setWidth(30);
        $objSheet->getColumnDimension('D')->setWidth(15);
        $objSheet->getColumnDimension('E')->setWidth(10);
        $objSheet->getColumnDimension('F')->setWidth(10);
        $objSheet->getColumnDimension('G')->setWidth(10);
        $objSheet->getColumnDimension('H')->setWidth(10);
        $objSheet->getColumnDimension('I')->setWidth(10);
        $objSheet->getColumnDimension('J')->setWidth(10);
        $objSheet->getColumnDimension('K')->setWidth(10);
        $objSheet->getColumnDimension('L')->setWidth(10);
        $objSheet->getColumnDimension('M')->setWidth(10);
        $objSheet->getColumnDimension('N')->setWidth(10);
        $objSheet->getColumnDimension('O')->setWidth(10);
        $objSheet->getColumnDimension('P')->setWidth(10);
        $objSheet->getColumnDimension('Q')->setWidth(10);
        $objSheet->getColumnDimension('R')->setWidth(10);

        //设置第一栏的标题
        $objSheet->setCellValue('A1', '订单日期')
            ->setCellValue('B1', '出货单号')
            ->setCellValue('C1', '订单内容')
            ->setCellValue('D1', '品名及规格')
            ->setCellValue('E1', '数量')
            ->setCellValue('F1', '单位')
            ->setCellValue('G1', '单双面')
            ->setCellValue('H1', '原价')
            ->setCellValue('I1', '其它')
            ->setCellValue('J1', '加工')
            ->setCellValue('K1', '折让')
            ->setCellValue('L1', '合计')
            ->setCellValue('M1', '出货状态')
            ->setCellValue('N1', '物流方式')
            ->setCellValue('O1', '物流单号')
            ->setCellValue('P1', '物流时间')
            ->setCellValue('Q1', '第三方订单流水号')
            ->setCellValue('R1', '备注');

        $search = $request->post('search');
        $partner_code = $request->post('partner_code');
        $partner_real_name = $request->post('partner_real_name');         //查询的客户简称
        $array = explode(" - ",$search);
        $end_date = $array[1];
        $start_date = $array[0];
        $data = $this->changeTime($start_date,$end_date);

        //redis索引值
        $redisKey = "KF_".$partner_code."_".$data['start']."_".$data['end'];
        if(!$partner_code){
            $redisKey = "KF_".$partner_real_name."_".$data['start']."_".$data['end'];
        }

        if(Redis::exists($redisKey)==0){
            $result = $this->getPortList($partner_code,$partner_real_name,$start_date,$end_date);
            foreach ($result as $k=>$v){
                if($v['order_state']=="done"){
                    $result[$k]['order_state']="未出货";
                }elseif ($v['order_state']=="gather" || $v['order_state']=="send"){
                    $result[$k]['order_state']="已出货";
                }
            }
        }
        else{
            $result = unserialize(Redis::get($redisKey));
        }

        foreach ($result as $k => $v) {
            $k = $k + 2;
            $objSheet->setCellValue('A' . $k, $v['order_date'])
                ->setCellValue('B' . $k, $v['order_name'])
                ->setCellValue('C' . $k, $v['order_print_name'])
                ->setCellValue('D' . $k, $v['order_product_name'])
                ->setCellValue('E' . $k, $v['order_single_num'])
                ->setCellValue('F' . $k, $v['order_uom_name'])
                ->setCellValue('G' . $k, $v['order_one_two'])
                ->setCellValue('H' . $k, $v['order_all_money'])
                ->setCellValue('I' . $k, $v['order_other_money'])
                ->setCellValue('J' . $k, $v['order_last_working_all_money'])
                ->setCellValue('K' . $k, $v['order_discount_money'])
                ->setCellValue('L' . $k, $v['order_total_money'])
                ->setCellValue('M' . $k, $v['order_state'])
                ->setCellValue('N' . $k, $v['express_type'])
                ->setCellValue('O' . $k, $v['express_num'])
                ->setCellValue('P' . $k, $v['express_date'])
                ->setCellValue('Q' . $k, $v['tripartite_serial_number'])
                ->setCellValue('R' . $k, $v['order_note']);
        }

        $this->downloadExcel($newExcel, "客户对账单", 'Xls');
    }

    //公共文件，用来传入xls并下载
    function downloadExcel($newExcel, $filename, $format)
    {
        // $format只能为 Xlsx 或 Xls
        if ($format == 'Xlsx') {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        } elseif ($format == 'Xls') {
            header('Content-Type: application/vnd.ms-excel');
        }

        header("Content-Disposition: attachment;filename="
            . $filename . date('Y-m-d') . '.' . strtolower($format));
        header('Cache-Control: max-age=0');
        $objWriter = IOFactory::createWriter($newExcel, $format);

        $objWriter->save('php://output');

    }


}