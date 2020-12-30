<?php
namespace App\Repositories;
use App\Models\SaasAttributeValues;

/**
 * 商品属性值仓库模板
 * 商品属性值仓库模板
 * @author: hlt <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/04/07
 */
class SaasAttributeValueRepository extends BaseRepository
{

    public function __construct(SaasAttributeValues $model)
    {
        $this->model = $model;
    }

    //获取列表 by hlt
    public function getList($where=[], $order='created_at', $sort = "desc")
    {
        return parent::getList($where, $order, $sort); // TODO: Change the autogenerated stub
    }

    //获取对应的父级属性
    public function getAttrArr($attrValueArr,$p=0)
    {
        $attrArr = $this->model->whereIn('attr_val_id', $attrValueArr)->get()->toArray();
        $data=[];
        foreach ($attrArr as $k => $v)
        {
           $data[$k]['attr_id'] = $v['attr_id'];
           $data[$k]['attr_value_id'] = $v['attr_val_id'];
           $data[$k]['is_p'] = 0;
            $data[$k]['value'] = $v['attr_val_name'];
            $data[$k]['p_value'] = "";
           if ($v['attr_id'] == $p){
               //p数属性
               $data[$k]['is_p'] = 1;
               //取出p数属性值中的数字
               preg_match('/^(\d)*/',$v['attr_val_name'],$matches);
               $data[$k]['p_value'] = $matches[0];
           }else{
               $data[$k]['p_value']="";
           }

        }

        return $data;
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
        //判断是否需要更新缓存
        if (isset($this->isCache)&&$this->isCache === true){
            $table_name = $this->model->getTable();
            $redis = app('redis.connection');
            $data['attr_val_id'] = $id;
            //将客户单号写进redis
            $redis->del($table_name.'_'.$id);
        }

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }
}
