<?php
namespace App\Http\Controllers\Merchant\Statistics;

use App\Exceptions\CommonException;
use App\Http\Controllers\Merchant\BaseController;
use App\Models\SaasLogisticsCostQueue;
use App\Repositories\SaasAreasRepository;
use App\Repositories\SaasOrderFileRepository;
use App\Repositories\SaasOrdersRepository;
use App\Services\Goods\Info;
use App\Services\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


/**
 * 项目说明 OMS系统
 * 详细说明 OMS系统
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/05/04
 */
class ExpressController extends BaseController
{
    protected $viewPath = 'merchant.statistics.express';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $merchantID = '';

    public function __construct(SaasOrdersRepository $orderRepository,Info $serviceInfo,
                                SaasOrderFileRepository $orderFileRepository,SaasAreasRepository $areasRepository)
    {
        parent::__construct();
        $this->orderRepository = $orderRepository;
        $this->orderFileRepository = $orderFileRepository;
        $this->areasRepository = $areasRepository;
        $this->serviceInfo = $serviceInfo;
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
            $Info =  $this->orderFileRepository->getTableList($params)->toArray();
            $orderShippingList = $Info['data'];
            foreach ($orderShippingList as $key=>$value){
                $orderShippingList[$key]['area_name'] = "";
                $area = $this->areasRepository->getById($value['rcv_province']);
                if(!empty($area)){
                    $orderShippingList[$key]['area_name'] = $area['area_name'];
                }
                $weight = number_format(ceil($value['product_weight']/1000),2);
                $orderShippingList[$key]['collect_weight'] = $weight;
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
        $orderInfo =  $this->orderFileRepository->getExportTableList($params)->toArray();


        $newExcel = new Spreadsheet();//创建一个新的excel文档
        $objSheet = $newExcel->getActiveSheet();  //获取当前操作sheet的对象
        $objSheet->setTitle('物流对账表');  //设置当前sheet的标题

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

        //设置第一栏的标题
        $objSheet->setCellValue('A1', '订单号')
            ->setCellValue('B1', '店铺来源')
            ->setCellValue('C1', '商品数量')
            ->setCellValue('D1', '商品总质量')
            ->setCellValue('E1', '发货日期')
            ->setCellValue('F1', '下单日期')
            ->setCellValue('G1', '供应商')
            ->setCellValue('H1', '配送方式')
            ->setCellValue('I1', '物流单号')
            ->setCellValue('J1', '配送费用')
            ->setCellValue('K1', '配送区域')
            ->setCellValue('L1', '揽件重量(kg)');


        foreach ($orderInfo as $k => $v) {
            $k = $k + 2;
            $weight = number_format(ceil($v['product_weight']/1000),2);
            $objSheet->setCellValue('A' . $k, $v['order_no']."\t")
                ->setCellValue('B' . $k, $v['shop_info'])
                ->setCellValue('C' . $k, $v['product_nums'])
                ->setCellValue('D' . $k, $v['product_weight'])
                ->setCellValue('E' . $k, date('Y-m-d H:i:s',$v['shipping_time']))
                ->setCellValue('F' . $k, date('Y-m-d H:i:s',$v['order_create_time']))
                ->setCellValue('G' . $k, $v['sp_info'])
                ->setCellValue('H' . $k, $v['express_name'])
                ->setCellValue('I' . $k, $v['delivery_code']."\t")
                ->setCellValue('J' . $k, $v['express_fee'])
                ->setCellValue('K' . $k, $v['province_name'])
                ->setCellValue('L' . $k, $weight);
        }

        $this->downloadExcel($newExcel, "物流对账表", 'Xls');

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

    /**
     * 物流成本导入数据表
     * @author：cjx
     * @version: 1.0
     * @date：2020/08/04
     */
    public function import()
    {
        try{
            \DB::beginTransaction();
            if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                $cost_queue_model = app(SaasLogisticsCostQueue::class);
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
                for ($j = 2; $j <= $highestRow; $j++) {
                    //快递单号
                    $delivery_code = $objPHPExcel->getActiveSheet()->getCell("B" . $j)->getValue();

                    //运费
                    $price = $objPHPExcel->getActiveSheet()->getCell("G" . $j)->getValue();

                    //判断该条记录是否已经保存到数据库
                    $isExit = $cost_queue_model->where(['cost_delivery_code'=>$delivery_code])->first();
                    //如果存在则不再操作
                    if(!empty($isExit)){
                        continue;
                    }

                    $data = [
                        'cost_delivery_code' => trim($delivery_code),
                        'cost_price' => trim($price),
                        'created_at'=>time()
                    ];
                    $ret = $cost_queue_model->insertGetId($data);

                    if(!$ret){
                        \DB::rollBack();
                        return $this->jsonFailed("数据保存出错，已保存0条数据!");
                    }
                    $total +=1;
                }
                \DB::commit();
                return $this->jsonSuccess("已成功导入".$total."条数据！已存在的记录不会再次保存，队列正在同步更新物流成本，请稍候几分钟再刷新页面");
            }
        }catch(CommonException $e){
            \DB::rollBack();
            return $this->jsonFailed($e->getMessage());
        }
    }

}
