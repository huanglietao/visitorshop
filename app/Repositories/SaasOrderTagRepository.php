<?php
namespace App\Repositories;

use App\Models\SaasOrderTag;
use App\Services\Helper;

/**
 * 订单标签仓库
 * @author: cjx
 * @version: 1.0
 * @date: 2020/05/06
 */

class SaasOrderTagRepository extends BaseRepository
{
    protected $mch_id;

    public function __construct(SaasOrderTag $model)
    {
        $this->mch_id = isset(session("admin")['mch_id']) ? session("admin")['mch_id'] : PUBLIC_CMS_MCH_ID;

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
        $where['mch_id'] = $this->mch_id;

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
        if(empty($data['tag_id'])){
            unset($data['tag_id']);
            $data['created_at'] = time();

            $ret = $this->model->create($data);
        } else {
            $priKeyValue = $data['tag_id'];
            unset($data['tag_id']);

            $data['updated_at'] = time();
            $ret =$this->model->where('tag_id',$priKeyValue)->update($data);
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
     * 获取标签集合
     * @return Array
     */
    public function getTagList()
    {
        $data = $this->model->where('mch_id',$this->mch_id)->select('tag_id','tag_name')->get();

        $arr = [];
        if (empty($data->toArray())){
            return $arr;
        }

        foreach ($data as $k=>$v){
            $arr[0][$v['tag_id']] = $v['tag_name'];
            $arr[1][$k] = 'tag_name'.$v['tag_id'];
        }
        return $arr;
    }

}