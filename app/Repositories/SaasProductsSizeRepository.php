<?php
namespace App\Repositories;

use App\Models\SaasProductSize;
use App\Exceptions\CommonException;
use App\Models\SaasSizeInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\Helper;
use App\Http\Requests\Backend\Goods\ProductsSizeInfoRequest;
use App\Repositories\SaasSizeInfoRepository;

/**
 * 商品规格仓库模板
 * 商品规格仓库模板
 * @author: hlt <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/04/07
 */
class SaasProductsSizeRepository extends BaseRepository
{

    protected $sizeInfo;
    public function __construct(SaasProductSize $model, SaasSizeInfoRepository $sizeInfo,SaasSizeInfo $infoModel,
                    SaasSizeInfoRepository $sizeInfoRepository,SaasCategoryRepository $categoryRepository
    )
    {
        $this->model = $model;
        $this->sizeInfo = $sizeInfo;
        $this->infoModel = $infoModel;
        $this->sizeInfoRepository = $sizeInfoRepository;
        $this->cateRepo = $categoryRepository;
    }
    /**
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getTableList($where=null, $order='created_at desc')
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

        $query = $this->model->with('categoryAttach');
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
        $list = $list->toArray();
        $sizeArr = [];
        //获取规格的设计区尺寸
        foreach ($list['data'] as $k => $v)
        {
            $sizeArr[] = $v['size_id'];
        }

        $designArr = $this->sizeInfoRepository->getSizeDesign($sizeArr);


        foreach ($list['data'] as $k => $v)
        {
            if (isset($designArr['design'][$v['size_id']]))
            {
                foreach ($designArr['design'][$v['size_id']] as $kk => $vv)
                {
                    $list['data'][$k]['design_size'][] = $vv['design_text'].":".$vv['design_size_text'];
                }
            }else{
                $list['data'][$k]['design_size'] = [];
            }

        }


        return $list;
    }


    /**
     * 新增/修改
     * @param $data
     * @return boolean
     */
    public function save($data)
    {

        //判断是否传入规格参数
        if (!isset($data['page_type']) || (isset($data['page_type']) && empty($data['page_type'])))
        {
            if (empty($data['id']))
            {
                $ret = [
                    'code' => 0,
                    'msg'  => "请填写规格参数",
                ];
                return $ret;
            }

        }


        try {

            \DB::beginTransaction();
            $insertSizeArr = [
                'size_name'    => $data['size_name'],
                'mch_id'       => $data['mch_id'],
                'size_desc'    => $data['size_desc'],
                'size_icon'    => $data['size_icon']??"",
                'size_cate_id' => $data['size_cate_id'],
                'size_type'    => $data['size_type'],
                'size_dpi'     => $data['size_dpi'],

            ];
        if(empty($data['id'])) {
            unset($data['id']);
            //组织规格表数据
            $insertSizeArr['created_at'] = time();
            $newSizeId = $this->model->insertGetId($insertSizeArr);
            $priKeyValue = $newSizeId;
            //
        } else {
            $priKeyValue = $data['id'];
            unset($data['id']);
            $insertSizeArr['updated_at'] = time();
            $this->model->where('size_id',$priKeyValue)->update($insertSizeArr);
        }


        foreach ($data['page_type'] as $k => $v)
        {
            $sizeInfoArr =[
                'size_design_w'        => $data['specificationsParam']['size_design_w'][$v],
                'size_design_h'        => $data['specificationsParam']['size_design_h'][$v],
                'size_location_top'    => $data['specificationsParam']['size_location_top'][$v],
                'size_location_left'   => $data['specificationsParam']['size_location_left'][$v],
                'size_location_bottom' => $data['specificationsParam']['size_location_bottom'][$v],
                'size_location_right'  => $data['specificationsParam']['size_location_right'][$v],
                'size_tip_top'         => $data['specificationsParam']['size_tip_top'][$v],
                'size_tip_bottom'      => $data['specificationsParam']['size_tip_bottom'][$v],
                'size_tip_left'        => $data['specificationsParam']['size_tip_left'][$v],
                'size_tip_right'       => $data['specificationsParam']['size_tip_right'][$v],
                'size_cut_top'         => $data['specificationsParam']['size_cut_top'][$v],
                'size_cut_bottom'      => $data['specificationsParam']['size_cut_bottom'][$v],
                'size_cut_left'        => $data['specificationsParam']['size_cut_left'][$v],
                'size_cut_right'       => $data['specificationsParam']['size_cut_right'][$v],
            ];
            //数据验证
            $check = $this->checkInfoData($sizeInfoArr);
            if ($check['code'] == 0){
                $data = [
                    'code' => 0,
                    'msg' => $check['msg']
                ];
                \DB::rollBack();
                return $data;
            }
            $sizeInfoArr['size_is_cross']   = $data['specificationsParam']['size_is_cross'][$v];
            $sizeInfoArr['size_is_2faced']  = $data['specificationsParam']['size_is_2faced'][$v];
            $sizeInfoArr['size_is_display'] = $data['specificationsParam']['size_is_display'][$v];
            $sizeInfoArr['size_is_output']  = $data['specificationsParam']['size_is_output'][$v];
            $sizeInfoArr['size_is_locked']  = $data['specificationsParam']['size_is_locked'][$v];
            $sizeInfoArr['size_info_dpi']   = $data['size_dpi'];


            if (! $data['specificationsParam']['size_info_id'][$v]){
                //新增详情数据
                $sizeInfoArr['size_type']  = $v;
                $sizeInfoArr['size_id'] = $priKeyValue;
                $sizeInfoArr['created_at'] = time();
                $this->infoModel->insert($sizeInfoArr);
            }else{
                $insertSizeArr['updated_at'] = time();
                //更新详情数据
                $this->infoModel->where(['size_info_id' => $data['specificationsParam']['size_info_id'][$v]])->update($sizeInfoArr);
            }


        }
            \DB::commit();
            $ret = [
                'code' => 1,
                'msg' => 'ok',
            ];
            return $ret;
        } catch (CommonException $e) {

            \DB::rollBack();
            $ret = [
                'code' => 0,
                'msg'  => $e->getMessage()

            ];
            return $ret;
        }

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
            $data['size_id'] = $id;
            $redis->del($table_name.'_'.$id);
        }

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }


    //获取列表 by hlt
    public function getList($where=[], $order='created_at', $sort = "desc")
    {
        return parent::getList($where, $order, $sort); // TODO: Change the autogenerated stub
    }

    /**
     *  获取规格数据
     * @param $mid ,$spec
     * $spec标识不同默认输出到form使用（二维数组：用类型当下标，规格名作值），其他要用可以传随意参数（一维数组id当下标名称做值）
     * @return array
     */
    public function getGoodsSpecList($mid = 0,$spec = 'form',$face=null)
    {
        if(empty($mid)) {
            $where_MID = [0];
        } else {
            $where_MID = [0, $mid];
        }
        $list = $this->model->whereIn('mch_id',$where_MID)->get()->toArray();

        $arrLink = [];

        if($spec=='table'){
            foreach ($list as $k=>$v) {
                if(!empty($face)){
                    $sizeInfo = $this->getNewFaceSizeName($v['size_id']);
                }else{
                    $sizeInfo = $this->getNewSizeName($v['size_id']);
                }
                $arrLink[$v['size_id']]= $sizeInfo[$v['size_id']]['size_name'];
            }
        }else{
            foreach ($list as $k=>$v) {
                if(!empty($face)){
                    $sizeInfo = $this->getNewFaceSizeName($v['size_id']);
                }else{
                    $sizeInfo = $this->getNewSizeName($v['size_id']);
                }
                $arrLink[$v['size_cate_id']][$v['size_id']]= $sizeInfo[$v['size_id']]['size_name'];
               // $arrLink[$v['size_cate_id']][$v['size_id']]= $v['size_name'];
            }
        }

        return $arrLink;
    }

    /**
     *  获取单条规格数据
     * @param $id
     * @return array
     */
    public function getProductSize($id)
    {
        $size = $this->model->where('size_id',$id)->first();
        return $size;

    }


    /**
     * 获取规格的组合信息
     * @param $sizeId 规格id
     * @param int $goodsId 商品id
     * @return array
     */
    public function getSizeCombInfo($sizeId ,$goodsId = 0)
    {
        //获取规格基础信息
        $sizeBasic = $this->getByIdFromCache($sizeId);
        if (empty ($sizeBasic)) {
            Helper::EasyThrowException('40015',__FILE__.__LINE__);
        }
        //获取规格详情信息

        $sizeInfo = [];
        //先查商品本身是否已经定义
        if (!empty($goodsId)) {
            $where[] = ['size_id' , $sizeId];
            $where[] = ['goods_id' , $goodsId];
            $sizeInfo = $this->sizeInfo->getList($where,'size_info_id', 'asc')->toArray();
        }

        //再查商品对应的
        if (empty($sizeInfo)) {
            $noGoodsWhere[] = ['size_id' , $sizeId];
            $noGoodsWhere[] = ['goods_id' , ZERO];
            $sizeInfo = $this->sizeInfo->getList($noGoodsWhere,'size_info_id', 'asc')->toArray();
        }


        foreach ($sizeInfo as $k=>$v) {
            $sizeBasic['detail_list'][$v['size_info_id']] = $v;
        }
        return $sizeBasic;

    }

    /**
     * 通过商户id获取规格信息
     * @param $mchId 商户id
     * @param array $where 其他条件
     * @return array
     */
    public function getSizeInfoByMid($mchId, $where = [])
    {
        $query = $this->model;
        if(!empty($mchId)) {
            $whereMid = [0, $mchId];
        } else {
            $whereMid = [0];
        }
        $query = $query->whereIn('mch_id', $whereMid);

        if(!empty($where)) {
            $bzWhere = $this->parseWhere($where);
            $query->where($bzWhere);
        }

        $list = $query->get()->toArray();
        return $list;
    }

    //验证规格详情数据
    public function checkInfoData($array)
    {
        foreach ($array as $k=>$v)
        {
            if (!$v && $v!=='0'){
                //非空验证
                $data = [
                    'code' => 0,
                    'msg'  => "请填写完整参数",
                ];
                return $data;
            }
            if (!is_numeric($v)){
                //数字验证
                $data = [
                    'code' => 0,
                    'msg'  => "参数必须为数字",
                ];
                return $data;
            }
        }

        $data=[
            'code' => 1,
            'msg'  => 'ok'
        ];
        return $data;


    }

    //获取规格的子页类型及规格详情
    public function getPageTypeAndInfo($sizeId,$goods_id = null)
    {

        if ($goods_id)
        {
            $new_where = ['goods_id' => $goods_id];
        }else{
            $new_where = ['goods_id' => 0];
        }


        $data = $this->infoModel->where(['size_id' => $sizeId])->where($new_where)->get()->toArray();
        if (empty($data) && $goods_id){
            $data = $this->infoModel->where(['size_id' => $sizeId,'goods_id' => 0])->get()->toArray();
        }


        $allPageType = config("goods.page_type");
        $pageType = [];
        $allSizeTypeInfo = [];

        foreach ($data as $k=>$v){
            //以size_type为健值来组织数组
            $allSizeTypeInfo[$v['size_type']] = $v;
            if (isset($allPageType[$v['size_type']])){
                $pageType[$v['size_type']] = $allPageType[$v['size_type']];
            }

        }
        ksort($allSizeTypeInfo);
        ksort($pageType);
        return [
            'data'            => $data,
            'allSizeTypeInfo' => $allSizeTypeInfo,
            'pageType'        => $pageType
        ];

    }
    //获取商品列表的规格数组
    public function getPSizeList($where=[],$mid = PUBLIC_CMS_MCH_ID)
    {
        if (!is_array($mid)){
            $mid = explode(',',$mid);
        }

        $pSizeList = $this->model->whereIn('mch_id',$mid)->where($where)->get()->toArray();

        foreach ($pSizeList as $k => $v)
        {
            $size_id[] = $v['size_id'];
        }
        if (!empty($size_id)){
            $sizeName = $this->getNewSizeName($size_id);
        }

        foreach ($pSizeList as $k => $v)
        {
            if (isset($sizeName[$v['size_id']]))
            {
                $pSizeList[$k]['size_new_name'] = $sizeName[$v['size_id']]['size_name'];
            }
        }
       return $pSizeList;

    }
    //获取商品规格显示格式
    public function getNewSizeName($size_id)
    {
        if (!is_array($size_id)){
            $size_id = explode(',',$size_id);
        }
        $array = $this->sizeInfoRepository->getSizeDesign($size_id);
        //获取内页数组
        $innerArr = $array['innerArr'];
        $newSizeName = [];
        foreach ($size_id as $k=>$v)
        {
            //获取该id的规格名称
            $sizeName = $this->model->where(['size_id'=>$v])->value('size_name');
            if (isset($innerArr[$v]))
            {
                //存在内页
                $sizeName = $sizeName."(".$innerArr[$v]['size_design_w']."mm*".$innerArr[$v]['size_design_h']."mm)";
            }
            $newSizeName[$v]['size_id'] = $v;
            $newSizeName[$v]['size_name'] = $sizeName;
        }
        return $newSizeName;

    }
    //获取商品规格显示格式(只做封面)
    public function getNewFaceSizeName($size_id)
    {
        if (!is_array($size_id)){
            $size_id = explode(',',$size_id);
        }
        $array = $this->sizeInfoRepository->getSizeFaceDesign($size_id);
        //获取内页数组
        $innerArr = $array['faceArr'];
        $newSizeName = [];
        foreach ($size_id as $k=>$v)
        {
            //获取该id的规格名称
            $sizeName = $this->model->where(['size_id'=>$v])->value('size_name');
            if (isset($innerArr[$v]))
            {
                //存在封面
                $sizeName = $sizeName."(".$innerArr[$v]['size_design_w']."mm*".$innerArr[$v]['size_design_h']."mm)";
            }
            $newSizeName[$v]['size_id'] = $v;
            $newSizeName[$v]['size_name'] = $sizeName;
        }
        return $newSizeName;

    }

    //获取商业印品分类下的所有规格
    public function getCommercialSize()
    {
        $cate = $this->cateRepo->getRow(['cate_flag'=>GOODS_PRINTER_CATEGORY_COM]);
        if(!empty($cate)){
            $cateList = $this->cateRepo->getList(['cate_parent_id'=>$cate->cate_id])->toArray();
        }

        $commerCateList = [];
        if(!empty($cateList)){
            $commerCateList = array_column($cateList,'cate_id');
        }

        $list = $this->model->whereIn('size_cate_id',$commerCateList)->get()->toArray();
        return $list;

    }





}

