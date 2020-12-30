<?php
namespace App\Repositories;

use App\Models\SaasInnerTemplatesPages;
use App\Models\SaasSizeInfo;

/**
 * 仓库模板
 * 内模板子页
 * @author: dai
 * @version: 1.0
 * @date: 2020/4/25
 */
class SaasInnerTemplatesPagesRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存

    public function __construct(SaasInnerTemplatesPages $model)
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

        $query = $this->model->with('innerSizeInfo');
        $query =  $query->where('mch_id',ZERO);// 主表加限制条件
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
            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;
        } else {
            $priKeyValue = $data['id'];
            unset($data['id']);
            $ret =$this->model->where('main_temp_page_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
        if (isset($this->isCache)&&$this->isCache === true){
            $table_name = $this->model->getTable();
            $redis = app('redis.connection');
            $data['main_temp_page_id'] = $priKeyValue;
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
            $data['main_temp_id'] = $id;
            $redis->del($table_name.'_'.$id);
        }

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }


    /**
     *  根据模板的子页id获取相对应的子页数据返回最后一天排序最大的
     * @param $tid
     * @return array
     */
    public function getInnerPagesList($tid)
    {
        $pagesList = $this->model->where('inner_page_id',$tid)->first();
        $tempPages = $this->model->where('inner_page_tid',$pagesList['inner_page_tid'])->orderBy('inner_page_sort','DESC')->first();

        return $tempPages;
    }




}
