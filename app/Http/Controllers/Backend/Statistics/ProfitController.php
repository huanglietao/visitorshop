<?php
namespace App\Http\Controllers\Backend\Statistics;

use App\Exceptions\CommonException;
use App\Http\Controllers\Backend\BaseController;
use App\Repositories\OmsMerchantInfoRepository;
use App\Repositories\SaasOrderFileRepository;
use App\Repositories\SaasOrdersRepository;
use App\Repositories\SaasSalesChanelRepository;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


/**
 * 项目说明 OMS系统
 * 详细说明 OMS系统
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/05/05
 */
class ProfitController extends BaseController
{
    protected $viewPath = 'backend.statistics.profit';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $merchantID = '';

    public function __construct(SaasOrdersRepository $orderRepository,OmsMerchantInfoRepository $omsMerchantInfoRepository,
                                SaasOrderFileRepository $orderFileRepository)
    {
        parent::__construct();
        $this->orderRepository = $orderRepository;
        $this->orderFileRepository = $orderFileRepository;
        $this->merchantInfo = $omsMerchantInfoRepository->getAllMerchantInfo();
    }


    /**
     * 功能首页结构view
     * @return mixed
     */
    public function index()
    {
        return view($this->viewPath.'.index',['InfoList'=>$this->merchantInfo]);
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
            $Info =  $this->orderFileRepository->getTableList($params,'order_file_id desc',ONE)->toArray();
            $orderShippingList = $Info['data'];
            foreach ($orderShippingList as $key=>$value){
                //成本小计
                $total_cost = round($value['product_cost']+$value['express_cost'],2);
                $orderShippingList[$key]['total_cost'] = $total_cost;
                //利润
                $orderShippingList[$key]['profit'] = $value['pay_amount']-$total_cost;
                if($value['pay_amount']=="0.00"){
                    //毛利率
                    $orderShippingList[$key]['gross_margin'] = round($orderShippingList[$key]['profit']*100,2);
                }else{
                    //毛利率
                    $orderShippingList[$key]['gross_margin'] = round($orderShippingList[$key]['profit']/$value['pay_amount']*100,2);
                }
            }

            $htmlContents = $this->renderHtml('',['list' =>$orderShippingList]);

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
        $orderInfo =  $this->orderFileRepository->getExportTableList($params,'order_file_id desc',ONE)->toArray();

        $newExcel = new Spreadsheet();//创建一个新的excel文档
        $objSheet = $newExcel->getActiveSheet();  //获取当前操作sheet的对象
        $objSheet->setTitle('利润统计表');  //设置当前sheet的标题

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

        //设置第一栏的标题
        $objSheet->setCellValue('A1', '订单号')
            ->setCellValue('B1', '下单日期')
            ->setCellValue('C1', '发货日期')
            ->setCellValue('D1', '商品数量')
            ->setCellValue('E1', '商品总额')
            ->setCellValue('F1', '配送费用')
            ->setCellValue('G1', '优惠金额')
            ->setCellValue('H1', '已支付金额')
            ->setCellValue('I1', '商品成本')
            ->setCellValue('J1', '物流成本')
            ->setCellValue('K1', '成本小计')
            ->setCellValue('L1', '利润')
            ->setCellValue('M1', '毛利率')
            ->setCellValue('N1', '店铺来源')
            ->setCellValue('O1', '渠道来源');


        foreach ($orderInfo as $k => $v) {
            //成本小计
            $total_cost = number_format($v['product_cost']+$v['express_cost'],2);
            //利润
            $profit = $v['pay_amount']-$total_cost;
            if($v['pay_amount']=="0.00"){
                //毛利率
                $gross_margin = round($profit*100,2);
            }else{
                //毛利率
                $gross_margin = round($profit/$v['pay_amount']*100,2);
            }

            $k = $k + 2;
            $objSheet->setCellValue('A' . $k, $v['order_no']."\t")
                ->setCellValue('B' . $k, date('Y-m-d H:i:s',$v['order_create_time']))
                ->setCellValue('C' . $k, date('Y-m-d H:i:s',$v['shipping_time']))
                ->setCellValue('D' . $k, $v['product_nums'])
                ->setCellValue('E' . $k, $v['product_amount'])
                ->setCellValue('F' . $k, $v['express_fee'])
                ->setCellValue('G' . $k, $v['discount_fee'])
                ->setCellValue('H' . $k, $v['pay_amount'])
                ->setCellValue('I' . $k, $v['product_cost'])
                ->setCellValue('J' . $k, $v['express_cost'])
                ->setCellValue('K' . $k, $total_cost)
                ->setCellValue('L' . $k, $profit)
                ->setCellValue('M' . $k, $gross_margin)
                ->setCellValue('N' . $k, $v['shop_info'])
                ->setCellValue('O' . $k, $v['cha_info']);
        }

        $this->downloadExcel($newExcel, "利润统计表", 'Xls');

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
