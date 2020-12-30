<?php
namespace App\Repositories;
use App\Models\DmsAgentApply;
use App\Services\Helper;
use Illuminate\Support\Facades\DB;

/**
 * 仓库模板
 * 仓库模板
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/04/15
 */
class DmsAgentApplyRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存

    public function __construct(DmsAgentApply $model)
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
        //审核通过的信息不显示
        $query = $query->whereIn('review_status',[1,3]);

        //查询时间
        if(isset($where['created_at'])){
            $created_at = $where['created_at'];
            $time_list = Helper::getTimeRangedata($created_at);
            $query = $query->whereBetween("created_at",[$time_list['start'],$time_list['end']]);
            unset($where['created_at']);
        }

        //店铺名称
        if(isset($where['agent_name']) && !empty($where['agent_name'])){
            $query = $query->where('agent_name', 'like', '%'.$where['agent_name'].'%');
            unset($where['agent_name']);
        }

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
        if(empty($data['agent_apply_id'])) {
            unset($data['agent_apply_id']);
            $data['created_at'] = time();
            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;
        } else {
            $priKeyValue = $data['agent_apply_id'];
            unset($data['agent_apply_id']);
            $data['updated_at'] = time();
            $ret =$this->model->where('agent_apply_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
         if (isset($this->isCache)&&$this->isCache === true){
             $table_name = $this->model->getTable();
             $redis = app('redis.connection');
             $data['agent_apply_id'] = $priKeyValue;
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
            $data['agent_apply_id'] = $id;
            $redis->del($table_name.'_'.$id);
        }

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }

    /**
     * 更新资料创建账号状态为已创建
     * @param $infoID
     * @return bool
     */
    public function updateIsCreate($infoID)
    {
        $ret = DB::table('dms_agent_apply')->where(['agent_info_id'=>$infoID])->update(['is_create_adm'=>'2']);
        return $ret;
    }


    //查找是否存在手机号的账号
    public function checkMobile($mobile)
    {
        $isExist = $this->model->where(['mobile'=>$mobile])->count();
        return $isExist;
    }


}
