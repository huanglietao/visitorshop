<?php
namespace App\Repositories;

use App\Models\SaasOrderErpPushQueue;
use App\Services\Helper;

/**
 *  推送erp队列数据仓库
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/7/03
 */
class SaasOrderErpPushQueueRepository extends BaseRepository
{
    public function __construct(SaasOrderErpPushQueue $model)
    {
        $this->model = $model;
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

        $orderwhere = [];
        if(!empty($where['order_id'])){
            $orderwhere['order_no'] = $where['order_id'];
            unset($where['order_id']);
        }

        //order 必须以 'id desc'这种方式传入.
        $orderBy = [];
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }

        $query = $this->model->WhereHas(
            'morder',function($query) use ($orderwhere) {
            if (!empty($orderwhere)) {
                $query->where($orderwhere);
            }
        })->with(['morder']);
        //查询时间
        if(isset($where['push_time'])){
            $compound_time = $where['push_time'];
            $time_list = Helper::getTimeRangedata($compound_time);
            $query = $query->where("start_time",">=",$time_list['start'])->where('end_time','<=',$time_list['end']);
            unset($where['push_time']);
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
        if(empty($data['id'])) {
            unset($data['id']);
            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;
        } else {
            $priKeyValue = $data['id'];
            unset($data['id']);
            $data['updated_at'] = time();
            $ret =$this->model->where('order_erp_push_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
        if (isset($this->isCache)&&$this->isCache === true){
            $table_name = $this->model->getTable();
            $redis = app('redis.connection');
            $data['order_erp_push_id'] = $priKeyValue;
            //将数据写入缓存
            $redis->set($table_name.'_'.$priKeyValue , json_encode($data));
        }
        return $ret;

    }

    /**
     *  统计队列状态
     *
     * @return array
     */
    public function getQueueStatus()
    {
        $progressStatus = $this->model->where(['order_push_status'=>'progress'])->get()->toArray();
        $finishStatus = $this->model->where(['order_push_status'=>'finish'])->get()->toArray();
        $errorStatus = $this->model->where(['order_push_status'=>'error'])->get()->toArray();
        $list = [];
        $list['progress'] = count($progressStatus);
        $list['finish']   = count($finishStatus);
        $list['error']    = count($errorStatus);

        return $list;
    }

    /**
     *  改变队列状态
     * @param $data
     * @return bool
     */
    public function updateQueueStatus($data)
    {
        if(!empty($data)) {
            $this->model->where('order_erp_push_id',$data['id'])->update(['order_push_status'=>$data['status']]);
            return true;
        }else{
            return false;
        }

    }

    //获取时间段订单与作品推送各状态的数量
    public function pushMonitorCount($timeArr,$merchant)
    {
        $where = [];
        if (!empty($merchant) && $merchant!='all')
        {
            $where['mch_id'] = $merchant;
        }
        //获取未推送订单数量集合
        //获取0-2小时内未推送的订单数量
        $data['order']['now_hours'] = $this->model->where($where)->whereIn('order_push_status',['ready','progress','error'])->whereBetween('created_at', [$timeArr['two_hours'],time()])->count();
        //获取2-4小时内未推送的订单数量
        $data['order']['two_hours'] = $this->model->where($where)->whereIn('order_push_status',['ready','progress','error'])->whereBetween('created_at', [$timeArr['four_hours'],$timeArr['two_hours']])->count();
        //获取4-6小时内未推送的订单数量
        $data['order']['four_hours'] = $this->model->where($where)->whereIn('order_push_status',['ready','progress','error'])->whereBetween('created_at', [$timeArr['six_hours'],$timeArr['four_hours']])->count();
        //获取6-12小时内未推送的订单数量
        $data['order']['six_hours'] = $this->model->where($where)->whereIn('order_push_status',['ready','progress','error'])->whereBetween('created_at', [$timeArr['tew_hours'],$timeArr['six_hours']])->count();
        //获取12-24小时内未推送的订单数量
        $data['order']['tew_hours'] = $this->model->where($where)->whereIn('order_push_status',['ready','progress','error'])->whereBetween('created_at', [$timeArr['tf_hours'],$timeArr['tew_hours']])->count();
        //获取24小时以外未推送的订单数量
        $data['order']['tf_hours'] = $this->model->where($where)->whereIn('order_push_status',['ready','progress','error'])->where('created_at', '<',$timeArr['tf_hours'])->count();

        //获取未推送作品数量集合
        //获取0-2小时内未推送的作品数量
        $now_hours = $this->model->withCount('orderProduct')->where($where)->whereIn('order_push_status',['ready','progress','error'])->whereBetween('created_at', [$timeArr['two_hours'],time()])->get()->toArray();
        $now_hours = array_column($now_hours,'order_product_count','order_erp_push_id');
        $data['order_prod']['now_hours'] = array_sum($now_hours);
        //获取2-4小时内未推送的作品数量
        $two_hours = $this->model->withCount('orderProduct')->where($where)->whereIn('order_push_status',['ready','progress','error'])->whereBetween('created_at', [$timeArr['four_hours'],$timeArr['two_hours']])->get()->toArray();
        $two_hours = array_column($two_hours,'order_product_count','order_erp_push_id');
        $data['order_prod']['two_hours'] = array_sum($two_hours);
        //获取4-6小时内未推送的作品数量
        $four_hours = $this->model->withCount('orderProduct')->where($where)->whereIn('order_push_status',['ready','progress','error'])->whereBetween('created_at', [$timeArr['six_hours'],$timeArr['four_hours']])->get()->toArray();
        $four_hours = array_column($four_hours,'order_product_count','order_erp_push_id');
        $data['order_prod']['four_hours'] = array_sum($four_hours);
        //获取6-12小时内未推送的作品数量
        $six_hours = $this->model->withCount('orderProduct')->where($where)->whereIn('order_push_status',['ready','progress','error'])->whereBetween('created_at', [$timeArr['tew_hours'],$timeArr['six_hours']])->get()->toArray();
        $six_hours = array_column($six_hours,'order_product_count','order_erp_push_id');
        $data['order_prod']['six_hours'] = array_sum($six_hours);
        //获取12-24小时内未推送的作品数量
        $tew_hours = $this->model->withCount('orderProduct')->where($where)->whereIn('order_push_status',['ready','progress','error'])->whereBetween('created_at', [$timeArr['tf_hours'],$timeArr['tew_hours']])->get()->toArray();
        $tew_hours = array_column($tew_hours,'order_product_count','order_erp_push_id');
        $data['order_prod']['tew_hours'] = array_sum($tew_hours);
        //获取24小时以外未推送的作品数量
        $tf_hours = $this->model->withCount('orderProduct')->where($where)->whereIn('order_push_status',['ready','progress','error'])->where('created_at', '<',$timeArr['tf_hours'])->get()->toArray();
        $tf_hours = array_column($tf_hours,'order_product_count','order_erp_push_id');
        $data['order_prod']['tf_hours'] = array_sum($tf_hours);
        return $data;

    }



}