<?php
namespace App\Http\Controllers\Api\Editor;

/**
 * 编辑器的基类
 *
 * 重写签名及返回的相关功能
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/27
 */
class BaseController extends \App\Http\Controllers\Api\BaseController
{

    /**
     * 接口正确返回
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data)
    {
       return response()->json(['success' => 'true', 'result' => $data]);
    }

    /**
     * 接口错误返回
     * @param $errCode
     * @param $errMsg
     * @return \Illuminate\Http\JsonResponse
     */
    public function error($errCode, $errMsg)
    {
        return response()->json(['success' => 'false','err_code'=>$errCode,  'err_msg' => $errMsg]);
    }

    /**
     * 将封面/内页/主模板的数据转化为统一输出格式
     * @param $type
     * @param $templateInfo
     * @return array
     */
    public function standardTempInfo($type, $templateInfo)
    {
        $info = [];
        switch ($type) {
            case TEMPLATE_PAGE_PAGE :   //封面模板库
                $info['id'] = $templateInfo['cover_temp_id'];
                $info['name'] = $templateInfo['cover_temp_name'];
                $info['goods_cate_id'] = $templateInfo['goods_type_id'];
                $info['temp_cate_id'] = $templateInfo['cover_temp_theme_id'];
                $info['size_id'] = $templateInfo['specifications_id'];
                $info['sort'] = $templateInfo['cover_temp_sort'];
                $info['thumb'] = $templateInfo['cover_temp_thumb'];
                $info['photo_count'] = $templateInfo['cover_temp_photo_count'];
                $info['check_status'] = $templateInfo['cover_temp_check_status'];
                $info['start_year'] = $templateInfo['cover_temp_start_year'];

                break;
            case TEMPLATE_PAGE_INNER :
                $info['id'] = $templateInfo['inner_temp_id'];
                $info['name'] = $templateInfo['inner_temp_name'];
                $info['goods_cate_id'] = $templateInfo['goods_type_id'];
                $info['temp_cate_id'] = $templateInfo['inner_temp_theme_id'];
                $info['size_id'] = $templateInfo['specifications_id'];
                $info['sort'] = $templateInfo['inner_temp_sort'];
                $info['thumb'] = $templateInfo['inner_temp_thumb'];
                $info['photo_count'] = $templateInfo['inner_temp_photo_count'];
                $info['check_status'] = $templateInfo['inner_temp_check_status'];
                $info['start_year'] = $templateInfo['inner_temp_start_year'];
                break;
            case TEMPLATE_PAGE_MAIN :
                $info['id'] = $templateInfo['main_temp_id'];
                $info['name'] = $templateInfo['main_temp_name'];
                $info['goods_cate_id'] = $templateInfo['goods_type_id'];
                $info['temp_cate_id'] = $templateInfo['main_temp_theme_id'];
                $info['size_id'] = $templateInfo['specifications_id'];
                $info['sort'] = $templateInfo['main_temp_sort'];
                $info['thumb'] = $templateInfo['main_temp_thumb'];
                $info['photo_count'] = $templateInfo['main_temp_photo_count'];
                $info['check_status'] = $templateInfo['main_temp_check_status'];
                $info['start_year'] = $templateInfo['main_temp_start_year'];
                break;
            default :
                break;
        }
        return $info;
    }

    /**
     * 将封面/内页/主模板的子页统一格式化
     * @param $type
     * @param $pagesInfo
     * @return array
     */
    public function formatPages($type, $pagesInfo)
    {
        $list = [];
        switch ($type) {
            case TEMPLATE_PAGE_PAGE :   //封面模板库
               foreach ($pagesInfo as $k=>$v) {
                   $list[$k]['id']  = $v['cover_temp_id'];
                   $list[$k]['type']= 0;
                   $list[$k]['index']= $v['cover_temp_sort'];
                   $list[$k]['name'] =$v['cover_temp_name'];
                   $list[$k]['mask_count'] = $v['cover_temp_photo_count']??0;
                   $list[$k]['thumb_url'] = config('template.material.upload.tp_url').'/'.$v['cover_temp_thumb'];
                   $list[$k]['page_width'] = $v['cover_real_page_w']??0;
                   $list[$k]['page_height'] = $v['cover_real_page_h']??0;
                   $list[$k]['dpi'] = $v['cover_temp_dpi']??200;
                   $list[$k]['stage_content'] = $v['cover_temp_stage']??'';
               }

                break;
            case TEMPLATE_PAGE_INNER :
                foreach ($pagesInfo as $k=>$v) {
                    $list[$k]['id']  = $v['inner_page_id'];
                    $list[$k]['type']= 1;
                    $list[$k]['index']= $v['inner_page_sort'];
                    $list[$k]['name'] =$v['inner_page_name'];
                    $list[$k]['mask_count'] = $v['inner_page_photo_count']??0;
                    $list[$k]['thumb_url'] = config('template.material.upload.tp_url').'/'.$v['inner_page_thumb'];
                    $list[$k]['page_width'] = $v['inner_page_real_w']??0;
                    $list[$k]['page_height'] = $v['inner_page_real_h']??0;
                    $list[$k]['dpi'] = $v['inner_page_dpi']??200;
                    $list[$k]['stage_content'] = $v['inner_page_stage']??'';
                }
                break;
            case TEMPLATE_PAGE_MAIN :
                foreach ($pagesInfo as $k=>$v) {

                    if ($v['main_temp_page_type'] == GOODS_SIZE_TYPE_COVER || $v['main_temp_page_type'] == GOODS_SIZE_TYPE_COVER_BACK) {
                        $type = 0;
                    } else if ($v['main_temp_page_type'] == GOODS_SIZE_TYPE_BACK) {
                        $type = 2;
                    } else {
                        $type = 1;
                    }

                    $list[$k]['id']  = $v['main_temp_page_id'];
                    $list[$k]['type']= $type;
                    $list[$k]['index']= $v['main_temp_page_sort'];
                    $list[$k]['name'] =$v['main_temp_page_name'];
                    $list[$k]['mask_count'] = $v['main_temp_page_photo_count']??0;
                    $list[$k]['thumb_url'] = config('template.material.upload.tp_url').'/'.$v['main_temp_page_thumb'];
                    $list[$k]['page_width'] = $v['main_temp_page_real_w']??0;
                    $list[$k]['page_height'] = $v['main_temp_page_real_h']??0;
                    $list[$k]['dpi'] = $v['main_temp_page_dpi']??200;
                    $list[$k]['stage_content'] = $v['main_temp_page_stage']??'';
                }
                break;
            default :
                break;
        }

        return $list;
    }

}