<?php
namespace App\Repositories;
use App\Models\OmsAuthRule;

/**
 * 仓库模板
 * 商户菜单功能数据逻辑
 * @author:david
 * @version: 1.0
 * @date: 2020/06/23
 */
class OmsAuthRuleRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存

    public function __construct(OmsAuthRule $model)
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
        $limit = "9999999999";  //不做分页

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
        if(empty($data['id'])) {
            unset($data['id']);
            $data['created_at']= time();
            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;
        } else {
            $priKeyValue = $data['id'];
            if($data['oms_auth_rule_pid']==$data['id']){
                return ['code' => 0,'msg'=>'父级不能选择本身'];
            }
            unset($data['id']);
            $data['updated_at']= time();
            $ret =$this->model->where('oms_auth_rule_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
         if (isset($this->isCache)&&$this->isCache === true){
             $table_name = $this->model->getTable();
             $redis = app('redis.connection');
             $data['oms_auth_rule_id'] = $priKeyValue;
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
            $data['oms_auth_rule_id'] = $id;
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
            $ret = $this->model->where('oms_auth_rule_id',$flag['id'])->update(['oms_auth_rule_ismenu'=>1]);
            return ['flag'=>ONE];
        }
        if($flag['flag'] == ONE) {
            $ret = $this->model->where('oms_auth_rule_id',$flag['id'])->update(['oms_auth_rule_ismenu'=>0]);
            return ['flag'=>ZERO];
        }
    }





}
