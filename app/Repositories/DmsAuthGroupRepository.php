<?php
namespace App\Repositories;

use App\Models\DmsAuthGroup;
use App\Models\DmsAuthRule;
use App\Services\Helper;

/**
 *
 * 仓库模板:DMS角色组
 * @author: cjx
 * @version: 1.0
 * @date: 2020/04/27
 */
class DmsAuthGroupRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存
    protected $agent_id;

    public function __construct(DmsAuthGroup $model,DmsAuthRule $authRule)
    {
        $this->model =$model;
        $this->models =$authRule;
        $this->agent_id = isset(session("admin")['agent_info_id']) ? session("admin")['agent_info_id'] : '';
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
        $where['agent_id'] = $this->agent_id;

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
        $data['agent_id'] = $this->agent_id;
        //权限菜单处理
        if(!empty($data['rules'])){
            $data['dms_group_rule'] = $data['rules'];
            unset($data['rules']);
        }

        if(empty($data['id'])) {
            unset($data['id']);
            $data['created_at'] = time();

            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;
        } else {
            $priKeyValue = $data['id'];
            unset($data['id']);
            $data['updated_at'] = time();

            $ret =$this->model->where('dms_group_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
         if (isset($this->isCache)&&$this->isCache === true){
             $table_name = $this->model->getTable();
             $redis = app('redis.connection');
             $data['dms_group_id'] = $priKeyValue;
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
     * 获取角色组数据
     * @param $id
     * @return bool
     */
    public function getDmsGroupList()
    {
        $list =  $this->model->get();
        $groupList = [];
        foreach ($list as $k=>$v){
            $groupList[$v['dms_group_id']] = $v['dms_group_name'];
        }
        return $groupList;
    }
    /**
     *  添加或编辑时获取角色对应的菜单规则数据
     * @param $id
     * @return json
     */
    public function getDmsRuleList($id = null)
    {
        $list =  $this->models->where('dms_auth_rule_status',1)->select('dms_auth_rule_id','dms_auth_rule_pid','dms_auth_rule_title')->get()->toArray();
        $ruleList = [];
        foreach ($list as $k=>$v){
            $ruleList[] = [
                'id'=>$v['dms_auth_rule_id'],
                'parent'=>$v['dms_auth_rule_pid'] ? $v['dms_auth_rule_pid'] : '#',
                'text'=>$v['dms_auth_rule_title'],
                'state'=>["selected"=>false],
            ];
        }
        $ruleParent = array_column($ruleList,'parent');

        if(!empty($id)){ //编辑
            $groupRule = $this->model->where('dms_group_id',$id)->select('dms_group_rule')->first()->toArray();

            $groupRule = explode(',',$groupRule['dms_group_rule']);
            foreach ($ruleList as $rk=>$rv){
                if(in_array($rv['id'],$groupRule)&&$rv['parent']!='#' || (in_array($rv['id'],$groupRule)&&$rv['parent']=='#'&&!in_array($rv['id'],$ruleParent))){
                    $ruleList[$rk]['state'] = ["selected"=>true];
                }
            }
        }

        return json_encode($ruleList,JSON_UNESCAPED_UNICODE);
    }
    /**
     *  添加或编辑时,当选择上下级角色时获取角色对应的菜单规则数据
     * @param $id
     * @return json
     */
    public function changeDmsRuleList($data)
    {
        $groupRule = $this->model->where('dms_group_id',$data['pid'])->select('dms_group_rule')->first()->toArray();
        $list =  $this->models->select('dms_auth_rule_id','dms_auth_rule_pid','dms_auth_rule_title')->get()->toArray();
        $ruleList = [];
        foreach ($list as $k=>$v){
            $ruleList[] = [
                'id'=>$v['dms_auth_rule_id'],
                'parent'=>$v['dms_auth_rule_pid'] ? $v['dms_auth_rule_pid'] : '#',
                'text'=>$v['dms_auth_rule_title'],
                'state'=>["selected"=>false],
            ];


        }
        if($groupRule['dms_group_rule']!='*'){
            $groupRule = explode(',',$groupRule['dms_group_rule']);
            foreach ($ruleList as $rk=>$rv){
                if(!in_array($rv['id'],$groupRule)){
                   unset($ruleList[$rk]);
                }
            }
            $ruleList = array_values($ruleList);
        }

        //编辑时有id
        if(!empty($data['id'])){
            foreach ($ruleList as $rk=>$rv){
                $ruleList[$rk]['state'] = ["selected"=>true];
            }
        }
        return json_encode($ruleList,JSON_UNESCAPED_UNICODE);

    }

}
