<?php
namespace App\Repositories;

use App\Models\SaasOrderServiceReason;
use App\Services\Helper;

/**
 * 售后原因文案仓库
 * @author: cjx
 * @version: 1.0
 * @date:  2020/07/01
 */
class SaasOrderServiceReasonRepository extends BaseRepository
{

    public function __construct(SaasOrderServiceReason $model)
    {
        $this->mch_id = isset(session("admin")['mch_id']) ? session("admin")['mch_id'] : '';
        $this->model =$model;
    }

    /**
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getTableList($where=null, $order='created_at desc')
    {
        $where = $this->parseWhere($where);
        $where['mch_id'] =$this->mch_id;

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

        $list = $query->get();
        return $list;
    }


    /**
     * 新增/修改
     * @param $data
     * @return boolean
     */
    public function save($data)
    {
        if(empty($data['service_reason_id'])) {
            unset($data['service_reason_id']);
            $data['created_at'] = time();
            $data['mch_id'] = $this->mch_id;
            $ret = $this->model->create($data);
        } else {
            $priKeyValue = $data['service_reason_id'];
            unset($data['service_reason_id']);
            $data['updated_at'] = time();
            $ret =$this->model->where('service_reason_id',$priKeyValue)->update($data);
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

    //获取问题分类
    public function getType()
    {
        $list =  $this->getRows(['reason_pid'=>ZERO,'mch_id'=>$this->mch_id],'created_at','asc')->toArray();

        return array_column($list,'reason','service_reason_id');
    }


}
