<?php
namespace App\Services\Template;

use app\admin\library\Constants;
use app\common\model\TemplateCategory;
use app\common\model\TemplateCategoryConf;
use think\session;

/**
 * 素材复杂罗辑处理
 *
 * @author: david
 * @version: 1.0
 * @date: 2020/5/3
 */


class Material
{

    /**
     * 获取处理图片后的列表数据
     * @param array $data
     * @return array
     */

    public function getMakeTableList($data)
    {
        $attachLogic = new Attachment();

        foreach ($data as $k =>$v) {
            if(empty($v['mater_attach'])){
                return false;
            }
            switch ($v['material_cate_flag']) {
                case MATERIAL_TYPE_FRAME : //画框
                    $data[$k]['attach_path'] = $v['material_cate_flag'].'/'.'sml/'.$v['mater_attach'][0]['material_atta_path'];
                    $data[$k]['attach_paths'] = $attachLogic->getFrameOther($v['material_cate_flag'].'/'.'sml/'.$v['mater_attach'][0]['material_atta_path']);
                    //unset($v['mater_attach'][0]);
                    break;
                case MATERIAL_TYPE_DECORATE : //装饰
                    $data[$k]['attach_path'] = $v['material_cate_flag'].'/'.'sml/'.$v['mater_attach'][0]['material_atta_path'];
                   // unset($v['mater_attach'][0]);
                    break;
                case MATERIAL_TYPE_BACKGROUND : //背景
                    $data[$k]['attach_path'] = $v['material_cate_flag'].'/'.'sml/'.$v['mater_attach'][0]['material_atta_path'];
                    //unset($v['mater_attach'][0]);
                    break;
                default :  $data[$k]['attach_path'] = $v['material_cate_flag'].'/'.'sml/'.$v['mater_attach'][0]['material_atta_path'];
                break;
            }
        }

        return $data;

    }


    public function getCategoryList($mid=0, $goods_type = 0, $cate_type=2)
    {
        //获取分类配置信息
        $query = db('template_category_conf')->where('mid', $mid)
            ->where('status',Constants::STATUS_YES)
            ->field('template_type, back_value, decorate_value, border_value');

        if (!empty($goods_type)) {
            $query->where('goods_type', $goods_type);
        }
        $listConf = $query->select();

        $listCate = model('template_category')->getCacheList($cate_type);

        $listKV = array_column($listCate, 'name', 'id');

        //重装数组返回

        $temp = array_column($listConf, 'template_type');
        $back = array_column($listConf, 'back_value');
        $decorate = array_column($listConf, 'decorate_value');
        $border = array_column($listConf, 'border_value');

        $tempArr = array_unique(explode(',', implode(',', $temp)));
        $backArr = array_unique(explode(',', implode(',', $back)));
        $decorateArr = array_unique(explode(',', implode(',', $decorate)));
        $borderArr = array_unique(explode(',', implode(',', $border)));

        //去空值
        $tempArr = array_filter($tempArr);
        $backArr = array_filter($backArr);
        $decorateArr = array_filter($decorateArr);
        $borderArr = array_filter($borderArr);

        $return = [];
        foreach ($backArr as $k=>$v) {
            $return[Constants::MATERIAL_TYPE_BACK][$v] = isset($listKV[$v])?$listKV[$v]:'';
        }
        foreach ($decorateArr as $k=>$v) {
            $return[Constants::MATERIAL_TYPE_DECORATE][$v] = isset($listKV[$v])?$listKV[$v]:'';
        }
        foreach ($borderArr as $k=>$v) {
            $return[Constants::MATERIAL_TYPE_FRAME][$v] = isset($listKV[$v])?$listKV[$v]:'';
        }
        return $return;
    }

    /**
     * @param int $mid
     * @param int $goods_type
     * @return array
     * Dai 修改代码获取有商户mid的模板分类
     */
    public function getTemplateCate($mid=0, $goods_type = 0)
    {
        //获取分类配置信息
        $query = db('template_category_conf')
            ->where(function ($query) use($mid) {
                $query->where('mid', Constants::DEFAULT_MID)->whereor('mid',$mid);
            })
            ->where('status',Constants::STATUS_YES)
            ->where('is_delete',Constants::DEFAULT_MID)
            ->field('template_type, goods_type');

        if (!empty($goods_type)) {
            $query->where('goods_type', $goods_type);
        }

        //产品-模板分类关联列表
        $listConf = $query->select();

        //分类列表
        $listCate = model('template_category')->getCacheList(Constants::TEMPLATE_CATEGORY);

        $listKV = array_column($listCate, 'name', 'id');

        //组装联动数据
        $arrLink = [];
        foreach ($listConf as $k=>$v) {
            $tempType = explode(',', $v['template_type']);

            foreach($tempType as $kk => $vv) {
                if(!empty($vv)) {
                    $arrLink[$v['goods_type']][$vv] = $listKV[$vv];
                }

            }
        }

        return $arrLink;
    }
}