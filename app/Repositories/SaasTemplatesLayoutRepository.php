<?php
namespace App\Repositories;
use App\Models\SaasTemplatesLayout;

/**
 * 仓库模板
 *  布局列表管理仓库
 * @author:
 * @version: 1.0
 * @date: 2020/5/6
 */
class SaasTemplatesLayoutRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存

    public function __construct(SaasTemplatesLayout $model)
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
        if(isset($data['_token'])){
            unset($data['_token']);
        }
        if(empty($data['id'])) {
            unset($data['id']);
            $data['created_at']= time();
            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;
        } else {
            $priKeyValue = $data['id'];
            $data['updated_at']= time();
            unset($data['id']);
            $ret =$this->model->where('temp_layout_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
         if (isset($this->isCache)&&$this->isCache === true){
             $table_name = $this->model->getTable();
             $redis = app('redis.connection');
             $data['temp_layout_id'] = $priKeyValue;
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
            $data['temp_layout_id'] = $id;
            $redis->del($table_name.'_'.$id);
        }

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }


    /**
     *  改变审核状态
     * @param $data
     * @return bool
     */
    public function changeCheckStatus($data)
    {
        if(!empty($data)) {
            $this->model->where('temp_layout_id',$data['id'])->update(['layout_check_status'=>$data['status']]);
            return true;
        }else{
            return false;
        }

    }




}
