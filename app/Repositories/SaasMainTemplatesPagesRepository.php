<?php
namespace App\Repositories;

use App\Models\SaasMainTemplatesPages;
use App\Models\SaasSizeInfo;

/**
 * 仓库模板
 * 主模板子页
 * @author: dai
 * @version: 1.0
 * @date: 2020/4/21
 */
class SaasMainTemplatesPagesRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存

    public function __construct(SaasMainTemplatesPages $model)
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
            $data['main_temp_page_id'] = $id;
            $redis->del($table_name.'_'.$id);
        }

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }


    /**
     *  获取规格数据并组装成子页列表所需要数据
     * @param $data
     * @return array
     */
    public function getSpecTempList($data,$spec_id)
    {
        $specList = SaasSizeInfo::where(['size_id'=>$spec_id])->get()->toArray();

       $specType = [];
       foreach ($specList as $k=>$v){
           $specType[$v['size_type']] = $v;
       }

        foreach ($data as $k=>$v){
            $data[$k]['size_is_cross'] = $specType[$v['main_temp_page_type']]['size_is_cross'];
            $data[$k]['size_design_w'] = $specType[$v['main_temp_page_type']]['size_design_w'];
            $data[$k]['size_design_h'] = $specType[$v['main_temp_page_type']]['size_design_h'];
        }

        return $data;
    }

    /**
     *  获取规格数据并组装成子页需要数据
     * @param $data
     * @return array
     */
    public function getSpecTypeList($spec_id)
    {
        $specList = SaasSizeInfo::where(['size_id'=>$spec_id])->get()->toArray();

        $specType = [];
        foreach ($specList as $k=>$v){
            $specType[$v['size_type']] = $v['size_type'];
        }
        return $specType;
    }


    /**
     *  根据模板的子页id获取相对应的子页数据返回最后一天排序最大的
     * @param $tid
     * @return array
     */
    public function getMainPagesList($tid)
    {
        $pagesList = $this->model->where('main_temp_page_id',$tid)->first();
        $tempPages = $this->model->where('main_temp_page_tid',$pagesList['main_temp_page_tid'])->orderBy('main_temp_page_sort','DESC')->first();

        return $tempPages;
    }




}
