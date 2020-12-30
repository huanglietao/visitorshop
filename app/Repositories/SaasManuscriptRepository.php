<?php
namespace App\Repositories;

use App\Models\SaasManuscript;

/**
 * 功能简介
 *
 * 功能详细说明
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/19
 */
class SaasManuscriptRepository extends BaseRepository
{
    public function __construct(SaasManuscript $model)
    {
        $this->model =$model;
    }

    /**
     * 新增/修改
     * @param $data
     * @return boolean
     */
    public function save($data)
    {
        if(empty($data['script_id'])) {
            unset($data['script_id']);
            $data['created_at'] = time();
            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;
        } else {
            $priKeyValue = $data['script_id'];
            unset($data['script_id']);
            $data['updated_at'] = time();
            $ret =$this->model->where('script_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
        if (isset($this->isCache)&&$this->isCache === true){
            $table_name = $this->model->getTable();
            $redis = app('redis.connection');
            $data['script_id'] = $priKeyValue;
            //将数据写入缓存
            $redis->set($table_name.'_'.$priKeyValue , json_encode($data));
        }
        return $ret;

    }
}