<?php
namespace App\Repositories;
use App\Models\SaasOrderFile;
use App\Services\Helper;

/**
 * 仓库模板
 * 仓库模板
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/07/06
 */
class SaasOrderFileRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存

    public function __construct(SaasOrderFile $model)
    {
        $this->merchantID = isset(session('admin')['mch_id'])? session('admin')['mch_id'] : ' ';
        $this->model =$model;
    }

    /**
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getTableList($where=null, $order=null,$isOMS=null)
    {
        $limit = isset($where['limit']) ? $where['limit']:config('common.page_limit');  //这个10取配置里的
        if(empty($isOMS)){
            $where['mch_id'] = $this->merchantID;
        }
        $where = $this->parseWhere($where);
        //order 必须以 'id desc'这种方式传入.
        $orderBy = [];
        if(empty($order)){
            $order = 'order_file_id desc';
        }
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }
        $query = $this->model;

        if(isset($where['cha_info']) && $where['cha_info']=="请选择"){
            unset($where['cha_info']);
        }

        //下单时间查询或者发货时间查询或生产时间
        if(isset($where['prod_time'])&&$where['prod_time']==1){
            if(isset($where['search_time'])){
                $order_shipping_time = $where['search_time'];
                $time_list = Helper::getTimeRangedata($order_shipping_time);
                $query = $query->whereBetween("shipping_time",[$time_list['start'],$time_list['end']]);
            }
            unset($where['search_time']);
            unset($where['prod_time']);
        }
        if(isset($where['prod_time'])&&$where['prod_time']==2){
            if(isset($where['search_time'])){
                $created_at = $where['search_time'];
                $time_list = Helper::getTimeRangedata($created_at);
                $query = $query->whereBetween("order_create_time",[$time_list['start'],$time_list['end']]);
            }
            unset($where['search_time']);
            unset($where['prod_time']);
        }
        if(isset($where['prod_time'])&&$where['prod_time']==3){
            if(isset($where['search_time'])){
                $submit_time = $where['search_time'];
                $time_list = Helper::getTimeRangedata($submit_time);
                $query = $query->whereBetween("submit_time",[$time_list['start'],$time_list['end']]);
            }
            unset($where['search_time']);
            unset($where['prod_time']);
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
        if(empty($data['order_file_id'])) {
            unset($data['order_file_id']);
            $data['created_at'] = time();
            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;
        } else {
            $priKeyValue = $data['order_file_id'];
            unset($data['order_file_id']);
            $data['updated_at'] = time();
            $ret =$this->model->where('order_file_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
         if (isset($this->isCache)&&$this->isCache === true){
             $table_name = $this->model->getTable();
             $redis = app('redis.connection');
             $data['order_file_id'] = $priKeyValue;
             //将数据写入缓存
             $redis->set($table_name.'_'.$priKeyValue , json_encode($data));
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

        //删除缓存数据
        if (isset($this->isCache)&&$this->isCache === true){
            $table_name = $this->model->getTable();
            $redis = app('redis.connection');
            $data['order_file_id'] = $id;
            $redis->del($table_name.'_'.$id);
        }

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }


    /**
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getExportTableList($where=null, $order=null,$isOMS=null)
    {
        $where = $this->parseWhere($where);
        if(empty($isOMS)){
            $where['mch_id'] = $this->merchantID;
        }
        //order 必须以 'id desc'这种方式传入.
        $orderBy = [];
        if(empty($order)){
            $order = 'order_file_id desc';
        }
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }
        $query = $this->model;

        if(isset($where['cha_info']) && $where['cha_info']=="请选择"){
            unset($where['cha_info']);
        }

        //下单时间查询或者发货时间查询或生产时间
        if(isset($where['prod_time'])&&$where['prod_time']==1){
            if(isset($where['search_time'])){
                $order_shipping_time = $where['search_time'];
                $time_list = Helper::getTimeRangedata($order_shipping_time);
                $query = $query->whereBetween("shipping_time",[$time_list['start'],$time_list['end']]);
            }
            unset($where['search_time']);
            unset($where['prod_time']);
        }
        if(isset($where['prod_time'])&&$where['prod_time']==2){
            if(isset($where['search_time'])){
                $submit_time = $where['search_time'];
                $time_list = Helper::getTimeRangedata($submit_time);
                $query = $query->whereBetween("order_create_time",[$time_list['start'],$time_list['end']]);
            }
            unset($where['search_time']);
            unset($where['prod_time']);
        }
        if(isset($where['prod_time'])&&$where['prod_time']==3){
            if(isset($where['search_time'])){
                $submit_time = $where['search_time'];
                $time_list = Helper::getTimeRangedata($submit_time);
                $query = $query->whereBetween("submit_time",[$time_list['start'],$time_list['end']]);
            }
            unset($where['search_time']);
            unset($where['prod_time']);
        }

        if(!empty ($where)) {
            $query =  $query->where($where);
        }

        if(!empty($order)) {
            $query =  $query->orderBy($orderBy[0],$orderBy[1]);
        }

        $list = $query->get();
        return $list;
    }

//获取各个阶段的订单发货数
    public function getOrderDeliveryInfo($timeArr)
    {
        //获取每个阶段的订单量
        foreach ($timeArr as $k => $v)
        {
            $orderCount = $this->model->whereBetween('shipping_time', [$v['start_time'], $v['end_time']])->count();
            $timeArr[$k]['delivery_count'] = $orderCount;
        }
        return $timeArr;
    }

    //获取各个阶段的订单发货地区统计
    public function getOrderDeliveryAreaCount($startTime,$endTime)
    {
        $data= $this->model->select(\DB::raw('COUNT(*) as area_count, province_name'))->groupBy('province_name')->whereBetween('shipping_time', [$startTime, $endTime])->get()->toArray();
        return $data;
    }
    //获取各个阶段的订单交期统计
    public function getOrderDeliveryDateCount($startTime,$endTime)
    {
        $data = $this->model->whereBetween('shipping_time', [$startTime, $endTime])->get()->toArray();
        $res = $this->consign($data);
        $res = array_count_values(array_column($res,'consign')) ;
        return $res;
    }

    //得出每个订单的发货率
    public function consign($data){
        foreach ($data as $k=>$v){
            //获取提交生产当天的年月日,获取发货时间的年月日
            $subTime = date('Y-m-d',$v['submit_time']);
            $shipTime = date('Y-m-d',$v['shipping_time']);
            //如果是当天发货，天数为0
            if($subTime==$shipTime){
                $consign = "T+0";
            }else{
                //将当天17点作为节点
                $nodeTime = strtotime($subTime.'17:00:00');
                //提交生产时间
                $submit_time = $v['submit_time'];
                //如果超过当天时间的17点，则算第二天提交的
                if($nodeTime-$v['submit_time']<0){
                    $submit_time = $v['submit_time']+86400;
                }

                //判断时间段是否存在周日
                $is_weekend = 0;
                $starTime = $submit_time;
                while (date("Y-m-d", $starTime) < date("Y-m-d", $v['shipping_time'])) {
                    $day = date("w", $starTime);
                    if ($day == 0) {
                        $is_weekend = 1;
                    }
                    $starTime = $starTime+86400;
                }
                //如果存在周日，则提交生产到发货时间减少一天
                if($is_weekend==1){
                    //获取提交生产到发货用了多少天时间，进一取值
                    $days = abs(ceil(($v['shipping_time']-$submit_time)/86400)-1);
                }else{
                    //获取提交生产到发货用了多少天时间，进一取值
                    $days = abs(ceil(($v['shipping_time']-$submit_time)/86400));
                }
                if($days>4){
                    $consign = "T≥5";
                }else{
                    $consign = "T+".$days;
                }
            }
            $data[$k]['consign'] = $consign;
        }
        return $data;
    }


    //大后台获取某一天的订单发货数
    public function getAllOrderDeliveryCount($mid=null,$starttime = null,$endtime = null)
    {
        //获得当日凌晨的时间戳
        if (empty($starttime)){
            $starttime = strtotime(date("Y-m-d"),time());
            $endtime = time();
        }
        $count = $this->model;
        if (!is_null($mid) && !is_array($mid))
        {
            $midArr = explode(',',$mid);
            $count = $count->whereIn('mch_id',$midArr);
        }
        $count = $this->model->whereBetween('shipping_time', [$starttime, $endtime])->count();
        return $count;
    }

}
