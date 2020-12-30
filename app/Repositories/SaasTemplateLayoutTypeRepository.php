<?php
namespace App\Repositories;
use App\Models\SaasTemplateLayoutType;

/**
 * 仓库模板:布局版式
 * 布局版式的数据处理逻辑
 * @author:dai
 * @version: 1.0
 * @date:2020/4/15
 */
class SaasTemplateLayoutTypeRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存

    public function __construct(SaasTemplateLayoutType $model)
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
        $query =  $query->where('temp_layout_type_status',1);
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
            $data = $data+['created_at'=>time()];
            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;
        } else {
            $priKeyValue = $data['id'];
            unset($data['id']);
            $data['updated_at'] = time(); //修改时更新时间
            $ret =$this->model->where('temp_layout_type_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
         if (isset($this->isCache)&&$this->isCache === true){
             $table_name = $this->model->getTable();
             $redis = app('redis.connection');
             $data['temp_layout_type_id'] = $priKeyValue;
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
            $data['temp_layout_type_id'] = $id;
            $redis->del($table_name.'_'.$id);
        }

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }

    /**
     * 获取布局版式列表
     * @param $mid 商户id
     * @return array
     */
    public function getlayoutTypes($mid = ZERO)
    {
        if(empty($mid)) {
            $where_MID = [ZERO];
        } else {
            $where_MID = [ZERO, $mid];
        }
        $list = $this->model->whereIn('mch_id',$where_MID)->get()->toArray();

        $arrLink = [];
        foreach ($list as $k=>$v) {
            $arrLink[$v['temp_layout_type_id']]= $v['temp_layout_type_name'];
        }

        return $arrLink;
    }





}
