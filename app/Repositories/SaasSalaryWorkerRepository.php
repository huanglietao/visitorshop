<?php
namespace App\Repositories;

use App\Models\SaasSalaryWorker;
use App\Services\Helper;

/**
 * 职工仓库
 * @author: cjx
 * @version: 1.0
 * @date:  2020/06/28
 */
class SaasSalaryWorkerRepository extends BaseRepository
{

    public function __construct(SaasSalaryWorker $model)
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
        if(empty($order)){$order='created_at desc';}
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
        $position_arr = config('salary.position_setting');

        foreach ($list as $k=>$v){
            //职位信息
            $list[$k]['position'] = $position_arr[$v['salary_worker_position']];
        }

        return $list;
    }


    /**
     * 新增/修改
     * @param $data
     * @return boolean
     */
    public function save($data)
    {
        if(empty($data['salary_worker_id'])) {
            unset($data['salary_worker_id']);
            $data['created_at'] = time();
            $ret = $this->model->create($data);
        } else {
            $priKeyValue = $data['salary_worker_id'];
            unset($data['salary_worker_id']);
            $data['updated_at'] = time();
            $ret =$this->model->where('salary_worker_id',$priKeyValue)->update($data);
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
     * 获取配置中职位信息
     * @return array
     */
    public function getPositionList()
    {
        $arr = [];
        $positions = config('salary.position_setting');
        foreach ($positions as $k=>$v){
            $arr['position'][$k] = $v['name'];
            $arr['rate'][$k] = $v['rate'];
            $arr['per_money'][$k] = $v['per_money'];
        }
        $arr['list'] = $positions;
        return $arr;
    }

    /**
     * Get Model by id.
     *
     * @param  int  $id
     * @return App\Models\Model
     */
    public function getById($id)
    {
        $data = $this->model->find($id);

        if(empty($data)){
            return $data;
        }

        //职位信息
        $position_arr = config('salary.position_setting');
        $data['position'] = $position_arr[$data['salary_worker_position']];

        return $data;
    }

}
