<?php
namespace App\Repositories;
use App\Models\SaasInnerTemplates;
use App\Models\SaasCategory;
use App\Models\SaasProductSize;
use App\Services\Template\Main;
use App\Services\Template\Attachment;


/**
 * 仓库模板
 * 仓库模板
 * @author:
 * @version: 1.0
 * @date:
 */
class SaasInnerTemplatesRepository extends BaseRepository
{
    protected $isCache = true; //是否使用缓存

    public function __construct(SaasInnerTemplates $model)
    {
        $this->model =$model;
    }

    /**
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getTableList($where=null, $order=null, $flag=null)
    {
        $limit = isset($where['limit']) ? $where['limit']:config('common.page_limit');  //这个10取配置里的
        if($flag){$limit = $flag;};
        $where = $this->parseWhere($where);

        //order 必须以 'id desc'这种方式传入.
        $orderBy = [];
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }

        $query = $this->model->with('sizeInfo'); //联表
        $query =  $query->where('mch_id',0);// 主表加限制条件去大后台
        //模板名称
        if(isset($where['inner_temp_name']) && !empty($where['inner_temp_name'])){
            $query = $query->where('inner_temp_name', 'like', '%'.$where['inner_temp_name'].'%');
            unset($where['inner_temp_name']);
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
        if($goodsCate->cate_flag == GOODS_DIY_CATEGORY_CALENDAR && $data['inner_temp_start_year']==''){
            return ['code'=>ZERO,'msg'=>'请选择起始年份'];
        }
        //根据规格查询规格标签
        $sizeType = SaasProductSize::where(['size_id'=>$data['specifications_id']])->first();
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
            $data['inner_spec_style']= $sizeType['size_type'];
            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;

            //自动生成对应类型的子页数据
            if($goodsCate->cate_flag == GOODS_DIY_CATEGORY_ALBUM) {
                $logicMain = new Main();
                //添加主模板库时自动添加照片书子页
                $logicMain->addPhotoBookPages($data,$priKeyValue);
            } else if($goodsCate->cate_flag == GOODS_DIY_CATEGORY_CALENDAR){
                $logicMain = new Main();
                //在添加主模板库时自动生成台历子页
                $logicMain->addCalendarPages($data, $priKeyValue);
            }else{
                $logicMain = new Main();
                //添加主模板库时自动添加其他商品类型子页
                $logicMain->addOtherPages($data, $priKeyValue);
            }
        } else {
            $priKeyValue = $data['id'];
            $id = $data['id'];
            unset($data['id']);
            $data['updated_at']= time();
            $ret =$this->model->where('inner_temp_id',$priKeyValue)->update($data);
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
            $attachLogic->removeRelation($priKeyValue, $attaIds, TEMPLATE_PAGE_INNER);
        }
        //判断是否需要更新缓存
         if (isset($this->isCache)&&$this->isCache === true){
             $table_name = $this->model->getTable();
             $redis = app('redis.connection');
             $data['inner_temp_id'] = $priKeyValue;
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
            $data['inner_temp_id'] = $id;
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
            $this->model->where('inner_temp_id',$data['id'])->update(['inner_temp_check_status'=>$data['status']]);
            return true;
        }else{
            return false;
        }

    }

    /**
     *  获取单条内页模板数据
     * @param $data
     * @return bool
     */
    public function getInnerTemp($tid)
    {
        $mainTemp = $this->model->where('inner_temp_id',$tid)->first();
        return $mainTemp;

    }








}
