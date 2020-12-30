<?php
namespace App\Http\Controllers\Backend\Goods;

use App\Http\Controllers\Backend\BaseController;
use App\Http\Requests\Backend\Goods\ProductsRequest;
use App\Models\SaasProductsSku;
use App\Repositories\SaasProductsRepository;
use Illuminate\Http\Request;
use App\Repositories\SaasCategoryRepository;
use App\Repositories\SaasBrandRepository;
use App\Repositories\SaasDeliveryTemplateRepository;
use App\Repositories\SaasSalesChanelRepository;
use App\Repositories\SaasSuppliersRepository;
use App\Repositories\SaasProductsSizeRepository;
use App\Services\Goods\Info;
use Illuminate\Support\Facades\DB;
use App\Repositories\SaasCustomerLevelRepository;

/**
 * 项目说明
 * 详细说明
 * @author:
 * @version: 1.0
 * @date:
 */
class ProductsController extends BaseController
{
    protected $viewPath = 'backend.goods.products';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(SaasProductsRepository $Repository,
                      SaasCategoryRepository $cateRepository,SaasBrandRepository $brandRepository,
                      SaasDeliveryTemplateRepository $deliveryRepository,SaasSalesChanelRepository $chanelRepository,
                      SaasSuppliersRepository $suppliersRepository,SaasProductsSizeRepository $sizeRepository,
                      Info $goodsServices, SaasCustomerLevelRepository $customerLevelRepository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->cateRepository = $cateRepository;
        $this->brandRepository = $brandRepository;
        $this->deliveryRepository = $deliveryRepository;
        $this->chanelRepository = $chanelRepository;
        $this->suppliersRepository = $suppliersRepository;
        $this->sizeRepository   = $sizeRepository;
        $this->goodsServices    = $goodsServices;
        $this->customerLevelRepository = $customerLevelRepository;

    }

    /**
     * 功能首页结构view
     * @return mixed
     */
    protected function index()
    {
        //获取商品分类
        $categoryList = $this->cateRepository->getLevelCateList('goods',CATEGORY_NO_THREE);

        return view('backend.goods.products.index',['categoryList' => $categoryList]);
    }


    /**
     * ajax获取列表项
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function table(Request $request)
    {

        try {

            $inputs = $request->all();

            $inputs['mch_id'] = PUBLIC_CMS_MCH_ID;
            $list = $this->repositories->getTableList($inputs);

            //获取品牌跟分类类目
            $list['data'] = $this->repositories->organizeData($list['data']);

            $htmlContents = $this->renderHtml('',['list' =>$list['data']]);

            $total = $list['total'];

            return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);

        } catch (CommonException $e) {
            //统一收集错误再做处理
            var_dump($e->getMessage());
        }

    }



    /**
     * 通用表单展示
     * @param Request $request
     * @return mixed
     */
    protected function form(Request $request)
    {
        try {
            $userInfo = session('admin');
            //获取一级分类
            $firstCategory = $this->cateRepository->getCategoryList();
            //获取品牌列表
            $brandList = $this->brandRepository->getBrandList();
            //获取物流模板列表
            $deliveryList = $this->deliveryRepository->getList(['del_temp_status' => PUBLIC_ENABLE,'mch_id' => PUBLIC_CMS_MCH_ID],'created_at')->toArray();
            //获取退货标识
            $returnGoodsArr = config('goods.return_goods')??[];
            //获取售后服务数组
            $afterSaleArr  = config('goods.after_sale')??[];
            //获取商品标签数组
            $goodsLabelArr = config('goods.goods_label')??[];

            //获取渠道列表
            $chanelList = $this->chanelRepository->getList()->toArray();
            //获取供应商列表
            $supplierList = $this->suppliersRepository->getSupplierList(PUBLIC_CMS_MCH_ID);
            //获取规格列表
            $productSizeList = $this->sizeRepository->getPSizeList(['mch_id' => PUBLIC_CMS_MCH_ID]);



            //添加商品分类视图
            $cateHtmlContents = $this->renderHtml('backend.goods.products.add.product_category',
                [
                    'firstCategory' => $firstCategory,
                    'adminId' => $userInfo['cms_adm_id'],
                    'personalPrint' => PERSONAL_PRINTING_ID, //传入影像类所属id
                    'commercialPrint' => COMMERCIAL_PRINTING_ID //传入商务印刷类所属id

                ]);
            //添加商品信息视图
            $infoHtmlContents = $this->renderHtml('backend.goods.products.add.product_info',
                [
                    'brandList'        => $brandList,
                    'deliveryList'     => $deliveryList,
                    'returnGoodsArr'   => $returnGoodsArr,
                    'afterSaleArr'     => $afterSaleArr,
                    'goodsLabelArr'    => $goodsLabelArr,
                    'afterSaleArr'     => $afterSaleArr,
                    'goodsLabelArr'    => $goodsLabelArr,
                    'chanelList'       => $chanelList,
                    'supplierList'     => $supplierList,
                ]);
            //商品sku视图
            $attrHtmlContents = $this->renderHtml('backend.goods.products.add.product_attr',
                [
                    'productSizeList'  => $productSizeList
                ]);


          return view($this->viewPath.'.'.'_form',['goodsCategory' => $cateHtmlContents,'goodsInfo' => $infoHtmlContents,'goodsAttr' => $attrHtmlContents]);

        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

    }
    //获取规格列表
    public function getCateSize(Request $request)
    {
        $post = $request->post();
        //获取分类下的规格列表
        $productSizeList = $this->sizeRepository->getPSizeList(['mch_id' => PUBLIC_CMS_MCH_ID,'size_cate_id' => $post['cate_id']]);
        return $productSizeList;
    }

    /**
     * 删除记录(软删除)
     * @param Request $request
     * @return bool
     */
    protected function delete(Request $request)
    {
        $skuModel = app(SaasProductsSku::class);
        $ret = $this->repositories->delete($request->id);
        //还得删除对应的sku记录
        $skuModel->where('prod_id',$request->id)->delete();
        if($ret) {
            return $this->jsonSuccess(['']);
        } else {
            return $this->jsonFailed("");
        }
    }

        //页面上下架
    public function onsale(Request $request)
    {
        $post = $request->post();
        //组织更新条件
        $where = [
            'prod_id' => $post['prod_id']
        ];
        $updata = [
            'prod_onsale_status' => $post['prod_onsale_status']
        ];


        $this->repositories->updataField($where,$updata);
        return $this->jsonSuccess([]);
    }
    //修改商品排序
    public function changeSort(Request $request)
    {
        $post = $request->post();
        $res = $this->repositories->update(['prod_id' => $post['prod_id']],['sort' => $post['sort']]);
        if ($res){
            return $this->jsonSuccess(['']);
        }else{
            return $this->jsonFailed("");
        }

    }


   //添加操作
    public function save(ProductsRequest $request)
    {
        $post = $request->post();
        //组织插入各个表中的数据
        $res = $this->repositories->setGoods($post);

        if (isset($res['code']) && $res['code'] == 0){
            return $this->jsonFailed($res['msg']);
        }else{
            return $this->jsonSuccess([]);
        }

    }

    //商品编辑
    public function edit(Request $request)
    {

        try {
        $prodId = $request->route('id');
        $prodInfo = $this->repositories->getProductsRelationInfo($prodId);
        //获取品牌列表
        $brandList = $this->brandRepository->getBrandList();
        //获取物流模板列表
        $deliveryList = $this->deliveryRepository->getList(['del_temp_status' => PUBLIC_ENABLE,'mch_id' => PUBLIC_CMS_MCH_ID],'created_at')->toArray();
        //获取退货标识
        $returnGoodsArr = config('goods.return_goods')??[];
        //获取售后服务数组
        $afterSaleArr  = config('goods.after_sale')??[];
        //获取商品标签数组
        $goodsLabelArr = config('goods.goods_label')??[];
        //获取渠道列表
        $chanelList = $this->chanelRepository->getList()->toArray();
        //获取供应商列表
        $supplierList = $this->suppliersRepository->getSupplierList(PUBLIC_CMS_MCH_ID);

        //获取规格列表
        $productSizeList = $this->sizeRepository->getPSizeList(['mch_id' => PUBLIC_CMS_MCH_ID,'size_cate_id'=>$prodInfo['prod_cate_uid']]);

            //编辑商品信息视图
        $infoHtmlContents = $this->renderHtml('backend.goods.products.edit.product_info',
            [
                'brandList'        => $brandList,
                'deliveryList'     => $deliveryList,
                'returnGoodsArr'   => $returnGoodsArr,
                'afterSaleArr'     => $afterSaleArr,
                'goodsLabelArr'    => $goodsLabelArr,
                'afterSaleArr'     => $afterSaleArr,
                'goodsLabelArr'    => $goodsLabelArr,
                'chanelList'       => $chanelList,
                'supplierList'     => $supplierList,
                'prodList'         => $prodInfo
            ]);




        //商品sku视图
        $attrHtmlContents = $this->renderHtml('backend.goods.products.edit.product_attr',
            [
                'productSizeList'  => $productSizeList,
                'prodList'  => $prodInfo
            ]);




            return view($this->viewPath.'.'.'_edit',['goodsInfo' => $infoHtmlContents,'goodsAttr' => $attrHtmlContents]);
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }
    }

    //商品信息更新
    public function update(ProductsRequest $request)
    {
        $post = $request->post();

        //组织插入各个表中的数据
        $res = $this->repositories->setGoods($post,'edit');

        if (isset($res['code']) && $res['code'] == 0){
            return $this->jsonFailed($res['msg']);
        }else{
            return $this->jsonSuccess([]);
        }
    }
    //查询商品分类
    public function categorySearch(Request $request)
    {
        $post = $request->post();
        $data = $this->cateRepository->getCategoryList($post['value'],$post['level'],$post['parent_level']);
       return $data;
    }

    //获取商品属性值
    public function getAttribute(Request $request)
    {
        $post = $request->post();
        $data = $this->repositories->getAttributeValue(PUBLIC_CMS_MCH_ID,$post['cate_id'],$post['is_add_page']);
        //获取sku显示视图
        //添加商品信息视图
        $HtmlContents = $this->renderHtml('backend.goods.products.add.product_sku_comb',
            [
                'attrList'        => $data,
            ]);
        return $this->jsonSuccess(['html' => $HtmlContents]);
    }

    //获取sku渲染列表
    public function getSkuTable(Request $request)
    {
        $post = $request->post();
        $attrIdArr = json_decode($post['attr_id_arr'],true);
        $attrValueArr = json_decode($post['attr_value_arr'],true);
        if (empty($attrIdArr)&&$post['is_sku'] == '2'){
            //没选sku
            $msg = "请选择商品属性";
            return $this->jsonFailed($msg);
        }

        //将属性内的每个sku数组转为字符串
        foreach ($attrIdArr as $k => $v)
        {

            if (!is_array($attrIdArr[$k]))
            {
                //单个属性情况
                $attrIdArr[$k] = [$v];
            }
            $attrIdArr[$k]['attr_str'] = implode(',',$attrIdArr[$k]);
        }
        foreach ($attrValueArr as $k => $v)
        {
            if (!is_array($attrValueArr[$k]))
            {
                //单个属性情况
                $attrValueArr[$k] = [$v];
            }
            $attrValueArr[$k]['attr_str'] = implode(',',$attrValueArr[$k]);

        }

        $HtmlContents = $this->renderHtml('backend.goods.products.add.product_sku_table',[
            'attr_id_arr'               => $attrIdArr,
            'attr_value_arr'            => $attrValueArr,
            'is_sku'                    => $post['is_sku'],
            'attr_p_value'              => $post['attr_p_value'],
            'min_p'                     => $post['min_p']??"",
            'is_add_page'               => $post['is_add_page']??0,
            'p_rule'                    => $post['p_rule']??"",
            'is_personal_print'         => $post['is_personal_print']??0,
        ]);
        return $this->jsonSuccess(['html' => $HtmlContents]);
    }

    public function salesPriceForm(Request $request)
    {
        $post = $request->post();
        $channleList = [];
        //获取渠道用户等级
        foreach ($post['channle'] as $k=>$v)
        {
            $channleList[$v['cha_id']]['channle_name'] = $this->chanelRepository->getChanleName($v['cha_id']);
            $channleList[$v['cha_id']]['customer'] = $this->customerLevelRepository->getGrade(PUBLIC_CMS_MCH_ID,$v['cha_flag']);
        }


        if ($post['is_edit']){
            //已写入价格，需要还原之前的价格页面
            $channleList = $this->repositories->recovePrice($channleList,json_decode($post['sale_channle_price'],true));
        }
        $HtmlContents = $this->renderHtml('backend.goods.products.add.sales_price_form',[
            'list'        => $post,
            'channleList' => $channleList
        ]);
        return $this->jsonSuccess(['html' => $HtmlContents]);
    }

    //供货定价

    public function supplierPriceForm(Request $request)
    {
        $post = $request->post();



        if ($post['is_edit']){
            //已写入价格，需要还原之前的价格页面
            $post['supplier'] = $this->repositories->recoveSupplierPrice($post['supplier'],json_decode($post['supplier_price'],true));
        }


        $HtmlContents = $this->renderHtml('backend.goods.products.add.supplier_price_form',[
            'list'        => $post,
        ]);
        return $this->jsonSuccess(['html' => $HtmlContents]);

    }
    //自定义规格页面
    public function CustomProductSizeView(Request $request)
    {

        try {
            if($request->ajax())
            {
                //判断是否有商品id 有则编辑，无则查看
                if ($request->input('prod_id'))
                {
                    $action = 'edit';
                    $prod_id = $request->input('prod_id');
                }else{
                    $action = 'show';
                    $prod_id = "";
                }


                //获取规格标签
                $sizeTypeList = config('goods.size_type');
                //获取单双页数组
                $isTurn = config('goods.is_turn');
                $sizeInfoArr = [];
                //获取该规格的子页类型
                $sizeAllInfo = $this->sizeRepository->getPageTypeAndInfo($request->input('id'),$prod_id);
                $pageType = $sizeAllInfo['pageType'];
                $sizeInfoArr = $sizeAllInfo['allSizeTypeInfo'];



                $row = $this->sizeRepository->getByIdFromCache($request->input('id'));

                $htmlContents = $this->renderHtml($this->viewPath.'.custom_prodsize_form', ['row' => $row,'sizeTypeList' =>$sizeTypeList,'isTurn'=>$isTurn,'pageType' =>$pageType,'sizeInfoArr'=>$sizeInfoArr,'action'=>$action,'prod_id'=>$prod_id]);

                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }
    }
    //自定义规格保存
    public function CustomProductSizeSave(Request $request)
    {

        $data = $request->all();

        $ret = $this->repositories->customSizeSave($data);

        if ($ret['code']) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed($ret['msg']);
        }
    }

}