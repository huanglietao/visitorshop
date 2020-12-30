<?php
namespace App\Repositories;
use App\Models\ErpFinanceDoc;

/**
 * 仓库模板
 * 仓库模板
 * @author:cjx
 * @version: 1.0
 * @date:2020/01/02
 */
class ErpFinanceDocRepository extends BaseRepository
{

    public function __construct(ErpFinanceDoc $model)
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
        $capital_change_status = isset($where['capital_change_status']) == true ? $where['capital_change_status'] : 3;
        $limit = isset($where['limit']) ? $where['limit']:config('common.page_limit');  //这个10取配置里的
        $where = $this->parseWhere($where);
        $where['status'] = 1;   //默认取支付成功的记录

        if(isset($where['pay_type'])) {
            //支付类型搜索
            unset($where['pay_type']);
            $where['pay_type'] = 1;
        }

        if($capital_change_status != 3) {
            //支付状态搜索
            if($capital_change_status == 2){
                unset($where['capital_change_status']);
            } else {
                $where['capital_change_status'] = $capital_change_status;
            }
        }

        /*金额范围搜索 start*/
        $range_flag = 0;
        if(isset($where['amount_min'])) {
            if(isset($where['amount_max'])) {
                $where[] = ['amount','>=',$where['amount_min']];
                $where[] = ['amount','<=',$where['amount_max']];
                $range_flag = 1;

                unset($where['amount_min']);
                unset($where['amount_max']);
            } else {
                $where[] = ['amount','>=',$where['amount_min']];
                $range_flag = 1;
                unset($where['amount_min']);
            }
        }

        if($range_flag == 0 && isset($where['amount_max'])) {
            $where[] = ['amount','<=',$where['amount_max']];
            unset($where['amount_max']);
        }
        /*金额范围搜索 end*/

        if(isset($where['createtime'])) {
            //创建时间搜索
            $arr = explode(" - ",$where['createtime']);
            $where[] = ['createtime','>=',strtotime($arr[0])];
            $where[] = ['createtime','<=',strtotime($arr[1])];

            unset($where['createtime']);
        }

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
     * 最近充值记录
     * @param $partnerCode 客户编号
     * @return array
     */
    public function getRechargeData($partnerCode,$num)
    {
        $data = $this->model
                ->where('partner_code',$partnerCode)
                ->where('status',1)
                ->select('recharge_no','amount','createtime')
                ->orderBy('createtime', 'desc')
                ->take($num)
                ->get();

        $list = collect($data)->toArray();

        foreach ($list as $k=>$v) {
            $list[$k]['createtime'] = date("Y-m-d",$v['createtime']);
        }
        return $list;
    }

}
