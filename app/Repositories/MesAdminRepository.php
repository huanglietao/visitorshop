<?php
namespace App\Repositories;

use App\Models\MesAdmin;
use App\Services\Helper;

/**
 * 供货商账号仓库模板
 * mes管理员仓库
 * @author: cjx
 * @version: 1.0
 * @date: 2020/05/14
 */
class MesAdminRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存

    public function __construct(MesAdmin $model)
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
        //处理状态值为0的情况把转化成int
        if(empty($where['scm_adm_status'])&&isset($where['scm_adm_status'])){
            $where['scm_adm_status'] = intval($where['scm_adm_status']);
        }
        //时间转时间戳
        if(!empty($where['created_at'])){
            $aa = Helper::getTimeRangedata($where['created_at']);
        }

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
            if(!empty($where['created_at'])){
                $query = $query->whereBetween('created_at',[$aa['start'],$aa['end']]);
            }

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
        if($data['_token']){
            unset($data['_token']);
        }
       // dump($data);die;
        $salt = Helper::build();
        if(empty($data['id'])) {
            unset($data['id']);
            //$data['cms_adm_password'] = md5(md5($data['cms_adm_password']).$salt); //密码加密规定原则
            $data['scm_adm_password'] = $this->setPassword($data['scm_adm_password'],$salt);
            $data = $data+['scm_adm_salt'=>$salt,'created_at'=>time()];
            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;
        } else {
            // 如果没有修改密码就不更改密码盐
            if($data['scm_adm_password']){
                $data['scm_adm_salt'] = $salt;
                $data['scm_adm_password'] = $this->setPassword($data['scm_adm_password'],$salt);
            }else{
                unset($data['scm_adm_password']);unset($data['scm_adm_salt']);
            }
            $data['updated_at'] = time(); //修改时更新时间
            $priKeyValue = $data['id'];
            unset($data['id']);
            $ret =$this->model->where('scm_adm_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
         if (isset($this->isCache)&&$this->isCache === true){
             $table_name = $this->model->getTable();
             $redis = app('redis.connection');
             $data['scm_adm_id'] = $priKeyValue;
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
            $data['scm_adm_id'] = $id;
            $redis->del($table_name.'_'.$id);
        }

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }
    //获取密码
    public function getPassword($password,$salt)
    {
        return $this->setPassword($password,$salt);
    }

    //根据供货商id获取账号信息
    public function getBySupID($sp_id)
    {
        $supAccount = $this->model->where(['sp_id'=>$sp_id])->first();
        if(empty($supAccount)){
            $supAccount = null;
        }
        return $supAccount;
    }
}
