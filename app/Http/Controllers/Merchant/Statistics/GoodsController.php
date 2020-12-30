<?php
namespace App\Http\Controllers\Merchant\Statistics;

use App\Exceptions\CommonException;
use App\Http\Controllers\Merchant\BaseController;
use App\Repositories\SaasOrderProductsRepository;
use App\Repositories\SaasOrdersRepository;
use App\Repositories\SaasProductsRelationAttrRepository;
use App\Repositories\SaasProductsRepository;
use App\Repositories\SaasProductsSkuRepository;
use App\Services\Helper;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


/**
 * 项目说明 OMS系统
 * 详细说明 OMS系统
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/04/25
 */
class GoodsController extends BaseController
{
    protected $viewPath = 'merchant.statistics.goods';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $merchantID = '';

    public function __construct(SaasOrderProductsRepository $orderProductsRepository,SaasProductsRepository $productsRepository,
                                SaasProductsSkuRepository $productsSkuRepository,SaasOrdersRepository $orderRepository,
                                SaasProductsRelationAttrRepository $productsRelAttrRepository)
    {
        parent::__construct();
        $this->orderProductsRepository = $orderProductsRepository;
        $this->productsRepository = $productsRepository;
        $this->productsSkuRepository = $productsSkuRepository;
        $this->productsRelAttrRepository = $productsRelAttrRepository;
        $this->orderRepository = $orderRepository;
        $this->merchantID = empty(session('admin')) == false ? session('admin')['mch_id'] : ' ';
        $this->totalNum = $orderProductsRepository->getOrderProducts($this->merchantID);
    }


    /**
     * 功能首页结构view
     * @return mixed
     */
    public function index()
    {
        return view($this->viewPath.'.index',['totalNum'=>$this->totalNum]);
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
            $limit = isset($params['limit']) ? $params['limit']:config('common.page_limit');  //这个10取配置里的
            $curPage = isset($params['page']) ? $params['page']:1;
            unset($params['o_status']);
            unset($params['l_status']);
            $params['mch_id'] = $this->merchantID;
            if(isset($params['deli_status']) && $params['deli_status']=='0'){
                $params['deli_status']=0;
            }
            $list = $this->orderProductsRepository->getOrderProdTableList($params);
            $offset = ($curPage-1)*$limit;
            if($limit>count($list)-$offset){
                $limit = count($list);
            };
            $total = count($list);
            $list = array_slice($list,$offset,$limit);
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

            foreach ($product_info_list as $key =>$value){
                $product_info_list[$key]['prod_sale_price'] = round($value['prod_sale_price'],2);
                $product_info_list[$key]['percentage'] = round($value['prod_num']/$this->totalNum['prod_num']*100,2);
            }

            $htmlContents = $this->renderHtml('',['list' =>$product_info_list]);

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
        $params['mch_id'] = $this->merchantID;
        if(isset($params['deli_status']) && $params['deli_status']=='0'){
            $params['deli_status']=0;
        }
        $orderInfo =  $this->orderProductsRepository->getOrderProdTableList($params);

        $newExcel = new Spreadsheet();//创建一个新的excel文档
        $objSheet = $newExcel->getActiveSheet();  //获取当前操作sheet的对象
        $objSheet->setTitle('商品统计表');  //设置当前sheet的标题

        //设置自动列宽
        $objSheet->getColumnDimension('A')->setAutoSize(true);
        $objSheet->getColumnDimension('B')->setAutoSize(true);
        $objSheet->getColumnDimension('C')->setWidth(50);
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

        $product_info_list = [];
        foreach ($orderInfo as $key=>$value) {
            ///货品号
            $product_info['prod_sku_sn'] = $value[0]['prod_sku']['prod_sku_sn'];
            //商品名称
            $product_info['prod_name'] = $value[0]['prod']['prod_name'];
            //货品的属性值
            $prod_attr_str = $this->productsRelAttrRepository->getProductAttr($value[0]['sku_id']);
            $product_info['prod_attr'] = $prod_attr_str;
            $product_info['prod_num'] = 0;
            //货品实收金额
            $product_info['prod_sale_price'] = 0;
            //货品售价
            $product_info['prod_sku_price'] = $value[0]['prod_sku']['prod_sku_price'];
            foreach ($value as $k=>$v){
                //订单中购买的货品的数量
                $product_info['prod_num'] += $v['prod_num'];
                //货品实收金额
                $product_info['prod_sale_price'] += $v['prod_sale_price'];

            }
            $product_info_list[] = $product_info;
        }
        $product_info_list = array_values($product_info_list);
        foreach ($product_info_list as $k => $v) {
            $product_info_list[$key]['prod_sale_price'] = round($v['prod_sale_price'],2);
            $percentage = round($v['prod_num']/$this->totalNum['prod_num']*100,2);
            $k = $k + 2;
            $objSheet->setCellValue('A' . $k, $v['prod_sku_sn'])
                ->setCellValue('B' . $k, $v['prod_name'])
                ->setCellValue('C' . $k, $v['prod_attr'])
                ->setCellValue('D' . $k, $v['prod_num'])
                ->setCellValue('E' . $k, $v['prod_sku_price'])
                ->setCellValue('F' . $k, $v['prod_sale_price'])
                ->setCellValue('G' . $k, $percentage);
        }

        $this->downloadExcel($newExcel, "商品统计表", 'Xls');

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
