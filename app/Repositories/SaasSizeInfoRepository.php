<?php
namespace App\Repositories;
use App\Models\SaasSizeInfo;

/**
 * 商品规格详情仓库模板
 * 商品规格详情数据处理
 * @author: david
 * @version: 1.0
 * @date:  2020/04/23
 */
class SaasSizeInfoRepository extends BaseRepository
{

    public function __construct(SaasSizeInfo $model)
    {
        $this->model = $model;
    }

    /**
     *  获取规格id查询一条对应的规格详情数据
     * @param $sizeId
     * @return array
     */
    public function getSizeInfoDetail($sizeId,$type)
    {
        if($type == GOODS_SIZE_TYPE_INNER){
            $sizeInfo = SaasSizeInfo::where(['size_id'=>$sizeId,'size_type'=>$type,'goods_id'=>ZERO])->first();
        }else{
            $sizeInfo =  SaasSizeInfo::where(['size_id'=>$sizeId,'goods_id'=>ZERO])->whereIn('size_type',$type)->first();
        }
        //$specInfo = SaasSizeInfo::where('size_type',$type)->where('size_id',$sid)->where('goods_id',ZERO)->first();
        return $sizeInfo;
    }
    //获取规格的设计区尺寸
    public function getSizeDesign($sizeId)
    {
        if (!is_array($sizeId)){
            //数组
            $sizeId = explode(',',$sizeId);
        }
        $designArr = $this->model->whereIn('size_id',$sizeId)->get()->toArray();
        //组织每个规格下面的各个类型的设计去尺寸
        $pageType = config("goods.page_type");
        $design = [];
        $innerArr = [];
        foreach ($designArr as $k => $v)
        {
            $design[$v['size_id']][$v['size_type']]['size_design_w'] = $v['size_design_w'];
            $design[$v['size_id']][$v['size_type']]['size_design_h'] = $v['size_design_h'];
            $design[$v['size_id']][$v['size_type']]['size_type']     = $v['size_type'];
            //将内页尺寸单独取出
            if ($v['size_type'] == GOODS_SIZE_TYPE_INNER)
            {
                $innerArr[$v['size_id']]['size_type']         =  $v['size_type'];
                $innerArr[$v['size_id']]['size_type_str']     =  $pageType[$v['size_type']]??"";
                $innerArr[$v['size_id']]['size_id']           =  $v['size_id'];
                $innerArr[$v['size_id']]['size_design_w']     =  $v['size_design_w'];
                $innerArr[$v['size_id']]['size_design_h']     =  $v['size_design_h'];

            }
        }
        foreach ($design as $k => $v)
        {
            foreach ($v as $kk => $vv){
                //将每个规格类型转换为中文
                $pageStr = $pageType[$kk];
                $design[$k][$kk]['design_text'] = $pageStr;
                $design[$k][$kk]['design_size_text'] = $vv['size_design_w'].'mm'." * ".$vv['size_design_h'].'mm';

            }
        }
        $data = [
            'design'   => $design,
            'innerArr' => $innerArr
        ];
        return $data;
    }



    //获取规格的封面参数设计区尺寸
    public function getSizeFaceDesign($sizeId)
    {
        if (!is_array($sizeId)){
            //数组
            $sizeId = explode(',',$sizeId);
        }
        $designArr = $this->model->whereIn('size_id',$sizeId)->get()->toArray();
        //组织每个规格下面的各个类型的设计去尺寸
        $pageType = config("goods.page_type");
        $design = [];
        $innerArr = [];
        foreach ($designArr as $k => $v)
        {
            $design[$v['size_id']][$v['size_type']]['size_design_w'] = $v['size_design_w'];
            $design[$v['size_id']][$v['size_type']]['size_design_h'] = $v['size_design_h'];
            $design[$v['size_id']][$v['size_type']]['size_type']     = $v['size_type'];
            //将内页尺寸单独取出
            if ($v['size_type'] == GOODS_SIZE_TYPE_COVER || $v['size_type'] == GOODS_SIZE_TYPE_COVER_BACK)
            {
                $innerArr[$v['size_id']]['size_type']         =  $v['size_type'];
                $innerArr[$v['size_id']]['size_type_str']     =  $pageType[$v['size_type']]??"";
                $innerArr[$v['size_id']]['size_id']           =  $v['size_id'];
                $innerArr[$v['size_id']]['size_design_w']     =  $v['size_design_w'];
                $innerArr[$v['size_id']]['size_design_h']     =  $v['size_design_h'];

            }
        }
        foreach ($design as $k => $v)
        {
            foreach ($v as $kk => $vv){
                //将每个规格类型转换为中文
                $pageStr = $pageType[$kk];
                $design[$k][$kk]['design_text'] = $pageStr;
                $design[$k][$kk]['design_size_text'] = $vv['size_design_w'].'mm'." * ".$vv['size_design_h'].'mm';

            }
        }
        $data = [
            'design'   => $design,
            'faceArr' => $innerArr
        ];
        return $data;
    }

    /**
     *  获取规格数据并组装成子页列表所需要数据
     * @param $data
     * @return array
     */
   /* public function getFaceSizeInfo($data,$spec_id)
    {
        $specList = SaasSizeInfo::where(['size_id'=>$spec_id])->get()->toArray();
        //如果封面有两条的情况就默认取一条，正常情况只有一条
        foreach ($data as $k=>$v){
            $data[$k]['size_design_w'] = $specList[0]['size_design_w'];
            $data[$k]['size_design_h'] = $specList[0]['size_design_h'];
            $data[$k]['size_location_top'] = $specList[0]['size_location_top'];
            $data[$k]['size_location_left'] = $specList[0]['size_location_left'];
            $data[$k]['size_location_bottom'] = $specList[0]['size_location_bottom'];
            $data[$k]['size_location_right'] = $specList[0]['size_location_right'];
        }

        return $data;
    }*/
}

