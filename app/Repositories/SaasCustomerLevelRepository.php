<?php
namespace App\Repositories;
use App\Models\SaasCustomerLevel;
use Illuminate\Support\Facades\DB;

/**
 * 仓库模板
 * 仓库模板
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/04/15
 */
class SaasCustomerLevelRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存

    public function __construct(SaasCustomerLevel $model)
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
        if(empty($data['cust_lv_id'])) {
            unset($data['cust_lv_id']);
            $data['created_at'] = time();
            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;
        } else {
            $priKeyValue = $data['cust_lv_id'];
            unset($data['cust_lv_id']);
            $data['updated_at'] = time();
            $ret =$this->model->where('cust_lv_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
         if (isset($this->isCache)&&$this->isCache === true){
             $table_name = $this->model->getTable();
             $redis = app('redis.connection');
             $data['cust_lv_id'] = $priKeyValue;
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
            $data['cust_lv_id'] = $id;
            $redis->del($table_name.'_'.$id);
        }

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }

    /*
     * 获取组别id和名称
     * @params $mch_id,$lv_type
     * @return $row
     */
    public function getGrade($mch_id,$lv_type,$id=null)
    {

        $row = [];
        if(empty($id)){
            $result = DB::table('saas_customer_level')->where(['mch_id'=>$mch_id,'cust_lv_type'=>$lv_type])->whereNull("deleted_at")->select('cust_lv_id','cust_lv_name')->get();
            $result = json_decode($result,true);
            foreach ($result as $key => $value){
                $row[$value['cust_lv_id']] = $value['cust_lv_name'];
            }
        }else{
            $result = DB::table('saas_customer_level')->where(['mch_id'=>$mch_id,'cust_lv_type'=>$lv_type,'cust_lv_id'=>$id])->whereNull("deleted_at")->select('cust_lv_name')->get();
            $result = json_decode($result,true);
            foreach ($result as $key => $value){
                $row = $value['cust_lv_name'];
            }
        }

        return $row;
    }
    //获取组别名称
    public function getGradeName($id)
    {
        $name = $this->model->where(['cust_lv_id' => $id])->value('cust_lv_name');
        return $name;
    }
}
