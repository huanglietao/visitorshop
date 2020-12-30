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
use App\Repositories\SaasAdvertisementRepository;
use App\Services\Goods\Price;
use Illuminate\Http\Request;

/**
 * 商品列表
 *
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date: 2020/4/21
 */

class ListController extends BaseController
{


    public function __construct(SaasCategoryRepository $categoryRepository,SaasProductsRepository $productsRepository,
                                SaasProductsSkuRepository $productsSkuRepository,DmsAgentInfoRepository $dmsAgentInfoRepository,
                                Price $price,SaasSalesChanelRepository $chanelRepository,SaasProdToCustLevel $prodToCustLevel,
                                SaasAdvertisementRepository $adListRepository,DmsProductsCollectRepository $dmsProductsCollectRepository)
    {
        parent::__construct();
        $this->categoryRepository = $categoryRepository;
        $this->productsRepository = $productsRepository;
        $this->productsSkuRepository = $productsSkuRepository;
        $this->dmsAgentInfoRepository = $dmsAgentInfoRepository;
        $this->prodToCustLevel = $prodToCustLevel;
        $this->chanelRepository = $chanelRepository;
        $this->adListRepo  = $adListRepository;
        $this->dmsProductsCollectRepository  = $dmsProductsCollectRepository;
        $this->price = $price;
        $this->merchantID = empty(session('admin')) == false ? session('admin')['mch_id'] : ' ';
        $this->agentID = empty(session('admin')) == false ? session('admin')['agent_info_id'] : ' ';
    }

    public function index()
    {
        try{

            //获取等级
            $cust_lv_id = $this->dmsAgentInfoRepository->getCustLvId($this->agentID);
            $category_List = $this->categoryRepository->getLevelCateList("goods",CATEGORY_NO_THREE);
            //获取分销渠道id by hlt
            $cha_id = $this->chanelRepository->getAgentChannleId();
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
                    continue;
                }
                $product_List = array_slice($product_List,0,8);
                $products_List[$cate_key] = array_values($product_List);
            }
            $category = $category_List;
            $category_List = array_slice($category_List,0,11,true);

            //相册排在第一位
            $album_id = $this->categoryRepository->getRow(['cate_flag'=>GOODS_DIY_CATEGORY_ALBUM],['cate_id']);
            $arr[$album_id['cate_id']] = [];
            foreach ($category as $k=>$v){
                $arr[$album_id['cate_id']] = $v;
                if($k != $album_id['cate_id']){
                    $arr[$k] = $v;
                }
            }
            $category = $arr;

            //获取商品广告 by .david
            $chanel = $this->chanelRepository->getAgentChannleId();
            $goodsAd = $this->adListRepo->getAdvertiseList(['mch_id'=>$this->merchantID,'channel_id'=>$chanel,'ad_flag'=>AD_FLAG_AGENT_GA])->toArray();
            $adList = [];
            //如果有多条只获取最后一条
            if(!empty($goodsAd)){
                $adList[]=array_pop($goodsAd);
                $adList = $this->adListRepo->getMakeAdList($adList);
            }

            //获取已收藏的商品
            $prod_collect = [];
            $collect = $this->dmsProductsCollectRepository->getList(['user_id'=>$this->agentID])->toArray();
            if($collect){
                $prod_collect = explode(",",$collect[0]['product_collect']);
            }


            return view('agent.goods.list.index',['category_list'=>$category_List,'category'=>$category,'products_list'=>$products_List,'goodsAd'=>$adList,'collect'=>$prod_collect]);
        }catch (CommonException $e){
            $this->jsonFailed($e->getMessage());
        }

    }


    //商品收藏
    public function collect(Request $request)
    {
        try{
            \DB::beginTransaction();
            $params = $request->all();
            $flag = $params['flag'];
            $prod_id = $params['prod_id'];
            $row = $this->dmsProductsCollectRepository->getList(['user_id'=>$this->agentID])->toArray();
            //首次收藏
            if(!$row){
                $prod_col_data = [
                    'user_id'=>$this->agentID,
                    'product_collect'=>$prod_id
                ];
            }else{
                //收藏商品
                if($flag=="0"){
                    if(empty($row[0]['product_collect'])){
                        $collect = $prod_id;
                    }else{
                        $collect = $row[0]['product_collect'].','.$prod_id;
                    }
                    $prod_col_data = [
                        'prod_col_id'=>$row[0]['prod_col_id'],
                        'product_collect'=>$collect
                    ];
                }
                //取消收藏
                elseif($flag=="1"){
                    $prod_collect = explode(",",$row[0]['product_collect']);
                    unset($prod_collect[array_search($prod_id,$prod_collect)]);
                    $collect = implode(",",$prod_collect);
                    $prod_col_data = [
                        'prod_col_id'=>$row[0]['prod_col_id'],
                        'product_collect'=>$collect
                    ];
                }
            }
            $ret = $this->dmsProductsCollectRepository->save($prod_col_data);
            if($ret){
                \DB::commit();
                return $this->jsonSuccess([]);
            }else{
                \DB::rollBack();
                return $this->jsonFailed('');
            }
        }catch(CommonException $e){
            \DB::rollBack();
            return $this->jsonFailed($e->getMessage());
        }

    }

}
