<?php
namespace App\Services\Template;

use app\common\library\Constants;
use app\common\model\GoodsSpecifications;
use app\common\model\GoodsSpecificationsParam;
use app\common\model\TemplatesAttachment;
use App\Models\SaasSizeInfo;
use App\Models\SaasTemplatesAttachment;
use App\Repositories\SaasTemplatesAttachmentRepository;
use exception\MyException;
use think\Db;
use think\session;

/**
 * 模板素材相关逻辑操作
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2018/7/27
 */
 /** .Daiyd 修改use引用继承公共的类 2018/8/15 */

class Attachment
{

    /**
     * 附件与模板关联操作
     * @param int $temp_id 模板id
     * @param array $attachment_ids 需相关的附件id
     * @return boolen
     */
    public function relationTemplate($temp_id, $attachment_ids)
    {
        $model = app(SaasTemplatesAttachment::class);
        //过滤掉数组中的空值
        $attachment_ids = array_filter($attachment_ids);

        if(empty($attachment_ids) || empty($temp_id)) {
            return ;
        }

        $attachment = $model->whereIn('temp_attach_id',$attachment_ids)->update(['temp_attach_tid'=>$temp_id]);

        return true;

    }

    /**
     * 解除关联
     * @param $temp_id 模板id
     * @param $attachment_ids 传过来的附件id
     * @param int $page_type 封面|内页
     * @return array $list
     */
    public function removeRelation($temp_id, $attachment_ids, $page_type=1)
    {
        $model = app(SaasTemplatesAttachment::class);
        $list = $model->where('temp_attach_tid', $temp_id)
            ->where('temp_attach_type',$page_type)->get()->toArray();

        $existsIds  = array_column($list,'temp_attach_id');
        $delIds = array_diff($existsIds, $attachment_ids);

        $model->whereIn('temp_attach_id',$delIds)->update(['temp_attach_tid'=>ZERO]);
        return $delIds;
    }

    /**
     * 获取模板相关的图片
     * @param int $temp_id 模板id
     * @param int $page_type 封面|内页
     * @param int $material_type 素材类型
     * @param int $mid 商户id
     * @return array $list
     *
     */
    //.Dai 修改mid，商户能正常获取大后台的图片数据 04/24
    public function getAttachmentByTempId($temp_id,$page_type = 1, $material_type = 0, $mid = 0)
    {
        if($mid){
            $whereArr = [0,$mid];
        }else{
            $whereArr = [0];
        }
        $model = app(SaasTemplatesAttachment::class);
        $query = $model
            ->whereIn('mch_id',$whereArr)
            ->where('temp_attach_tid',$temp_id)
            ->where('temp_attach_type', $page_type);

        if(!empty($material_type)) {
            $query->where('temp_attach_material_type', $material_type);
        }
        //$list = $query->get();

        $list = $query->get();
        return $list;

    }

    /**
     * 通过画框得到另一个地址
     * @param string $src
     * @return string $border_name
     */
    public function getFrameOther($src = '')
    {
        $hq_hz = config('template.material.upload')['frame_hz'];
        if(strstr($src,$hq_hz)) {
            $border_name = str_replace($hq_hz,'',$src);

        } else {
            $border_name = str_replace('.',$hq_hz.'.',$src);
        }
        return $border_name;
    }

    /**
     * 通过规格计算书脊 毫米转像素
     * @param $spec_id
     * @param $page_type
     * @return array
     */
    public function getBackPx($spec_id)
    {
        $params = SaasSizeInfo::where(['size_id'=>$spec_id,'goods_id'=>ZERO])->get();
        $data = [];
        foreach ($params as $k=>$v){
            $data[$v['size_type']] = $v;
        }
        return $data;
    }

    /**
     * 验证提交的画框数据是否有误
     * @param $frames
     * @throws MyException
     */
    public function validFrame($frames) {
        if (count($frames)%2) {
            throw new MyException(__('The frame must be uploaded in pairs'));
        }

        //如果存在后缀

        $return = array_map( function($v) {
            if(strstr($v, '#')) {
                $arr = explode('#', $v);
                return $arr[0];
            }

            return $v;
        }, $frames);

        $frames = $return;

        foreach($frames as $k=>$v) {
            $otherSrc = $this->getFrameOther($v);
            if(!in_array($otherSrc , $frames)) {
                throw new MyException(__('Please check  the name of the frame rules'));
            }
        }

    }

    /**
     * .Dai 单独处理模板样板的图片获取 2018/11/14
     * @param int $temp_id 模板id
     * @param int $page_type 封面|内页
     * @param int $material_type 素材类型
     * @param int $mid 商户id
     * @return array $list
     */
    public function getAttachmentMstyleByTempId($temp_id,$page_type = 1, $material_type = 0, $mid = 0)
    {
        $query = model('templates_attachment')
            ->where('mid', $mid)
            ->where('template_id', $temp_id)
            ->where('material_type',$material_type)
            ->where('type', $page_type);

        if(!empty($material_type)) {
            $query->where('material_type', $material_type);
        }

        $list = $query->field('path,material_type,id')->select();

        return $list;
    }

}