<?php
namespace App\Services\Goods;

use App\Repositories\SaasProductsRepository;
use App\Repositories\SaasProductsSkuRepository;
use App\Repositories\SaasSkuSupPriceRepository;
use App\Repositories\SaasSkuToCustlevelPriceRepository;
use App\Repositories\SaasSuppliersRepository;
use App\Services\ChanelUser;
use App\Services\Helper;

/**
 * 商品价格体系
 *
 * 商品针对渠道定价、供货商成本价等价格相关处理
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/20
 */

class Price
{

    protected $repoGoods;      //商品仓库
    protected $repoSuppliers;  //供货商仓库
    protected $repoSuppPrice;  //商品对应供货商价格仓库
    protected $repoSku;        //sku仓库
    protected $repoLevelPrice;
    public function __construct(SaasProductsRepository $prod, SaasSuppliersRepository $suppliers,
        SaasSkuSupPriceRepository $supPrice, SaasProductsSkuRepository $sku,SaasSkuToCustlevelPriceRepository $levelPrice)
    {
        $this->repoGoods      = $prod;
        $this->repoSuppliers  = $suppliers;
        $this->repoSuppPrice  = $supPrice;
        $this->repoSku        = $sku;
        $this->repoLevelPrice = $levelPrice;
    }
    /**
     * 获取对应sku的供货商价格
     * @param $skuId       货品id
     * @param $supplierId  供货商id
     * @param int $totalPage    加减P数量
     * @return mixed
     */
    public function getSupplierPrice($skuId, $supplierId, $totalPage = 0)
    {
        //通过skuId和supplierId获取供货商定价
        $priceInfo   = $this->repoSuppPrice->getRow(['prod_sku_id' => $skuId, 'sup_id'=>$supplierId]);
        $productInfo = $this->repoSku->getById($skuId);
        $goodsId= $productInfo['prod_id'];

        //是否增减p
        $info = $this->repoGoods->addPageInfo($goodsId);
        $addpInfo = $productInfo['prod_sku_addp_info'];

        //增减p的情况
        if (!empty($info['prod_pt_variable']) && !empty($totalPage)) {
            //加减P情况下P数不能小于最小P数范围
            if ($totalPage - $info['prod_pt_min_p'] <0) {
                Helper::EasyThrowException('40011',__FILE__.__LINE__);
            }
            //加P的数量
            $addP = $totalPage - $info['prod_pt_min_p'];
            if (!empty($priceInfo)) {  //有供货商价格
                $costPrice = number_format($priceInfo['sku_sup_price'] + ceil($addP/$info['prod_pt_variable_base'])*$priceInfo['addp_price'], 2);

            } else {  //无供货商价格取默认统一价格
                $addpInfo = $productInfo['prod_sku_addp_info'];
                $arrAddpInfo = explode('|', $addpInfo); //销售价|成本价|重量

                if (empty($arrAddpInfo) || empty($arrAddpInfo[1])) { //未定义成本价格
                    Helper::EasyThrowException('40012',__FILE__.__LINE__);
                }
                $costPrice = number_format($productInfo['prod_sku_cost']  +  ceil($addP/$info['prod_pt_variable_base'])*$arrAddpInfo[1], 2);
            }

        } else {

            if (!empty($priceInfo)) {  //有供货商价格
                $costPrice = $priceInfo['sku_sup_price'];
            } else {
                $costPrice = $productInfo['prod_sku_cost'];
            }
        }

        return empty($costPrice) ? '0.00' :$costPrice;
    }

    /**
     * 获取商品的渠道定价
     * @param $skuId     货品id
     * @param $groupId   组别id
     * @param int $totalPage   总P数
     * @return mixed
     */
    public function getChanelPrice($skuId, $groupId, $totalPage = 0)
    {
        $priceInfo   = $this->repoLevelPrice->getRow(['prod_sku_id' => $skuId, 'cust_lv_id'=>$groupId]);
        $productInfo = $this->repoSku->getById($skuId);
        $goodsId= $productInfo['prod_id'];
        //是否增减p
        $info = $this->repoGoods->addPageInfo($goodsId);
        $addpInfo = $productInfo['prod_sku_addp_info'];
        //组别折扣
        $groupDiscount = app(ChanelUser::class)->getGroupDiscount($groupId);

        //增减p的情况
        if (!empty($info['prod_pt_variable']) && !empty($totalPage)) {
            //加减P情况下P数不能小于最小P数范围
            if ($totalPage - $info['prod_pt_min_p'] <0) {
                Helper::EasyThrowException('40011',__FILE__.__LINE__);
            }
            //加P的数量
            $addP = $totalPage - $info['prod_pt_min_p'];
            if (!empty($priceInfo)) {  //有渠道价格
                $goodsPrice = number_format($priceInfo['sku_cust_lv_price'] + ceil($addP/$info['prod_pt_variable_base'])*$priceInfo['addp_price'], 2);

            } else {  //无渠道价格取默认统一价格
                $addpInfo = $productInfo['prod_sku_addp_info'];
                $arrAddpInfo = explode('|', $addpInfo); //销售价|成本价|重量

                if (empty($arrAddpInfo) || empty($arrAddpInfo[0])) { //未定义销售价格
                    Helper::EasyThrowException('40012',__FILE__.__LINE__);
                }

                $goodsPrice = number_format(($productInfo['prod_sku_price']  +  ceil($addP/$info['prod_pt_variable_base'])*$arrAddpInfo[0])*$groupDiscount, 2);
            }

        } else {

            if (!empty($priceInfo)) {  //有渠道价格
                $goodsPrice = $priceInfo['sku_cust_lv_price'];
            } else {
                $goodsPrice = number_format($productInfo['prod_sku_price'] *$groupDiscount, 2);
            }
        }
        return empty($goodsPrice) ? '0.00' :$goodsPrice;

    }
}