<?php
namespace App\Repositories;

use App\Models\SaasSpDownloadQueue;
use App\Models\SaasSuppliersOrderProduct;
use App\Services\Helper;

/**
 * 供货商下载队列仓库
 * @author: cjx
 * @version: 1.0
 * @date: 2020/05/08
 */

class SaasSpDownloadQueueRepository extends BaseRepository
{
    protected $mch_id;

    public function __construct(SaasSpDownloadQueue $downloadQueue)
    {
        $this->mch_id = isset(session("admin")['mch_id']) ? session("admin")['mch_id'] : PUBLIC_CMS_MCH_ID;
        $this->model = $downloadQueue;
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
        if(empty($data['sp_ord_id'])){
            unset($data['sp_ord_id']);
            $ret = $this->model->create($data);
        } else {
            $priKeyValue = $data['sp_ord_id'];
            unset($data['sp_ord_id']);

            $data['updated_at'] = time();
            $ret =$this->model->where('sp_ord_id',$priKeyValue)->update($data);
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
     * 更新下载队列记录状态
     * @param $id
     * @return bool
     */
    public function updateQueueStatus($data)
    {
        switch ($data['download_status']){
            case "progress":
                $data['download_begin_time'] = time();
                break;
            case "finish" :
                $data['download_finish_time'] = time();
                $data['is_down'] = 1;
                break;
        }

        $ret = $this->model->where('sp_down_queue_id',$data['sp_down_queue_id'])->update($data);
        if($ret){
            $res = $this->model->updateOrderStatus($data['sp_down_queue_id']);  //更新订单下状状态
            $result = ['code'=>ONE,'msg'=>'更新成功'];
        }else{
            $result = ['code'=>'11003','msg'=>'下载出错'];
        }
        return $result;

    }

}