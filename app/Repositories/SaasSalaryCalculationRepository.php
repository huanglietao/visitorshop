<?php
namespace App\Repositories;
use App\Models\SaasSalaryCalculation;
use App\Services\Helper;

/**
 * 仓库模板
 * 仓库模板
 * @author:
 * @version: 1.0
 * @date:
 */
class SaasSalaryCalculationRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存

    public function __construct(SaasSalaryCalculation $model)
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
        if(isset($where['finish_time'])){
            $created_at = $where['finish_time'];
            $time_list = Helper::getTimeRangedata($created_at);
            $query = $query->whereBetween("finish_time",[$time_list['start'],$time_list['end']]);
            unset($where['finish_time']);
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
        if(empty($data['id'])) {
            unset($data['id']);
            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;
        } else {
            $priKeyValue = $data['id'];
            unset($data['id']);
            $ret =$this->model->where('salary_calc_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
         if (isset($this->isCache)&&$this->isCache === true){
             $table_name = $this->model->getTable();
             $redis = app('redis.connection');
             $data['salary_calc_id'] = $priKeyValue;
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
            $data['salary_calc_id'] = $id;
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
    public function getExportTableList($where=null, $order=null)
    {
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
        if(isset($where['finish_time'])){
            $created_at = $where['finish_time'];
            $time_list = Helper::getTimeRangedata($created_at);
            $query = $query->whereBetween("finish_time",[$time_list['start'],$time_list['end']]);
            unset($where['finish_time']);
        }

        if(!empty ($where)) {
            $query =  $query->where($where);
        }

        if(!empty($order)) {
            $query =  $query->orderBy($orderBy[0],$orderBy[1]);
        }

        $list = $query->get()->toArray();

        $position_arr = config('salary.position_setting');

        $data = [];
        $position = [];
        foreach ($list as $k=>$v){
            //职位信息
            $positions = $position_arr[$v['salary_worker_position']];
            $data[$v['workers_name']][$v['finish_time']] = $v['salary'];
            $position[$v['workers_name']] = $positions;
        }

        $list = [
            'data'=>$data,
            'position'=>$position
        ];

        return $list;
    }


}
