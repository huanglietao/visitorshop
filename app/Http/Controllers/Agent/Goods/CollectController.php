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

/**
 * 商品分类
 *
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date: 2020/7/21
 */

class CollectController extends BaseController
{
    protected $viewPath = 'agent.goods.collect';  //当前控制器所的view所在的目录
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
        try{
            //获取已收藏的商品
            $prod_collect = [];
            $product_List = [];
            $collect = $this->dmsProductsCollectRepository->getList(['user_id'=>$this->agentID])->toArray();
            if($collect){
                $prod_collect = explode(",",$collect[0]['product_collect']);
            }
            //获取等级
            $cust_lv_id = $this->dmsAgentInfoRepository->getCustLvId($this->agentID);
            $product_List = $this->productsRepository->getProductsCollect($prod_collect);
            //获取该商品下的所有货品
            foreach ($product_List as $prod_k => $prod_v){
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

            return view('agent.goods.collect.index',['products_list'=>$product_List,'collect'=>$prod_collect]);
        }catch (CommonException $e){
            $this->jsonFailed($e->getMessage());
        }
    }

}