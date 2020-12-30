<?php
namespace App\Repositories;
use App\Models\SaasCoverTemplates;
use App\Services\Template\Attachment;
use App\Models\SaasCategory;

/**
 * 仓库模板
 * 封面模板库数据操作逻辑处理仓库
 * @author: david
 * @version: 1.0
 * @date: 2020/4/23
 */
class SaasCoverTemplatesRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存

    public function __construct(SaasCoverTemplates $model)
    {
        $this->model =$model;
    }

    /**
     * @param null $where
     * @param null $order
     * @param $flag 传标识条数
     * @return mixed
     */
    public function getTableList($where=null, $order=null ,$flag=null)
    {
        $limit = isset($where['limit']) ? $where['limit']:config('common.page_limit');  //这个10取配置里的
        if($flag){$limit=$flag;};
        $where = $this->parseWhere($where);

        //order 必须以 'id desc'这种方式传入.
        $orderBy = [];
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }
        $query = $this->model->with('sizeInfo');

        $query = $query->where('mch_id',0);
        //模板名称
        if(isset($where['cover_temp_name']) && !empty($where['cover_temp_name'])){
            $query = $query->where('cover_temp_name', 'like', '%'.$where['cover_temp_name'].'%');
            unset($where['cover_temp_name']);
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
        //先根据商品分类id查询该对应标识分类
        $goodsCate = SaasCategory::where(['cate_id'=>$data['goods_type_id']])->select('cate_flag')->first();
        if($goodsCate->cate_flag == GOODS_DIY_CATEGORY_CALENDAR && $data['cover_temp_start_year']==''){
            return ['code'=>ZERO,'msg'=>'请选择起始年份'];
        }
        //转化数据库不存在的字段
        if(isset($data['background']) || isset($data['decorate']) || isset($data['frame'])){
            $attach['background'] = $data['background'];
            $attach['decorate'] = $data['decorate'];
            $attach['frame'] = $data['frame'];
        }
        unset($data['background'],$data['decorate'],$data['frame']);
        if(empty($data['id'])) {
            unset($data['id']);
            $data['created_at']= time();
            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;
        } else {
            $priKeyValue = $data['id'];
            $id = $data['id'];
            unset($data['id']);
            $data['updated_at']= time();
            $ret =$this->model->where('cover_temp_id',$priKeyValue)->update($data);
        }

        //处理图片逻辑
        $attachLogic = new Attachment();

        if(empty($attach['background']) && empty($attach['decorate']) && empty($attach['frame'])) {
            $attaIds = [];
        } else{
            //操作相关的素材
            $attachment = $attach['background'].','.$attach['decorate'].','.$attach['frame'];
            $arrAtta = explode(',', trim($attachment, ','));

            $attaIds = [];
            foreach ($arrAtta as $k=>$v) {
                if(!empty($v) && $k%2==0) {
                    $attaIds[] = $v;
                }
            }
        }

        //处理模板与附件的关联关系
        $attachLogic->relationTemplate($priKeyValue , $attaIds);

        //处理图片删除,删除不作事务，不影响其他操作
        if(isset($id)) {
            $attachLogic->removeRelation($priKeyValue, $attaIds, TEMPLATE_PAGE_PAGE);
        }

        //判断是否需要更新缓存
         if (isset($this->isCache)&&$this->isCache === true){
             $table_name = $this->model->getTable();
             $redis = app('redis.connection');
             $data['cover_temp_id'] = $priKeyValue;
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
            $data['cover_temp_id'] = $id;
            $redis->del($table_name.'_'.$id);
        }

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }

    /**
     *  改变审核状态
     * @param $data
     * @return bool
     */
    public function changeCheckStatus($data)
    {
        if(!empty($data)) {
            $this->model->where('cover_temp_id',$data['id'])->update(['cover_temp_check_status'=>$data['status']]);
            return true;
        }else{
            return false;
        }

    }

    /**
     *  获取单条封面模板数据
     * @param $data
     * @return bool
     */
    public function getCoverTemp($tid)
    {
        $mainTemp = $this->model->where('cover_temp_id',$tid)->first();
        return $mainTemp;

    }




}
