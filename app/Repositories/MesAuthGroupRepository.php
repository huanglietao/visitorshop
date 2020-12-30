<?php
namespace App\Repositories;

use App\Models\CmsAuthGroup;
use App\Models\CmsAuthRule;

/**
 *
 * 仓库模板:mes角色组
 * @author: cjx
 * @version: 1.0
 * @date: 2020/05/14
 */
class MesAuthGroupRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存

    public function __construct(CmsAuthGroup $model,CmsAuthRule $authRule)
    {
        $this->model =$model;
        $this->models =$authRule;
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
        //权限菜单处理
        if(!empty($data['rules'])){
            $data['cms_group_rule'] = $data['rules'];
            unset($data['rules']);
        }

        if(empty($data['id'])) {
            unset($data['id']);
            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;
        } else {
            $priKeyValue = $data['id'];
            unset($data['id']);
            $ret =$this->model->where('cms_group_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
         if (isset($this->isCache)&&$this->isCache === true){
             $table_name = $this->model->getTable();
             $redis = app('redis.connection');
             $data['cms_group_id'] = $priKeyValue;
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
            $data['cms_group_id'] = $id;
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
            $groupList[$v['cms_group_id']] = $v['cms_group_name'];
        }
        return $groupList;
    }
    /**
     *  添加或编辑时获取角色对应的菜单规则数据
     * @param $id
     * @return json
     */
    public function getCmsRuleList($id = null)
    {
        $list =  $this->models->where('cms_auth_rule_status',1)->select('cms_auth_rule_id','cms_auth_rule_pid','cms_auth_rule_title')->get()->toArray();
        $ruleList = [];
        foreach ($list as $k=>$v){
            $ruleList[] = [
                'id'=>$v['cms_auth_rule_id'],
                'parent'=>$v['cms_auth_rule_pid'] ? $v['cms_auth_rule_pid'] : '#',
                'text'=>$v['cms_auth_rule_title'],
                'state'=>["selected"=>false],
            ];
        }
        $ruleParent = array_column($ruleList,'parent');

        if(!empty($id)){ //编辑
            $groupRule = $this->model->where('cms_group_id',$id)->select('cms_group_rule')->first()->toArray();

            $groupRule = explode(',',$groupRule['cms_group_rule']);
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
        $groupRule = $this->model->where('cms_group_id',$data['pid'])->select('cms_group_rule')->first()->toArray();
        $list =  $this->models->select('cms_auth_rule_id','cms_auth_rule_pid','cms_auth_rule_title')->get()->toArray();
        $ruleList = [];
        foreach ($list as $k=>$v){
            $ruleList[] = [
                'id'=>$v['cms_auth_rule_id'],
                'parent'=>$v['cms_auth_rule_pid'] ? $v['cms_auth_rule_pid'] : '#',
                'text'=>$v['cms_auth_rule_title'],
                'state'=>["selected"=>false],
            ];


        }
        if($groupRule['cms_group_rule']!='*'){
            $groupRule = explode(',',$groupRule['cms_group_rule']);
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
