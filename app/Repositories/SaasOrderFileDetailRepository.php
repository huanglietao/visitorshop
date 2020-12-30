<?php
namespace App\Repositories;

use App\Models\SaasOrderFileDetail;
use App\Services\Helper;

/**
 * 仓库模板
 * 仓库模板
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/07/06
 */
class SaasOrderFileDetailRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存

    public function __construct(SaasOrderFileDetail $model)
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
        $where = $this->parseWhere($where);
        if(empty($isOMS)){
            $where['mch_id'] = $this->merchantID;
        }
        //order 必须以 'id desc'这种方式传入.
        if(empty($order)){
            $order = 'file_detail_id desc';
        }
        $orderBy = [];
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }
        $query = $this->model;

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
        if(empty($data['file_detail_id'])) {
            unset($data['file_detail_id']);
            $data['created_at'] = time();
            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;
        } else {
            $priKeyValue = $data['file_detail_id'];
            unset($data['file_detail_id']);
            $data['updated_at'] = time();
            $ret =$this->model->where('file_detail_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
         if (isset($this->isCache)&&$this->isCache === true){
             $table_name = $this->model->getTable();
             $redis = app('redis.connection');
             $data['file_detail_id'] = $priKeyValue;
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
            $data['file_detail_id'] = $id;
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
            $order = 'file_detail_id desc';
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

        if(!empty ($where)) {
            $query =  $query->where($where);
        }

        if(!empty($order)) {
            $query =  $query->orderBy($orderBy[0],$orderBy[1]);
        }

        $list = $query->get();
        return $list;
    }


}
