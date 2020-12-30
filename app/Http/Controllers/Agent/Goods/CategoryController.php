<?php
namespace App\Http\Controllers\Agent\Goods;

use App\Exceptions\CommonException;
use App\Http\Controllers\Agent\BaseController;
use App\Models\SaasProdToCustLevel;
use App\Repositories\DmsAgentInfoRepository;
use App\Repositories\DmsProductsCollectRepository;
use App\Repositories\SaasCategoryRepository;
use App\Repositories\SaasProductsRepository;
use App\Repositories\SaasProductsSkuRepository;
use App\Repositories\SaasSalesChanelRepository;
use App\Services\Goods\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

/**
 * 商品分类
 *
 * @author: cjx <781714246@qq.com>
 * @version: 1.0
 * @date: 2019/8/7
 */

class CategoryController extends BaseController
{
    protected $viewPath = 'agent.goods.category';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $productsRepository;

    public function __construct(SaasProductsRepository $productsRepository,SaasCategoryRepository $cateRepository,
                                SaasProductsSkuRepository $productsSkuRepository,DmsAgentInfoRepository $dmsAgentInfoRepository,
                                Price $price,SaasSalesChanelRepository $chanelRepository,SaasProdToCustLevel $prodToCustLevel,
                                DmsProductsCollectRepository $dmsProductsCollectRepository)
    {
        parent::__construct();
        $this->productsRepository = $productsRepository;
        $this->cateRepository = $cateRepository;
        $this->productsSkuRepository = $productsSkuRepository;
        $this->dmsAgentInfoRepository = $dmsAgentInfoRepository;
        $this->chanelRepository = $chanelRepository;
        $this->prodToCustLevel = $prodToCustLevel;
        $this->dmsProductsCollectRepository = $dmsProductsCollectRepository;
        $this->price = $price;
        $this->merchantID = empty(session('admin')) == false ? session('admin')['mch_id'] : ' ';
        $this->agentID = empty(session('admin')) == false ? session('admin')['agent_info_id'] : ' ';
    }
    public function index()
    {
        return view('agent.goods.category.index');
    }

    /**
     * 列表测试
     */
    public function table(Request $request)
    {
        try{
            //获取等级
            $cust_lv_id = $this->dmsAgentInfoRepository->getCustLvId($this->agentID);
            $category_id = $request->route("category_id");
            //获取分销渠道id by hlt
            $cha_id = $this->chanelRepository->getAgentChannleId();
            if($category_id == 'all'){
                //全部商品
                //获取等级
                $cust_lv_id = $this->dmsAgentInfoRepository->getCustLvId($this->agentID);
                $category_List = $this->cateRepository->getLevelCateList("goods",CATEGORY_NO_THREE);
                foreach ($category_List as $cate_key=>$cate_val){
                    $product_List = $this->productsRepository->getProducts($cate_key,$this->merchantID);
                    //获取该商品下的所有货品
                    foreach ($product_List as $prod_k => $prod_v){
                        //判断该商品对应该账号的分销等级是否开卖 by hlt
                        $is_sale = $this->prodToCustLevel->where(['prod_id' => $prod_v['prod_id'],'cha_id' => $cha_id,'cust_lv_id'=> $cust_lv_id])->exists();
                        if (!$is_sale){
                            //不开卖，则不显示该商品
                            unset($product_List[$prod_k]);
                            continue;
                        }
                        $prod_sku_ids = $this->productsSkuRepository->getProductPrice($prod_v['prod_id']);
                        $product_fee = [];
                        //找到货品的价格
                        foreach ($prod_sku_ids as $k => $v){
                            $fee = $this->price->getChanelPrice($v,$cust_lv_id);
                            array_push($product_fee,$fee);
                        }
                        //商品的价格显示为增减p之后的价格
                        $product_List[$prod_k]['prod_fee'] = min($product_fee);
                    }
                    if(empty($product_List)){
                        unset($category_List[$cate_key]);
                    }
                    $products_List[$cate_key] = $product_List;
                }

                //相册排在第一位
                $album_id = $this->cateRepository->getRow(['cate_flag'=>GOODS_DIY_CATEGORY_ALBUM],['cate_id']);
                $arr[$album_id['cate_id']] = [];
                foreach ($category_List as $k=>$v){
                    $arr[$album_id['cate_id']] = $v;
                    if($k != $album_id['cate_id']){
                        $arr[$k] = $v;
                    }
                }
                $category_info = $arr;
                $products_list = $products_List;

            }else{
                $info = $this->cateRepository->getLevelCateList("goods",'3',$category_id);
                $products_list = $this->productsRepository->getProducts($category_id,$this->merchantID);
                //获取该商品下的所有货品
                foreach ($products_list as $prod_k => $prod_v){
                    //判断该商品对应该账号的分销等级是否开卖 by hlt
                    $is_sale = $this->prodToCustLevel->where(['prod_id' => $prod_v['prod_id'],'cha_id' => $cha_id,'cust_lv_id'=> $cust_lv_id])->exists();
                    if (!$is_sale){
                        //不开卖，则不显示该商品
                        unset($products_list[$prod_k]);
                        continue;
                    }
                    $prod_sku_ids = $this->productsSkuRepository->getProductPrice($prod_v['prod_id']);
                    $product_fee = [];
                    //找到货品的价格
                    foreach ($prod_sku_ids as $k => $v){
                        $fee = $this->price->getChanelPrice($v,$cust_lv_id);
                        array_push($product_fee,$fee);
                    }
                    //商品的价格显示为增减p之后的价格
                    $products_list[$prod_k]['prod_fee'] = min($product_fee);
                }
                $category_info = $info[$category_id];
            }

            //获取已收藏的商品
            $prod_collect = [];
            $collect = $this->dmsProductsCollectRepository->getList(['user_id'=>$this->agentID])->toArray();
            if($collect){
                $prod_collect = explode(",",$collect[0]['product_collect']);
            }

            return view('agent.goods.category.index',['category_info'=>$category_info,'products_list'=>$products_list,'category_id'=>$category_id,'collect'=>$prod_collect]);
        }catch (CommonException $e){
            $this->jsonFailed($e->getMessage());
        }

    }

   // 商品搜索页
    public function searchGoods(Request $request)
    {
        $post = $request->all();
        if(isset($post['_token'])){
            unset($post['_token']);
        }

        try{
            $where = [];
            if(isset($post['cname'])){
                $where['prod_name']=$post['cname'];
            }
            //获取分销渠道id by hlt
            $cha_id = $this->chanelRepository->getAgentChannleId();
            //获取等级
            $cust_lv_id = $this->dmsAgentInfoRepository->getCustLvId($this->agentID);
            $category_List = $this->cateRepository->getLevelCateList("goods",CATEGORY_NO_THREE);
            foreach ($category_List as $cate_key=>$cate_val){
                $product_List = $this->productsRepository->getProducts($cate_key,$this->merchantID,null,$where);
                //获取该商品下的所有货品
                foreach ($product_List as $prod_k => $prod_v){
                    //判断该商品对应该账号的分销等级是否开卖 by hlt
                    $is_sale = $this->prodToCustLevel->where(['prod_id' => $prod_v['prod_id'],'cha_id' => $cha_id,'cust_lv_id'=> $cust_lv_id])->exists();
                    if (!$is_sale){
                        //不开卖，则不显示该商品
                        unset($product_List[$prod_k]);
                        continue;
                    }
                    $prod_sku_ids = $this->productsSkuRepository->getProductPrice($prod_v['prod_id']);
                    $product_fee = [];
                    //找到货品的价格
                    foreach ($prod_sku_ids as $k => $v){
                        $fee = $this->price->getChanelPrice($v,$cust_lv_id);
                        array_push($product_fee,$fee);
                    }
                    //商品的价格显示为增减p之后的价格
                    $product_List[$prod_k]['prod_fee'] = min($product_fee);
                }
                if(empty($product_List)){
                    unset($category_List[$cate_key]);
                }
                $products_List[$cate_key] = $product_List;
            }
            $category = $category_List;
            //获取已收藏的商品
            $prod_collect = [];
            $collect = $this->dmsProductsCollectRepository->getList(['user_id'=>$this->agentID])->toArray();
            if($collect){
                $prod_collect = explode(",",$collect[0]['product_collect']);
            }

            return view('agent.goods.category.searchgoods',['category'=>$category,'products_list'=>$products_List,'collect'=>$prod_collect]);
        }catch (CommonException $e){
            $this->jsonFailed($e->getMessage());
        }

    }


}