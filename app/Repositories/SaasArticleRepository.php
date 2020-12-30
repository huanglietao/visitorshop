<?php
namespace App\Repositories;

use App\Models\SaasArticle;
use App\Models\SaasCategory;
use App\Services\Helper;

/**
 * 仓库模板
 * 文章列表数据仓库处理数据逻辑
 * @author: david
 * @version: 1.0
 * @date:2020/5/20
 */
class SaasArticleRepository extends BaseRepository
{
    protected $isCache = true; //是否使用缓存

    public function __construct(SaasArticle $model,SaasCategory $categoryModel)
    {
        $this->model = $model;
        $this->cateModel = $categoryModel;
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
            if(isset($where['art_title'])){
                $query =  $query->where('art_title', 'like', '%'.$where['art_title'].'%');
                unset($where['art_title']);
                $query =  $query->where($where);
            }else{
                $query =  $query->where($where);
            }
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
        if($data['art_sign']==GOODS_MAIN_CATEGORY_HELP){
            $cate = $this->cateModel->where(['cate_id'=>$data['art_type']])->first();
            if($cate['cate_parent_id'] == ZERO){
                return ['code'=>ZERO,'msg'=>'只能在帮助中心下级添加文章，请先添加下级分类'];
            }
        }

        if(empty($data['id'])) {
            unset($data['id']);
            $data['created_at'] = time();
            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;
        } else {
            $priKeyValue = $data['id'];
            unset($data['id']);
            $data['updated_at'] = time();
            $ret =$this->model->where('art_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
         if (isset($this->isCache)&&$this->isCache === true){
             $table_name = $this->model->getTable();
             $redis = app('redis.connection');
             $data['art_id'] = $priKeyValue;
             //将数据写入缓存
             $redis->set($table_name.'_'.$priKeyValue , json_encode($data));
         }
        return ['code'=>$ret,'msg'=>'操作成功'];

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
            $data['art_id'] = $id;
            $redis->del($table_name.'_'.$id);
        }

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }

    //获取文章信息组合返回上下编
    public function getArticleInfo($id,$mid=null)
    {
        //获取文章分类重新组合数组
        $artCate = $this->cateModel->where(['cate_parent_id'=>ZERO,'cate_uid'=>'article','cate_flag'=>GOODS_MAIN_CATEGORY_HELP])->first();
        $artype = $this->cateModel->where(['cate_parent_id'=>$artCate['cate_id']])->get()->toArray();
        //获取大后台跟商户所有的帮助中心文章
        $articles = $this->model->where(['art_sign'=>GOODS_MAIN_CATEGORY_HELP,'is_open'=>ONE])->whereIn('mch_id',[0,$mid])->get()->toArray();

        $articlearr=[];
        foreach ($artype as $k=>$v){
            foreach ($articles as $key=>$value){
                if($value['art_type']==$v['cate_id']){
                    $articlearr[]=$value;
                }
            }
        }

        foreach ($articlearr as $kk=>$kv) {
            if ($kv['art_id']==$id){
                //上一篇
                if(!empty($articlearr[$kk-1])){
                    $front = $articlearr[$kk-1];
                }else{
                    $front= [];
                }
                //下一篇
                if(!empty($articlearr[$kk+1])){
                    $after = $articlearr[$kk+1];
                }else{
                    $after = [];
                }
            }
        }

        return ['front'=>$front,'after'=>$after];
    }

    //获取所有文章信息以及文章分类组合数据
    public function getTypeArticleList($mid=null)
    {
        //获取文章分类重新组合数组
        $artCate = $this->cateModel->where(['cate_parent_id'=>ZERO,'cate_uid'=>'article','cate_flag'=>GOODS_MAIN_CATEGORY_HELP])->first();
        $artype = $this->cateModel->where(['cate_parent_id'=>$artCate['cate_id']])->get()->toArray();
        //获取大后台跟商户所有的帮助中心文章
        $articles = $this->model->where(['art_sign'=>GOODS_MAIN_CATEGORY_HELP,'is_open'=>ONE])->whereIn('mch_id',[0,$mid])->get()->toArray();

        $articlearr=[];
        foreach ($artype as $k=>$v){
            foreach ($articles as $key=>$value){
                if($value['art_type']==$v['cate_id']){
                    $articlearr[$v['cate_id']][]=$value;
                }
            }
        }

        return $articlearr;
    }

    //获取 大后台或者商户的文章未读消息（公告和通知）
    public function getUnReadArticle($ids,$mid)
    {
        $list = $this->model->where('mch_id',$mid)
            ->whereNotIn('art_id',$ids)
            ->where(function ($q){
                $q->where('art_sign','announce')
                    ->orWhere('art_sign','notice');
            })
            ->get();
        return $list;
    }

    //获取发给商户的全部消息
    public function getTableNewsList($where=null, $order=null)
    {
        $limit = isset($where['limit']) ? $where['limit']:config('common.page_limit');  //这个10取配置里的
        //去除搜索条件的限制条数，避免查询出错
        if (isset($where['limit'])){
            unset($where['limit']);
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
        /*$query = $this->model->with('linkNews'); //联表*/
        $query = $this->model->with(['linkNews'=>function($q)use($where){
            $q->where(['mch_id'=>$where['mch_id']]);
        }]); //联表

        //去除搜索条件的mid，避免查询数据出错
        if (isset($where['mch_id'])){
            unset($where['mch_id']);
        }

        if(!empty ($where)) {
            $query =  $query->where('mch_id',ZERO)->where($where);
        }else{
            $query =  $query->where('mch_id',ZERO)
                ->where(function ($q){
                    $q->where('art_sign','announce')
                        ->orWhere('art_sign','notice');
                });
        }

        if(!empty($order)) {
            $query =  $query->orderBy($orderBy[0],$orderBy[1]);
        }

        $list = $query->paginate($limit);
        return $list;
    }

    //获取发给分销商的全部消息
    public function getTableMchNewsList($where=null, $order=null)
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

        $query = $this->model->with('linkMchNews'); //联表

        if(!empty ($where)) {
            $query =  $query->where($where);
        }
        if(!isset($where['art_sign'])){
            $query =  $query
                ->where(function ($q){
                    $q->where('art_sign','announce')
                        ->orWhere('art_sign','notice');
                });
        }

        if(!empty($order)) {
            $query =  $query->orderBy($orderBy[0],$orderBy[1]);
        }

        $list = $query->paginate($limit);
        return $list;
    }





}
