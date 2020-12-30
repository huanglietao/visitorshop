<?php

namespace App\Repositories;

use App\Models\OmsAuthGroup;
use App\Models\OmsMerchantAccount;
use App\Models\OmsMerchantInfo;
use App\Services\Helper;
use App\Services\Tree;

/**
 * 商户账号仓库模板
 * @author:cjx
 * @version: 1.0
 * @date:2020/04/03
 */
class OmsMerchantAccountRepository extends BaseRepository
{

    public function __construct(OmsMerchantAccount $model, OmsMerchantInfo $info,OmsAuthGroup $authGroup,OmsMerchantAccount $merchantAccount)
    {
        $this->model =$model;
        $this->merchantInfoModel = $info;
        $this->merchantGroupModel = $authGroup;
        $this->omsAccountModel = $merchantAccount;
        $this->mch_id = isset(session("admin")['mch_id']) ? session("admin")['mch_id'] : '';
    }

    /**
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getTableList($where=null, $order=null, $isMain)
    {
        $limit = isset($where['limit']) ? $where['limit']:config('common.page_limit');  //这个10取配置里的
        $where = $this->parseWhere($where);

        if($isMain == 0){
            //商户平台管理员列表
            $where['mch_id'] = session("admin")['mch_id'];
            $order = 'is_main desc';
        }else{
            $where['is_main'] = $isMain;
        }

        //order 必须以 'id desc'这种方式传入.
        $orderBy = [];
        if(empty($order)){
            $order='oms_adm_id desc';
        }
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
    public function save($data,$is_main=0)
    {
        //密码盐
        $data['oms_adm_salt'] = Helper::build();

        $data['is_main'] = isset($data['is_main']) ? $data['is_main']: $is_main;
        $data['mch_id'] = isset($data['mch_id']) ? $data['mch_id'] : session('admin')['mch_id'];

        if(empty($data['id'])) {
            unset($data['id']);

            $data['oms_adm_password'] = $this->setPassword($data['oms_adm_password'],$data['oms_adm_salt']);
            $data['created_at'] = time();
            $ret = $this->model->create($data);
        } else {
            $priKeyValue = $data['id'];
            unset($data['id']);

            if(!empty($data['oms_adm_password'])){
                $data['oms_adm_password'] = $this->setPassword($data['oms_adm_password'],$data['oms_adm_salt']);
            }else{
                unset($data['oms_adm_password']);
                unset($data['oms_adm_salt']);
            }

            $data['updated_at'] = time();
            $ret =$this->model->where('oms_adm_id',$priKeyValue)->update($data);
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
     * 获取所有未创建账号的商户资料
     * @return array
     */
    public function getAllMerchantInfo($mch_id=null)
    {
        $infoList = $this->merchantInfoModel->select("mch_id","mch_name")->get();
        if(empty($mch_id)){
            foreach ($infoList as $k=>$v){
                $is_exist = $this->omsAccountModel->where(['mch_id'=>$v['mch_id'],'is_main'=>1])->first();
                if(!empty($is_exist)){
                    unset($infoList[$k]);
                }
            }
        }

        return $infoList;
    }

    /**
     * 获取商户角色组(关联oms_group_pid下所有组)
     * @return array
     */
    public function getMerchantGroup($oms_adm_group_id)
    {
        $groupList = $this->merchantGroupModel->where(['mch_id'=>$this->mch_id])->orWhere(['oms_group_id'=>$oms_adm_group_id])->get()->toArray();
        foreach ($groupList as $k=>$v){
            if($v['oms_group_status'] != PUBLIC_ENABLE){
                unset($groupList[$k]);
            }
        }
        return $groupList;
    }

    //树级无限级分类
    public function getTreeList($array=[],$pidname='oms_group_pid',$table_id='oms_group_id',$name='oms_group_name')
    {
        $tree = new Tree();
        //组合无限极分类
        $tree::instance()->init($array,$pidname,$table_id);

        $categoryList = $tree::instance()->getTreeList($tree::instance()->getTreeArray(0), $name);
        return $categoryList;
    }
    

    //获取密码
    public function getPassword($password,$salt)
    {
        return $this->setPassword($password,$salt);
    }

    /**
     * 获取商户角色组(oms_group_pid=0)
     * @return array
     */
    public function getGroupList($flag=null)
    {
        if(empty($flag)){
            $where = ['oms_group_pid'=>0,'oms_group_status'=>PUBLIC_ENABLE];
        }else{
            $where = ['oms_group_pid'=>0];
        }
        $groupList = $this->merchantGroupModel->where($where)->get()->toArray();
        $data = [];
        foreach ($groupList as $k=>$v){
            $data[$v['oms_group_id']] = $v['oms_group_name'];
        }
        return $data;
    }

}
