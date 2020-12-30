<?php
/**
 * 功能简介
 *
 * 功能详细说明
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/8/10
 */

namespace App\Repositories;


use App\Models\SaasExceptionLog;

class SaasExceptionLogRepository extends BaseRepository
{
    public function __construct(SaasExceptionLog $model)
    {
        $this->model = $model;
    }

    /**
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getTableList($where=null, $order=null)
    {
        $limit = isset($where['limit']) ? $where['limit']:config('common.page_limit');  //这个10取配置里的
        if(isset($where['is_solved'])){
            $where['is_solved'] = intval($where['is_solved']);
        }
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
        if(isset($where['title'])){
            $query =  $query->where('title', 'like', '%'.$where['title'].'%');
            unset($where['title']);
        }
        if (!empty($where)) {
            $query = $query->where($where);
        }
        if(!empty($order)) {
            $query =  $query->orderBy($orderBy[0],$orderBy[1]);
        }

        $list = $query->paginate($limit);
        return $list;
    }

    /**
     *  改变字段是否为菜单
     * @param $flag
     * @return bool
     */
    public function changeUpdateField($flag)
    {
        if($flag['flag'] == ZERO) {
            $ret = $this->model->where('id',$flag['id'])->update(['is_solved'=>1,'updated_at'=>time()]);
            return ['flag'=>ONE];
        }
        if($flag['flag'] == ONE) {
            $ret = $this->model->where('id',$flag['id'])->update(['is_solved'=>0,'updated_at'=>time()]);
            return ['flag'=>ZERO];
        }
    }




}