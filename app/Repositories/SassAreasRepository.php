<?php
namespace App\Repositories;
use App\Models\SassAreas;

/**
 * 仓库模板
 * 仓库模板
 * @author: daiyd
 * @version: 1.0
 * @date: 2020/3/2
 */
class SassAreasRepository extends BaseRepository
{

    public function __construct(SassAreas $model)
    {
        $this->model =$model;
    }

    /**
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getTableList($where=null, $order=null)
    {   //dump($where);die;
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
        }else{
            $query =  $query->where($where)->where('level',1);
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
     *  所属上级地区转换成地区名称
     *
     * @return array
     */
    public function getPidList()
    {
        $list =  $this->model->get();
        $pidList = [];
        foreach ($list as $k=>$v){
            $pidList[$v['id']] = $v['name'];
        }

        return $pidList;
    }
    /**
     *  获取地区的id并查找出pid
     *
     * @return array
     */

    public function getAreaIdList($id)
    {
        $pid =  $this->model->where('id',$id)->first();
        return $pid;
    }


}
