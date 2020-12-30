<?php
namespace App\Services\Common;

use DB;
/**
 * mongodb类
 *
 * mongodb初始化及连接
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/9/3
 */
class Mongo
{
    public $manager;
    private $config;

    /**
     * Mongo constructor.
     * @param null $config
     * example:$manager = new MongoDB\Driver\Manager("mongodb://myusername:mypassword@example.com/mydatabase");
     */
    public function __construct($config = null)
    {
        if(empty($config)){
            $config = $this->config  = config('common.mongo');
        }
        $this->manager = new \MongoDB\Driver\Manager($config['host']);
    }

    /**
     *
     * @param $tables collection集合
     * @return mixed
     */
    public function connectionMongodb($tables)
    {
        return $users = DB::connection('mongodb')->collection($tables);
    }

    /**
     * @param $collection 集合
     * @param $where 条件
     * @return mixed
     */
    public function select($collection, $where)
    {
        $query =  new \MongoDB\Driver\Query($where,[]);
        $cursor = $this->manager->executeQuery($this->config['db'].'.'.$collection, $query);
        $cursor->setTypeMap(['root' => 'array', 'document' => 'array', 'array' => 'array']);

        $arrInfo = $cursor->toArray();

        return $arrInfo;
    }

    /**
     * 插入操作
     * @param $collection
     * @param $data
     * @return boolean
     */
    public function insert($collection ,$data)
    {
        $bulk = new \MongoDB\Driver\BulkWrite;
        $bulk->insert($data);

        $tables = $this->config['db'].'.'.$collection;
        $writeConcern = new \MongoDB\Driver\WriteConcern(\MongoDB\Driver\WriteConcern::MAJORITY, 1000);
        $ret = $this->manager->executeBulkWrite($tables, $bulk, $writeConcern);
        if($ret->getInsertedCount()){
            return true;
        }else{
            return false;
        }

    }

    /**
     * 更新操作
     * @param $collection
     * @param $set
     * @param $where
     * @return bool
     */
    public function update($collection, $set, $where)
    {
        $bulk = new \MongoDB\Driver\BulkWrite;

        if(empty($where)){  //所有的update必须加条件
            return false;
        }
        $set = ['$set' => $set];
        $bulk->update($where, $set, ['multi' => true, 'upsert' => false]);

        $tables = $this->config['db'].'.'.$collection;
        $writeConcern = new \MongoDB\Driver\WriteConcern(\MongoDB\Driver\WriteConcern::MAJORITY, 1000);
        $ret = $this->manager->executeBulkWrite($tables, $bulk, $writeConcern);

        if($ret->getModifiedCount() || $ret->getModifiedCount()===0){
            return true;
        }else{
            return false;
        }
    }
}