<?php
namespace  App\Http\Controllers\Api\Editor;

use App\Exceptions\CommonException;
use App\Http\Requests\Api\Material\GetMaterialListRequest;
use App\Http\Requests\Api\Material\UploadRequest;
use App\Repositories\SaasCategoryRepository;
use App\Repositories\SaasProductsSizeRepository;
use App\Repositories\SaasTemplatesAttachmentRepository;
use App\Services\Helper;
use App\Services\Template\TemplateCommon;
use Illuminate\Http\Request;

/**
 * 素材相关接口
 *
 * 素材上传，分类及列表数据
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/27
 */
class MaterialController extends BaseController
{
    /**
     * 素材附件通用上传方法
     * @param UploadRequest $request
     * @param SaasTemplatesAttachmentRepository $attaRepository
     * @return  mixed
     */
    public function upload(UploadRequest $request, SaasTemplatesAttachmentRepository $attaRepository)
    {
        //上传的相关配置项
        $uploadConfig = config("template.material.upload");

        $type = $request->input('type');
        $pageType = $request->input('page_type');
        $mchId    = $request->input('mid')?? 0;
        $materilType = $request->input('m_type');
        $uniqid   = $request->input('uniqid');

        $className = $type ?? 'base';
        $config = array_merge($uploadConfig, $request->all());

        $namespace = "App\\Services\\Template\\Material\\Upload";
        $ret = app($namespace."\\".ucfirst($className), ['config' =>$config])->run();

        if(isset($uploadConfig['material_type'][$type]['crop']['sml'])){
            $ml = 'sml';
        }else{
            $ml = 'big';
        }

        //成功处理
        if($ret['success']) {
            //正常素材处理
            if (!empty($ret['data']['full_name'])) {
                if (empty($pageType)) { //无页面类型，通用素材处理
                    $data = [
                        'material_atta_orig_name'  => $ret['data']['oriName'],
                        'material_atta_path'       => $ret['data']['full_name'],
                        'material_atta_file_name'  => $ret['data']['file_name'],
                        'material_atta_size'       => $ret['data']['size'],
                        'material_atta_uniqid'     => $uniqid
                    ];

                    $objMaterialAtta = $attaRepository->insertMaterialAtta($data);
                    $id = $objMaterialAtta->material_atta_id;
                } else {  //模板素材处理
                    $data = [
                        'mch_id'                        => $mchId,
                        'temp_attach_type'              => $pageType,
                        'temp_attach_material_type'     => $type,
                        'temp_attach_orig_name'         => $ret['data']['oriName'],
                        'temp_attach_path'              => $ret['data']['full_name'],
                        'temp_attach_file_name'         => $ret['data']['file_name'],
                        'temp_attach_size'              => $ret['data']['size'],
                        'temp_attach_uniqid'            => $uniqid
                    ];
                    $objTempAtta = $attaRepository->insert($data);
                    $id = $objTempAtta->temp_attach_id;

                }
                $url = $type.'/'.$ml.'/'.$ret['data']['full_name'];
            } else {  //针对画框的两张图特殊处理
                $id = 0;
                $url =  $type.'/'.$ml.'/'.$ret['data']['url'];
            }
            return $this->success(['atta_id' => $id ,'url' => $url]);
        } else {
            return $this->error('', '上传失败');
        }

    }

    /**
     * 获取素材分类列表(适应旧版逻辑)
     * @param Request $request
     * @param SaasCategoryRepository $cate
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMaterialStyleList(Request $request, SaasCategoryRepository $cate)
    {
        try {
            $spId       = $request->input('sp_id') ?? 0;
            $kind       = $request->input('material_kind') ?? 'background';
            $cateId     = $request->input('goods_type') ?? 0;

            $kind = $kind == 'decoration' ? 'decorate' : $kind;

            $where['cate_flag'] = $kind;
            $parentInfo = $cate->getRow($where);
            if (empty($parentInfo)) {
                Helper::apiThrowException('50030',__FILE__.__LINE__);
            }
            //先获取对应类型的父级
            if($kind == "background") {
                $cateWhere['cate_uid']  = $kind;
            } else {
                $cateWhere['cate_parent_id']  = $parentInfo['cate_id'];
            }


            if (empty ($spId)) {
                $cateWhere['mch_id'] = ZERO;
            } else {
                $cateWhere['mch_id'] = [ZERO, $spId];
            }
            $cateList = $cate->getRows($cateWhere, 'cate_id', 'acs');

            $list['list'] = [];
            foreach ($cateList as $k=>$v) {
                $list['list'][$k]['id']     = $v['cate_id'];
                $list['list'][$k]['name']   = $v['cate_name'];
            }

            return $this->success([$list]);

        }catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }

    }

    /**
     * 获取素材列表
     * @param GetMaterialListRequest $request
     * @param TemplateCommon $template
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMaterialList(GetMaterialListRequest $request, TemplateCommon $template,SaasProductsSizeRepository $sizeRepo)
    {
        $spId   = $request->input('sp_id') ?? 0;
        $tid    = $request->input('tid')== -1 ? 0 : $request->input('tid');
        $type   = $request->input('material_kind');
        $size   = $request->input('size');
        $index   = $request->input('index');
        $cateId  = $request->input('style_id');
        $specId  = $request->input('size_id');

        $type = $type == 'decoration' ? 'decorate' : $type;

        //模板素材 分封面/内页/封底模板的素材
        if (!empty($tid)) {
            $list = $template->getTemplateMaterial($tid, $type, $size, $index);
            $return = $this->formatTempMaterialListReturn($list['list'], $type);

            $count = ceil($list['count']/$size);
            return $this->success([['list' => $return, 'total_page' => $count]]);
        } else {

            $where['material_cate_flag'] = $type;
            if ($cateId != -1 && !empty($cateId)) {
                $where['material_cateid'] = $cateId;
            }
            //只有背景素材时只取对应该规格下的背景图
            if($type==GOODS_MAIN_CATEGORY_BACKGROUND){
                $sizeInfo = $sizeRepo->getById($specId);
                if(isset($sizeInfo['size_type'])){
                    $where['specification_style'] = $sizeInfo['size_type'];
                }
            }


            $list = $template->getMaterial($where, $size, $index);

            $return = $this->formatMaterialListReturn($list['list'], $type);

            $count = ceil($list['count']/$size);
            return $this->success([['list' => $return, 'total_page' => $count]]);
        }
    }

    /**
     * 格式化需要返回需材列表的形式
     * @param $data
     * @param $type
     * @return array
     */
    private function formatTempMaterialListReturn($data, $type)
    {
        $tpUrl = config('template.material.upload.tp_url');
        $return = [];
        if(!empty ($data) && is_array($data)) {
            $templates = app(TemplateCommon::class);
            $typeDir = $type;
            foreach ($data as $k => $v) {
                $return[$k]['id'] = $v['temp_attach_id'];
                $return[$k]['index'] = isset($v['sort']) ?$v['sort'] : 0;
                $return[$k]['name'] = isset($v['name']) ? $v['name'] : '';
                $return[$k]['style_id'] = isset($v['cate_uid']) ? $v['cate_uid'] : '';
                $info = $templates->getMaterialPathInfo($v['temp_attach_path'], $type);
                if ($type == MATERIAL_TYPE_FRAME) { //画框特殊处理
                    $return[$k]['thumb_url'] = !empty($v['temp_attach_path']) ? $info['thumb_url'] : '';
                    $return[$k]['frame_url'] = !empty($v['temp_attach_path']) ? $info['frame_url'] : '';
                    $return[$k]['frame_mask_url'] = !empty($v['temp_attach_path']) ? $info['frame_mask_url'] : '';
                } else {
                    $return[$k]['thumb_url'] = !empty($v['temp_attach_path']) ? $info['thumb_url'] : '';
                    $return[$k]['url'] = !empty($v['temp_attach_path']) ? $info['url'] : '';
                }
            }
        }

        return $return;
    }

    /**
     * 格式化需要返回需材列表的形式
     * @param $data
     * @param $type
     * @return array
     */
    private function formatMaterialListReturn($data, $type)
    {
        $tpUrl = config('template.material.upload.tp_url');
        $return = [];
        if(!empty ($data) && is_array($data)) {
            $templates = app(TemplateCommon::class);
            $typeDir = $type;
            foreach ($data as $k => $v) {
                $return[$k]['id'] = $v['material_id'];
                $return[$k]['index'] = isset($v['sort']) ?$v['sort'] : 0;
                $return[$k]['name'] = isset($v['material_name']) ? $v['material_name'] : '';
                $return[$k]['style_id'] = isset($v['material_cateid']) ? $v['material_cateid'] : '';
                $attachmentPath = isset($v['mater_attach'][0]['material_atta_path']) ? $v['mater_attach'][0]['material_atta_path'] : $v['mater_attach']['material_atta_path'];
                $info = $templates->getMaterialPathInfo($attachmentPath, $type);
                if ($type == MATERIAL_TYPE_FRAME) { //画框特殊处理
                    $return[$k]['thumb_url'] = !empty($attachmentPath) ? $info['thumb_url'] : '';
                    $return[$k]['frame_url'] = !empty($attachmentPath) ? $info['frame_url'] : '';
                    $return[$k]['frame_mask_url'] = !empty($attachmentPath) ? $info['frame_mask_url'] : '';
                } else {
                    $return[$k]['thumb_url'] = !empty($attachmentPath) ? $info['thumb_url'] : '';
                    $return[$k]['url'] = !empty($attachmentPath) ? $info['url'] : '';
                }
            }
        }

        return $return;
    }
}