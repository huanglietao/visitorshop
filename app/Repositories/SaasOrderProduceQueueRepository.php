<?php
namespace App\Repositories;

use App\Models\SaasOrderProduceQueue;
use App\Services\Helper;

/**
 * 生产队列数据仓库
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/26
 */
class SaasOrderProduceQueueRepository extends BaseRepository
{
    public function __construct(SaasOrderProduceQueue $model)
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
        if(isset($where['produce_time'])){
            $compound_time = $where['produce_time'];
            $time_list = Helper::getTimeRangedata($compound_time);
            $query = $query->where("start_time",">=",$time_list['start'])->where('end_time','<=',$time_list['end']);
            unset($where['produce_time']);
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
            $ret =$this->model->where('produce_queue_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
        if (isset($this->isCache)&&$this->isCache === true){
            $table_name = $this->model->getTable();
            $redis = app('redis.connection');
            $data['produce_queue_id'] = $priKeyValue;
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
        $progressStatus = $this->model->where(['produce_queue_status'=>'progress'])->get()->toArray();
        $finishStatus = $this->model->where(['produce_queue_status'=>'finish'])->get()->toArray();
        $errorStatus = $this->model->where(['produce_queue_status'=>'error'])->get()->toArray();
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
            $this->model->where('produce_queue_id',$data['id'])->update(['produce_queue_status'=>$data['status']]);
            return true;
        }else{
            return false;
        }

    }



}