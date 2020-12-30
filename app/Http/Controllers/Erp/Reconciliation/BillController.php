<?php
namespace App\Http\Controllers\Erp\Reconciliation;

use App\Http\Controllers\Erp\BaseController;
use App\Http\Requests\Erp\Reconciliation\BillRequest;
use App\Repositories\BillRepository;
use App\Services\Outer\Erp\Api;
use Illuminate\Http\Request;
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
    protected $viewPath = 'erp.reconciliation.bill';  //当前控制器所的view所在的目录
    protected $modules = 'sys';//当前控制器所属模块
    public function __construct(BillRepository $Repository)
    {
        parent::__construct();
        $this->repositories = $Repository;
    }

    public function index()
    {
        $setting = $this->getSetting();
        $pageLimit = $setting['default_pages_limit'];
        return view('erp.reconciliation.bill.index',compact('pageLimit'));
    }

    //获取接口数据
    public function getPortList($partner_code,$start_date,$end_date)
    {
        $data = [
            'partner_code'=>$partner_code,
            'start_date'=>$start_date,
            'end_date'=>$end_date
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

    //ajax方式获取列表
    public function table(Request $request)
    {
        if (session("capital")) {
            $userinfo = session("capital");
        }else{
            //未登录，返回登陆页面
            return redirect('login/index');
        }

        $search = $request->post("search");
        $limit = $request->post('limit');
        $curPage = $request->post('curPage');

        //切割时间段
        $array = explode(" - ",$search);
        $end_date = $array[1];
        $start_date = $array[0];
        //转换时间戳
        $data = $this->changeTime($start_date,$end_date);

        //得到客户登录编号
        $partner_code = $userinfo['data']['partner_code'];

        //存入redis
        if(Redis::exists($partner_code."_".$data['start']."_".$data['end'])==0)
        {
            $result = $this->getPortList($partner_code,$start_date,$end_date);
            foreach ($result as $k=>$v){
                if($v['order_state']=="done"){
                    $result[$k]['order_state']="未出货";
                }elseif ($v['order_state']=="gather" || $v['order_state']=="send"){
                    $result[$k]['order_state']="已出货";
                }
            }
            Redis::setex($partner_code."_".$data['start']."_".$data['end'],86400,serialize($result));
        }else{
            $result = unserialize(Redis::get($partner_code."_".$data['start']."_".$data['end']));
        }

        $list = [];
        $i=0;

        foreach ($result as $k =>$v){
            if($v['order_state']=="已出货"){
                $list[$i] = $v;
                $i++;
            }
        }
        $offset = ($curPage-1)*10;
        if($limit>count($list)-$offset){
            $limit = count($list);
        };
        $total = count($list);
        $list = array_slice($list,$offset,$limit);
        $htmlContents = $this->renderHtml('',['list' =>$list]);
        return response()->json(['status' => 200, 'html' => $htmlContents,'total' => $total]);
    }

    //导出客户对账单表格
    public function export(Request $request)
    {
        if (session("capital")) {
            $userinfo = session("capital");
        }else{
            //未登录，返回登陆页面
            return redirect('login/index');
        }

        $newExcel = new Spreadsheet();//创建一个新的excel文档
        $objSheet = $newExcel->getActiveSheet();  //获取当前操作sheet的对象
        $objSheet->setTitle('管理员对账表');  //设置当前sheet的标题

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
        $array = explode(" - ",$search);
        $end_date = $array[1];
        $start_date = $array[0];
        $data = $this->changeTime($start_date,$end_date);

        $partner_code = $userinfo['data']['partner_code'];

        if(Redis::exists($partner_code."_".$data['start']."_".$data['end'])==0){
            $result = $this->getPortList($partner_code,$start_date,$end_date);
            foreach ($result as $k=>$v){
                if($v['order_state']=="done"){
                    $result[$k]['order_state']="未出货";
                }elseif ($v['order_state']=="gather" || $v['order_state']=="send"){
                    $result[$k]['order_state']="已出货";
                }
            }
        }
        else{
            $result = unserialize(Redis::get($partner_code."_".$data['start']."_".$data['end']));
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

   //添加/编辑操作
    public function save(BillRequest $request)
    {
        $ret = $this->repositories->save($request->all());
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }

}