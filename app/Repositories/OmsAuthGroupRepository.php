<?php
namespace App\Repositories;

use App\Models\OmsAuthGroup;
use App\Models\OmsAuthRule;
use App\Services\Helper;

/**
 *
 * 仓库模板:oms角色组
 * @author: cjx
 * @version: 1.0
 * @date: 2020/04/13
 */
class OmsAuthGroupRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存

    public function __construct(OmsAuthGroup $model,OmsAuthRule $authRule)
    {
        $this->model =$model;
        $this->models =$authRule;
        $this->mch_id = isset(session("admin")['mch_id']) ? session("admin")['mch_id'] : '';
    }

    /**
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getTableList($where=null, $order=null, $oms_adm_group_id=null)
    {
        $limit = isset($where['limit']) ? $where['limit']:config('common.page_limit');  //这个10取配置里的
        $where = $this->parseWhere($where);
        if(!empty($this->mch_id)){
            $where['mch_id'] = $this->mch_id;
        }

        //order 必须以 'id desc'这种方式传入.
        $orderBy = [];
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }

        $query = $this->model;

        $orwhere = [];
        if(!empty($oms_adm_group_id)){
            $orwhere['oms_group_id'] = $oms_adm_group_id;
        }else{
            //cms进入商户角色管理
            $where['oms_group_pid'] = 0;
        }

        //查询时间
        if(isset($where['created_at'])){
            $created_at = $where['created_at'];
            $time_list = Helper::getTimeRangedata($created_at);
            $query = $query->whereBetween("created_at",[$time_list['start'],$time_list['end']]);

            $wheretime1 = ['created_at','>=',$time_list['start']];
            $wheretime2 = ['created_at','<=',$time_list['end']];
            unset($where['created_at']);
            unset($orwhere['oms_group_id']);
        }

        if(isset($where['oms_group_name'])){
            unset($orwhere['oms_group_id']);
            if(isset($wheretime1)){
                $orwhere = [['mch_id',0],['oms_group_name',$where['oms_group_name']],$wheretime1,$wheretime2];
            }else{
                $orwhere = [['mch_id',0],['oms_group_name',$where['oms_group_name']]];
            }
        }

        if(!empty ($where)) {
            $query =  $query->where($where)->orWhere($orwhere);
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
    public function save($data,$pid=null)
    {
        if(!empty($data['rules'])){
            $data['oms_group_rule'] = $data['rules'];
            unset($data['rules']);
        }

        if(empty($data['id'])) {
            unset($data['id']);
            $data['created_at'] = time();

            if (!empty($pid)){
                $data['oms_group_pid'] = $pid;
                $data['mch_id'] = $this->mch_id;
            }

            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;
        } else {
            $priKeyValue = $data['id'];
            unset($data['id']);
            $ret =$this->model->where('oms_group_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
         if (isset($this->isCache)&&$this->isCache === true){
             $table_name = $this->model->getTable();
             $redis = app('redis.connection');
             $data['oms_group_id'] = $priKeyValue;
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
            $data['oms_group_id'] = $id;
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
    public function getCmsGroupList()
    {
        $list =  $this->model->get();
        $groupList = [];
        foreach ($list as $k=>$v){
            $groupList[$v['oms_group_id']] = $v['oms_group_name'];
        }
        return $groupList;
    }
    /**
     *  添加或编辑时获取角色对应的菜单规则数据
     * @param $id
     * @return json
     */
    public function getOmsRuleList($id = null)
    {
        $list =  $this->models->select('oms_auth_rule_id','oms_auth_rule_pid','oms_auth_rule_title')->where('oms_auth_rule_status',1)->get()->toArray();
        $ruleList = [];
        foreach ($list as $k=>$v){
            $ruleList[] = [
                'id'=>$v['oms_auth_rule_id'],
                'parent'=>$v['oms_auth_rule_pid'] ? $v['oms_auth_rule_pid'] : '#',
                'text'=>$v['oms_auth_rule_title'],
                'state'=>["selected"=>false],
            ];
        }
        $ruleParent = array_column($ruleList,'parent');

        if(!empty($id)){ //编辑
            $groupRule = $this->model->where('oms_group_id',$id)->select('oms_group_rule')->first()->toArray();

            $groupRule = explode(',',$groupRule['oms_group_rule']);
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
    public function changeCmsRuleList($data)
    {
        $groupRule = $this->model->where('oms_group_id',$data['pid'])->select('oms_group_rule')->first()->toArray();
        $list =  $this->models->select('oms_auth_rule_id','oms_auth_rule_pid','oms_auth_rule_title')->get()->toArray();
        $ruleList = [];
        foreach ($list as $k=>$v){
            $ruleList[] = [
                'id'=>$v['oms_auth_rule_id'],
                'parent'=>$v['oms_auth_rule_pid'] ? $v['oms_auth_rule_pid'] : '#',
                'text'=>$v['oms_auth_rule_title'],
                'state'=>["selected"=>false],
            ];


        }
        if($groupRule['oms_group_rule']!='*'){
            $groupRule = explode(',',$groupRule['oms_group_rule']);
            foreach ($ruleList as $rk=>$rv){
                if(!in_array($rv['id'],$groupRule)){
                   unset($ruleList[$rk]);
                }
            }
            $ruleList = array_values($ruleList);
        }
        //dump($ruleList);die;
        //编辑时有id
        if(!empty($data['id'])){
            foreach ($ruleList as $rk=>$rv){
                $ruleList[$rk]['state'] = ["selected"=>true];
            }
        }
    //dump($ruleList);die;
        return json_encode($ruleList,JSON_UNESCAPED_UNICODE);
        $data =json_encode([
            ["id"=>1,"parent"=>"#","text"=>"控制台","state"=>["selected"=>false]],
            ["id"=>2,"parent"=>"1","text"=>"控制台2","state"=>["selected"=>true]],
            ["id"=>3,"parent"=>"1","text"=>"控制台3","state"=>["selected"=>false]],
            ["id"=>9,"parent"=>"8","text"=>"控制台9","state"=>["selected"=>false]],
            ["id"=>8,"parent"=>"#","text"=>"控制台8","state"=>["selected"=>true]]
        ],JSON_UNESCAPED_UNICODE);
    }

}
