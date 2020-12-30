<?php
namespace App\Repositories;
use App\Models\DmsFinanceDoc;
use App\Services\Helper;

/**
 *
 * 仓库模板:DMS账户充值
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/04/167
 */
class DmsFinanceDocRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存
    protected $agent_id;

    public function __construct(DmsFinanceDoc $dmsFinanceDoc)
    {
        $this->model = $dmsFinanceDoc;
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

//        var_dump($where);exit;
        //下单时间查询或者发货时间查询
        if(isset($where['search_type']) && $where['search_type']==1 && isset($where['search_time'])){
            $created_at = $where['search_time'];
            $time_list = Helper::getTimeRangedata($created_at);
            $query = $query->whereBetween("created_at",[$time_list['start'],$time_list['end']]);
            unset($where['search_time']);
        }
        if(isset($where['search_type']) && $where['search_type']==2 && isset($where['search_time'])){
            $finishtime = $where['search_time'];
            $time_list = Helper::getTimeRangedata($finishtime);
            $query = $query->whereBetween("finishtime",[$time_list['start'],$time_list['end']]);
            unset($where['search_time']);

        }
        unset($where['search_type']);

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
        if(empty($data['finance_doc_id'])) {
            unset($data['finance_doc_id']);
            $data['created_at'] = time();
            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;
        } else {
            $priKeyValue = $data['finance_doc_id'];
            unset($data['finance_doc_id']);
            $data['updated_at'] = time();
            $ret =$this->model->where('finance_doc_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
        if (isset($this->isCache)&&$this->isCache === true){
            $table_name = $this->model->getTable();
            $redis = app('redis.connection');
            $data['finance_doc_id'] = $priKeyValue;
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
            $data['finance_doc_id'] = $id;
            $redis->del($table_name.'_'.$id);
        }

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }

    //根据流水号获取数据信息
    public function getByRechargeNo($recharge_no)
    {
        $rechargeInfo = $this->model->where(['recharge_no' => $recharge_no])->get()->toArray();
        return $rechargeInfo;
    }

    //根据流水号更新数据信息
    public function updateFinanceDoc($recharge_no,$data)
    {
        $updateResult = $this->model->where(['recharge_no' => $recharge_no])->update($data);
        return $updateResult;
    }

}
