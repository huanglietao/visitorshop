<?php
namespace App\Http\Controllers\Merchant\Statistics;

use App\Exceptions\CommonException;
use App\Http\Controllers\Merchant\BaseController;
use App\Models\SaasOrders;
use App\Repositories\SaasOrderFileDetailRepository;
use App\Repositories\SaasOrderFileRepository;
use App\Repositories\SaasSalesChanelRepository;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


/**
 * 项目说明 OMS系统
 * 详细说明 OMS系统
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/07/07
 */
class CostsController extends BaseController
{
    protected $viewPath = 'merchant.statistics.costs';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $merchantID = '';

    public function __construct(SaasSalesChanelRepository $chanelRepository,SaasOrderFileRepository $orderFileRepository,
                                SaasOrderFileDetailRepository $orderFileDetailRepository)
    {
        parent::__construct();
        $this->orderFileDetailRepository = $orderFileDetailRepository;
        $this->orderFileRepository = $orderFileRepository;
        $this->merchantID = empty(session('admin')) == false ? session('admin')['mch_id'] : ' ';
        $this->chaList = $chanelRepository->getSalesChanel(CHANEL_TERMINAL_AGENT);
    }


    /**
     * 功能首页结构view
     * @return mixed
     */
    public function index()
    {
        return view($this->viewPath.'.index',['chaList'=>$this->chaList]);
    }

    /**
     * ajax获取列表项
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function table(Request $request)
    {
        try{
            $params = $request->all();
            $Info =  $this->orderFileDetailRepository->getTableList($params)->toArray();
            $costsInfo = $Info['data'];
            foreach ($costsInfo as $key=>$value){
                $unit_cost = number_format($value['product_cost']/$value['product_nums']/$value['product_page_num'],2);
                //单位成本
                $costsInfo[$key]['unit_cost'] = $unit_cost;
//                $costsInfo[$key]['express_fee'] = '0.00';
//                //运费
//                $express_fee = $this->orderFileRepository->getList(['order_no'=>$value['order_no']])->toArray();
//                if(!empty($express_fee)){
//                    $costsInfo[$key]['express_fee'] = $express_fee[0]['express_fee'];
//                }
            }
            $htmlContents = $this->renderHtml('',['list' =>$costsInfo]);


            $total = $Info['total'];

            return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
        }catch (CommonException $e){
            $this->jsonFailed($e->getMessage());
        }

    }

    //导出
    public function export(Request $request)
    {
        //临时更改限制文件的大小
        ini_set('memory_limit', '2G');
        $search = $request->get('info');
        $params = (array)json_decode($search);
        $orderInfo =  $this->orderFileDetailRepository->getExportTableList($params)->toArray();

        $newExcel = new Spreadsheet();//创建一个新的excel文档
        $objSheet = $newExcel->getActiveSheet();  //获取当前操作sheet的对象
        $objSheet->setTitle('销售成本统计表');  //设置当前sheet的标题

        //设置自动列宽
        $objSheet->getColumnDimension('A')->setAutoSize(true);
        $objSheet->getColumnDimension('B')->setAutoSize(true);
        $objSheet->getColumnDimension('C')->setWidth(10);
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
//        $objSheet->getColumnDimension('S')->setWidth(10);

        //设置第一栏的标题
        $objSheet->setCellValue('A1', '订单号')
            ->setCellValue('B1', '项目号')
            ->setCellValue('C1', '快递方式')
            ->setCellValue('D1', '物流单号')
            ->setCellValue('E1', '配送区域')
            ->setCellValue('F1', '商品名称')
            ->setCellValue('G1', '货号')
            ->setCellValue('H1', '工厂编号')
            ->setCellValue('I1', '属性')
            ->setCellValue('J1', '数量')
            ->setCellValue('K1', '单位成本')
            ->setCellValue('L1', '张数')
//            ->setCellValue('M1', '运费')
            ->setCellValue('M1', '成本小计')
            ->setCellValue('N1', '发货时间')
            ->setCellValue('O1', '下单时间')
            ->setCellValue('P1', '供应商')
            ->setCellValue('Q1', '渠道')
            ->setCellValue('R1', '店铺来源');

        $orderModel = app(SaasOrders::class);
        foreach ($orderInfo as $k => $v) {
            $unit_cost = number_format($v['product_cost']/$v['product_nums']/$v['product_page_num'],2);
            $v['express_fee'] = $orderModel->where(['order_no'=>$v['order_no']])->value('order_exp_fee');
            $k = $k + 2;
            $objSheet->setCellValue('A' . $k, $v['order_no']."\t")
                ->setCellValue('B' . $k, $v['order_item_no']."\t")
                ->setCellValue('C' . $k, $v['express_name'])
                ->setCellValue('D' . $k, $v['delivery_code'])
                ->setCellValue('E' . $k, $v['rcv_address'])
                ->setCellValue('F' . $k, $v['product_name']."\t")
                ->setCellValue('G' . $k, $v['product_sku_sn'])
                ->setCellValue('H' . $k, $v['product_process_code'])
                ->setCellValue('I' . $k, $v['product_attr'])
                ->setCellValue('J' . $k, $v['product_nums'])
                ->setCellValue('K' . $k, $unit_cost)
                ->setCellValue('L' . $k, $v['product_page_num'])
//                ->setCellValue('M' . $k, $v['express_fee'])
                ->setCellValue('M' . $k, $v['product_cost'])
                ->setCellValue('N' . $k, date('Y-m-d H:i:s',$v['shipping_time']))
                ->setCellValue('O' . $k, date('Y-m-d H:i:s',$v['order_create_time']))
                ->setCellValue('P' . $k, $v['sp_info'])
                ->setCellValue('Q' . $k, $v['cha_info'])
                ->setCellValue('R' . $k, $v['shop_info']);
        }

        $this->downloadExcel($newExcel, "销售成本统计表", 'Xls');

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

   //添加/编辑操作
    public function save(Request $request)
    {
        $ret = $this->repositories->save($request->all());
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }

}
