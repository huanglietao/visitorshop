<?php
namespace App\Http\Controllers\Agent\Finance;

use App\Http\Controllers\Agent\BaseController;
use App\Presenters\CommonPresenter;
use App\Repositories\SaasAreasRepository;
use App\Repositories\SaasDeliveryRepository;
use App\Repositories\SaasOrderProductsRepository;
use App\Repositories\SaasOrdersRepository;
use App\Repositories\SaasProductsRelationAttrRepository;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * 资金明细
 * @author: liujh
 * @version: 1.0
 * @date: 2020/6/24
 */

class FileExportController extends BaseController
{

    public function __construct(SaasOrdersRepository $ordersRepository,CommonPresenter $commonPresenter,SaasAreasRepository $areasRepository,
                                SaasOrderProductsRepository $orderProductsRepository,SaasProductsRelationAttrRepository $productsRelAttrRepository,
                                SaasDeliveryRepository $deliveryRepository)
    {
        $this->ordersRepository = $ordersRepository;
        $this->commonPresenter = $commonPresenter;
        $this->orderProductsRepository = $orderProductsRepository;
        $this->productsRelAttrRepository = $productsRelAttrRepository;
        $this->areasRepository = $areasRepository;
        $this->deliveryRepository = $deliveryRepository;
        $this->merchantID = empty(session('admin')) == false ? session('admin')['mch_id'] : ' ';
        $this->agentID = empty(session('admin')) == false ? session('admin')['agent_info_id'] : ' ';
        parent::__construct();
    }


    //订单统计导出
    public function ordersExport(Request $request)
    {
        //临时更改限制文件的大小
        ini_set('memory_limit', '2G');
        $search = $request->get('info');
        $params = (array)json_decode($search);
        if(isset($params['status'])){
            unset($params['status']);
        }
        unset($params['o_status']);
        unset($params['l_status']);
        //订单状态
        if(isset($params['order_status'])){
            if($params['order_status']==""){
                $params['order_status'] = null;
            }else{
                $order_status = explode(",",$params['order_status']);
                $params['order_status'] = $order_status;
            }

        }
        //物流状态
        if(isset($params['deli_status'])){
            if($params['deli_status']==""){
                $params['order_status'] = null;
            }else{
                $deli_status = explode(",",$params['deli_status']);
                $params['order_shipping_status'] = $deli_status;
                unset($params['deli_status']);
            }

        }
        $Info =  $this->ordersRepository->getOrderTableList($params,'created_at desc',ONE);

        $newExcel = new Spreadsheet();//创建一个新的excel文档
        $objSheet = $newExcel->getActiveSheet();  //获取当前操作sheet的对象
        $objSheet->setTitle('订单统计表');  //设置当前sheet的标题

        //设置自动列宽
        $objSheet->getColumnDimension('A')->setAutoSize(true);
        $objSheet->getColumnDimension('B')->setAutoSize(true);
        $objSheet->getColumnDimension('C')->setWidth(10);
        $objSheet->getColumnDimension('D')->setWidth(15);
        $objSheet->getColumnDimension('E')->setWidth(10);
        $objSheet->getColumnDimension('F')->setWidth(25);

        //设置第一栏的标题
        $objSheet->setCellValue('A1', '订单号')
            ->setCellValue('B1', '状态')
            ->setCellValue('C1', '数量')
            ->setCellValue('D1', '金额')
            ->setCellValue('E1', '运费')
            ->setCellValue('F1', '下单时间');


        foreach ($Info['data'] as $k=>$v) {
            $prod_nums = 0;
            foreach ($v['item'] as $prod_key => $prod_val) {
                $prod_nums +=$prod_val['prod_num'];
            }
            $orderProductInfo = $this->ordersRepository->orderDetailInfo($v['order_id'])->toArray();
            $k = $k + 2;
            $objSheet->setCellValue('A' . $k, $orderProductInfo['order_no']."\t")
                ->setCellValue('B' . $k, $this->commonPresenter->exchangeOrderStatus($v['order_status']))
                ->setCellValue('C' . $k, $prod_nums)
                ->setCellValue('D' . $k, $orderProductInfo['order_real_total'])
                ->setCellValue('E' . $k, $orderProductInfo['order_exp_fee'])
                ->setCellValue('F' . $k, date('Y-m-d H:i:s',$orderProductInfo['created_at']));

        }

        $this->downloadExcel($newExcel, "订单统计表", 'Xls');

    }

    //商品统计导出
    public function goodsExport(Request $request)
    {
        //临时更改限制文件的大小
        ini_set('memory_limit', '2G');
        $search = $request->get('info');
        $params = (array)json_decode($search);
        if(isset($params['status'])){
            unset($params['status']);
        }
        unset($params['o_status']);
        unset($params['l_status']);
        $totalNum = $this->orderProductsRepository->getOrderProducts($this->merchantID,$this->agentID);

        $params['mch_id'] = $this->merchantID;
        $params['user_id'] = $this->agentID;
        if(isset($params['deli_status']) && $params['deli_status']=='0'){
            $params['deli_status']=0;
        }
        $list = $this->orderProductsRepository->getOrderProdTableList($params);
        $product_info_list = [];

        foreach ($list as $key=>$value) {
            //货品号
            $product_info['prod_sku_sn'] = $value[0]['prod_sku']['prod_sku_sn'];
            //商品名称
            $product_info['prod_name'] = $value[0]['prod']['prod_name'];
            //货品的属性值
            $prod_attr_str = $this->productsRelAttrRepository->getProductAttr($value[0]['sku_id']);
            $product_info['prod_attr'] = $prod_attr_str;
            $product_info['prod_num'] = 0;
            //货品售价
            $product_info['prod_sku_price'] = $value[0]['prod_sku']['prod_sku_price'];
            //货品实收金额
            $product_info['prod_sale_price'] = 0;
            foreach ($value as $k=>$v){
                //订单中购买的货品的数量
                $product_info['prod_num'] += $v['prod_num'];
                //货品实收金额
                $product_info['prod_sale_price'] += $v['prod_sale_price'];

            }
            $product_info_list[$key] = $product_info;

        }

        $newExcel = new Spreadsheet();//创建一个新的excel文档
        $objSheet = $newExcel->getActiveSheet();  //获取当前操作sheet的对象
        $objSheet->setTitle('商品统计表');  //设置当前sheet的标题

        //设置自动列宽
        $objSheet->getColumnDimension('A')->setAutoSize(true);
        $objSheet->getColumnDimension('B')->setAutoSize(true);
        $objSheet->getColumnDimension('C')->setAutoSize(true);
        $objSheet->getColumnDimension('D')->setWidth(15);
        $objSheet->getColumnDimension('E')->setWidth(10);
        $objSheet->getColumnDimension('F')->setWidth(10);
        $objSheet->getColumnDimension('G')->setWidth(10);

        //设置第一栏的标题
        $objSheet->setCellValue('A1', '货号')
            ->setCellValue('B1', '名称')
            ->setCellValue('C1', '属性')
            ->setCellValue('D1', '数量')
            ->setCellValue('E1', '价格')
            ->setCellValue('F1', '金额')
            ->setCellValue('G1', '占比');

        $k=2;
        foreach ($product_info_list as $key => $v) {
            $product_info_list[$key]['prod_sale_price'] = round($v['prod_sale_price'],2);
            if($totalNum['prod_num']==0){
                $percentage = round(0,2);
            }else{
                $percentage = round($v['prod_num']/$totalNum['prod_num']*100,2);
            }
            $objSheet->setCellValue('A' . $k, $v['prod_sku_sn'])
                ->setCellValue('B' . $k, $v['prod_name'])
                ->setCellValue('C' . $k, $v['prod_attr'])
                ->setCellValue('D' . $k, $v['prod_num'])
                ->setCellValue('E' . $k, $v['prod_sku_price'])
                ->setCellValue('F' . $k, $v['prod_sale_price'])
                ->setCellValue('G' . $k, $percentage);
            $k +=1;
        }

        $this->downloadExcel($newExcel, "商品统计表", 'Xls');

    }

    //地区导出
    public function areasExport(Request $request)
    {
        //临时更改限制文件的大小
        ini_set('memory_limit', '2G');
        $search = $request->get('info');
        $params = (array)json_decode($search);
        if(isset($params['status'])){
            unset($params['status']);
        }
        unset($params['o_status']);
        unset($params['l_status']);
        unset($params['limit']);
        $list = $this->ordersRepository->getAreaTableList($params);
        $newExcel = new Spreadsheet();//创建一个新的excel文档
        $objSheet = $newExcel->getActiveSheet();  //获取当前操作sheet的对象
        $objSheet->setTitle('地区统计表');  //设置当前sheet的标题

        //设置自动列宽
        $objSheet->getColumnDimension('A')->setAutoSize(true);
        $objSheet->getColumnDimension('B')->setAutoSize(true);
        $objSheet->getColumnDimension('C')->setWidth(10);
        $objSheet->getColumnDimension('D')->setWidth(15);
        $objSheet->getColumnDimension('E')->setWidth(10);

        //设置第一栏的标题
        $objSheet->setCellValue('A1', '省/直辖市')
            ->setCellValue('B1', '订单数量')
            ->setCellValue('C1', '销售金额')
            ->setCellValue('D1', '客单价')
            ->setCellValue('E1', '占比');

        $k=2;
        foreach ($list['data'] as $key=>$value) {
            $proName = $this->areasRepository->getById($key);
            $totals = 0;
            foreach ($value as $vk=>$vv){
                $totals += $vv['order_real_total'];
            }
            $objSheet->setCellValue('A' . $k, $proName['area_name'])
                ->setCellValue('B' . $k, count($value))
                ->setCellValue('C' . $k, $totals)
                ->setCellValue('D' . $k, number_format($totals/count($value),2))
                ->setCellValue('E' . $k, number_format(count($value)/$list['ordNums']*100,2));
            $k += 1;
        }

        $this->downloadExcel($newExcel, "地区统计表", 'Xls');

    }

    //物流导出
    public function logisticsExport(Request $request)
    {
        //临时更改限制文件的大小
        ini_set('memory_limit', '2G');
        $search = $request->get('info');
        $params = (array)json_decode($search);
        if(isset($params['status'])){
            unset($params['status']);
        }
        unset($params['o_status']);
        unset($params['l_status']);
        unset($params['limit']);

        $list = $this->ordersRepository->getLogisticsTableList($params);

        $newExcel = new Spreadsheet();//创建一个新的excel文档
        $objSheet = $newExcel->getActiveSheet();  //获取当前操作sheet的对象
        $objSheet->setTitle('物流统计表');  //设置当前sheet的标题

        //设置自动列宽
        $objSheet->getColumnDimension('A')->setAutoSize(true);
        $objSheet->getColumnDimension('B')->setAutoSize(true);
        $objSheet->getColumnDimension('C')->setWidth(10);
        $objSheet->getColumnDimension('D')->setWidth(15);

        //设置第一栏的标题
        $objSheet->setCellValue('A1', '物流公司')
            ->setCellValue('B1', '总票数')
            ->setCellValue('C1', '运费小计')
            ->setCellValue('D1', '占比');

        $k=2;
        foreach ($list['data'] as $key=>$value) {
            $expName = $this->deliveryRepository->getById($key);
            $exp_fee = 0;
            foreach ($value as $vk=>$vv){
                $exp_fee += $vv['order_exp_fee'];
            }
            $objSheet->setCellValue('A' . $k, $expName['delivery_name'])
                ->setCellValue('B' . $k, count($value))
                ->setCellValue('C' . $k, $exp_fee)
                ->setCellValue('D' . $k, number_format(count($value)/$list['ordNums']*100,2));


            $k += 1;
        }
        $this->downloadExcel($newExcel, "物流统计表", 'Xls');

    }

    //物流明细导出
    public function logisticsDetailExport(Request $request)
    {
        //临时更改限制文件的大小
        ini_set('memory_limit', '2G');
        $search = $request->get('info');
        $params = (array)json_decode($search);
        if(isset($params['status'])){
            unset($params['status']);
        }
        unset($params['o_status']);
        unset($params['l_status']);

        $list = $this->ordersRepository->getLogisticsDetailTableList($params,ONE);

        $newExcel = new Spreadsheet();//创建一个新的excel文档
        $objSheet = $newExcel->getActiveSheet();  //获取当前操作sheet的对象
        $objSheet->setTitle('物流明细统计表');  //设置当前sheet的标题

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
            ->setCellValue('B1', '下单时间')
            ->setCellValue('C1', '物流公司')
            ->setCellValue('D1', '物流单号')
            ->setCellValue('E1', '发货时间')
            ->setCellValue('F1', '数量')
            ->setCellValue('G1', '总数量')
            ->setCellValue('H1', '地区')
            ->setCellValue('I1', '状态')
            ->setCellValue('J1', '运费')
            ->setCellValue('K1', '订单金额')
            ->setCellValue('L1', '运费占比');


        $express_info_list = [];
        $k=2;
        foreach ($list['data'] as $key=>$value) {
            $expName = $this->deliveryRepository->getById($value['order_delivery_id']);
            $products = $this->orderProductsRepository->getList(['ord_id'=>$value['order_id']])->toArray();
            $nums = 0;
            foreach ($products as $pk =>$pv){
                $nums += $pv['prod_num'];
            }
            //省市区转换
            $province = $this->areasRepository->getAreaIdList($value['order_rcv_province'])->toArray();
            $city = $this->areasRepository->getAreaIdList($value['order_rcv_city'])->toArray();
            $area = $this->areasRepository->getAreaIdList($value['order_rcv_area'])->toArray();
            $province = !empty($province) ? $province['area_name'] : '';
            $city = !empty($city) ? $city['area_name'] : '';
            $area_name = !empty($area) ? $area['area_name'] : '';

            $express_info_list[$key]['order_status'] = $value['order_status'];
            $express_info_list[$key]['order_exp_fee'] = $value['order_exp_fee'];
            $express_info_list[$key]['order_real_total'] = $value['order_real_total'];
            $mix_exp_fee = 0;

            if($value['order_exp_fee']!='0.00'){
                $mix_exp_fee = number_format($value['order_exp_fee']/$list['expFees'],2);
            }
            $shipping_time = null;
            if(!empty($value['order_shipping_time'])){
                $shipping_time = date('Y-m-d H:i:s',$value['order_shipping_time']);
            }

            $objSheet->setCellValue('A' . $k, $value['order_no']."\t")
                ->setCellValue('B' . $k, date('Y-m-d H:i:s',$value['created_at']))
                ->setCellValue('C' . $k, $expName['delivery_name'])
                ->setCellValue('D' . $k, $value['delivery_code']."\t")
                ->setCellValue('E' . $k, $shipping_time)
                ->setCellValue('F' . $k, count($products))
                ->setCellValue('G' . $k, $nums)
                ->setCellValue('H' . $k, $province.'-'.$city.'-'.$area_name)
                ->setCellValue('I' . $k, $this->commonPresenter->exchangeOrderStatus($value['order_status']))
                ->setCellValue('J' . $k, $value['order_exp_fee'])
                ->setCellValue('K' . $k, $value['order_real_total'])
                ->setCellValue('L' . $k, $mix_exp_fee);
            $k += 1;
        }
        $this->downloadExcel($newExcel, "物流明细统计表", 'Xls');

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



}