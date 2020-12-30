<?php
namespace App\Repositories;
use App\Models\SaasOrderSyncQueue;
use App\Services\Helper;

/**
 * 仓库模板
 * 同步队列仓库数据处理
 * @author: david
 * @version: 1.0
 * @date:  2020/07/02
 */
class SaasOrderSyncQueueRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存

    public function __construct(SaasOrderSyncQueue $model)
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
       // if(empty($order)){$order='order_push_id desc';}
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
            $query = $query->where("created_at",">=",$time_list['start'])->where('created_at','<=',$time_list['end']);
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
        if(empty($data['id'])) {
            unset($data['id']);
            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;
        } else {
            $priKeyValue = $data['id'];
            unset($data['id']);
            $data['updated_at'] = time();
            $ret =$this->model->where('sync_queue_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
         if (isset($this->isCache)&&$this->isCache === true){
             $table_name = $this->model->getTable();
             $redis = app('redis.connection');
             $data['sync_queue_id'] = $priKeyValue;
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
            $data['order_push_id'] = $id;
            $redis->del($table_name.'_'.$id);
        }

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }

    /**
     *  统计队列状态
     *
     * @return array
     */
    public function getQueueStatus()
    {
        $progressStatus = $this->model->where(['sync_status'=>'progress'])->get()->toArray();
        $finishStatus = $this->model->where(['sync_status'=>'finish'])->get()->toArray();
        $errorStatus = $this->model->where(['sync_status'=>'error'])->get()->toArray();
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
            $this->model->where('sync_queue_id',$data['id'])->update(['sync_status'=>$data['status']]);
            return true;
        }else{
            return false;
        }

    }

    /**
     * @author: cjx
     * @time: 2020-07-22
     *  通过淘宝订单查出系统订单号
     *  param $tb_no 淘宝订单号
     */
    public function getOrderNoByTbNo($tb_no)
    {
        $data = $this->model->where(['outer_order_no'=>$tb_no,'sync_status'=>'finish'])->select('order_no')->first();
        if(empty($data)){
            //该订单号未生成系统订单
            Helper::EasyThrowException(70100,__FILE__.__LINE__);
        }
        return $data['order_no'];
    }



}
