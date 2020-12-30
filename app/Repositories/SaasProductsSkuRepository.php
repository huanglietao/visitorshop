<?php
namespace App\Repositories;
use App\Models\SaasCategory;
use App\Models\SaasProducts;
use App\Models\SaasProductsPrint;
use App\Models\SaasProductsSku;
use Illuminate\Support\Facades\DB;

/**
 * 商品sku仓库模板
 * 商品sku仓库模板
 * @author: hlt <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/04/07
 */
class SaasProductsSkuRepository extends BaseRepository
{

    public function __construct(SaasProductsSku $model,SaasProductsPrint $printModel)
    {
        $this->model = $model;
        $this->printModel = $printModel;
    }

    /**
     * Author: LJH
     * Date: 2020/4/25
     * Time: 16:31
     * @param $prod_id
     * @param null $prod_attr_comb
     * @return mixed
     * use_address: Agent/Goods/DetailController
     */
    public function getProductPrice($prod_id)
    {
        //返回多个货品价格信息
        $price = $this->model->where(['prod_id' => $prod_id])->select('prod_sku_id')->get();
        $product_price = json_decode($price, true);
        foreach ($product_price as $key => $value) {
            $product_price[$key] = $value['prod_sku_id'];
        }
        return $product_price;
    }

    //获取指定规格的价格
    public function getPrice($prod_id)
    {
        $price = $this->model->where(['prod_id' => $prod_id])->get();
        $prod_sku = json_decode($price, true);
        return $prod_sku;
    }


    /**
     * Author: LJH
     * Date: 2020/4/26
     * Time: 10:29
     * @param $prod_sku_id
     * @param null $prod_sku_sn
     * @return mixed
     */
    public function getProductSkuInfo($prod_sku_id,$prod_sku_sn=null)
    {
        if(empty($prod_sku_sn)){
            $skuInfo = $this->model->where(['prod_sku_id'=>$prod_sku_id])->get()->toArray();
        }else{
            $skuInfo = $this->model->where(['prod_sku_id'=>$prod_sku_id,'prod_sku_sn'=>$prod_sku_sn])->get()->toArray();
        }
        return $skuInfo;
    }

    /**
     * 根据属性参数和商品id返回prod_sku_id
     * Author: LJH
     * Date: 2020/4/26
     * Time: 10:37
     * @param $prod_id
     * @param $prod_attr_comb
     * @return mixed
     */
    public function getSkuId($prod_id,$prod_attr_comb)
    {
        $prod_sku_info = $this->model->where(['prod_id' => $prod_id, 'prod_attr_comb' => $prod_attr_comb,'prod_sku_onsale_status'=>1])->select('prod_sku_id')->get()->toArray();
        $prod_sku_id = $prod_sku_info;
        return $prod_sku_id;
    }

    /**
     * 根据货号返回prod_sku_id
     * Author: LJH
     * Date: 2020/4/26
     * Time: 10:38
     * @param $prod_sku_sn
     * @return mixed
     */
    public function getProdSkuId($prod_sku_sn,$mid)
    {
        $prod_sku_info = [];
//        $prod_sku_info = $this->model->where(['prod_sku_sn' => $prod_sku_sn])->select('prod_sku_id','prod_id')->get()->toArray();

        $prod_sku_info = $this->getSkuInfo($prod_sku_sn,$mid);
        if (empty($prod_sku_info))
        {
            $firstStr = mb_substr($prod_sku_sn,0,1,'utf-8');
            //判断是否有套餐商品的货号标识
            if ($firstStr == PACKAGE_SN){
                //含有套餐货号的商品，将套餐货号去除重新尝试查找货品
                $prod_sku_sn = str_replace(PACKAGE_SN,'',$prod_sku_sn);
                $prod_sku_info = $this->getSkuInfo($prod_sku_sn,$mid);
            }
        }
        //判断是否为冲印商品
        if (empty($prod_id_arr))
        {
            //为空先判断是否为冲印商品
            $firstStr = strstr($prod_sku_sn,SINGLE_SN);
            //判断是否有冲印商品的货号标识'-'
            if ($firstStr){
                //含有冲印货号标识的商品，截取货号标识前面的字符串作为货号
                $prod_sku_sn = substr($prod_sku_sn,0,strrpos($prod_sku_sn,SINGLE_SN));
                $prod_sku_info = $this->getSkuInfo($prod_sku_sn,$mid);
            }
        }


        return $prod_sku_info;
    }

    public function getSkuInfo($prod_sku_sn,$mid)
    {
        $prod_sku_info = $this->model
            ->where(['saas_products_sku.prod_sku_sn' => $prod_sku_sn])
            ->leftJoin('saas_products', 'saas_products_sku.prod_id', '=', 'saas_products.prod_id')
            ->where(['saas_products.mch_id' => $mid])
            ->whereNull('saas_products.deleted_at')
            ->whereNull('saas_products_sku.deleted_at')
            ->select('saas_products_sku.prod_id','saas_products_sku.prod_sku_id','saas_products_sku.prod_sku_sn','saas_products.mch_id')
            ->get()
            ->toArray();
        return $prod_sku_info;
    }


    //获取商品对应货品信息详情
    public function getSkuDetail($prodId)
    {
        $skuArr = $this->model->where(['prod_id' => $prodId])->get()->toArray();

        foreach ($skuArr as $k => $v)
        {
            if ($v['prod_attr_comb'])
            {
                //sku商品，取出属性值
                $relationRepository = app(SaasProductsRelationAttrRepository::class);
                //获取属性
                $skuAttrArr = $relationRepository->getProductsSkuAttr($v['prod_sku_id']);
                $skuArr[$k]['attr_value_id'] = $skuAttrArr['attr_value_id'];
                $skuArr[$k]['attr_value_name'] = $skuAttrArr['attr_value_name'];
            }
            if ($v['prod_sku_addp_info'])
            {
                //加减p商品
                $addInfo = explode('|',$v['prod_sku_addp_info']);
                $skuArr[$k]['add_p_price'] = $addInfo[0]??"";
                $skuArr[$k]['add_p_cost'] = $addInfo[1]??"";
                $skuArr[$k]['add_p_weight'] = $addInfo[2]??"";
                if (isset($addInfo[3])){
                    $skuArr[$k]['add_p_spine_thickness'] = $addInfo[3]?number_format($addInfo[3],1):0;
                }else{
                    $skuArr[$k]['add_p_spine_thickness'] = 0;
                }

            }


        }
        return $skuArr;

    }

    //根据商品id和货品属性获取货品id和货品P数
    public function getProdSku($prod_id,$prod_attr_comb)
    {
        $prod_sku_info = $this->model->where(['prod_id' => $prod_id, 'prod_attr_comb' => $prod_attr_comb,'prod_sku_onsale_status'=>1])->first();
        if(empty($prod_sku_info['prod_p_num'])){
            $prod_p_num = $this->printModel->where(['prod_id'=>$prod_id])->select('prod_pt_min_p')->first();
            $prod_sku_info['prod_p_num'] = $prod_p_num['prod_pt_min_p'];
        }
        return $prod_sku_info;
    }

    //根据货号获取商品类型（实物或者个性印品）
    public function getGoodstype($sku_sn,$mid)
    {

        $prod_id_arr = $this->getProdSkuId($sku_sn,$mid);

        if (!empty($prod_id_arr))
        {
            $prod_id = $prod_id_arr[0]['prod_id'];
            $sku_id  = $prod_id_arr[0]['prod_sku_id'];
            //分类
            $categoryRepository = app(SaasCategoryRepository::class);
            $categoryModel = app(SaasCategory::class);
            $goodsModel = app(SaasProducts::class);
            //获取商品对应的分类
            $goodsCate = $goodsModel->where(['prod_id' => $prod_id])->value('prod_cate_uid');
            if ($goodsCate)
            {
                    //获取商品三级分类
                    $thirdType = $categoryModel->where('cate_id',$goodsCate)->where(['cate_level' => 3])->value('cate_flag');

                   $par_id = $categoryRepository->getParentId($goodsCate,'goods');
                   $par_id = explode(',',$par_id);
                    $goodsType = $categoryModel->whereIn('cate_id',$par_id)->where(['cate_level' => 1])->value('cate_flag');
                    if (is_null($goodsType))
                    {
                        return [
                            'code' => 0,
                            'msg'  => "货号".$sku_sn."所属一级分类未找到"

                        ];
                    }
                    return [
                        'code' => 1,
                        'goods_type' => $goodsType,
                        'sku_id'     => $sku_id,
                        'third_type' => $thirdType
                    ];
            }else{
                return [
                    'code' => 0,
                    'msg'  => "货号".$sku_sn."所属分类找不到"
                ];
            }
        }else{
            return [
                'code' => 0,
                'msg'  => "货号".$sku_sn."找不到"
            ];
        }
    }


}

