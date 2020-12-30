<?php
namespace App\Repositories;

use App\Models\SaasOrderProducts;
use App\Models\SaasOrders;
use App\Models\SaasProducts;
use App\Models\SaasProductsSku;
use App\Services\Helper;

/**
 * 订单详情仓库
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/23
 */
class SaasOrderProductsRepository extends BaseRepository
{
    public function __construct(SaasOrderProducts $model,SaasOrders $orders,SaasProducts $products,
                                SaasProductsRelationAttrRepository $productsRelationAttrRepository,SaasProductsSku $productsSku)
    {
        $this->model = $model;
        $this->order = $orders;
        $this->prodModel = $products;
        $this->skuModel = $productsSku;
        $this->prodRelationAttrRepository = $productsRelationAttrRepository;

    }



    /**
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getTableList($where=null, $order=null)
    {
        $limit = isset($where['limit']) ? $where['limit']:config('common.page_limit');  //这个10取配置里的
        $where = $this->parseWhere($where);

        //order 必须以 'id desc'这种方式传入.
        $orderBy = [];
        if(empty($order)){
            $order = "ord_prod_id desc";
        }
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }
        $query = $this->model;
        if(!empty ($where)) {
            $query =  $query->where($where);
        }

        if(!empty($order)) {
            $query =  $query->orderBy($orderBy[0],$orderBy[1]);
        }

        $list = $query->paginate($limit);
        return $list;
    }

    /*
     * 根据商家id统计
     * $param $mch_id
     * $return $total_num
     * use_address:Merchant/Statistics/GoodsController
     */
    public function getOrderProducts($mch_id=null,$user_id=null)
    {
        $where = [];
        $ord_ids = [];
        if(!empty($mch_id)){
            $where['mch_id'] = $mch_id;
        }
        if(!empty($user_id)){
            $where['user_id'] = $user_id;
        }

        $ids = $this->order
            ->where($where)
            ->get(['order_id'])
            ->toArray();

        $ord_ids = array_column($ids,'order_id');
        $prod_num = $this->model->whereIn('ord_id',$ord_ids)->sum('prod_num');
        $sku_num = $this->model->whereIn('ord_id',$ord_ids)->groupBy('sku_id')->get(['sku_id'])->toArray();

        //销售总额
        $prices = $this->order
            ->where($where)
            ->sum(\DB::raw('order_real_total'));

        $total_num['prod_num'] = $prod_num;
        $total_num['sku_num'] = count($sku_num);
        $total_num['prices'] = number_format($prices,2);
        return $total_num;
    }


    public function getOrderProdTableList($where=null, $order=null)
    {
        $where = $this->parseWhere($where);

        //order 必须以 'id desc'这种方式传入.
        $orderBy = [];
        if(empty($order)){
            $order = "ord_prod_id desc";
        }
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }

        $where_prod = [];
        $ord_where = [];
        $where_order = [];
        //查询出该商户的有效的订单
        if(isset($where['mch_id'])){
            $where_order['mch_id']= $where['mch_id'];
        }
        if(isset($where['user_id'])){
            $where_order['user_id']= $where['user_id'];
            unset($where['user_id']);
        }
        //下单时间查询或者发货时间查询
        if(isset($where['prod_time'])&&$where['prod_time']==1){
            if(isset($where['search_time'])){
                $order_shipping_time = $where['search_time'];
                $time_list = Helper::getTimeRangedata($order_shipping_time);
                $where_order['search_time'] = 'order_shipping_time';
                $where_order['start'] = $time_list['start'];
                $where_order['end'] = $time_list['end'];
            }
        }
        if(isset($where['prod_time'])&&$where['prod_time']==2){
            if(isset($where['search_time'])){
                $order_shipping_time = $where['search_time'];
                $time_list = Helper::getTimeRangedata($order_shipping_time);
                $where_order['search_time'] = 'created_at';
                $where_order['start'] = $time_list['start'];
                $where_order['end'] = $time_list['end'];
            }
        }

        //订单状态
        $order_status = [];
        if(isset($where['order_status'])){
            $order_status = explode(",",$where['order_status']);
            $where_order['order_status'] = $order_status;
        }
        //物流状态
        $order_shipping_status = [];
        if(isset($where['deli_status'])){
            $order_shipping_status = explode(",",$where['deli_status']);
            $where_order['order_shipping_status'] = $order_shipping_status;
        }

//        $orderSql = $this->order->where($ord_where);
//        if(!empty($order_status)){
//            $orderSql = $orderSql->whereIn('order_status',$order_status);
//        }
//        if(!empty($order_shipping_status)){
//            $orderSql = $orderSql->whereIn('order_shipping_status',$order_shipping_status);
//        }
//        if(isset($where_order['search_time'])){
//            $orderSql = $orderSql->whereBetween($where_order['search_time'],[$where_order['start'],$where_order['end']]);
//        }
//
//        $ids = $orderSql->get(['order_id'])->toArray();
//
//        $ord_ids = array_column($ids,'order_id');

        //商品货号
        $where_prodSku = [];
        if(!empty($where['prod_sku_sn'])){
            $where_prodSku['prod_sku_sn'] = $where['prod_sku_sn'];
        }

        unset($where['search_time']);
        unset($where['prod_time']);
        unset($where['order_status']);
        unset($where['deli_status']);
        unset($where['prod_sku_sn']);
        unset($where['user_id']);

        $query = $this->model
            ->whereHas(
                'order',function($query) use ($where_order){
                if (!empty($where_order)) {
                    if(isset($where_order['mch_id'])){
                        $query->where('mch_id',$where_order['mch_id']);
                    }
                    if(isset($where_order['user_id'])){
                        $query->where('user_id',$where_order['user_id']);
                    }
                    if(isset($where_order['search_time'])){
                        $query->whereBetween($where_order['search_time'],[$where_order['start'],$where_order['end']]);
                    }
                    if(isset($where_order['order_status'])){
                        $query->whereIn('order_status',$where_order['order_status']);
                    }
                    if(isset($where_order['order_shipping_status'])){
                        $query->whereIn('order_shipping_status',$where_order['order_shipping_status']);
                    }
                }
            })
                ->whereHas(
                    'prod',function($query) use ($where_prod){
                    if (!empty($where_prod)) {
                        $query->where($where_prod);
                    }
                })->whereHas(
                    'prodSku',function($query) use ($where_prodSku){
                    if (!empty($where_prodSku)) {
                        $query->where($where_prodSku);
                    }
                })->with(['prod','prodSku']);


        if(!empty($order)) {
            $query =  $query->orderBy($orderBy[0],$orderBy[1]);
        }

        $list = $query->where($where)->get(['sku_id','prod_id','prod_num','prod_sale_price'])->groupBy('sku_id')->toArray();

        return $list;
    }

    public function orderProductInfo($order_no)
    {
        //商品信息数据组装
        $prod_info = $this->model->where("order_no",$order_no)->get()->toArray();

        foreach ($prod_info as $k=>$v){
            //商品信息
            $info = $this->prodModel->where('prod_id',$v['prod_id'])->select('prod_name','prod_sn')->first();
            $prod_info[$k]['prod_name'] = $info['prod_name'];
            $prod_info[$k]['prod_sn'] = $info['prod_sn'];

            //商品属性
            $prod_info[$k]['prod_attr_str'] = $this->prodRelationAttrRepository->getProductAttr($v['sku_id']);

            //货品信息
            $sku_info = $this->skuModel->where('prod_sku_id',$v['sku_id'])->select('prod_sku_sn','prod_sku_weight','prod_sku_price','prod_sku_cost','prod_process_code')->first();
            $prod_info[$k]['sku_sn'] = $sku_info['prod_sku_sn'];
            $prod_info[$k]['prod_sku_weight'] = $sku_info['prod_sku_weight'];
            $prod_info[$k]['prod_sku_price'] = $sku_info['prod_sku_price'];
            $prod_info[$k]['prod_process_code'] = $sku_info['prod_process_code'];
        }


        return $prod_info;
    }




}