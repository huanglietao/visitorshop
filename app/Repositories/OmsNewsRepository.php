<?php
namespace App\Repositories;

use App\Models\OmsNews;
use App\Services\Helper;

/**
 * 仓库模板
 * 商户消息中心仓库数据处理
 * @author: david
 * @version: 1.0
 * @date: 2020/5/26
 */
class OmsNewsRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存

    public function __construct(OmsNews $model)
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
        //查询时间
        if(isset($where['created_at'])){
            $created_at = $where['created_at'];
            $time_list = Helper::getTimeRangedata($created_at);
            $query = $query->whereBetween("created_at",[$time_list['start'],$time_list['end']]);
            unset($where['created_at']);
        }
        if(!empty ($where)) {
            if(isset($where['art_type_name'])){
                $query =  $query->where('art_type_name', 'like', '%'.$where['art_type_name'].'%');
                unset($where['art_type_name']);
                $query =  $query->where($where);
            }else{
                $query =  $query->where($where);
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
        if(empty($data['id'])) {
            unset($data['id']);
            if($data){
                $arr = $this->model->where(['channel_id'=>$data['channel_id'],'mch_id'=>ZERO])->get()->toArray();
                foreach ($arr as $k=>$v)
                {
                    if ($v['art_type_sign']==$data['art_type_sign']&&$v['art_type_sign']!="")
                    { //同个渠道下的标识必须不一样
                        return ['code'=>ZERO,'msg'=>'该标识已存在,请重新填写'];
                    }
                }
            }
            $data['created_at'] = time();
            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;
        } else {
            $priKeyValue = $data['id'];
            unset($data['id']);
            $data['updated_at'] = time();
            $ret =$this->model->where('art_type_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
         if (isset($this->isCache)&&$this->isCache === true){
             $table_name = $this->model->getTable();
             $redis = app('redis.connection');
             $data['art_type_id'] = $priKeyValue;
             //将数据写入缓存
             $redis->set($table_name.'_'.$priKeyValue , json_encode($data));
         }
        return ['code'=>$ret,'msg'=>'操作成功'];

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
            $data['art_type_id'] = $id;
            $redis->del($table_name.'_'.$id);
        }

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }

    public function getSaveNews($artid,$mid)
    {
        $newInfo = $this->model->where(['articles_id'=>$artid,'mch_id'=>$mid])->first();
        if(empty($newInfo)){
            $data= ['articles_id'=>$artid,'mch_id'=>$mid];
            $this->model->insertGetId($data);
        }

    }

}
