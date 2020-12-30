<?php
namespace App\Http\Controllers\Merchant\Statistics;

use App\Exceptions\CommonException;
use App\Http\Controllers\Merchant\BaseController;
use App\Repositories\SaasOrderFileRepository;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


/**
 * 项目说明 OMS系统 交货率统计
 * 详细说明 OMS系统
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/07/09
 */
class ConsignmentController extends BaseController
{
    protected $viewPath = 'merchant.statistics.consignment';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $merchantID = '';

    public function __construct(SaasOrderFileRepository $orderFileRepository)
    {
        parent::__construct();
        $this->orderFileRepository = $orderFileRepository;
        $this->merchantID = empty(session('admin')) == false ? session('admin')['mch_id'] : ' ';
    }


    /**
     * 功能首页结构view
     * @return mixed
     */
    public function index()
    {
        return view($this->viewPath.'.index');
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
            $Info = $this->orderFileRepository->getTableList($params)->toArray();
            $data = $this->orderFileRepository->consign($Info['data']);

            $htmlContents = $this->renderHtml('',['list' =>$data]);
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
        $Info =  $this->orderFileRepository->getExportTableList($params)->toArray();
        $data =  $this->orderFileRepository->consign($Info);

        $newExcel = new Spreadsheet();//创建一个新的excel文档
        $objSheet = $newExcel->getActiveSheet();  //获取当前操作sheet的对象
        $objSheet->setTitle('交货率统计表');  //设置当前sheet的标题

        //设置自动列宽
        $objSheet->getColumnDimension('A')->setAutoSize(true);
        $objSheet->getColumnDimension('B')->setWidth(15);
        $objSheet->getColumnDimension('C')->setWidth(15);
        $objSheet->getColumnDimension('D')->setWidth(15);
        $objSheet->getColumnDimension('E')->setWidth(20);
        $objSheet->getColumnDimension('F')->setWidth(20);
        $objSheet->getColumnDimension('G')->setWidth(15);

        //设置第一栏的标题
        $objSheet->setCellValue('A1', '订单号')
            ->setCellValue('B1', '订单金额')
            ->setCellValue('C1', '渠道来源')
            ->setCellValue('D1', '店铺来源')
            ->setCellValue('E1', '生产日期')
            ->setCellValue('F1', '发货日期')
            ->setCellValue('G1', '发货率');


        foreach ($data as $k => $v) {
            $k = $k + 2;
            $objSheet->setCellValue('A' . $k, $v['order_no']."\t")
                ->setCellValue('B' . $k, $v['order_amount'])
                ->setCellValue('C' . $k, $v['cha_info'])
                ->setCellValue('D' . $k, $v['shop_info'])
                ->setCellValue('E' . $k, date('Y-m-d H:i:s',$v['submit_time']))
                ->setCellValue('F' . $k, date('Y-m-d H:i:s',$v['shipping_time']))
                ->setCellValue('G' . $k, $v['consign']);
        }

        $this->downloadExcel($newExcel, "交货率统计表", 'Xls');

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
