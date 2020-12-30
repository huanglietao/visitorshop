<?php
namespace App\Repositories;

use App\Models\ErpVipCustomer;

/**
 * 仓库模板
 * 仓库模板
 * @author:cjx
 * @version: 1.0
 * @date:2020/01/02
 */
class ErpVipCustomerRepository extends BaseRepository
{

    public function __construct(ErpVipCustomer $model)
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
            $ret = $this->model->create($data);
        } else {
            $priKeyValue = $data['id'];
            unset($data['id']);
            $ret =$this->model->where('id',$priKeyValue)->update($data);
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
     * 客户最近登录信息
     * @param $partnerCode 客户编号
     * @return array
     */
    public function getUserLoginInfo($partnerCode)
    {
        $data = $this->model
                ->where('partner_code',$partnerCode)
                ->select('id','prevtime','joinip')
                ->orderBy('created_at','desc')
                ->first();

        $list = collect($data)->toArray();
        $list['prevtime'] = date("Y-m-d",$list['prevtime']);

        return $list;
    }

}
