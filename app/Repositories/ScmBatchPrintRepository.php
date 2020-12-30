<?php
namespace App\Repositories;

use App\Models\SaasAreas;
use App\Models\SaasCategory;
use App\Models\SaasDelivery;
use App\Models\SaasExpress;
use App\Models\SaasProducts;
use App\Models\SaasProductsSku;
use App\Models\ScmBatchPrint;
use App\Services\Helper;
use Illuminate\Support\Facades\Redis;

/**
 * 供货商批量打单仓库
 * @author: cjx
 * @version: 1.0
 * @date: 2020/06/09
 */

class ScmBatchPrintRepository extends BaseRepository
{
    protected $mch_id;

    public function __construct(ScmBatchPrint $model,SaasAreas $areas,SaasDelivery $delivery,SaasProducts $products,SaasCategory $category,SaasProductsSku $productsSku,SaasExpress $express)
    {
        $this->mch_id = isset(session("admin")['mch_id']) ? session("admin")['mch_id'] : PUBLIC_CMS_MCH_ID;
        $this->model =$model;
        $this->areasModel =$areas;
        $this->deliveryModel =$delivery;
        $this->productModel =$products;
        $this->categoryModel =$category;
        $this->skuModel =$productsSku;
        $this->expressModel =$express;
    }


    /**
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getTableList($limit=null, $order=null, $sku_id=null, $tab)
    {
        $limit = !empty($limit) ? $limit : config('common.page_limit');  //这个10取配置里的

        //order 必须以 'id desc'这种方式传入.
        $orderBy = [];
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }
        $query = $this->model;

        if(!empty($order)) {
            $query =  $query->orderBy($orderBy[0],$orderBy[1]);
        }

        if($tab == 'printed'){
            //已打印
            $where['is_print'] = PUBLIC_YES;
        }else if($tab == 'not_printed'){
            //未打印
            $where['is_print'] = PUBLIC_NO;
        }

        if(isset($where)){
            $list = $query->where($where)->limit($limit)->get()->toArray();

        }else{
            $list = $query->limit($limit)->get()->toArray();

        }

        foreach ($list as $k=>$v){

            if(!empty($sku_id)){
                if(!in_array($v['sku_id'],json_decode($sku_id,true))){
                    unset($list[$k]);
                    continue;
                }
            }

            //有错误信息的订单不显示
           /* if(!empty(Redis::get("error".$v['order_no']))){
                if($v['is_print'] !== 2){
                    $this->model->where(['order_no'=>$v['order_no']])->update(['is_print'=>2]);
                }
                unset($list[$k]);
                continue;
            }*/


            //地区id转换
            $list[$k]['receiver_info'] = $this->exchangeArea($v['print_rcv_province'],$v['print_rcv_city'],$v['print_rcv_area']);

            //快递方式
            $list[$k]['delivery_name'] = $this->deliveryModel->where('delivery_id',$v['order_delivery_id'])->value('delivery_name');
            $list[$k]['express_code'] = $this->getExpressCode($v['order_delivery_id']);

            //匹配错误信息
            $list[$k]['error_msg'] = Redis::get("error".$v['order_no']);

            //供货商码
            $list[$k]['prod_supplier_sn'] = $this->skuModel->where('prod_sku_id',$v['sku_id'])->value('prod_supplier_sn');
        }
        return $list;
    }


    /**
     * 新增/修改
     * @param $data
     * @return boolean
     */
    public function save($data)
    {
        if(empty($data['tag_id'])){
            unset($data['tag_id']);
            $ret = $this->model->create($data);
        } else {
            $priKeyValue = $data['tag_id'];
            unset($data['tag_id']);

            $data['updated_at'] = time();
            $ret =$this->model->where('tag_id',$priKeyValue)->update($data);
        }
        return $ret;
    }

    /**
     * 删除(软删除)
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $model = $this->model->find($id);
        $model->delete();

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }

    /**
     * 地区ID转换名称
     * @param ID  $province 省 $city 市 $area 区
     * @return array
     */
    public function exchangeArea($province,$city,$area)
    {
        $p = $this->areasModel->where('area_id',$province)->value('area_name');
        $c = $this->areasModel->where('area_id',$city)->value('area_name');
        $a = $this->areasModel->where('area_id',$area)->value('area_name');

        return ['p'=>$p,'c'=>$c,'a'=>$a];
    }

    /**
     * 获取供应商码列表
     * @return array
     */
    public function getSupplierSnList()
    {
        //实物分类id
        $res = $this->categoryModel->where('cate_flag',GOODS_MAIN_CATEGORY_ENTITY)->select('cate_id')->get()->toArray();
        $entity_cate_ids = array_column($res,'cate_id');

        //实物商品(mch_id=0)
        $result = $this->productModel->where(['prod_onsale_status'=>PRODUCT_ON,'mch_id'=>PUBLIC_CMS_MCH_ID])->whereIn('prod_cate_uid',$entity_cate_ids)->select('prod_id')->get()->toArray();
        $entity_product_ids = array_column($result,'prod_id');

        //获取实物商品(mch_id=0)对应供货商码
        $list = $this->skuModel->whereIn('prod_id',$entity_product_ids)->select('prod_supplier_sn')->get()->toArray();
        $sp_sn_list = array_column($list,'prod_supplier_sn');

        //获取供货商码对应所有货品ID
        $arr = [];
        foreach ($sp_sn_list as $k=>$v){
            $arr[$k]['prod_supplier_sn'] = $v;
            $sku_info = $this->skuModel->where('prod_supplier_sn',$v)->get()->toArray();
            foreach ($sku_info as $key=>$val){
                $arr[$k]['sku_ids'][$key] = $val['prod_sku_id'];
            }

        }
        return $arr;
    }

    /**
     * 根据快递id确定快递方式代码(如"YTO","SF"等等)
     * param $delivery_id delivery表id
     * @return string 如"YTO"
     */
    public function getExpressCode($delivery_id)
    {
        $delivery_list = $this->deliveryModel->where('delivery_id',$delivery_id)->value('delivery_express_list');
        $delivery_list = explode(',',$delivery_list);
        $express_code = $this->expressModel->whereIn('express_id',$delivery_list)->orderBy('weight','desc')->value('express_code');

        return strtoupper($express_code);
    }

    /**
     * 统计数量
     *  param $sku_ids 当前实物商品sku_id
     * @return array
     */
    public function getCount($sku_ids)
    {
        $count = [0,0,0];
        $list = $this->model->whereIn('sku_id',$sku_ids)->select('batch_print_id','is_print')->get()->toArray();
        if(!empty($list)){
            $print_num = 0;
            $not_print_num = 0;
            $total = count($list);
            foreach ($list as $k=>$v){
                if($v['is_print'] == PUBLIC_YES){
                    $print_num++;
                }elseif($v['is_print'] == PUBLIC_NO){
                    $not_print_num++;
                }
            }
            $count = [$total,$print_num,$not_print_num];
        }
        return $count;
    }
}