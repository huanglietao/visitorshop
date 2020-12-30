<?php
namespace App\Repositories;
use App\Models\SaasSuppliersLogisticsCosts;

/**
 * 仓库模板
 * 仓库模板
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/06/16
 */
class SaasSuppliersLogisticsCostsRepository extends BaseRepository
{

    public function __construct(SaasSuppliersLogisticsCosts $model)
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
        if(empty($data['sup_log_cos_id'])) {
            unset($data['sup_log_cos_id']);
            $data['created_at'] = time();
            $ret = $this->model->create($data);
        } else {
            $priKeyValue = $data['sup_log_cos_id'];
            unset($data['sup_log_cos_id']);
            $data['updated_at'] = time();
            $ret =$this->model->where('sup_log_cos_id',$priKeyValue)->update($data);
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

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }

    /**
     * 根据供货商id获取设置的物流成本
     * Date: 2020/6/16
     * Time: 15:51
     * @param $sup_id
     */
    public function getCosts($sup_id)
    {
        $logCosts = $this->model->where(['sup_id'=>$sup_id])->first();
        return $logCosts;
    }

}
