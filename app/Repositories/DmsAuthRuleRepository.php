<?php
namespace App\Repositories;

use App\Models\DmsAuthGroup;
use App\Models\DmsAuthRule;
use App\Services\Helper;

/**
 *
 * 仓库模板:DMS菜单
 * @author: david
 * @version: 1.0
 * @date: 2020/07/10
 */
class DmsAuthRuleRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存
    protected $agent_id;

    public function __construct(DmsAuthGroup $authGroup,DmsAuthRule $model)
    {
        $this->model =$model;
        $this->models =$authGroup;
        $this->agent_id = isset(session("admin")['agent_info_id']) ? session("admin")['agent_info_id'] : '';
    }

    /**
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getTableList($where=null, $order=null)
    {
        //$limit = isset($where['limit']) ? $where['limit']:config('common.page_limit');  //这个10取配置里的
        $limit = "99999";  //不做分页
        $where = $this->parseWhere($where);
        //$where['agent_id'] = $this->agent_id;

        //order 必须以 'id desc'这种方式传入.
        $orderBy = [];
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }

        $query = $this->model;

        if(isset($where['created_at']) && !isset($where['dms_group_name'])){
            //只按时间查询
            $created_at = $where['created_at'];
            $time_list = Helper::getTimeRangedata($created_at);
            $query = $query->whereBetween("created_at",[$time_list['start'],$time_list['end']]);
            unset($where['created_at']);
        }

        if(isset($where['dms_group_name'])){
            if(isset($where['created_at'])){
                $created_at = $where['created_at'];
                $time_list = Helper::getTimeRangedata($created_at);
                $query = $query->whereBetween("created_at",[$time_list['start'],$time_list['end']]);
                unset($where['created_at']);
            }
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
            $data['created_at']= time();
            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;
        } else {
            $priKeyValue = $data['id'];
            if($data['dms_auth_rule_pid']==$data['id']){
                return ['code' => 0,'msg'=>'父级不能选择本身'];
            }
            unset($data['id']);
            $data['updated_at']= time();
            $ret =$this->model->where('dms_auth_rule_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
        if (isset($this->isCache)&&$this->isCache === true){
            $table_name = $this->model->getTable();
            $redis = app('redis.connection');
            $data['dms_auth_rule_id'] = $priKeyValue;
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
            $data['dms_group_id'] = $id;
            $redis->del($table_name.'_'.$id);
        }

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }

    /**
     *  改变字段是否为菜单
     * @param $flag
     * @return bool
     */
    public function changeUpdateField($flag)
    {
        if($flag['flag'] == ZERO) {
            $ret = $this->model->where('dms_auth_rule_id',$flag['id'])->update(['dms_auth_rule_ismenu'=>1]);
            return ['flag'=>ONE];
        }
        if($flag['flag'] == ONE) {
            $ret = $this->model->where('dms_auth_rule_id',$flag['id'])->update(['dms_auth_rule_ismenu'=>0]);
            return ['flag'=>ZERO];
        }
    }



}
