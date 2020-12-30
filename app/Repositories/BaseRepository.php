<?php namespace App\Repositories;

use App\Services\Helper;

abstract class BaseRepository {

	/**
	 * The Model instance.
	 *
	 * @var Illuminate\Database\Eloquent\Model
	 */
	protected $model;

	/**
	 * 获取主model条数.
	 *
	 * @return array
	 */
	public function getNumber()
	{
		$total = $this->model->count();


		return compact('total');
	}

	/**
	 * Destroy a model.
	 *
	 * @param  int $id
	 * @return void
	 */
	public function destroy($id)
	{
		$this->getById($id)->delete();
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
	 * Get Model by id.
	 *
	 * @param  int  $id
	 * @return App\Models\Model
	 */
	public function getById($id)
	{
        return $this->model->find($id);
	}

    /**
     * 生成密码
     * @param $pwd 明文密码
     * @param string $salt 密码盐值
     * @return string
     */
	public function setPassword($pwd, $salt = '')
    {
        return md5($pwd.$salt);
    }

    /**
     * @param null $where
     * @return mixed
     */
    protected function parseWhere($where=null)
    {
        if(empty($where)) {
            return [];
        }


        if(is_array($where)) {
            unset($where['limit']);
            unset($where['page']);
            foreach ($where as $k=>$v) {

                if(empty($v)&&$v!==0) {
                    unset($where[$k]);
                }
            }
            return $where;
        }
        return $where;
    }

    /**
     * 根据条件获取结果集
     * @param $where 条件
     * @return array
     */
    public function getRow($where,$field=[])
    {
        $where = $this->parseWhere($where);
        if (empty($field))
            return $this->model->where($where)->first();
        else
            return $this->model->where($where)->first($field);
    }

    /**
     * 根据条件获取结果集
     * @param $where 条件
     * @return array
     */
    public function getList($where,$order='created_at',$sort="desc")
    {
        return $this->model->where($where)->orderby($order,$sort)->get();
    }

    /**
     * 获取结果集,比getList更灵活
     * @param $where
     * @param string $order
     * @param string $sort
     * @param int limit
     */
    public function getRows($where, $order,$sort="desc", $limit = 0)
    {
        $query = $this->model;
        foreach ($where as $k=>$v) {
            if (is_array($v)) {
                $query = $query->whereIn($k,$v);
            } else {
                $query = $query->where($k,$v);
            }
        }

        $query = $query->orderby($order,$sort);
        if (!empty($limit)) {
            $query = $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * @param $where  条件
     * @param int $offset 偏移量
     * @param int $limit  每页多少个
     * @param $order  排序
     * @param string $sort
     * @return array
     */
    public function getRowsPage($where, $offset, $limit , $order, $sort="desc")
    {
        $query = $this->model;
        foreach ($where as $k=>$v) {
            if (is_array($v)) {
                $query = $query->whereIn($k,$v);
            } else {
                $query = $query->where($k,$v);
            }
        }
        $count = $query->count();
        $list = $query->offset($offset)->limit($limit)->orderby($order,$sort)->get()->toArray();

        return [
            'list'    => $list,
            'count'   => $count
        ];
    }

    /**
     * 插入记录
     * @param $data
     * @return int|boolean
     */
    public function insert($data)
    {
        $ret = $this->model->create($data);
        return $ret;
    }
    /**
     * 获取插入id
     * @param $data
     * @return int|boolean
     */
    public function insertGetId($data)
    {
        $ret = $this->model->insertGetId($data);
        return $ret;
    }

    /**
     * 更新记录
     * @param $where
     * @param $data
     */
    public function update($where, $data)
    {
        $where = $this->parseWhere($where);
        return $this->model->where($where)->update($data);
    }

    /**
     * whereIn查询
     * @param $field
     * @param $array
     */
    public function WhereIn($field, $array)
    {

        return $this->model->WhereIn($field,$array)->get();
    }

    /**
     *获取仓库对应的model
     */
    public function getModel()
    {
        return $this->model;
    }
    /**
     *获取缓存数据
     */
    public function getByIdFromCache($id)
    {
        if (isset($this->isCache)&&$this->isCache === true){
            //查询缓存直接返回缓存数据
            $table_name = $this->model->getTable();
            $redis = app('redis.connection');

            $data = json_decode($redis->get($table_name.'_'.$id),true);

            if (empty($data)){
                //缓存找不到则查询数据库
                return $this->model->find($id)?$this->model->find($id)->toArray():null;

            }
            return $data;
        }else{
            return $this->model->find($id)?$this->model->find($id)->toArray():null;
        }
    }


    /**
     * 批量更新数据
     * @param $data
     * @return boolean
     */
    public function batchUpdate($tableName, $data)
    {
        foreach($data as $k=>$row){
            $set_arr = array();
            $arr_key = array();
            $fields = array();

            foreach ($row as $key=>$val){

                if($key != $this->model->getKeyName()){
                    $fields[] = $key.'=VALUES('.$key.')';
                }
                $arr_key[] = $key;
                $set_arr[] = '\''.addslashes($val).'\'';
            }

            $arr_value[] = '('.implode(',',$set_arr).')';
        }
        if(!empty($set_arr)){
            $sql = 'INSERT INTO '.$tableName . ' ('.implode(',',$arr_key).') VALUES ' . implode(', ', $arr_value) . ' ON DUPLICATE KEY UPDATE '.implode(',',$fields);
            $ret = \DB::getPdo()->exec($sql);
            if(!empty($ret) || $ret === 0){
                return true;
            }else{
                Helper::EasyThrowException('10020',__FILE__.__LINE__);
            }
        }

        return true;
    }
    /**
     * 批量更新数据
     * @param $data
     * @return boolean
     */
    public function batchInsert($tableName, $data)
    {
        foreach($data as $k=>$row){
            $set_arr = array();
            $arr_key = array();
            $fields = array();

            foreach ($row as $key=>$val){

                if($key != $this->model->getKeyName()){
                    $fields[] = $key.'=VALUES('.$key.')';
                }
                $arr_key[] = $key;
                $set_arr[] = '\''.addslashes($val).'\'';
            }

            $arr_value[] = '('.implode(',',$set_arr).')';
        }
        if(!empty($set_arr)){
            $sql = 'INSERT INTO '.$tableName . ' ('.implode(',',$arr_key).') VALUES ' . implode(', ', $arr_value);
            $ret = \DB::getPdo()->exec($sql);
            if(!empty($ret) || $ret === 0){
                return true;
            }else{
                Helper::EasyThrowException('10020',__FILE__.__LINE__);
            }
        }

        return true;
    }

    /**
     * @desc arraySort php二维数组排序 按照指定的key 对数组进行自然排序
     * @param array $arr 将要排序的数组
     * @param string $keys 指定排序的key
     * @param string $type 排序类型 asc | desc
     * @return array
     */
    public function arraySort($arr, $keys, $type = 'asc')
    {
        $keysvalue = $new_array = array();
        foreach ($arr as $k => $v) {
            $keysvalue[$k] = $v[$keys];
        }
        // dump($keysvalue);

        if ($type == 'asc') {
            natcasesort($keysvalue);
        }
        if ($type == 'desc') {
            natcasesort($keysvalue);
            $keysvalue = array_reverse($keysvalue, TRUE);
        }
        // dump($keysvalue);
        foreach ($keysvalue as $k => $v) {
            $new_array[$k] = $arr[$k];
        }
        // dump($new_array);
        return $new_array;

    }

    /**
     * 字段自增
     * @param $field
     * @param int $num
     */
    public function increment($where,$field,$num=1)
    {
        $this->model->where($where)->increment($field,$num);
    }


}
