<?php
namespace App\Repositories;
use App\Models\SaasAdPosition;

/**
 * 仓库模板
 * 广告位置仓库数据逻辑处理
 * @author: david
 * @version: 1.0
 * @date: 2020/5/15
 */
class SaasAdPositionRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存

    public function __construct(SaasAdPosition $model)
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
            if(isset($where['ad_position'])){
                $query =  $query->where('ad_position', 'like', '%'.$where['ad_position'].'%');
                unset($where['ad_position']);
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
                    if ($v['pos_flag']==$data['pos_flag']&&$v['pos_flag']!="")
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
            $data['updated_at'] = time();
            /*if($data){
                $arr = $this->model->where(['channel_id'=>$data['channel_id'],'mch_id'=>ZERO])->get()->toArray();

                foreach ($arr as $k=>$v)
                {
                    if ($v['ad_pos_id']!=$data['id']&&$v['pos_flag']==$data['pos_flag']&&$v['pos_flag']!="")
                    {//同个渠道下的标识必须不一样，去除了本身的标识避免重复
                        return ['code'=>ZERO,'msg'=>'广告标识已存在,请重新填写广告标识'];
                    }
                }
            }*/

            unset($data['id']);
            $ret =$this->model->where('ad_pos_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
         if (isset($this->isCache)&&$this->isCache === true){
             $table_name = $this->model->getTable();
             $redis = app('redis.connection');
             $data['ad_pos_id'] = $priKeyValue;
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
            $data['ad_pos_id'] = $id;
            $redis->del($table_name.'_'.$id);
        }

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }

    /**
     * 获取广告位置说明数据（默认取全部，可根据传渠道id获取对应数据）
     *
     * @param $where (传入条件，数组形式)
     * @return array
     */
    public function getAdPositionList($where=[],$is_mid=null)
    {
        if(empty($is_mid)){
            $posLists = $this->model->where(['ad_status'=>ONE])->where('ad_pos_id','<>',1)->where($where)->get();
        }else{
            $posLists = $this->model->where(['ad_status'=>ONE])->where($where)->get();
        }

        return $posLists;
    }

    /**
     * 获取广告位置说明数据（详情）
     * @param $cid
     * @return array
     */
    public function getAdPositionInfo($cid)
    {
        if($cid){
            $posInfo = $this->model->where(['ad_status'=>ONE,'ad_pos_id'=>$cid])->first();
        }
        return $posInfo;
    }



}
