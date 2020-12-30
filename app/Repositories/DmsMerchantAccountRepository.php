<?php
namespace App\Repositories;
use App\Models\DmsAuthGroup;
use App\Models\DmsMerchantAccount;
use App\Models\DmsAgentInfo;
use App\Services\Helper;
use App\Services\Tree;
use Illuminate\Support\Facades\DB;

/**
 * 仓库模板
 * 仓库模板
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/04/16
 */
class DmsMerchantAccountRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存
    protected $merchantGroupModel;
    protected $merchantInfoModel;

    public function __construct(DmsMerchantAccount $model, DmsAgentInfo $info,DmsAuthGroup $authGroup)
    {
        $this->model =$model;
        $this->merchantInfoModel = $info;
        $this->merchantGroupModel = $authGroup;
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
        //密码盐
        $data['dms_adm_salt'] = Helper::build();

        if(empty($data['dms_adm_id'])) {
            unset($data['dms_adm_id']);
            $data['dms_adm_password'] = $this->setPassword($data['dms_adm_password'],$data['dms_adm_salt']);
            $data['created_at'] = time();

            $ret = $this->model->insertGetId($data);

            $result['dms_adm_group_id'] = $this->createGroup($data['agent_info_id']);

            $this->model->where('dms_adm_id',$ret)->update($result);

            $priKeyValue = $ret;

        } else {
            $priKeyValue = $data['dms_adm_id'];
            unset($data['dms_adm_id']);

            if(!empty($data['dms_adm_password'])){
                $data['dms_adm_password'] = $this->setPassword($data['dms_adm_password'],$data['dms_adm_salt']);
            }else{
                unset($data['dms_adm_password']);
                unset($data['dms_adm_salt']);
            }
            $data['updated_at'] = time();
            $ret =$this->model->where('dms_adm_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
         if (isset($this->isCache)&&$this->isCache === true){
             $table_name = $this->model->getTable();
             $redis = app('redis.connection');
             $data['dms_adm_id'] = $priKeyValue;
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
            $data['id'] = $id;
            $redis->del($table_name.'_'.$id);
        }

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }

    /**
     * 添加默认角色组
     * @return $ret
     */
    public function createGroup($agent_id)
    {
        $data=[
            'dms_group_pid'=>'0',
            'agent_id'=>$agent_id,
            'dms_group_name'=>'系统默认组',
            'dms_group_rule'=>'*',
            'created_at'=>time()
        ];
        $ret = $this->merchantGroupModel->insertGetId($data);
        return $ret;
    }


    /**
     * 获取所有商户资料
     * @return array
     */
    public function getAllMerchantInfo($agent_info_id)
    {
        $infoList = $this->merchantInfoModel->where("agent_info_id",$agent_info_id)->select("agent_info_id","agent_name")->get();
        $result = json_decode($infoList,true);
        return $result;
    }


    //获取密码
    public function getPassword($password,$salt)
    {
        return $this->setPassword($password,$salt);
    }


    /**
     * 获取商户角色组
     * @return array
     */
    public function getMerchantGroup()
    {
        $groupList = $this->merchantGroupModel->where("dms_group_status",1)->get()->toArray();
        $data = [];
        if (!empty($groupList)){
            //树级分类
            $data = $this->getTreeList($groupList);
        }
        return $data;
    }


    /**
     * 获取商户角色组(不带树级分类)
     * @return array
     */
    public function getGroupList()
    {
        $groupList = $this->merchantGroupModel->get()->toArray();
        $data = [];
        foreach ($groupList as $k=>$v){
            $data[$v['dms_group_id']] = $v['dms_group_name'];
        }
        return $data;
    }


    //树级无限级分类
    public function getTreeList($array=[],$pidname='dms_group_pid',$table_id='dms_group_id',$name='dms_group_name')
    {
        $tree = new Tree();
        //组合无限极分类
        $tree::instance()->init($array,$pidname,$table_id);

        $categoryList = $tree::instance()->getTreeList($tree::instance()->getTreeArray(0), $name);
        return $categoryList;
    }


    //更新分销账号的信息
    public function updateInfo($agent_apply_id,$agent_info_id)
    {
        $data = [
            'agent_info_id' => $agent_info_id,
            'dms_adm_status' => ONE
        ];

        $ret = $this->model->where(['agent_info_id'=>$agent_apply_id])->update($data);

        $group_ret = app(DmsAuthGroup::class)->where(['agent_id'=>$agent_apply_id])->update(['agent_id' => $agent_info_id]);

        if($ret && $group_ret){
            return $ret;
        }


    }


    //根据id获取商家信息
    public function getAgentInfo($agent_info_id)
    {
        $agent = $this->model->where(['agent_info_id'=>$agent_info_id,'is_main'=>ONE])->first();
        if(!empty($agent)){
            return $agent;
        }
    }

}
