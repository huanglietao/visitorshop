<?php
/**
 * 功能简介
 *
 * 功能详细说明
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date: 2020/6/29
 */

namespace App\Repositories;

use App\Models\SaasSalaryDetail;
use App\Services\Helper;

class SaasSalaryDetailRepository extends BaseRepository
{
    public function __construct(SaasSalaryDetail $model)
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

        if(empty($data['salary_detail_id'])){
            unset($data['salary_detail_id']);

            $data['created_at'] = time();
            $ret = $this->model->create($data);
        } else {
            $priKeyValue = $data['salary_detail_id'];
            unset($data['salary_detail_id']);

            $data['updated_at'] = time();
            $ret =$this->model->where('salary_detail_id',$priKeyValue)->update($data);
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

    //根据MD5值查看该条记录是否存在
    public function isExit($md5_code)
    {
        $ret = $this->model->where('md5_code', $md5_code)->count();
        return $ret;
    }
}