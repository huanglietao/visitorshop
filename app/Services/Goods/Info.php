<?php
namespace App\Services\Goods;

use App\Repositories\SaasProdToCustLevelRepository;
use App\Repositories\SaasProductsRepository;
use App\Repositories\SaasProductsSizeRepository;
use App\Repositories\SaasProductsSkuRepository;
use App\Repositories\SaasAttributeValueRepository;
use App\Repositories\SaasProductsAttributeRepository;
use App\Repositories\SaasProductsMediaRepository;
use App\Repositories\SaasProductsChanleRepository;
use App\Repositories\SaasProductsSuppliersRepository;
use App\Repositories\SaasProductsPrintRepository;
use App\Repositories\SaasProductsRelationAttrRepository;
use App\Repositories\SaasSizeInfoRepository;
use App\Repositories\SaasSkuToCustlevelPriceRepository;
use App\Repositories\SaasSkuSupPriceRepository;
use App\Services\Common\Mongo;
use App\Services\Helper;
use App\Exceptions\CommonException;
use App\Models\SaasProductsRelationAttr;



/**
 * 商品信息处理类
 *
 * 针对商品自身信息处理，重量计算、商品添加/编辑.....
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/8/15
 */
class Info
{
    protected $repoSku;
    protected $repoGoods;

    public function __construct(SaasProductsSkuRepository $sku, SaasProductsRepository $goods,
                                SaasAttributeValueRepository $attrValue, SaasProductsAttributeRepository $attr,
                                SaasProductsMediaRepository $media, SaasProductsChanleRepository $pChanle,
                                SaasProductsSuppliersRepository $pSupplier, SaasProductsPrintRepository $pPrint,
                                SaasProductsRelationAttrRepository $relationAttr, SaasSkuToCustlevelPriceRepository $custlevelPrice,
                                SaasSkuSupPriceRepository $supPrice, SaasProductsSizeRepository $prodSize,
                                SaasProdToCustLevelRepository $pCustLevel,SaasProductsRelationAttr $relationAttrModel

    )
    {
        $this->repoSku = $sku;
        $this->repoGoods = $goods;
        $this->attrValue = $attrValue;
        $this->attr = $attr;
        $this->media = $media;
        $this->pChanle = $pChanle;
        $this->pSupplier = $pSupplier;
        $this->pPrint = $pPrint;
        $this->relationAttr = $relationAttr;
        $this->custlevelPrice = $custlevelPrice;
        $this->supPrice = $supPrice;
        $this->prodSize = $prodSize;
        $this->pCustLevel = $pCustLevel;
        $this->relationAttrModel = $relationAttrModel;
    }

    /**
     * 获取商品重量
     * @param $skuId
     * @param int $totalPage
     * @return int
     */
    public function getGoodsWeight($skuId, $totalPage = 0)
    {
        $productInfo = $this->repoSku->getById($skuId);
        $goodsId = $productInfo['prod_id'];

        //是否增减p
        $info = $this->repoGoods->addPageInfo($goodsId);
        //增减p的情况
        if (!empty($info) && !empty($totalPage)) {
            if ($totalPage - $info['prod_pt_min_p'] < 0) {
                Helper::EasyThrowException('70021', __FILE__ . __LINE__);
            }
            $addP = $totalPage - $info['prod_pt_min_p'];

            $addpInfo = $productInfo['prod_sku_addp_info'];
            $arrAddpInfo = explode('|', $addpInfo);
            if (empty($arrAddpInfo) || empty($arrAddpInfo[1])) { //未定义成本价格
                Helper::EasyThrowException('70022', __FILE__ . __LINE__);
            }
            $weight = intval($productInfo['prod_sku_weight'] + ceil($addP / $info['prod_pt_variable_base']) * $arrAddpInfo[2], 2);
        } else {
            $weight = $productInfo['prod_sku_weight'];
        }


        return $weight;
    }

    //创建商品逻辑
    public function createGoods($postArr)
    {


        try {
            \DB::beginTransaction();
            //商品表数据
            $product = [
                'mch_id'                    => $postArr['mch_id'],
                'prod_cate_uid'             => $postArr['prod_cate_uid'],
                //商家自定义分类
                'mch_prod_cate_uid'         => $postArr['mch_prod_cate_uid']??0,
                'prod_name'                 => $postArr['prod_name']??"",
                'prod_abbr'                 => $postArr['prod_abbr']??"",
                'prod_title'                => $postArr['prod_title']??"",
                'prod_sn'                   => $postArr['prod_sn']??"",
                'prod_fee'                  => $postArr['prod_fee']??0.00,
                'prod_unit'                 => $postArr['prod_unit']??"",
                'prod_stock_status'         => $postArr['prod_stock_status']??0,
                'prod_brand_id'             => $postArr['prod_brand_id']??"",
                'prod_express_type'         => $postArr['prod_express_type']??"",
                'prod_details_pc'           => $postArr['prod_details_pc']??"",
                'prod_details_mobile'       => $postArr['prod_details_mobile']??"",
                'prod_comment_flag'         => $postArr['prod_comment_flag']??"",
                'prod_aftersale_flag'       => $postArr['prod_aftersale_flag']??"",
                'prod_return_flag'          => $postArr['prod_return_flag']??"",
                'prod_onsale_status'        => $postArr['prod_onsale_status'],
                'prod_price_type'           => $postArr['prod_price_type'],
                'prod_onsale_issingle'      => $postArr['prod_onsale_issingle'],
                'prod_dist_rule'            => $postArr['prod_dist_rule'],
                'created_at'                => time(),
            ];


            //是否启用库存
            if ($product['prod_stock_status'] == PROD_STOCK_STATUS) {
                $product['prod_stock_inventory'] = $postArr['prod_stock_inventory'];
                $product['prod_stock_waring'] = $postArr['prod_stock_waring'];
            }
            //物流方式
            if ($postArr['prod_express_type'] == FIXED_FEE) {
                //固定收取物流费用
                $product['prod_express_fee'] = $postArr['prod_express_fee'] ? $postArr['prod_express_fee'] : 0;
            } else {
                //使用快递模板收费
                $product['prod_express_tpl_id'] = $postArr['prod_express_tpl_id'];
            }
            //插入商品表并获取商品id  waiting
            $productId = $this->repoGoods->insertGetId($product);


            //商品媒体表数据

            if ($postArr['prod_photos']!=""){
                $photoArr = explode(',',$postArr['prod_photos']);
                foreach ($photoArr as $k=>$v){
                    $mediaArr = [];
                    $mediaArr['prod_id']        = $productId;
                    $mediaArr['prod_md_path']   = $v;
                    $mediaArr['prod_md_ismain'] = 1;
                    $mediaArr['prod_md_type']   = 1;
                    $mediaArr['sort']           = ++$k;
                    $mediaArr['created_at']     = time();
                    //插入商品媒体表 waiting
                    $this->media->insert($mediaArr);
                }
            }



            //商品对应渠道表数据

            foreach ($postArr['sales_chanel'] as $k => $v) {
                $channleArr = [];
                $channleArr['cha_id'] = $v;
                $channleArr['prod_id'] = $productId;
                $channleArr['mch_id'] = $postArr['mch_id'];
                $channleArr['sort'] = ++$k;
                $channleArr['created_at'] = time();
                //插入商品对应渠道表 waiting
                $this->pChanle->insert($channleArr);
            }


            //商品客户等级组别绑定表(商户需加)
            if ($postArr['mch_id']!=PUBLIC_CMS_MCH_ID && isset($postArr['sale_channle_cumtomer']))
            {
                foreach ($postArr['sale_channle_cumtomer'] as $k => $v)
                {
                    foreach ($v as $kk=>$vv)
                    {
                        $prodCustomerArr = [];
                        $prodCustomerArr['cha_id'] = $k;
                        $prodCustomerArr['prod_id'] = $productId;
                        $prodCustomerArr['mch_id'] = $postArr['mch_id'];
                        $prodCustomerArr['cust_lv_id'] = $vv;
                        $prodCustomerArr['sort'] = ++$kk;
                        $prodCustomerArr['created_at'] = time();
                        //插入商品对应渠道表 waiting
                        $this->pCustLevel->insert($prodCustomerArr);
                    }

                }
            }



            //商品对应供货商表

            foreach ($postArr['supplier'] as $k => $v) {
                $supplierArr = [];
                $supplierArr['prod_id'] = $productId;
                $supplierArr['sup_id'] = $v;
                $supplierArr['created_at'] = time();
                //插入商品对供货商表 waiting
                $this->pSupplier->insert($supplierArr);

            }
            //个性影像跟实物商品添加逻辑
            if ($postArr['is_personal_printing']) {
                //个性影像
                //规格逻辑操作
                $sizeArr = [];
                //判断是否增减p
                $sizeArr['prod_pt_variable'] = $postArr['prod_is_add_page'];
                $sizeArr['prod_id'] = $productId;
                $sizeArr['mch_id'] = $postArr['mch_id'];
                $sizeArr['prod_pt_min_p'] = $postArr['prod_min_add_page']??0;
                $sizeArr['prod_pt_max_p'] = $postArr['prod_max_add_page']??0;
                $sizeArr['prod_size_id'] = $postArr['prod_size_id'];
                $sizeArr['created_at'] = time();

                if ($postArr['prod_is_add_page']) {
                    //可增减p
                    $sizeArr['prod_pt_variable_base'] = $postArr['prod_add_page'];
                }
                //插入商品印刷表 waiting
                $this->pPrint->insert($sizeArr);

            }

            //添加sku表（1可加减p 2不可加减p ）

            if ($postArr['prod_price_type'] == 1) {
                //spu 无需关联属性操作
                //直接加入sku表
                foreach ($postArr['prod_sku_arr'] as $k => $v) {
                    $prodSku = [];
                    $prodSku['prod_id'] = $productId;
                    $prodSku['prod_attr_comb'] = "";
                    $prodSku['prod_sku_sn'] = $v['prod_sku_sn'];
                    //添加mid

                    $prodSku['prod_sku_price'] = $v['prod_sku_price'];
                    $prodSku['prod_sku_cost'] = $v['prod_sku_cost'];
                    $prodSku['prod_sku_weight'] = $v['prod_sku_weight'];
                    $prodSku['prod_min_photo'] = $v['prod_min_photo'];
                    $prodSku['prod_max_photo'] = $v['prod_max_photo'];
                    $prodSku['prod_supplier_sn'] = $v['prod_supplier_sn'];
                    $prodSku['prod_sku_addp_info'] = $v['prod_sku_addp_info'];
                    $prodSku['prod_sku_onsale_status'] = $v['sku_onsale'];
                    //个性印刷需要加上书脊厚度
                    if ($postArr['is_personal_printing']) {
                        $prodSku['prod_spine_thickness'] = $v['prod_spine_thickness'];
                    }
                    $prodSku['created_at'] = time();


                    //插入商品sku表 并获取skuid waiting
                    $skuId = $this->repoSku->insertGetId($prodSku);

                    //插入销售渠道定价
                    if ($v['sale_channle_price']) {
                        $saleChannleArr = json_decode($v['sale_channle_price'], true);

                        foreach ($saleChannleArr as $kk => $vv) {
                            if ((isset($vv['channle_price']) && $vv['channle_price'] !== "" ) || (isset($vv['channle_add_price']) && $vv['channle_add_price'] !== "" ))
                            {
                                $saleChannleArrIn = [];
                                $saleChannleArrIn['mch_id'] = $postArr['mch_id'];
                                $saleChannleArrIn['prod_sku_id'] = $skuId;
                                $saleChannleArrIn['cha_id'] = $vv['channle_id'];
                                $saleChannleArrIn['cust_lv_id'] = $vv['customer_id'];
                                $saleChannleArrIn['sku_cust_lv_price'] = $vv['channle_price']??0;
                                $saleChannleArrIn['addp_price'] = $vv['channle_add_price']??0;
                                $saleChannleArrIn['created_at'] = time();
                                $this->custlevelPrice->insert($saleChannleArrIn);
                            }
                        }

                    }
                    //插入供货商定价
                    if ($v['supplier_price']) {
                        $supplerPriceArr = json_decode($v['supplier_price'], true);

                        foreach ($supplerPriceArr as $kk => $vv) {
                            if ((isset($vv['start_price']) && $vv['start_price'] !== "" ) || (isset($vv['add_price']) && $vv['add_price'] !== "" ))
                            {
                                $supplerPriceArrIn = [];
                                $supplerPriceArrIn['mch_id'] = $postArr['mch_id'];
                                $supplerPriceArrIn['prod_sku_id'] = $skuId;
                                $supplerPriceArrIn['sup_id'] = $vv['sup_id'];
                                $supplerPriceArrIn['sku_sup_price'] = $vv['start_price']??0;
                                $supplerPriceArrIn['addp_price'] = $vv['add_price']??0;
                                $supplerPriceArrIn['created_at'] = time();
                                $this->supPrice->insert($supplerPriceArrIn);
                            }
                        }

                    }


                }


            } else {
                //sku
                //先将属性插进商品属性关联表，再插入sku表
                foreach ($postArr['prod_sku_arr'] as $k => $v) {
                    $relaCombId = [];
                    $attrValueArr = explode(',', $v['attr_id']);
                    //获取对应的父级属性（这里的PAGE_ID用与取出p数对应的数字用于存进sku表）
                    $attrArr = $this->attrValue->getAttrArr($attrValueArr, PAGE_ID);
                    //写进商品属性关联表
                    foreach ($attrArr as $kk => $vv) {
                        $relationArr = [];
                        $relationArr['product_id'] = $productId;
                        $relationArr['attr_id'] = $vv['attr_id'];
                        $relationArr['attr_val_id'] = $vv['attr_value_id'];
                        $relationArr['created_at'] = time();
                        //判断是否为p数属性
                        if ($vv['is_p']) {
                            //将p数存进该条sku数据中
                            $postArr['prod_sku_arr'][$k]['p_value'] = $vv['p_value'];
                        }
                        //插入商品属性关联表并收集id便于插入sku表
                        //判断是否有关联属性表是否有这id
                        $isSetRelation = $this->relationAttrModel->where(['product_id' => $productId,'attr_id' => $vv['attr_id'],'attr_val_id' => $vv['attr_value_id']])->first();
                        if (empty($isSetRelation))
                        {
                            //插入商品属性表
                            $relationId = $this->relationAttr->insertGetId($relationArr);
                        }else{
                            $relationId = $isSetRelation['rel_attr_id'];
                        }
                        $relaCombId[] = $relationId;

                    }
                    asort($relaCombId);
                    //把商品关联属性id数组转成字符串
                    $relationId = implode(',', $relaCombId);

                    //插入sku表

                    $prodSku = [];
                    $prodSku['prod_id']                 = $productId;
                    $prodSku['prod_attr_comb']          = $relationId;
                    $prodSku['prod_sku_sn']             = $v['prod_sku_sn'];
                    $prodSku['prod_sku_price']          = $v['prod_sku_price'];
                    $prodSku['prod_sku_cost']           = $v['prod_sku_cost'];
                    $prodSku['prod_sku_weight']         = $v['prod_sku_weight'];
                    $prodSku['prod_min_photo']          = $v['prod_min_photo'];
                    $prodSku['prod_max_photo']          = $v['prod_max_photo'];
                    $prodSku['prod_supplier_sn']        = $v['prod_supplier_sn'];
                    $prodSku['prod_sku_addp_info']      = $v['prod_sku_addp_info'];
                    $prodSku['prod_sku_onsale_status']  = $v['sku_onsale'];
                    //个性印刷需要加上书脊厚度
                    if ($postArr['is_personal_printing']) {
                        $prodSku['prod_spine_thickness'] = $v['prod_spine_thickness'];
                    }
                    $prodSku['prod_p_num']              = $postArr['prod_sku_arr'][$k]['p_value']??0;
                    $prodSku['created_at']              = time();

                    //插入商品sku表 并获取skuid waiting
                    $skuId = $this->repoSku->insertGetId($prodSku);



                    //插入销售渠道定价
                    if ($v['sale_channle_price']) {
                        $saleChannleArr = json_decode($v['sale_channle_price'], true);

                        foreach ($saleChannleArr as $kk => $vv) {
                            if ((isset($vv['channle_price']) && $vv['channle_price'] !== "") || (isset($vv['channle_add_price']) && $vv['channle_add_price'] !== "")) {
                                $saleChannleArrIn = [];
                                $saleChannleArrIn['mch_id'] = $postArr['mch_id'];
                                $saleChannleArrIn['prod_sku_id'] = $skuId;
                                $saleChannleArrIn['cha_id'] = $vv['channle_id'];
                                $saleChannleArrIn['cust_lv_id'] = $vv['customer_id'];
                                $saleChannleArrIn['sku_cust_lv_price'] = $vv['channle_price']??0;
                                $saleChannleArrIn['addp_price'] = $vv['channle_add_price']??0;
                                $saleChannleArrIn['created_at'] = time();
                                $this->custlevelPrice->insert($saleChannleArrIn);
                            }

                        }

                    }
                    //插入供货商定价
                    if ($v['supplier_price']) {
                        $supplerPriceArr = json_decode($v['supplier_price'], true);
                        foreach ($supplerPriceArr as $kk=>$vv){
                            if ((isset($vv['start_price']) && $vv['start_price'] !== "" ) || (isset($vv['add_price']) && $vv['add_price'] !== "" ))
                            {
                                $supplerPriceArrIn = [];
                                $supplerPriceArrIn['mch_id'] = $postArr['mch_id'];
                                $supplerPriceArrIn['prod_sku_id'] = $skuId;
                                $supplerPriceArrIn['sup_id'] = $vv['sup_id'];
                                $supplerPriceArrIn['sku_sup_price'] = $vv['start_price']??0;
                                $supplerPriceArrIn['addp_price'] = $vv['add_price']??0;
                                $supplerPriceArrIn['created_at'] = time();
                                $this->supPrice->insert($supplerPriceArrIn);
                            }


                        }

                    }


                }


            }
            \DB::commit();
            return true;
        } catch (CommonException $e) {
            \DB::rollBack();
            return [
                'code' => 0,
                'msg'  => $e->getMessage()

            ];
        }

    }


    /**
     * 通过sku获取商品所有的信息
     * @param $skuId
     * @return array
     */
   /* public function getGoodsAllInfo($skuId)
    {
        $data = [];
        //sku货品信息
        $skuInfo = $this->repoSku->getByIdFromCache($skuId);
        if (empty($skuInfo)) {
            Helper::EasyThrowException('40013', __FILE__ . __LINE__);
        }
        //获取当前sku属性组合的信息
        $standardSkuAttr = $this->getStandardSkuAttr($skuId);
        $skuInfo['attr_info'] = $standardSkuAttr;
        //获取当前skuP数信息
        $pageCount = 0;
        if (!empty($standardSkuAttr)) {
            $attrPageInfo = $this->getPageAttr();
            foreach ($standardSkuAttr as $k=>$v) {
                if ($v['attr_id'] == $attrPageInfo['attr_id']) {
                    $attrPageName = $v['attr_val_name'];
                    $pageCount = $this->getPageByAttr($attrPageName);
                }
            }
        }
        $data['sku'] = $skuInfo;
        //商品主信息
        $goodsInfo = $this->repoGoods->getByIdFromCache($skuInfo['prod_id']);
        if (empty($goodsInfo)) {
            Helper::EasyThrowException('40014', __FILE__ . __LINE__);
        }
        $data['goods_info'] = $goodsInfo;
        //取所有货品的数据
        $skuWhere[] = ['prod_id', $goodsInfo['prod_id']];
        $skuList = $this->repoSku->getList($skuWhere, 'prod_sku_id');
        $goodsSkuItems = [];
        foreach ($skuList as $k => $v) {
            $goodsSkuItems[$v['prod_sku_id']] = $this->getStandardSkuAttr($v['prod_sku_id']);
        }
        $data['sku_list'] = $goodsSkuItems;

        //商品印刷表数据
        $goodsPrintInfo = $this->pPrint->getRow(['prod_id' => $goodsInfo['prod_id']])->toArray();
        $data['print_info'] = $goodsPrintInfo;
        //商品规格信息
        $sizeInfo = [];
        if (!empty($goodsPrintInfo)) {
            $sizeInfo = $this->getGoodSizeInfo($goodsPrintInfo['prod_size_id'], $goodsInfo['prod_id']);
            //加入P数
            $data['print_info']['page_total'] = empty($pageCount)? $data['print_info']['prod_pt_min_p'] : $pageCount;
        }
        $data['size_info'] = $sizeInfo;
        $data['photos_list'] = $this->getGoodsPhotosList($goodsInfo['prod_id']);

        return $data;
    }*/

   /**
     * 通过sku获取商品所有的信息
     * @param $skuId
     * @return array
     */
    public function getGoodsAllInfo($skuId)
    {
        $data = [];
        //sku货品信息
        $skuInfo = $this->repoSku->getById($skuId);
        if (empty($skuInfo)) {
            Helper::EasyThrowException('40013', __FILE__ . __LINE__);
        }
        //获取当前sku属性组合的信息
        $standardSkuAttr = $this->getStandardSkuAttr($skuId);
        $skuInfo['attr_info'] = $standardSkuAttr;
        //获取当前skuP数信息
        $pageCount = 0;
        if (!empty($standardSkuAttr)) {
            $attrPageInfo = $this->getPageAttr();
            foreach ($standardSkuAttr as $k=>$v) {
                if ($v['attr_id'] == $attrPageInfo['attr_id']) {
                    $attrPageName = $v['attr_val_name'];
                    $pageCount = $this->getPageByAttr($attrPageName);
                }
            }
        }
        $data['sku'] = $skuInfo;
        //商品主信息
        $goodsInfo = $this->repoGoods->getById($skuInfo['prod_id']);
        if (empty($goodsInfo)) {
            Helper::EasyThrowException('40014', __FILE__ . __LINE__);
        }
        $data['goods_info'] = $goodsInfo;
        //取所有货品的数据
        $skuWhere[] = ['prod_id', $goodsInfo['prod_id']];
        $skuList = $this->repoSku->getList($skuWhere, 'prod_sku_id');
        $goodsSkuItems = [];
        foreach ($skuList as $k => $v) {
            $goodsSkuItems[$v['prod_sku_id']] = $this->getStandardSkuAttr($v['prod_sku_id']);
        }
        $data['sku_list'] = $goodsSkuItems;

        //商品印刷表数据
        $goodsPrintInfo = $this->pPrint->getRow(['prod_id' => $goodsInfo['prod_id']])->toArray();
        $data['print_info'] = $goodsPrintInfo;
        //商品规格信息
        $sizeInfo = [];
        if (!empty($goodsPrintInfo)) {
            $sizeInfo = $this->getGoodSizeInfo($goodsPrintInfo['prod_size_id'], $goodsInfo['prod_id']);
            //加入P数
            $data['print_info']['page_total'] = empty($pageCount)? $data['print_info']['prod_pt_min_p'] : $pageCount;
        }
        $data['size_info'] = $sizeInfo;
        $data['photos_list'] = $this->getGoodsPhotosList($goodsInfo['prod_id']);

        return $data;
    }

    /**
     * 通过skuId获取标准sku的数据
     * @param $skuId
     * @return array
     */
    public function getStandardSkuAttr($skuId)
    {
        $skuAttr = $this->relationAttr->getProductsSkuAttr($skuId);

        $list = [];
        if (!empty($skuAttr)) {
            $arrAttrName = explode(',', $skuAttr['attr_name']);
            $arrAttrVlaId = explode(',', $skuAttr['attr_value_id']);
            $arrAttrValName = explode(',', $skuAttr['attr_value_name']);
            $arrAttrId      = explode(',', $skuAttr['attr_id']);

            foreach ($arrAttrName as $k => $v) {
                $list[$arrAttrVlaId[$k]]['attr_id'] = $arrAttrId[$k];
                $list[$arrAttrVlaId[$k]]['attr_val_id'] = $arrAttrVlaId[$k];
                $list[$arrAttrVlaId[$k]]['attr_name'] = $arrAttrName[$k];
                $list[$arrAttrVlaId[$k]]['attr_val_name'] = $arrAttrValName[$k];
            }
        }
        return $list;
    }

    /**
     * 获取商品规格信息
     * @param $sizeId 规格id
     * @param $goodsId 商品id
     * @return array
     */
    public function getGoodSizeInfo($sizeId, $goodsId)
    {
        $sizeInfo = $this->prodSize->getSizeCombInfo($sizeId, $goodsId);
        return $sizeInfo;
    }


    /**
     * 获取商品图片信息
     * @param $goodsId
     * @return array
     */
    public function getGoodsPhotosList($goodsId)
    {
        $where[] = ['prod_id', $goodsId];
        $where[] = ['prod_md_type', GOODS_MEDIA_PHOTOS];
        $photos = $this->media->getList($where, 'sort', 'asc');

        if (!isset($photos[0]['prod_md_path']) && empty($photos[0]['prod_md_path'])) {
            $coverUrl = '';
        } else {
            if (strpos($photos[0]['prod_md_path'], 'http://') !== false || strpos($photos[0]['prod_md_path'], 'https://') !== false ) {
                $coverUrl = $photos[0]['prod_md_path'];
            } else {
                $coverUrl = config('common.static_url')."/".$photos[0]['prod_md_path'];
            }
        }

        return [
            'cover' => $coverUrl,
            'all' => $photos
        ];
    }

    /**
     * 根据p数属性获取当前sku的P数
     * @param $PageAttr 当前属性值 如 7P
     * @return int
     */
    public function getPageByAttr($PageAttr)
    {
        preg_match('/(\d+)/',$PageAttr, $match);

        return $match[0] ??0;
    }

    /**
     * 获取P数所对应的属性记录
     */
    public function getPageAttr()
    {
        $pageAttr = $this->attr->getPageAttr();
        return $pageAttr;
    }


    /**
     * 获取sku货品指定页数对应的书脊厚度
     * @param $skuId
     * @param int $pageCount 总页数
     * @return float
     */
    public function getGoodsSpineThickness($skuId, $pageCount = 0)
    {
        $skuInfo = $this->repoSku->getByIdFromCache($skuId);

        if (empty($skuInfo)) {
            Helper::EasyThrowException('40013', __FILE__ . __LINE__);
        }

        $goodsInfo = $this->repoGoods->getByIdFromCache($skuInfo['prod_id']);
        if (empty($goodsInfo)) {
            Helper::EasyThrowException('40014', __FILE__ . __LINE__);
        }

        //商品印刷表数据
        $goodsPrintInfo = $this->pPrint->getRow(['prod_id' => $goodsInfo['prod_id']])->toArray();

        //非印品无书脊
        if (empty($goodsPrintInfo)) {
            return 0.0;
        }

        //不加减p的情况
        if (empty($goodsPrintInfo['prod_pt_variable'])) {
            return $skuInfo['prod_spine_thickness'];
        } else {
            $addpInfo = $skuInfo['prod_sku_addp_info'];
            $arrAddpInfo = explode('|', $addpInfo);
            $basepThickness = isset($arrAddpInfo[3]) ? $arrAddpInfo[3]:0;

            if ($pageCount < $goodsPrintInfo['prod_pt_min_p']) {
                Helper::EasyThrowException('40016', __FILE__ . __LINE__);
            }
            //起始P数的厚度+加P的厚度
            $thickness = round($skuInfo['prod_spine_thickness'] + ($pageCount - $goodsPrintInfo['prod_pt_min_p'])*($basepThickness/$goodsPrintInfo['prod_pt_variable_base']),1);

        }

        return $thickness;
    }

    /**
     * 通过规格id和商品附加条件获取附合条件的商品列表
     * @param int $sizeId 规格id ,为0的情况取全部
     * @param $where
     * @return array
     */
    public function getGoodsListBySize($sizeId = 0, $where)
    {
        //获取商品规格下的商品
        $sizeWhere = [];
        if (!empty($sizeId)) {
            $sizeWhere[] = ['prod_size_id', $sizeId];
        }

        $goodsPrintList = $this->pPrint->getList($sizeWhere, 'prod_pt_id', 'asc')->toArray();
        $arrGoodsId = array_column($goodsPrintList, 'prod_id');
        $arrSizeToGoods = array_column($goodsPrintList, 'prod_size_id', 'prod_id');

        //获取当前规格下包含的商品id
        $goodsWhere['prod_id'] = $arrGoodsId;
        $goodsWhere = array_merge($goodsWhere, $where);
        $goodList = $this->repoGoods->getRows($goodsWhere, 'prod_id', 'desc')->toArray();

        foreach ($goodList as $k=>$v) {
            $goodList[$k]['size_id'] = $arrSizeToGoods[$v['prod_id']];
        }

        return $goodList;
    }


    /**
     * 通过skuId获取商品信息
     * @param $skuId
     * @return array
     */
    public function getGoodsBySku($skuId)
    {
        return $this->repoSku->getByIdFromCache($skuId);
    }

    /**
     * 获取当前商品id合成配置信息
     * @param $goodsId 商品id
     */
    public function getCompoundSetting($goodsId)
    {
        $mongo = new Mongo();
        $info = $mongo->select('compound_setting', ['goods_id' => strval($goodsId)]);

        if (empty($info)) {
            Helper::EasyThrowException('40017', __FILE__ . __LINE__);
        }

        return $info[0]['info'];
    }

    /**
     * 同步标准商品配置到oms的商品中
     * @param $goodsId oms商品的id
     * @return mixed
     */
    public function syncCmsCompoundSettingToOms($goodsId)
    {
        //获取原始商品id
        $goodsInfo = $this->repoGoods->getRow(['prod_id' => $goodsId], ['parent_prod_id']);
        $parentProdId = $goodsInfo['parent_prod_id'];

        if (empty($parentProdId)) {
            return -1;  //不存在父级商品
        }

        $info = $this->getCompoundSetting($parentProdId);

        $data['goods_id'] = strval($goodsId);
        $data['info'] = $info;
        $mongo = new Mongo();

        $exists = $mongo->select('compound_setting', ['goods_id' => strval($goodsId)]);

        if (!empty($exists)) {  //更新记录
            $mongo->update('compound_setting', ['info' =>$info], ['goods_id' => strval($goodsId)]);
        } else {  //添加记录
            $mongo->insert('compound_setting', $data);
        }

        return 1;
    }

}