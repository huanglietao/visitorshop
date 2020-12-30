<?php
namespace App\Repositories;

use App\Models\SaasSuppliersOrderProduct;
use App\Services\Helper;

/**
 * 供货商订单详情仓库
 * @author: cjx
 * @version: 1.0
 * @date: 2020/05/08
 */

class SaasSuppliersOrderProductRepository extends BaseRepository
{
    protected $mch_id;

    public function __construct(SaasSuppliersOrderProduct $suppliersOrderProduct)
    {
        $this->mch_id = isset(session("admin")['mch_id']) ? session("admin")['mch_id'] : PUBLIC_CMS_MCH_ID;
        $this->model = $suppliersOrderProduct;
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

        //查询时间
        if(isset($where['created_at'])){
            $created_at = $where['created_at'];
            $time_list = Helper::getTimeRangedata($created_at);
            $query = $query->whereBetween("created_at",[$time_list['start'],$time_list['end']]);
            unset($where['created_at']);
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
        if(empty($data['sp_ord_id'])){
            unset($data['sp_ord_id']);
            $ret = $this->model->create($data);
        } else {
            $priKeyValue = $data['sp_ord_id'];
            unset($data['sp_ord_id']);

            $data['updated_at'] = time();
            $ret =$this->model->where('sp_ord_id',$priKeyValue)->update($data);
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

}