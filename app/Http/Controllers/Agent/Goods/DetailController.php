<?php
namespace App\Http\Controllers\Agent\Goods;

use function AlibabaCloud\Client\json;
use App\Exceptions\CommonException;
use App\Http\Controllers\Agent\BaseController;
use App\Http\Controllers\Agent\UploadController;
use App\Models\SaasCategory;
use App\Models\SaasOuterErpOrderCreate;
use App\Models\SaasProdToCustLevel;
use App\Models\SaasProducts;
use App\Models\SaasProductsSku;
use App\Repositories\AgentRepository;
use App\Repositories\CommercialTempRepository;
use App\Repositories\DmsAgentInfoRepository;
use App\Repositories\SaasCartRepository;
use App\Repositories\SaasCategoryRepository;
use App\Repositories\SaasDeliveryRepository;
use App\Repositories\SaasDownloadQueueRepository;
use App\Repositories\SaasExpressRepository;
use App\Repositories\SaasMainTemplatesRepository;
use App\Repositories\SaasManuscriptRepository;
use App\Repositories\SaasProductsPrintRepository;
use App\Repositories\SaasProductsRelationAttrRepository;
use App\Repositories\SaasProductsRepository;
use App\Repositories\SaasProductsSkuRepository;
use App\Repositories\SaasProjectsOrderTempRepository;
use App\Repositories\SaasProjectsRepository;
use App\Repositories\SaasSalesChanelRepository;
use App\Repositories\SaasSizeInfoRepository;
use App\Repositories\SaasTemplateTagsRepository;
use App\Repositories\TempSizeRelationRepository;
use App\Services\Goods\Info;
use App\Services\Goods\Price;
use App\Services\Helper;
use App\Services\Outer\CommonApi;
use App\Services\Works\WorksAbstract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

/**
 * 商品分类
 *
 * @author: cjx <781714246@qq.com>
 * @version: 1.0
 * @date: 2019/8/7
 */

class DetailController extends BaseController
{

    protected $noCookie = 'template,get_template,get_m_template,work_upload,fileUpload,delete_pdf,filesave,checkPage';

    public function __construct(SaasProductsRepository $productsRepository,DmsAgentInfoRepository $dmsAgentInfoRepository,
        SaasProductsRelationAttrRepository $productsRelAttrRepository,SaasProductsSkuRepository $productsSkuRepository,
                                SaasCategoryRepository $categoryRepository,SaasMainTemplatesRepository $mainTemplatesRepository,
                                Price $price,SaasSizeInfoRepository $sizeInfoRepository,Info $info,WorksAbstract $worksAbstract,
                                SaasProjectsRepository $projectsRepository,SaasManuscriptRepository $manuscriptRepository,
                                SaasDownloadQueueRepository $downloadQueueRepository,SaasCategory $categoryModel,
                                SaasProductsSku $skuModel,SaasProductsPrintRepository $productsPrintRepository,
                                SaasTemplateTagsRepository $templateTagsRepository,CommercialTempRepository $repoCommer,
                                SaasProducts $products,SaasSalesChanelRepository $chanelRepository,SaasProdToCustLevel $prodToCustLevel,
                                SaasCartRepository $cartRepository,TempSizeRelationRepository $tempSizeRelation
    )
    {
        parent::__construct();
        $this->productsRepository = $productsRepository;
        $this->dmsAgentInfoRepository = $dmsAgentInfoRepository;
        $this->productsRelAttrRepository = $productsRelAttrRepository;
        $this->productsSkuRepository = $productsSkuRepository;
        $this->categoryRepository = $categoryRepository;
        $this->mainTemplatesRepository = $mainTemplatesRepository;
        $this->price = $price;
        $this->info = $info;
        $this->sizeInfoRepository = $sizeInfoRepository;
        $this->worksAbstract = $worksAbstract;
        $this->projectsRepository = $projectsRepository;
        $this->manuscriptRepository = $manuscriptRepository;
        $this->downloadQueueRepository = $downloadQueueRepository;
        $this->categoryModel = $categoryModel;
        $this->skuModel = $skuModel;
        $this->productsPrintRepository = $productsPrintRepository;
        $this->templateTagsRepository = $templateTagsRepository;
        $this->commerRepo = $repoCommer;
        $this->products = $products;
        $this->chanelRepository = $chanelRepository;
        $this->prodToCustLevel = $prodToCustLevel;
        $this->cartRepository = $cartRepository;
        $this->tempSizeRelationRepo = $tempSizeRelation;
        $this->merchantID = empty(session('admin')) == false ? session('admin')['mch_id'] : ' ';
        $this->agentID = empty(session('admin')) == false ? session('admin')['agent_info_id'] : ' ';
    }
    public function index()
    {

        return view('agent.goods.detail.index');
    }

    /**
     * 列表测试
     */
    public function table(Request $request)
    {
        try{
            $product_id = $request->route("product_id");

            //获取商品信息
            $product_info = $this->productsRepository->getProductInfo($this->merchantID,$product_id);

            //判断该商品是否为商务印品类商品 by dai
            $is_commercial = 'NO';
            $commerArr = $this->categoryRepository->getCommercialList(ONE);
            if(in_array($product_info[0]['prod_cate_uid'],$commerArr)){
                $is_commercial = 'YS';
            }

            //获取商品分类标识  by hlt
            $cate_type = $this->categoryModel->where(['cate_id' => $product_info[0]['prod_cate_uid']])->value('cate_flag');
            //判断是否是实物
            $is_entity = 0;
            if ($cate_type == GOODS_MAIN_CATEGORY_ENTITY){
                $is_entity = 1;
            }
            $product = $product_info[0];
            //获取商品定价类型
            $sku_onsale = 1;
            $spu_onsale = 0;
            $price_type = $product['prod_price_type'];
            if ($product['prod_price_type'] != SKU)
            {
                //spu 直接获取该spu商品对应货品的开卖状态
                $sku_id = $this->skuModel->where(['prod_id' => $product_id, 'prod_attr_comb' => ''])->select('prod_sku_id','prod_sku_onsale_status')->get()->toArray();
                if(empty($sku_id)){
                    echo "该商品不存在";
                    die;
                }
                $spu_onsale = $sku_id[0]['prod_sku_onsale_status'];
                $sku_onsale = 0;
            }

            //获取商品属性值
            $attr_info_list = $this->productsRelAttrRepository->getAttribute($product_id);
            $rel_attr_ids = [];
            //默认选中第一个属性
            if(!empty($attr_info_list)){
                foreach ($attr_info_list as $attr_k => $attr_v){
                    $rel_attr_ids[] = $attr_v['rel_attr_id'][0];
                }
                asort($rel_attr_ids);
                $rel_attr_ids = implode(",",$rel_attr_ids);
            }
            //获取该属性的商品是否开卖
            if(!empty($rel_attr_ids)){
                $prod_sku_id = $this->productsSkuRepository->getSkuId($product_id,$rel_attr_ids);
                if(empty($prod_sku_id)){
                    $sku_onsale = 0;
                }
            }

            //获取等级
            $cust_lv_id = $this->dmsAgentInfoRepository->getCustLvId($this->agentID);
            //获取货品价格
            $prod_sku_ids = $this->productsSkuRepository->getProductPrice($product_id);
            $product_fee = [];
            foreach ($prod_sku_ids as $prod_k => $prod_v){
                $fee = $this->price->getChanelPrice($prod_v,$cust_lv_id);
                array_push($product_fee,$fee);
            }
            if(!empty($product['prod_aftersale_flag'])){
                $product['prod_aftersale_flag'] = explode(",",$product['prod_aftersale_flag']);
            }

            $product['product_price'] = $product_fee;
            $product['mid'] = $this->merchantID;
            $product['aid'] = $this->agentID;
            $after_sale = config("goods.after_sale");
            //获取分销渠道id by hlt
            $cha_id = $this->chanelRepository->getAgentChannleId();
            // .david 获取该商品同分类的四条商品推荐
            $recommendPro = $this->productsRepository->getProducts($product['prod_cate_uid'],$this->merchantID);

            foreach ($recommendPro as $k=>$v){
                //判断该商品对应该账号的分销等级是否开卖 by hlt
                $is_sale = $this->prodToCustLevel->where(['prod_id' => $v['prod_id'],'cha_id' => $cha_id,'cust_lv_id'=> $cust_lv_id])->exists();

                if (!$is_sale){
                    //不开卖，则不显示该商品
                    unset($recommendPro[$k]);
                    continue;
                }

                $product_info = $this->productsRepository->getProductInfo($this->merchantID,$v['prod_id']);

                $recommendPro[$k]=$product_info[0];
                //获取等级
                $cust_lv_id = $this->dmsAgentInfoRepository->getCustLvId($this->agentID);
                //获取货品价格
                $prod_sku_ids = $this->productsSkuRepository->getProductPrice($v['prod_id']);
                $product_fee = [];
                foreach ($prod_sku_ids as $prod_k => $prod_v){
                    $fee = $this->price->getChanelPrice($prod_v,$cust_lv_id);
                    array_push($product_fee,$fee);
                }
                $recommendPro[$k]['product_price'] = min($product_fee);
                if($v['prod_id'] == $product_id){
                    unset($recommendPro[$k]);
                }
            }
            $recommendPro = array_slice($recommendPro,0,4);
            //$recommendPro = array_values($recommendPro);

            return view('agent.goods.detail.index',['product'=>$product,'rel_attr_ids'=>$rel_attr_ids,'sku_onsale'=>$sku_onsale,'attr_info'=>$attr_info_list,'is_entity' => $is_entity,'spu_onsale' => $spu_onsale,'price_type' => $price_type,'after_sale'=>$after_sale,'recommendPro'=>$recommendPro,'is_comprint'=>$is_commercial]);
        }catch (CommonException $e){
            $this->jsonFailed($e->getMessage());
        }

    }


    public function getPrice(Request $request)
    {
        try{
            $params = $request->all();
            $rel_attr = explode(",",$params['rel_attr_id']);
            asort($rel_attr);
            $prod_attr_comb = implode(",",$rel_attr);
            $prod_sku_id = $this->productsSkuRepository->getSkuId($params['prod_id'],$prod_attr_comb);
            if(empty($prod_sku_id)){
                return response()->json(['status' => 201]);
            }
            $prod_sku_id = $prod_sku_id[0]['prod_sku_id'];
            $cust_lv_id = $this->dmsAgentInfoRepository->getCustLvId($this->agentID);
            $product_fee = $this->price->getChanelPrice($prod_sku_id,$cust_lv_id);

            return response()->json(['status' => 200, 'price' => $product_fee]);
        }catch (CommonException $e){
            $this->jsonFailed($e->getMessage());
        }
    }


    //制作链接弹窗
    public function tips(Request $request)
    {
        $post = $request->all();
        $prod_id = $post['prod_id'];
        //货品属性
        $prod_attr_comb = $post['prod_attr_comb'];
        //货品属性
        $prod_attr_comb = explode(",",$prod_attr_comb);
        asort($prod_attr_comb);
        $prod_attr_comb = implode(",",$prod_attr_comb);
        //获取sku_id
        $prod_sku_info = $this->skuModel->where(['prod_id' => $prod_id, 'prod_attr_comb' => $prod_attr_comb,'prod_sku_onsale_status'=>1])->first();
        if (empty($prod_sku_info)){
            $sku_id = "";
        }else{
            $sku_id = $prod_sku_info['prod_sku_id'];
        }

        if($post['comprint_flag']=='YS'){
            $data = [
                /*"url"=>"http://".config("app.agent_url")."/goods/detail/comltemplate?mid=".$this->merchantID."&aid=".$this->agentID."&prod_id=".$prod_id."&prod_attr_comb=".$prod_attr_comb."",*/
                "url"=>"http://".config("app.agent_url")."/ct/".$sku_id."/".$this->agentID,
            ];
        }else{
            $data = [
                /*"url"=>"http://".config("app.agent_url")."/goods/detail/template?mid=".$this->merchantID."&aid=".$this->agentID."&prod_id=".$prod_id."&prod_attr_comb=".$prod_attr_comb."",*/
                "url"=>"http://".config("app.agent_url")."/t/".$sku_id."/".$this->agentID,
            ];
        }

        $content = $this->renderHtml("agent.goods.detail.tips",['data' =>$data]);
        return response()->json(['status' => 200, 'html' => $content]);

    }

    //进入模板市场页面
    public function template(Request $request)
    {
        try{
            $params = $request->all();
            $order_no = isset($params['order_no'])?$params['order_no']:'';
            //添加短链接判断 by hlt
            if ($request->route('sku_id')){
                //从短链接过来的,为不影响之前流程，转化为长链接所需要的数据，
                $sku_id = $request->route('sku_id');
                $skuInfo = $this->skuModel->where(['prod_sku_id'=>$sku_id,'prod_sku_onsale_status'=>1])->select('prod_id','prod_attr_comb')->first();
                if (empty($skuInfo)){
                    $msg = "该货品未开卖";
                    return view('agent.goods.detail.template',['msg'=>$msg]);
                }
                //商品id
                $prod_id = $skuInfo['prod_id'];
                //获取商家id
                $mid = $this->products->where('prod_id',$prod_id)->value('mch_id');
                if (is_null($mid)){
                    $msg = "该商品不存在";
                    return view('agent.goods.detail.template',['msg'=>$msg]);
                }
                //获取属性信息
                $prod_attr_comb = $skuInfo['prod_attr_comb'];
                //获取分销id
                if (!$request->route('agent_id')){
                    $msg = "该分销商不存在";
                    return view('agent.goods.detail.template',['msg'=>$msg]);
                }
                $aid = $request->route('agent_id');
                $msg = "";
            }else{
                if(!isset($params['prod_id']) || !isset($params['mid']) || !isset($params['aid']) || !isset($params['prod_attr_comb'])){
                    $msg = "该链接不存在";
                    return view('agent.goods.detail.template',['msg'=>$msg]);
                }
                //商品id
                $prod_id = $params['prod_id']??0;
                $mid = $params['mid'];
                $aid = $params['aid'];
                //提示信息
                $msg = "";
                //货品属性
                $prod_attr_comb = explode(",",$params['prod_attr_comb']);
                asort($prod_attr_comb);
                $prod_attr_comb = implode(",",$prod_attr_comb);
            }
            //获取货品id和货品P数
            $prodSkuInfo = $this->productsSkuRepository->getProdSku($prod_id,$prod_attr_comb);
            if(!isset($prodSkuInfo['prod_sku_id'])){
                $msg = "该货品未开卖";
                return view('agent.goods.detail.template',['msg'=>$msg]);
            }

            $prod_info = $this->productsRepository->getProductInfo($mid,$prod_id);
            if (!$prod_info){
                $msg = "该链接存在问题，请联系客服重新获取";
                return view('agent.goods.detail.template',['msg'=>$msg]);
            }
            //获取商品的规格id
            $prod_print = $this->productsPrintRepository->getProductPrint($prod_id,$mid);
            $prod_size_id = $prod_print['prod_size_id'];
            //分类id
            $prod_cate_uid = $prod_info[0]['prod_cate_uid'];

            //判断端口为手机端还是pc端
            $is_mobile = $this->isMobile();

                //获取特殊分类的IDS 冲印和摆台&插画&框画
                $specialIds = config('goods.special_ids');
                //如果该商品属于这两个分类，则直接跳转到制作页面，不用经过模板市场
                if(in_array($prod_cate_uid,$specialIds)){
                    $template_info=$this->mainTemplatesRepository
                        ->getTableList(['mch_id'=>[0,$mid],'goods_type_id'=>$prod_cate_uid,
                            'specifications_id'=>$prod_size_id,'main_temp_check_status'=>TEMPLATE_STATUS_VERIFYED],
                            "main_temp_use_times desc",16)->toArray();
                    if(empty($template_info['data'])){
                        return view('agent.goods.detail.template',['msg'=>'该商品暂时不存在模板']);
                    }
                    $template_ids = $template_info['data'][0]['main_temp_id'];
                    //如果是冲印类的
                    if(!$is_mobile && $prod_cate_uid==$specialIds['single']){
                        $url = "http://".config("app.agent_url")."/printer/index.html?sp=".$mid."&a=".$aid."&g=".$prod_id."&p=".$prodSkuInfo['prod_sku_id']."&t=".$template_ids."&pc=0&order_no=".$order_no."";
                    }else{
                        if($is_mobile){
                            $url = "http://".config("app.agent_url")."/ds_m/?sp=".$mid."&a=".$aid."&g=".$prod_id."&p=".$prodSkuInfo['prod_sku_id']."&t=".$template_ids."&pc=0&order_no=".$order_no."";
                        }else{
                            $url = "http://".config("app.agent_url")."/ds/ed.html?sp=".$mid."&a=".$aid."&g=".$prod_id."&p=".$prodSkuInfo['prod_sku_id']."&t=".$template_ids."&pc=0&order_no=".$order_no."";
                        }

                    }
                    return redirect($url);
                }

            //获取商品简称，有则取商品简称，没有就取商品名称
            if (!empty($prod_info[0]['prod_abbr']))
            {
                $prod_info[0]['prod_name'] = $prod_info[0]['prod_abbr'];
            }
            //获取店铺名称
            $shop_name = $this->dmsAgentInfoRepository->getTableList(['agent_info_id'=>$aid])->toArray();
            if (!$shop_name['data']){
                $msg = "该分销商不存在";
                return view('agent.goods.detail.template',['msg'=>$msg]);
            }
            $shop_name = $shop_name['data'][0]['agent_name'];

            $product_info = [
                'agent_name'=>$shop_name,
                'prod_id'=>$prod_info[0]['prod_id'],
                'prod_name'=>$prod_info[0]['prod_name'],
                'sku_id'=>$prodSkuInfo['prod_sku_id'],
                'prod_p_num'=>$prodSkuInfo['prod_p_num'],
                'mid'=>$mid,
                'aid'=>$aid,
                'order_no'=>$order_no
            ];
            $categoryArr = $this->categoryRepository->getTList('template');
            $template_info=$this->mainTemplatesRepository->getTableList(['mch_id'=>[0,$mid],'goods_type_id'
            =>$prod_cate_uid,'specifications_id'=>$prod_size_id,'main_temp_check_status'=>TEMPLATE_STATUS_VERIFYED],"main_temp_use_times desc",16)->toArray();
            $template = $template_info['data'];


            //获取是否加减p dai 6-30
            $productPrint = $this->productsPrintRepository->getRow(['prod_id'=>$prod_id]);
            if($productPrint['prod_pt_variable']==ONE){
                $photo = $productPrint['prod_pt_min_p'];
            }else{
                $standardSkuAttr = $this->info->getStandardSkuAttr($prodSkuInfo['prod_sku_id']);
                //获取当前skuP数信息
                $photo = 0;
                if (!empty($standardSkuAttr)) {
                    $attrPageInfo = $this->info->getPageAttr();
                    foreach ($standardSkuAttr as $k=>$v) {
                        if ($v['attr_id'] == $attrPageInfo['attr_id']) {
                            $attrPageName = $v['attr_val_name'];
                            $photo = $this->info->getPageByAttr($attrPageName);
                        }
                    }
                }
            }

            //使用正则表达式将商品名称中括号内内容截取掉
            $f_array = ['(','（'];
            $prod_name = $product_info['prod_name'];
            foreach ($f_array as $k => $v)
            {
                if (strstr($prod_name ,$v) !== false){
                    $prod_name = substr($prod_name,0,strrpos($prod_name,$v));
                    break;
                }
            }
            $product_info['prod_name'] = $prod_name;
            //在商品名称后加上p数
            if (!empty($photo))
            {
                $product_info['prod_name'] =  $product_info['prod_name'].'  '.$photo.'P';
            }

            //获取规格单双页
            $sizeInfo = $this->sizeInfoRepository->getRow(['size_id'=>$prod_info[0]['prod_size_id'],'size_type'=>3]);
            if(!empty($sizeInfo)){
                if($sizeInfo['size_is_2faced']==ZERO){
                    $photo = $photo+1;
                }else{
                    $photo = $photo/2+1;
                }
            }

            foreach ($template as $key=>$value){
                if ($is_mobile)
                {
                    //手机端链接
                    $template[$key]['url'] = "http://".config("app.agent_url")."/ds_m/?sp=".$mid."&a=".$aid."&g=".$prod_id."&p=".$prodSkuInfo['prod_sku_id']."&t=".$value['main_temp_id']."&pc=".$prodSkuInfo['prod_p_num']."&order_no=".$order_no."";
                }else{
                    //pc端链接
                    $template[$key]['url'] = "http://".config("app.agent_url")."/ds/ed.html?sp=".$mid."&a=".$aid."&g=".$prod_id."&p=".$prodSkuInfo['prod_sku_id']."&t=".$value['main_temp_id']."&pc=0&order_no=".$order_no."";
                    $template[$key]['min_photo'] = intval($value['main_temp_avg_photo'])*$photo;
                    $template[$key]['max_photo'] = intval($value['main_temp_avg_photo']+1)*$photo;
                }
            }

            foreach ($categoryArr as $k=>$v){
                $count = $this->mainTemplatesRepository->getCountThemeid([0,$mid],$prod_cate_uid,$v['cate_id'],$prod_size_id);
                $category[0] = $v['cate_id'];
                $category[1] = $v['cate_name'];
                $category[2] = $count;
                $category_list[$k] = $category;
            }


            //获取模板标签
            $tempTags = $this->templateTagsRepository->getTemptagsList($mid);

            $total = $template_info['total'];


            if ($is_mobile)
            {
                //手机端模板市场视图
                return view('agent.goods.detail.m_template',['category'=>$category_list,'tempTags'=>$tempTags,'template'=>$template,'product_info'=>$product_info,'total'=>$total,'msg'=>$msg]);
            }else{
                //pc端模板市场视图
                return view('agent.goods.detail.template',['category'=>$category_list,'tempTags'=>$tempTags,'template'=>$template,'product_info'=>$product_info,'total'=>$total,'msg'=>$msg]);
            }

        }catch (CommonException $e){
            $this->jsonFailed($e->getMessage());
        }

    }



    //搜索对应的模板
    public function get_template(Request $request)
    {
        try{
            $params = $request->all();
            $prod_id = $params['prod_id'];
            $sku_id = $params['sku_id'];
            $page_num = $params['page_num'];
            $mid = $params['mid'];
            $aid = $params['aid'];
            $order_no = $params['order_no'];
            $template = [];
            $prod_info = $this->productsRepository->getProductInfo($mid,$prod_id);
            $prod_cate_uid = $prod_info[0]['prod_cate_uid'];
            //获取商品的规格id
            $prod_print = $this->productsPrintRepository->getProductPrint($prod_id,$mid);
            $prod_size_id = $prod_print['prod_size_id'];

            $where = [
                'mch_id'=>[0,$mid],
                'limit'=>$params['limit']??16,
                'goods_type_id'=>$prod_cate_uid,
                'specifications_id'=>$prod_size_id,
                'main_temp_check_status'=>TEMPLATE_STATUS_VERIFYED,
                'main_temp_name'=>$params['temp_name']??''
            ];

            if($params['cate_id']=="all"){
                $where['main_temp_theme_id'] = null;
            }else{
                $where['main_temp_theme_id'] = $params['cate_id'];
            }

            if($params['tag_id']=="alltags"){
                $where['temp_tag'] = null;
            }else{
                $where['temp_tag'] = $params['tag_id'];
            }

            $order_use_times = "main_temp_use_times desc";
            $order_created_at = "created_at desc";

            if($params['sel_value']=="use_times"){
                $template_info =  $this->mainTemplatesRepository->getTableList($where,$order_use_times)->toArray();
                $template = $template_info['data'];
            }
            //按创建时间排序
            else{
                $template_info =  $this->mainTemplatesRepository->getTableList($where,$order_created_at)->toArray();
                $template = $template_info['data'];
            }

            //获取是否加减p dai 6-30
            $productPrint = $this->productsPrintRepository->getRow(['prod_id'=>$prod_id]);
            if($productPrint['prod_pt_variable']==ONE){
                $photo = $productPrint['prod_pt_min_p'];
            }else{
                $standardSkuAttr = $this->info->getStandardSkuAttr($sku_id);
                //获取当前skuP数信息
                $photo = 0;
                if (!empty($standardSkuAttr)) {
                    $attrPageInfo = $this->info->getPageAttr();
                    foreach ($standardSkuAttr as $k=>$v) {
                        if ($v['attr_id'] == $attrPageInfo['attr_id']) {
                            $attrPageName = $v['attr_val_name'];
                            $photo = $this->info->getPageByAttr($attrPageName);
                        }
                    }
                }
            }

            //获取规格单双页
            $sizeInfo = $this->sizeInfoRepository->getRow(['size_id'=>$prod_info[0]['prod_size_id'],'size_type'=>3]);
            if(!empty($sizeInfo)){
                if($sizeInfo['size_is_2faced']==ZERO){
                    $photo = $photo+1;
                }else{
                    $photo = $photo/2+1;
                }
            }

            foreach ($template as $key=>$value){
                $template[$key]['url'] = "http://".config("app.agent_url")."/ds/ed.html?sp=".$mid."&a=".$aid."&g=".$prod_id."&p=".$sku_id."&t=".$value['main_temp_id']."&pc=".$page_num."&order_no=".$order_no."";
                $template[$key]['min_photo'] = intval($value['main_temp_avg_photo'])*$photo;
                $template[$key]['max_photo'] = intval($value['main_temp_avg_photo']+1)*$photo;
            }
            if($template){
                $data['status'] = 200;
            }else{
                $data['status'] = 101;
            }


            $data['template'] = $template;
            $data['total'] = $template_info['total'];
            return $data;
        }catch (CommonException $e){
            $this->jsonFailed($e->getMessage());
        }
    }


    //手机对应的模板
    public function get_m_template(Request $request)
    {
        $params = $request->all();
        $prod_id = $params['prod_id'];
        $sku_id = $params['sku_id'];
        $page_num = $params['page_num'];
        $mid = $params['mid'];
        $aid = $params['aid'];
        $order_no = $params['order_no'];
        $template = [];
        $prod_info = $this->productsRepository->getProductInfo($mid,$params['prod_id']);
        $prod_cate_uid = $prod_info[0]['prod_cate_uid'];
        //获取商品的规格id
        $prod_print = $this->productsPrintRepository->getProductPrint($params['prod_id'],$mid);
        $prod_size_id = $prod_print['prod_size_id'];
        $where = [
            'mch_id'=>[0,$aid],
            'goods_type_id'=>$prod_cate_uid,
            'specifications_id'=>$prod_size_id,
            'main_temp_check_status'=>TEMPLATE_STATUS_VERIFYED
        ];
        $order_use_times = "main_temp_use_times desc";
        $order_created_at = "created_at desc";
        //如果模板选择为全部模板
        if($params['cate_id']=='all'){
             $template_info =  $this->mainTemplatesRepository->getMobileTemplateTableList($where,$order_use_times)->toArray();

             $template = $template_info;
        }
        //如果模板选择有值
        else{
            $where['main_temp_theme_id'] =$params['cate_id'];
            //按创建时间排序
             $template_info =  $this->mainTemplatesRepository->getMobileTemplateTableList($where,$order_use_times)->toArray();
             $template = $template_info;
        }

        foreach ($template as $key=>$value){
            $template[$key]['url'] = "http://".config("app.agent_url")."/ds_m/?sp=".$mid."&a=".$aid."&g=".$prod_id."&p=".$sku_id."&t=".$value['main_temp_id']."&pc=".$page_num."&order_no=".$order_no."";
        }

        if($template){
            $data['status'] = 200;
        }else{
            $data['status'] = 101;
        }

        $data['template'] = $template;
        return $data;
    }

    //稿件上传页面
    public function work_upload(Request $request)
    {
        try{
            //获取请求参数
            $params = $request->all();
            $user_id = $params['a'];
            $mch_id = $params['mid'];
            $isLogin = ONE;
            if(empty(session('admin'))){
                $isLogin = ZERO;
            }
            //获取店铺名称
            $shop_name = $this->dmsAgentInfoRepository->getTableList(['agent_info_id'=>$user_id])->toArray();
            $shop_name = $shop_name['data'][0]['agent_name'];

            //商品id
            $prod_id = $params['prod_id'];
            //组装货品属性
            $prod_attr_comb = explode(",",$params['prod_attr_comb']);
            asort($prod_attr_comb);
            $prod_attr_comb = implode(",",$prod_attr_comb);
            //获取货品信息
            $prodSkuInfo = $this->productsSkuRepository->getProdSku($prod_id,$prod_attr_comb);

            //获取货品的属性值
            $attr_value = $this->productsRelAttrRepository->getProductAttr($prodSkuInfo['prod_sku_id']);

            //获取商品信息
            $prod_info = $this->productsRepository->getProductInfo($mch_id,$prod_id);

            //根据商品获取模板id
            $temp_id = $this->mainTemplatesRepository->getTempID($prod_info[0]['prod_cate_uid']);

            if(empty($temp_id)){
                $temp_id=0;
            }
            //尺寸信息
            //商品规格参数表取出对应货品的商品id的信息
            $size_info = $this->info->getGoodSizeInfo($prod_info[0]['prod_size_id'],$prod_id);
            //如果该货品不支持增减p
            if(empty($prod_info[0]['prod_pt_variable'])){
                //书脊厚度为货品设置的厚度
                $thickness = $prodSkuInfo['prod_spine_thickness'];
            }else{
                $thickness = 10;
            }

            //获取商品的P数
            $p = 0;  //如果是0做加减P规则

            preg_match('/(\d+)p/i',$attr_value, $match);
            if(!empty($match)) {
                $p = $match[1];
            }

            //定高
            $fixed_height = 120; //整个大小定为120px

            //稿件上传类型
            $sizeType = [];


            foreach ($size_info['detail_list'] as $k=>$v) {
                if ($v['size_is_cross'] == 0 || $v['size_type'] == GOODS_SIZE_TYPE_INNER) {
                    $thickness = 0;
                }
                $bl = $fixed_height / $v['size_design_h'];
                $display_width = ($v['size_design_w'] / $v['size_design_h']) * $fixed_height;
                $prod_size_data[$k] = [
                    'design_width' => $v['size_design_w'],
                    'design_height' => $v['size_design_h'],
                    'bleed_up' => $v['size_cut_top'],
                    'bleed_left' => $v['size_cut_left'],
                    'bleed_down' => $v['size_cut_bottom'],
                    'bleed_right' => $v['size_cut_right'],

                    'display_width' => ($v['size_design_w'] / $v['size_design_h']) * $fixed_height,
                    'real_width' => $v['size_design_w'] + $v['size_cut_left'] + $v['size_cut_right'] + $thickness,
                    'real_height' => $v['size_design_h'] + $v['size_cut_top'] + $v['size_cut_bottom'],

                    'real_up' => $v['size_cut_top'] * $bl,
                    'real_left' => $v['size_cut_left'] * $bl,
                    'real_down' => $v['size_cut_bottom'] * $bl,
                    'real_right' => $v['size_cut_right'] * $bl,

                    //书脊位置
                    'sj' => ($display_width - ($v['size_cut_left'] * $bl) - ($v['size_cut_right'] * $bl)) / 2,
                    'size_is_cross' => $v['size_is_cross'],
                    'size_type' => $v['size_type']
                ];

                //判断页面类型
                if($v['size_type']==GOODS_SIZE_TYPE_COVER){
                    $sizeType[] = [
                        'size_name'=>'封面',
                        'size_type'=>$v['size_type'],
                        'size_is_cross'=>$v['size_is_cross']
                        ];

                }
                else if($v['size_type']==GOODS_SIZE_TYPE_COVER_BACK){
                    $sizeType[] = [
                        'size_name'=>'封面-封底',
                        'size_type'=>$v['size_type'],
                        'size_is_cross'=>$v['size_is_cross']
                    ];
                }
                else if($v['size_type']==GOODS_SIZE_TYPE_INNER){
                    $sizeType[] = [
                        'size_name'=>'内页',
                        'size_type'=>$v['size_type'],
                        'size_is_cross'=>$v['size_is_cross']
                    ];
                }
                else if($v['size_type']==GOODS_SIZE_TYPE_BACK){
                    $sizeType[] = [
                        'size_name'=>'封底',
                        'size_type'=>$v['size_type'],
                        'size_is_cross'=>$v['size_is_cross']
                    ];
                }
                else if($v['size_type']==GOODS_SIZE_TYPE_SPECIAL){
                    $sizeType[] = [
                        'size_name'=>'特殊页',
                        'size_type'=>$v['size_type'],
                        'size_is_cross'=>$v['size_is_cross']
                    ];
                }
            }

            $data = [
                'agent_name'=>$shop_name,
                'prod_id'=>$prod_info[0]['prod_id'],
                'prod_name'=>$prod_info[0]['prod_name'],
                'attr_value'=>$attr_value,
                'fixed_height'=>$fixed_height,
            ];

            $save_data = [
                'mch_id'    =>$mch_id,
                'cha_id'    =>1,
                'user_id'   =>$user_id,
                'prod_id'   =>$prod_id,
                'sku_id'    =>$prodSkuInfo['prod_sku_id'],
                'page_num'  =>$prodSkuInfo['prod_p_num'],
                'temp_id'   =>$temp_id
            ];

            $save_data = json_encode($save_data);

            return view('agent.goods.detail._upload',['data'=>$data,'sizeType'=>$sizeType,'prod_size_data'=>$prod_size_data,'save_data'=>$save_data,'isLogin'=>$isLogin,'page'=>$p]);

        }catch (CommonException $e){
            $this->jsonFailed($e->getMessage());
        }

    }



    //pdf文件上传
    public function fileUpload(Request $request)
    {
        try{
            if ($_FILES['file']['error'] == 0) {
                $file_path = config('agent.works_file').config('agent.works_pdf_file');//设置文件路径
                $blob_num = $request->post('chunk');//当前片数
                $total_num = $request->post('chunks');//总片数
                $file_name = $request->post('name');//文件名称
                $temp_name = $_FILES['file']['tmp_name'];//临时文件名称
                $uploadClass = new UploadController($file_path, $blob_num, $total_num, $file_name, $temp_name);//实例化upload类，并传入相关参数
                $data = $uploadClass->apiReturn();
                return json_encode($data);
            } else {
                $data['code'] = 0;
                $data['msg'] = 'error code:' . $_FILES['file']['error'];
                $data['file_path'] = '';
                return json_encode($data);
            }
        }catch (CommonException $e){
            $this->jsonFailed($e->getMessage());
        }

    }

    //删除pdf文件
    public function delete_pdf(Request $request)
    {
        $params = $request->all();
        if($params['file_path']){
            $path = $params['file_path'];
            unlink($path);
            return $this->jsonSuccess([]);
        }else{
            return $this->jsonFailed('');
        }

    }

    //提交作品
    public function filesave(Request $request)
    {
        $params = $request->all();
        $parent_path = config("agent.works_file_url").config('agent.works_pdf_file');
        $data = json_decode($params['save_data'],true);
        $script_url = [];
        $file_path = [];
        $pageType = config('goods.page_type');
        foreach ($pageType as $k => $v){
            if(isset($params["file_path".$k]) && !empty($params["file_path".$k])){
                $path = explode("/",$params['file_path'.$k]);
                $file_path[] = $params['file_path'.$k];
                $script_url[] = $parent_path.$path[count($path)-1];
            }
        }

        $file_path = implode("||",$file_path);
        $script_url = implode("||",$script_url);

        $works_name = $params['works_name'];

        try{
            \DB::beginTransaction();
            $manuscript_data = [
                'mch_id'    =>$data['mch_id'],
                'cha_id'    =>$data['cha_id'],
                'user_id'   =>$data['user_id'],
                'prod_id'   =>$data['prod_id'],
                'sku_id'    =>$data['sku_id'],
                'script_url'=>$script_url,
                'prj_file_path' =>$file_path,
                'prj_page_num'  =>$data['page_num'],
            ];
            $manuscript_id = $this->manuscriptRepository->save($manuscript_data);

            $project_data = [
                'mch_id'    =>$data['mch_id'],
                'cha_id'    =>$data['cha_id'],
                'user_id'   =>$data['user_id'],
                'prod_id'   =>$data['prod_id'],
                'sku_id'    =>$data['sku_id'],
                'manuscript_id'=>$manuscript_id,
                'prj_name'  =>$works_name,
                'prj_sn'    =>$this->worksAbstract->createWorksNo(),
                'prj_file_status'=>0,
                'prj_status'    =>WORKS_DIY_STATUS_WAIT_CONFIRM,
                'prj_file_path' =>$file_path,
                'prj_page_num'  =>$data['page_num'],
                'prj_tpl_id'   =>$data['temp_id'],
                'prj_file_type'=>2,
                'created_at'=>time()
            ];

            $prj_id = $this->projectsRepository->save($project_data);

            if($prj_id && $manuscript_id){
                \DB::commit();
                if(empty($params['isLogin'])){
                    return $this->jsonSuccess([]);
                }else{
                    return $prj_id;
                }

            }else{
                \DB::rollBack();
                return $this->jsonFailed('数据保存出错');
            }
            }catch (CommonException $e){
                \DB::rollBack();
                return $this->jsonFailed($e->getMessage());
        }
    }


    //稿件上传页面 加入购物车
    public function shoppingCart(Request $request)
    {
        $prj_id = $this->filesave($request);
        $car_data = $this->cartRepository->shoppingCar($prj_id);
        if($car_data['status']){
            $return = $this->cartRepository->addCartGoods($car_data['data']);
            if($return){
                return $this->jsonSuccess(['message'=>"",'cart'=>ONE]);
            }else{
                return $this->jsonSuccess(['message'=>"作品订购失败",'cart'=>ONE]);
            }
        }else{
            return $this->jsonSuccess(['message'=>$car_data['msg'],'cart'=>ONE]);
        }
    }

    //稿件上传作品直接订购
    public function orderCreate(Request $request)
    {
        $prj_id = $this->filesave($request);
        $params = $request->all();
        $data = json_decode($params['save_data'],true);
        $sku_id = $data['sku_id'];
        $redisKey = 'cart_'.$prj_id;

        $saveData[] = [
            'sku_id'=>[$sku_id],
            'proj_id'=>[$prj_id],
            'num'=>[1]
        ];
        $redisData = json_encode($saveData);
        Redis::setex($redisKey,86400,$redisData);

        return $this->jsonSuccess(['cart_id'=>$redisKey]);
    }


    //添加购物车
    public function addCart(Request $request)
    {
        $post = $request->post();
        //判断为sku商品还是spu商品
        if ($post['price_type'] == 'spu')
        {
            //spu
            $prod_sku_id = $this->productsSkuRepository->getSkuId($post['prod_id'],"");
            if(empty($prod_sku_id)){
                return $this->jsonFailed('获取货品id出错');
            }
            $prod_sku_id = $prod_sku_id[0]['prod_sku_id'];
        }else{
            //sku
            $rel_attr = explode(",",$post['prod_attr_comb']);
            asort($rel_attr);
            $prod_attr_comb = implode(",",$rel_attr);
            $prod_sku_id = $this->productsSkuRepository->getSkuId($post['prod_id'],$prod_attr_comb);
            if(empty($prod_sku_id)){
                return $this->jsonFailed('获取货品id出错');
            }
            $prod_sku_id = $prod_sku_id[0]['prod_sku_id'];
        }

        $channleRepository = app(SaasSalesChanelRepository::class);

        $cha_id = $channleRepository->getAgentChannleId();
        $num = $post['prod_num'];
        $res = $this->productsRepository->addCart($prod_sku_id,$cha_id,$num);
        if ($res)
        {
            return $this->jsonSuccess([]);
        }else{
            return $this->jsonFailed('加入购物车错误');
        }

    }

    //进入商业模板市场页面
    public function comltemplate(Request $request)
    {
        try{
            $params = $request->all();

            //提示信息
            $msg = "";
            //添加短链接判断 by hlt
            if ($request->route('sku_id')){
                //从短链接过来的,为不影响之前流程，转化为长链接所需要的数据，
                $sku_id = $request->route('sku_id');
                $skuInfo = $this->skuModel->where(['prod_sku_id'=>$sku_id,'prod_sku_onsale_status'=>PRODUCT_ON])->select('prod_id','prod_attr_comb')->first();
                if (empty($skuInfo)){
                    $msg = "该货品未开卖";
                    return view('agent.goods.detail.comltemplate',['msg'=>$msg]);
                }
                //商品id
                $prod_id = $skuInfo['prod_id'];
                //获取商家id
                $mid = $this->products->where('prod_id',$prod_id)->value('mch_id');
                if (is_null($mid)){
                    $msg = "该商品不存在";
                    return view('agent.goods.detail.comltemplate',['msg'=>$msg]);
                }
                //获取属性信息
                $prod_attr_comb = $skuInfo['prod_attr_comb'];
                //获取分销id
                $aid = $request->route('agent_id');
                $agentInfo = $this->dmsAgentInfoRepository->getById($aid);
                if (!$agentInfo){
                    $msg = "该分销商不存在";
                    return view('agent.goods.detail.comltemplate',['msg'=>$msg]);
                }

            }else{
                //商品id
                $prod_id = $params['prod_id'];
                $mid = $params['mid'];
                $aid = $params['aid'];
                //货品属性
                $prod_attr_comb = explode(",",$params['prod_attr_comb']);
                asort($prod_attr_comb);
                $prod_attr_comb = implode(",",$prod_attr_comb);
            }

            //获取货品id和货品P数
            $prodSkuInfo = $this->productsSkuRepository->getProdSku($prod_id,$prod_attr_comb);
            if(!isset($prodSkuInfo['prod_sku_id'])){
                $msg = "该货品未开卖";
                return view('agent.goods.detail.comltemplate',['msg'=>$msg]);
            }

            $prod_info = $this->productsRepository->getProductInfo($mid,$prod_id);
            //获取店铺名称
            $agentInfo = $this->dmsAgentInfoRepository->getById($aid);

            $product_info = [
                'agent_name'=>$agentInfo['agent_name'],
                'prod_id'=>$prod_info[0]['prod_id'],
                'prod_name'=>$prod_info[0]['prod_name'],
                'sku_id'=>$prodSkuInfo['prod_sku_id'],
                'prod_p_num'=>$prodSkuInfo['prod_p_num'],
                'mid'=>$mid,
                'aid'=>$aid,
                //'order_no'=>$order_no
            ];
            $apiService = app(CommonApi::class);
            $tempCate = $apiService->request(config("template.coml_url").'/template/classList',[],'GET');
            $tempCateName = Helper::ListToKV('tplClassId','name',$tempCate);
            //请求接口或获取缓存的全部模板数据
            $list = $this->commerRepo->getTableList();
            foreach ($list as $k=>$v){
                $comlList[$v['tid']]=$v;
            }

            //获取关联规格模板数据
            $limit = isset($params['limit']) ? $params['limit']:16;
            $inputs=[];
            $inputs['size_id'] = $prod_info[0]['prod_size_id'];
            $inputs['limit'] = $limit;
            $tempList = $this->tempSizeRelationRepo->getTableList($inputs);
            foreach ($tempList as $k=>$v){
                if(isset($comlList[$v['tid']])){
                    $tempList[$k]['thumb'] = $comlList[$v['tid']]['thumb'];
                    $tempList[$k]['temp_name'] = $comlList[$v['tid']]['temp_name'];
                }
            }
            $pagesInfo = $tempList->toArray();
            $total = $pagesInfo['total'];

            //加密传参数到商业印刷编辑链接
            $orgig = base64_encode($aid.'-'.$prod_info[0]['prod_id'].'-'.$prodSkuInfo['prod_sku_id']);

            return view('agent.goods.detail.comltemplate',
                ['tempCategory'=>$tempCateName,'list'=>$tempList,'total'=>$total,'msg'=>$msg,'product_info'=>$product_info,'diy_url'=>config("template.coml_pc_url"),'orgig'=>$orgig]);

        }catch (CommonException $e){
            $this->jsonFailed($e->getMessage());
        }

    }

    //商印模板搜索
    public function getComlTemplate(Request $request)
    {
        $params = $request->all();

        $limit = isset($params['limit']) ? $params['limit']:16;
        $curPage = isset($params['page']) ? $params['page']: 1;

        $prod_info = $this->productsRepository->getProductInfo($params['mid'],$params['prod_id']);
        //请求接口或获取缓存的全部模板数据
        $list = $this->commerRepo->getTableList();
        foreach ($list as $k=>$v){
            $comlList[$v['tid']]=$v;
        }

        //获取关联规格模板数据
        $inputs=[];
        $inputs['limit'] = $limit;
        $inputs['page'] = $curPage;
        $inputs['size_id'] = $prod_info[0]['prod_size_id']; //带入规格
        if($params['cate_id']=='all'){ //全部分类
            $inputs['temp_cate'] = [];
        }else{
            $inputs['temp_cate'] = $params['cate_id'];
        }

        $tempList = $this->tempSizeRelationRepo->getTableList($inputs);
        foreach ($tempList as $k=>$v){
            if(isset($comlList[$v['tid']])){
                $tempList[$k]['thumb'] = $comlList[$v['tid']]['thumb'];
                $tempList[$k]['temp_name'] = $comlList[$v['tid']]['temp_name'];
                $tempList[$k]['diy_url'] = config("template.coml_pc_url")."/design?id=".$v['tid']."&uprodsku=".base64_encode($params['aid']."-".$params['prod_id']."-".$params['sku_id']);
            }
        }
        $pagesInfo = $tempList->toArray();

        $total = $pagesInfo['total'];

        //如果模板选择为全部模板
      /*  if($where['temp_cateid']=='all'){
            $list = $this->commerRepo->getTableList();
        }
        //如果模板选择有值
        else{
            $list = $this->commerRepo->getTableList($where);
        }

        foreach ($list as $key=>$value){
            $list[$key]['diy_url'] = config("template.coml_pc_url")."/design?id=".$value['tid']."&uprodsku=".base64_encode($params['aid']."-".$params['prod_id']."-".$params['sku_id']);
        }

        $offset = ($curPage-1)*$limit;
        if($limit>count($list)-$offset){
            $limit = count($list);
        };

        $total = count($list);
        $list = array_slice($list,$offset,$limit);*/

        return $this->jsonSuccess(['list'=>$pagesInfo['data'],'total'=>$total]);
    }


    //检查是否符合商品页数
    public function checkPage(Request $request)
    {
        $params = $request->all();
        //文件地址
        $file_path = $params['file_path'];
        //文件类型 1：封面；2：封面/封底；3：内页；4：封底；5：特殊页
        $type = $params['type'];
        //是否跨页 0：否 1：是
        $cross = $params['cross'];
        //商品页数，默认一页
        $page = 1;

        //如果为内页，考虑是否是跨页
        if($type==GOODS_SIZE_TYPE_INNER){
            if($cross==ONE){
                $page = $params['page']/2;
            }else{
                $page = $params['page'];
            }
        }

        //计算上传文件的大小
        $size = ceil(filesize($file_path)/(1024*1024));
        if($size<128){
            $size = 128;
        }else{
            $size = $size + 10;
        }
        //临时更改限制文件的大小
        ini_set('memory_limit', $size.'M');
        //读取文件计算文件页数
        $pdftext = file_get_contents($file_path);
        $num = preg_match_all("/\/Page\W/", $pdftext, $dummy);
        //如果页数符合要求
        if($page==$num){
            return $this->jsonSuccess([]);
        }else{
            return $this->jsonFailed("");
        }

    }




}
