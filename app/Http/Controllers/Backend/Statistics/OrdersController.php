<?php
namespace App\Http\Controllers\Backend\Statistics;

use App\Exceptions\CommonException;
use App\Http\Controllers\Backend\BaseController;
use App\Repositories\OmsMerchantInfoRepository;
use App\Repositories\SaasOrderFileRepository;
use App\Repositories\SaasOrdersRepository;
use App\Repositories\SaasSalesChanelRepository;
use App\Services\Helper;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


/**
 * 项目说明 OMS系统
 * 详细说明 OMS系统
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/05/03
 */
class OrdersController extends BaseController
{
    protected $viewPath = 'backend.statistics.orders';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $merchantID = '';

    public function __construct(SaasOrdersRepository $orderRepository, SaasOrderFileRepository $orderFileRepository,
                                OmsMerchantInfoRepository $omsMerchantInfoRepository)
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

            foreach ($Info['data'] as $k=>$v){
                if(mb_strlen($v['product_info'])>150){
                    $Info['data'][$k]['product_info'] = mb_substr($v['product_info'],0,145)."...";
                }
            }
            $htmlContents = $this->renderHtml('',['list' =>$Info['data']]);

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
        $objSheet->setTitle('订单发货统计表');  //设置当前sheet的标题

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
        $objSheet->getColumnDimension('M')->setWidth(100);
        $objSheet->getColumnDimension('N')->setWidth(10);
        $objSheet->getColumnDimension('O')->setWidth(10);
        $objSheet->getColumnDimension('P')->setWidth(10);

        //设置第一栏的标题
        $objSheet->setCellValue('A1', '订单号')
            ->setCellValue('B1', '外部订单号')
            ->setCellValue('C1', '下单日期')
            ->setCellValue('D1', '收货人')
            ->setCellValue('E1', '收货地址')
            ->setCellValue('F1', '手机')
            ->setCellValue('G1', '订单金额')
            ->setCellValue('H1', '支付方式')
            ->setCellValue('I1', '支付状态')
            ->setCellValue('J1', '快递方式')
            ->setCellValue('K1', '快递单号')
            ->setCellValue('L1', '发货日期')
            ->setCellValue('M1', '商品信息')
            ->setCellValue('N1', '店铺来源')
            ->setCellValue('O1', '渠道来源')
            ->setCellValue('P1', '供应商');


        foreach ($orderInfo as $k => $v) {
            $k = $k + 2;
            $objSheet->setCellValue('A' . $k, $v['order_no']."\t")
                ->setCellValue('B' . $k, $v['order_relation_no']."\t")
                ->setCellValue('C' . $k, date('Y-m-d H:i:s',$v['order_create_time']))
                ->setCellValue('D' . $k, $v['rcv_user'])
                ->setCellValue('E' . $k, $v['rcv_address'])
                ->setCellValue('F' . $k, $v['rcv_mobile']."\t")
                ->setCellValue('G' . $k, $v['order_amount'])
                ->setCellValue('H' . $k, $v['pay_name'])
                ->setCellValue('I' . $k, "已付款")
                ->setCellValue('J' . $k, $v['express_name'])
                ->setCellValue('K' . $k, $v['delivery_code'])
                ->setCellValue('L' . $k, date('Y-m-d H:i:s',$v['shipping_time']))
                ->setCellValue('M' . $k, $v['product_info'])
                ->setCellValue('N' . $k, $v['shop_info'])
                ->setCellValue('O' . $k, $v['cha_info'])
                ->setCellValue('P' . $k, $v['sp_info']);
        }

        $this->downloadExcel($newExcel, "订单发货统计表", 'Xls');

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
