<?php
namespace App\Repositories;
use App\Models\SaasAdPosition;
use App\Models\SaasAdvertisement;
use App\Http\Controllers\BaseController;
use App\Services\Helper;

/**
 * 仓库模板
 * 仓库模板
 * @author:
 * @version: 1.0
 * @date:
 */
class SaasAdvertisementRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存

    public function __construct(SaasAdvertisement $model)
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
        //查询时间
        if(isset($where['created_at'])){
            $created_at = $where['created_at'];
            $time_list = Helper::getTimeRangedata($created_at);
            $query = $query->whereBetween("created_at",[$time_list['start'],$time_list['end']]);
            unset($where['created_at']);
        }
        if(!empty ($where)) {
            if(isset($where['ad_title'])){
                $query =  $query->where('ad_title', 'like', '%'.$where['ad_title'].'%');
                unset($where['ad_title']);
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
        if(empty($data['id'])) {
            unset($data['id']);
            //查询位置对应的广告标识加入广告表中
            $adInfo = SaasAdPosition::where('ad_pos_id',$data['ad_position'])->first();
            $data['ad_flag'] = $adInfo['pos_flag'];
            $data['created_at'] = time();  //dd($data);
            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;
        } else {
            $priKeyValue = $data['id'];
            unset($data['id']);
            $data['updated_at'] = time();
            $ret =$this->model->where('ad_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
         if (isset($this->isCache)&&$this->isCache === true){
             $table_name = $this->model->getTable();
             $redis = app('redis.connection');
             $data['ad_id'] = $priKeyValue;
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
            $data['ad_id'] = $id;
            $redis->del($table_name.'_'.$id);
        }

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }

    /**
     * 获取广告列表数据
     *
     * @param $where (传入条件，数组形式)
     * @return array
     */
    public function getAdvertiseList($where=[])
    {
        $posLists = $this->model->where($where)->get();
        return $posLists;
    }

    //处理列表数据
    public function getMakeAdList($data)
    {

        foreach ($data as $k=>$v){
            if(strpos($v['ad_images'],',')){
                $imgArr = explode(',',$v['ad_images']);
                $data[$k]['ad_images'] = $imgArr;
            }else{
                $data[$k]['ad_images'] =[$v['ad_images']];
            }
        }

        return $data;
    }

    /**
     * 获取专属服务模块广告数据
     * @param $channel_id
     * @param $mid
     * @return array
     */
    public function getZsAdlist($mid=null,$channel_id)
    {
        $zsList = $this->model->where(['mch_id'=>$mid,'channel_id'=>$channel_id,'ad_flag'=>AD_FLAG_AGENT_ZS])->orderBy('ad_id','desc')->limit(1)->get()->toArray();

        if(empty($zsList)){//大后台
            //$zsLists = $this->model->where(['mch_id'=>ZERO,'channel_id'=>$channel_id,'ad_flag'=>AD_FLAG_AGENT_ZS])->orderBy('ad_id','desc')->first()->toArray();
            $zsLists = $this->model->where(['mch_id'=>ZERO,'channel_id'=>$channel_id,'ad_flag'=>AD_FLAG_AGENT_ZS])->orderBy('ad_id','desc')->limit(1)->get()->toArray();
            $zsList = $this->getMakeAdList($zsLists);
        }
        return $zsList;
    }

    /**
     * 获取优势模块广告数据
     * @param $channel_id
     * @param $mid
     * @return array
     */
    public function getYsAdlist($mid=null,$channel_id)
    {
        $ysList = $this->model->where(['mch_id'=>$mid,'channel_id'=>$channel_id,'ad_flag'=>AD_FLAG_AGENT_YS])->orderBy('ad_id','desc')->limit(1)->get()->toArray();

        if(empty($ysList)){//大后台
            $ysLists = $this->model->where(['mch_id'=>ZERO,'channel_id'=>$channel_id,'ad_flag'=>AD_FLAG_AGENT_YS])->orderBy('ad_id','desc')->limit(1)->get()->toArray();
            $ysList = $this->getMakeAdList($ysLists);
        }
        return $ysList;
    }

    /**
     * 获取优势模块广告数据
     * @param $channel_id
     * @param $mid
     * @return array
     */
    public function getHzAdlist($mid=null,$channel_id)
    {
        $hzList = $this->model->where(['mch_id'=>$mid,'channel_id'=>$channel_id,'ad_flag'=>AD_FLAG_AGENT_HZ])->orderBy('ad_id','desc')->limit(1)->get()->toArray();

        if(empty($hzList)){//大后台
            $hzLists = $this->model->where(['mch_id'=>ZERO,'channel_id'=>$channel_id,'ad_flag'=>AD_FLAG_AGENT_HZ])->orderBy('ad_id','desc')->limit(1)->get()->toArray();
            $hzList = $this->getMakeAdList($hzLists);

        }
        return $hzList;
    }






}
