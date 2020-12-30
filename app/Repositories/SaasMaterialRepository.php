<?php
namespace App\Repositories;

use App\Models\SaasMaterial;
use App\Services\Helper;
/**
 * 仓库模板
 *  素材管理仓库，数据处理逻辑
 * @author:
 * @version: 1.0
 * @date:
 */
class SaasMaterialRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存

    public function __construct(SaasMaterial $model,SaasCategoryRepository $cateReposity)
    {
        $this->model =$model;
        $this->cateRepoty = $cateReposity;
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

        $query = $this->model->with('materAttach');
        //查询时间
        if(isset($where['created_at'])){
            $created_at = $where['created_at'];
            $time_list = Helper::getTimeRangedata($created_at);
            $query = $query->whereBetween("created_at",[$time_list['start'],$time_list['end']]);
            unset($where['created_at']);
        }
        if(isset($where['mch_id'])&& !empty($where['mch_id'])){ //区分商户跟大后台
            $query = $query
            ->whereIn('mch_id',$where['mch_id']);
            unset($where['mch_id']);
            //$query = $query->where($where);
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
        if(empty($data['id'])) {
            $data['created_at']= time();
            unset($data['id']);

            $goodsCate = $this->cateRepoty->getCategoryFlag($data['material_cateid'],'parentId');
            if($goodsCate['cate_flag'] == MATERIAL_TYPE_BACKGROUND){
                $data['material_cate_flag'] = MATERIAL_TYPE_BACKGROUND;
            }elseif($goodsCate['cate_flag'] == MATERIAL_TYPE_DECORATE){
                $data['material_cate_flag'] = MATERIAL_TYPE_DECORATE;
            }elseif($goodsCate['cate_flag'] == MATERIAL_TYPE_FRAME){
                $data['material_cate_flag'] = MATERIAL_TYPE_FRAME;
            }elseif ($goodsCate['cate_flag'] == MATERIAL_TYPE_SPECIAL){
                $data['material_cate_flag'] = MATERIAL_TYPE_SPECIAL;
            }

            if($data['attachment_id']){
                $arr = explode(',',$data['attachment_id']);

                //把关联的附件id取出来
                $arrId = [];
                foreach ($arr as $k=>$v) {
                    if($k%2==0){
                        $arrId[] = $v;
                    }
                }

                //批量添加
                foreach ($arrId as $k=>$v) {
                    if($v == 0 ){
                        continue;
                    }
                    $data['attachment_id'] = $v;
                    $ret = $this->model->insertGetId($data);
                }
                $priKeyValue = $ret;
            }


        } else {
            $priKeyValue = $data['id'];
            $data['updated_at']= time();
            unset($data['id']);
            $ret =$this->model->where('material_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
         if (isset($this->isCache)&&$this->isCache === true){
             $table_name = $this->model->getTable();
             $redis = app('redis.connection');
             $data['material_id'] = $priKeyValue;
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
            $data['material_id'] = $id;
            $redis->del($table_name.'_'.$id);
        }

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }

    /**
     * 获取带附件的素材列表
     * @param $where
     * @param $offset
     * @param $limit
     * @param $order
     * @param string $sort
     * @return array
     */
    public function getMaterialWithAttachment($where, $offset, $limit,$order, $sort = 'desc')
    {
        $query = $this->model->with('materAttach');
        foreach ($where as $k=>$v) {
            if (is_array($v)) {
                $query = $query->whereIn($k,$v);
            } else {
                $query = $query->where($k,$v);
            }
        }
        $count = $query->count();
        $list = $query->offset($offset)->limit($limit)->orderby($order,$sort)->get()->toArray();

        return [
            'list'    => $list,
            'count'   => $count
        ];
    }

}
