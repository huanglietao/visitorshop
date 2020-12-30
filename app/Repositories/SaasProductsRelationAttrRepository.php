<?php
namespace App\Repositories;

use App\Models\SaasProductsRelationAttr;
use App\Models\SaasProductsSku;
use Illuminate\Support\Facades\DB;
use App\Models\SaasProductsAttribute;
use App\Models\SaasAttributeValues;

/**
 * 商品sku联系表仓库模板
 * @author: cjx
 * @version: 1.0
 * @date:  2020/04/22
 */
class SaasProductsRelationAttrRepository extends BaseRepository
{
    protected $skuModle;

    public function __construct(SaasProductsRelationAttr $model,SaasProductsSku $productsSku,SaasProductsAttribute $attrModel,
                        SaasAttributeValues $attrValueModel
)
    {
        $this->model = $model;
        $this->skuModle = $productsSku;
        $this->attrModel = $attrModel;
        $this->attrValueModel = $attrValueModel;
    }

    /**
     *  传入货品id获得属性(格式如: "颜色:优雅黑,纸张:200g双铜纸")
     *  param $sku_id 商品skuId
     * @return string
     */
    public function getProductAttr($sku_id)
    {
        $str = '';
        $sku_info = $this->skuModle->where("prod_sku_id",$sku_id)->select('prod_attr_comb')->first();
        if(empty($sku_info['prod_attr_comb'])){
            return $str;
        }
        $arr = explode(",",$sku_info['prod_attr_comb']);

        foreach ($arr as $k=>$v){
            $data = $this->model->with(['attr','attrValue'])->where('rel_attr_id',$v)->first();

            $attr_name = !empty($data['attr']['attr_name']) ? $data['attr']['attr_name'] : '无';
            $attr_val_name = !empty($data['attrValue']['attr_val_name']) ? $data['attrValue']['attr_val_name'] : '无';
            if($k == count($arr) -1){
                $str .= $attr_name.' : '.$attr_val_name;

            }else{
                $str .= $attr_name.' : '.$attr_val_name.'，';

            }
        }
        return $str;
    }

    //获取商品属性值
    public function getAttribute($pro_id)
    {
        $attribute_info = [];
        //找到商品关联的属性
        $attr = $this->model->where('product_id',$pro_id)->select('rel_attr_id','attr_val_id','attr_id')->get()->groupBy('attr_id');
        $row = json_decode($attr,true);


        foreach ($row as $key => $value){

            //查找属性名称
            $attr_name = DB::table('saas_products_attribute')->where('attr_id',$key)->select('attr_name')->get();
            $attr_name = json_decode($attr_name,true)[0];
            $attr_val = [];
            $rel_attr_id = [];
            //查找属性下的具体数值
            foreach ($value as $k => $v){
                $attr_val_name = DB::table('saas_attribute_values')->where('attr_val_id',$v['attr_val_id'])->select('attr_val_name')->get();
                $attr_val_name = json_decode($attr_val_name,true)[0];
                $attr_val[$k] = $attr_val_name['attr_val_name'];
                $rel_attr_id[$k] = $v['rel_attr_id'];
            }

            //数据整合
            $attribute_info[$key]['attr_name']= $attr_name['attr_name'];
            $attribute_info[$key]['attr_val_name']= $attr_val;
            $attribute_info[$key]['rel_attr_id'] = $rel_attr_id;

        }


        return $attribute_info;
    }

    //获取商品列表编辑是的属性列表与该商品属性选中值
    public function getAllAttribute($pro_id,$mid = PUBLIC_CMS_MCH_ID)
    {
        $attribute_info = [];
        //找到商品关联的属性
        $attr = $this->model->where('product_id',$pro_id)->select('rel_attr_id','attr_val_id','attr_id')->get()->toArray();
        foreach ($attr as $k => $v){

            $attribute_info['attr_id'][] = $v['attr_id'];
            $attribute_info['attr_val_id'][] = $v['attr_val_id'];
        }
        //去重
        $attribute_info['attr_id']     = array_unique($attribute_info['attr_id']);
        $attribute_info['attr_val_id'] = array_unique($attribute_info['attr_val_id']);

        //取出属性列表
        $attribute = [];
        $attr_name = [];
        $prodAttrArr = $this->attrModel->whereIn('attr_id',$attribute_info['attr_id'])->select('attr_id','attr_name','attr_flag')->get()->toArray();
        foreach ($prodAttrArr as $k => $v)
        {

            $attribute[$v['attr_id']]['attr_name'] = $v['attr_name'];
            $valueArr = $this->attrValueModel->where(['attr_id' => $v['attr_id']])->select('attr_val_id','attr_val_name')->get()->toArray();
            if ($v['attr_flag'] == GOODS_ATTR_PAGE_FLAG)
            {
                //p数属性排序
                $attribute[$v['attr_id']]['attr_value'] = $this->arraySort($valueArr,'attr_val_name');
            }else{
                $attribute[$v['attr_id']]['attr_value'] = $valueArr;
            }

            $attr_name[] = $v['attr_name'];
        }



        $return = [
            'attribute' => $attribute,
            'attr_value' => $attribute_info['attr_val_id'],
            'attr_name'  => implode(',',$attr_name),
        ];

        return $return;
    }

    //获取货品的属性值id
    public function getProductsSkuAttr($sku_id)
    {
        $sku_info = $this->skuModle->where("prod_sku_id",$sku_id)->select('prod_attr_comb')->first();
        $arr = [];
        if (!empty($sku_info)){
            $arr = explode(",",$sku_info['prod_attr_comb']);
        }
        $data = $this->model->with(['attr','attrValue'])->whereIn('rel_attr_id',$arr)->get()->toArray();
        if (!empty($data))
        {
            foreach ($data as $k=>$v){
                $array['attr_name'][$v['attr_id']] = $v['attr']['attr_name'];
                $array['attr_value_id'][$v['attr_id']] = $v['attr_val_id'];
                $array['attr_value_name'][$v['attr_id']] = $v['attr_value']['attr_val_name'];
                $array['attr_id'][$v['attr_id']] = $v['attr_id'];
            }
			ksort($array['attr_name']);
			ksort($array['attr_value_id']);
			ksort($array['attr_value_name']);
			ksort($array['attr_id']);

			return [
				'attr_id'       => implode(',',$array['attr_id']),
				'attr_name'     => implode(',',$array['attr_name']),
				'attr_value_id' => implode(',',$array['attr_value_id']),
				'attr_value_name' => implode(',',$array['attr_value_name'])
			];
        }else{
            return [];
        }

    }
    //获取rel_attr_id
    public function getRelationId($where)
    {
        return $this->model->where($where)->value('rel_attr_id');
    }

}

