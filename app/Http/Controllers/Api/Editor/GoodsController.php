<?php
namespace App\Http\Controllers\Api\Editor;

use App\Exceptions\CommonException;
use App\Repositories\SaasCategoryRepository;
use App\Repositories\SaasProductsRepository;
use App\Repositories\SaasProductsSizeRepository;
use App\Services\Goods\Info;
use Illuminate\Http\Request;

/**
 * 商品相关接口
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/28
 */
class GoodsController extends BaseController
{
    /**
     * @param Request $request
     * @param Info $objGoods
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGoods(Request $request, Info $objGoods,SaasProductsRepository $productsRepo)
    {
        try {
            $goodId     = $request->input('goods_id');
            $skuId      = $request->input('product_id');

            $goodsDetail = $objGoods->getGoodsAllInfo($skuId);

            //获取商品简称，如果没有就取全称 by .david
            $prodAbbr = $productsRepo->getProductAbbr($goodId);
            //数据组装
            $return['name'] = $prodAbbr;//$goodsDetail['goods_info']['prod_name'];
            $return['allow_page_adjustment'] = $goodsDetail['print_info']['prod_pt_variable'] ?? 0;
            $return['add_page_span'] =$goodsDetail['print_info']['prod_pt_variable_base'] ?? 0;
            $return['type_id'] =  $goodsDetail['goods_info']['prod_cate_uid'];
            $return['thumb_url'] = !empty($goodsDetail['photos_list']['cover']) ? $goodsDetail['photos_list']['cover']:'';

            $return['min_image_count'] = $goodsDetail['sku']['prod_min_photo'] ?? 0;
            $return['max_image_count'] = $goodsDetail['sku']['prod_max_photo'] ?? 0;
            $return['page_count'] = $goodsDetail['print_info']['page_total'];
            $return['max_page_count'] = $goodsDetail['print_info']['prod_pt_max_p'];

            //属性
            $return['product_item'] = $this->getSkuItem( $goodsDetail['sku']);
            $return['product_item_list'] = $this->getSkuItems($goodsDetail['sku_list']);
            $return['size_item'] = $this->formatSizeInfo($goodsDetail['size_info']);

            return $this->success([$return]);

        } catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    /**
     * 获取商品规格列表
     * @param Request $request
     * @param SaasProductsSizeRepository $repoSize
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGoodsSizeList(Request $request, SaasProductsSizeRepository $repoSize)
    {
        try {
            $mchId    = $request->input('sp_id') ?? 0;
            $cateId   = $request->input('type_id') ?? -1;

            $cateId = $cateId == -1 ? 0 : $cateId;
            $where = [];
            if (!empty($cateId)) {
                $where = ['size_cate_id' => $cateId];
            }
            $specList = $repoSize->getSizeInfoByMid($mchId,$where);

            $list = [];
            foreach ($specList as $k=>$v) {
                $res = $repoSize->getSizeCombInfo($v['size_id']);
                $list['list'][] = $this->formatSizeInfo($res);
            }

            return $this->success([$list]);
        }catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }

    }

    /**
     * 获取书脊厚度
     * @param Request $request
     * @param Info $objGoods
     * @return mixed
     */
    public function getGoodsThickness(Request $request, Info $objGoods)
    {
        try {
            $skuId = $request->input('product_id');

            //如果是做模板的情况下默认
            if ($skuId == -1 || empty($skuId)) {
                $thickness = config('common.goods_default_thickness');
            } else {
                $thickness = $objGoods->getGoodsSpineThickness($skuId, $request->input(['page_count']));
            }
            return $this->success([$thickness]);
        }catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }

    }

    /**
     * 获取商品列表信息
     * @param Request $request
     * @param Info $objGoods
     * @param SaasCategoryRepository $cate
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGoodsList(Request $request, Info $objGoods, SaasCategoryRepository $cate)
    {
        try {
            $mchId  = $request->input('sp_id') ?? 0;
            $sizeId = $request->input('type_id');
            if ($sizeId <=0) {
                $sizeId = 0;
            }
            //超级商户的情况
            $mchId = $mchId == -1 ? 0 : $mchId;

            $where = ['mch_id'=>$mchId];

            //超级商户的情况
//            if ($mchId == -1) {
//                $where['mch_id'] = ZERO;
//                $where['parent_prod_id'] = ZERO;
//            }

            //获取商品详情
            $goodsList = $objGoods->getGoodsListBySize($sizeId, $where);

            //获取分类信息
            $cateInfo  = $cate->getRows(['cate_level' => CATEGORY_NO_THREE], 'cate_id')->toArray();
            $cateKv    = array_column($cateInfo, 'cate_name', 'cate_id');
            $return = [];

            foreach ($goodsList as $k=>$v) {
                $photoList = $objGoods->getGoodsPhotosList($v['prod_id']);

//                if (empty($photoList['cover'])) {
//                    $coverUrl = '';
//                } else {
//                    if (strpos($photoList['cover'], 'http://') !== false || strpos($photoList['cover'], 'https://') !== false ) {
//                        $coverUrl = $photoList['cover'];
//                    } else {
//                        $coverUrl = config('common.static_url')."/".$photoList['cover'];
//                    }
//                }

                $return['list'][$k]['id'] = $v['prod_id'];
                $return['list'][$k]['name'] = $v['prod_name'];
                $return['list'][$k]['type_id'] = $v['prod_cate_uid'];
                $return['list'][$k]['type_name'] = $cateKv[$v['prod_cate_uid']];
                $return['list'][$k]['status'] = $v['prod_onsale_status'];
                $return['list'][$k]['thumb_url'] = $photoList['cover'];
                $sizeInfo = $objGoods->getGoodSizeInfo($v['size_id'], $v['prod_id']);
                $return['list'][$k]['size_item'] = $this->formatSizeInfo($sizeInfo);
            }

            return $this->success([$return]);

        }catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    /**
     * 获取产品类型列表
     * @param Request $request
     * @param SaasCategoryRepository $cate
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGoodsTypeList(Request $request, SaasCategoryRepository $cate)
    {
        try {
            //个性印刷的id
            $where = ['cate_flag'=>GOODS_PRINTER_CATEGORY_DIY];
            $personalDiyInfo = $cate->getrow($where);
            $personalDiyId = $personalDiyInfo['cate_id'];

           //获取分类列表
            $cateWhere['cate_parent_id'] = $personalDiyId;
            $diyCateInfo = $cate->getRows($cateWhere, 'cate_id', 'asc');

            $list['list'] = [];
            foreach ($diyCateInfo as $k=>$v) {
                $list['list'][$k]['id']   = $v['cate_id'];
                $list['list'][$k]['name'] = $v['cate_name'];
            }
            return $this->success([$list]);

        } catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }


    /**
     * 前端所需的数据格式
     * @param $skuInfo
     * @return array
     */
    private function getSkuItem($skuInfo)
    {
        $info['id']     = $skuInfo['prod_sku_id'];
        $info['name']   = '';
        $attrInfo = [];
        foreach ($skuInfo['attr_info'] as $k=>$v) {
            $attrInfo[$k]['id'] = $v['attr_val_id'];
            $attrInfo[$k]['name'] = $v['attr_name'];
            $attrInfo[$k]['value'] = $v['attr_val_name'];
            $attrInfo[$k]['thumb_url'] ='';
        }
        $info['infos'] = array_values($attrInfo);
        return $info;
    }

    /**
     * @param $skuInfo
     * @return array
     */
    private function getSkuItems($skuInfo)
    {
        $list = [];
        foreach ($skuInfo as $k=>$v) {
            $info['prod_sku_id'] = $k;
            $info['attr_info'] = $v;
            $list[] = $this->getSkuItem($info);
        }
        return $list;
    }


}