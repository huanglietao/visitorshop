<?php
namespace App\Http\Controllers\Api\Editor;

use App\Exceptions\CommonException;
use App\Http\Requests\Api\Template\saveLayoutDataRequest;
use App\Repositories\SaasTemplateLayoutTypeRepository;
use App\Repositories\SaasTemplatesLayoutRepository;
use App\Services\Goods\Info;
use App\Services\Helper;
use Illuminate\Http\Request;

/**
 * 模板布局相关接口
 *
 * 布局列表，保存等操作
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/5/7
 */

class LayoutController extends BaseController
{
    /**
     * 获取布局分类
     * @param Request $request
     * @param SaasTemplateLayoutTypeRepository $layoutType
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLayoutKindList(Request $request, SaasTemplateLayoutTypeRepository $layoutType)
    {
        try {
            $mchId = $request->input('sp_id');
            if (empty ($mchId)) {
                $where['mch_id'] = [ZERO];
            } else {
                $where['mch_id'] = [ZERO, $mchId];
            }


            $layoutList = $layoutType->getRows($where, 'temp_layout_type_id', 'asc')->toArray();
            $return = [];
            foreach ($layoutList as $k=>$v) {
                $return[$k]['id'] = $v['temp_layout_type_id'];
                $return[$k]['name'] = $v['temp_layout_type_name'];
            }

            return $this->success([['list' => $return]]);

        }catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    /**
     * 获取布局列表
     * @param Request $request
     * @param SaasTemplatesLayoutRepository $layout
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLayoutList(Request $request, SaasTemplatesLayoutRepository $layout)
    {
        try {
            $mchId = $request->input('sp_id');
            $sizeId = $request->input('size_id');
            $typeId = $request->input('kind_id');
            $checkStatus = $request->input('all_state');
            $source = $request->input('source');

            if (!empty($mchId)) {
                $where['mch_id'] = [ZERO, $mchId];
            } else {
                $where['mch_id'] = ZERO;
            }
            //规格id
            if (!empty($sizeId)) {
                $where['specifications_id'] = $sizeId;
            }
            //所属分类
            if (!empty($typeId) && $typeId != -1) {
                $where['temp_layout_type'] = $typeId;
            }
            //审核状态
            if (empty ($checkStatus)) {
                $where['layout_check_status'] = TEMPLATE_STATUS_VERIFYED;
            }

            $layoutList = $layout->getRows($where, 'temp_layout_sort', 'desc');

            $return = [];
            //组装数据
            foreach ($layoutList as $k => $v) {
                $return[$k]['id'] = $v['temp_layout_id'];
                $return[$k]['kind_id'] = $v['temp_layout_type'];
                $return[$k]['index'] = $v['temp_layout_sort'] ?? 0;
                $return[$k]['name'] = $v['temp_layout_name'] ?? '';
                $return[$k]['mask_count'] = $v['layout_photo_nums']??0;
                $return[$k]['thumb_url'] = !empty($v['temp_layout_thumb']) ? $v['temp_layout_thumb'] : '';
                $return[$k]['page_width'] = $v['layout_real_page_w']??0;
                $return[$k]['page_height'] = $v['layout_real_page_h']??0;
                $return[$k]['dpi'] = $v['layout_real_dpi']??0;
                $return[$k]['stage_content'] = $v['temp_layout_stage']??'';
            }
            return $this->success([['list' => $return]]);
        }catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    /**
     * 获取单个布局数据
     * @param Request $request
     * @param SaasTemplatesLayoutRepository $layout
     * @param Info $serviceGoods
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLayoutData(Request $request, SaasTemplatesLayoutRepository $layout, Info $serviceGoods)
    {
        try {
            $tid = $request->input('id');
            if (empty($tid)) {
                Helper::EasyThrowException('10022',__FILE__.__LINE__);
            }
            $info = $layout->getRow(['temp_layout_id' => $tid]);
            $sizeId = $info['specifications_id'];
            //获取规格相关信息
            $sizeInfo = $serviceGoods->getGoodSizeInfo($sizeId, 0);
            $sizeItems =  $this->formatSizeInfo($sizeInfo);

            $return = [];
            $return['kind_id'] = $info['temp_layout_type'];
            $return['name'] = $info['temp_layout_name'];
            $return['source'] = !empty($info['mch_id']) ? 'custom' : 'common';
            $return['thumb_url'] =   !empty($info['temp_layout_thumb']) ? config('common.static_url').'/'.$info['temp_layout_thumb'] :'';

            $return['page_info']['id'] = $info['temp_layout_id'];
            $return['page_info']['mask_count'] = $info['layout_photo_nums']??0;
            $return['page_info']['page_width'] = $info['layout_real_page_w']??0;
            $return['page_info']['page_height'] = $info['layout_real_page_h']??0;
            $return['page_info']['dpi'] = $info['layout_real_dpi']??0;
            $return['page_info']['stage_content'] = $info['temp_layout_stage']??'';

            $return['size_item'] = $sizeItems;
            return $this->success([$return]);

        }catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }


    public function saveLayoutData(saveLayoutDataRequest $request, SaasTemplatesLayoutRepository $layout)
    {
        try {
            $id = $request->input('id');
            if (empty($id)) {
                Helper::EasyThrowException('10022',__FILE__.__LINE__);
            }
            $updateData = [
                'layout_photo_nums' => $request->input('mask_count'),
                'layout_real_page_w' => $request->input('page_width'),
                'layout_real_page_h' => $request->input('page_height'),
                'layout_real_dpi' => $request->input('dpi'),
                'temp_layout_stage' => $request->input('stage_content'),
                'updated_at' => time(),
            ];
            //更新布局数据
            $res = $layout->update(['temp_layout_id' => $id], $updateData);
            if (empty($res)) {
                Helper::EasyThrowException('50038',__FILE__.__LINE__);
            }

            return $this->success(null);

        }catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }
}