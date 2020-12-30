<?php
/**
 * mongodb记录日志
 *
 * 功能详细说明
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/8/22
 */

namespace App\Services\Common\Log;


class Mongo implements LogInterface
{
    protected $table = 'ty_logs';


    /**
     * 指定存储的collection
     * @param $collection 指定存储的集合名称
     */
    public function setCollection($collection)
    {
        $this->table = $collection;
    }
    /**
     * 记录日志到Mongodb数据库
     * @param $data
     */


    public function record($data)
    {
        // TODO: Implement record() method.
        $connection = app(\App\Services\Common\Mongo::class)->connectionMongodb($this->table);
        $connection->insert($data);
    }
    /**
     * 从Mongodb数据库取出错误日志
     * @param $data 搜索规则
     * @internal $offset 查询开始位置
     * @internal  $limit 查询条数
     * @string $order 排序字段
     * @string $sort 排序方式（默认倒序）
     */


    public function getLog($data,$offset=null,$limit=null,$order=null,$sort="desc")
    {

        // TODO: Implement getLog() method.
        $connection = app(\App\Services\Common\Mongo::class)->connectionMongodb($this->table);

        $res['count'] = $connection->where($data)->count();
        if ($order){
            $res['data'] = $connection->where($data)->skip($offset)->take($limit)->orderBy($order, $sort)->get();
        }else{
            $res['data'] = $connection->where($data)->skip($offset)->take($limit)->get();
        }

        return $res;
    }

    /**
     * 从Mongodb数据库刪除错误日志
     * @param $data 刪除條件
     */


    public function delLog($data)
    {
        // TODO: Implement getLog() method.
        $connection = app(\App\Services\Common\Mongo::class)->connectionMongodb($this->table);
        $res = $connection->where($data)->delete();
        return $res;
    }
}