<?php
namespace App\Repositories;

use App\Models\SaasDelivery;
use App\Models\SaasDeliveryTemplate;
use App\Models\SaasProducts;
use App\Services\Helper;
use Illuminate\Support\Facades\DB;
/**
 * 仓库模板
 * 仓库模板
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/03/17
 */
class SaasDeliveryRepository extends BaseRepository
{

    public function __construct(SaasDelivery $model)
    {
        $this->model =$model;
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
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }
        $query = $this->model;
        //查询时间
        if(isset($where['created_at'])){
            $created_at = $where['created_at'];
            $time_list = Helper::getTimeRangedata($created_at);
            $query = $query->whereBetween("created_at",[$time_list['start'],$time_list['end']]);
            unset($where['created_at']);
        }

        if(!empty ($where)) {
            $query =  $query->where($where);
        }

        if(!empty($order)) {
            $query =  $query->orderBy($orderBy[0],$orderBy[1]);
        }

        $list = $query->paginate($limit);
        return $list;
    }


    /**
     * 新增/修改
     * @param $data
     * @return boolean
     */
    public function save($data)
    {
        if(empty($data['delivery_id'])) {
            unset($data['delivery_id']);
            $data['created_at'] = time();
            $ret = $this->model->create($data);
        } else {
            $priKeyValue = $data['delivery_id'];
            unset($data['delivery_id']);
            $data['updated_at'] = time();
            $ret =$this->model->where('delivery_id',$priKeyValue)->update($data);
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
     * 获取快递公司
     * @param $id
     * @return $row
     */
    public function getDelivery($id=null)
    {
        if(empty($id)){
            $row = DB::table('saas_express')->where('express_status','1')->whereNull('deleted_at')->select('express_id','express_name')->get();
        }else{
            $result = DB::table("saas_express")->where('express_id',$id)->select('express_name')->get();
            $row =  json_decode($result,true);
        }
        return $row;
    }

    public function getDeliveryByMid($mid)
    {
        $expressList = $this->model->where(['delivery_status'=>ONE,'mch_id'=>$mid])->select('delivery_id','delivery_name')->get()->toArray();
        if(empty($expressList)){
            $expressList = $this->model->where(['delivery_status'=>ONE,'mch_id'=>ZERO])->select('delivery_id','delivery_name')->get()->toArray();
        }
        $helper = app(Helper::class);
        $expList = $helper->ListToKV('delivery_id','delivery_name',$expressList);
        return $expList;
    }

    //根据商品id获取默认快递方式 支持多商品
    public function getDefaultDeliveryId($goods_id)
    {
        if (!is_array($goods_id))
        {
            $goods_id = explode(',',$goods_id);
        }
        $productsModel = app(SaasProducts::class);
        $deliveryTempModel = app(SaasDeliveryTemplate::class);
        $all_array = [];
        $same_array = [];
        //循环获取商品的物流模板
        foreach ($goods_id as $k => $v)
        {
            $shipping_temp_id = $productsModel->where('prod_id', $v)->value('prod_express_tpl_id');
            if (!empty($shipping_temp_id)) {
                //获取模板中的快递方式
                $temp_str = $deliveryTempModel->where('del_temp_id',$shipping_temp_id)->value('del_temp_delivery_list');
                if (!empty($temp_str)){
                    $t_arr = explode(',',$temp_str);
                    //将快递方式存进数组中
                    $all_array = array_merge($all_array,$t_arr);
                    if (empty($same_array)){
                        //第一次进来，将模板的快递方式赋予，便于下次比较
                        $same_array = $t_arr;
                    }else{
                        //获取重复的数据
                        $same_array = array_intersect($same_array,$t_arr);
                    }
                }
            }else{
                return [
                    'code' => 0,
                    'msg'  => "id为".$v."的商品没有快递模板"
                ];
            }
        }

        //判断商品是否有共用的快递方式
        if (!empty($same_array))
        {
            //拥有共用的快递方式，取出当中权重最高的快递方式返回
            $where_array = $same_array;
        }else{
            //没有共用的快递方式，则取所有快递方式中权重最高的
            $all_array = array_unique($all_array);
            $where_array = $all_array;
        }
        $delivery_arr = $this->model->whereIn('delivery_id',$where_array)->orderBy('weight','desc')->first();
        if (!empty($delivery_arr)){
            return [
                'code'         => 1,
                'delivery_id'  => $delivery_arr['delivery_id'],
            ];
        }else{
            return [
                'code' => 0,
                'msg'  => "快递模板获取失败"
            ];
        }
    }

}
