<?php
namespace App\Http\Controllers\Api\Editor;

use App\Exceptions\CommonException;
use App\Http\Requests\Api\Template\GetTemplateListRequest;
use App\Http\Requests\Api\Template\saveTemplateDataRequest;
use App\Repositories\SaasCategoryRepository;
use App\Repositories\SaasInnerTemplatesRepository;
use App\Repositories\SaasMainTemplatesRepository;
use App\Services\Goods\Info;
use App\Services\Helper;
use App\Services\Template\TemplateCommon;
use Illuminate\Http\Request;

/**
 * 编辑器模板所需接口
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/5/4
 */

class TemplateController extends BaseController
{
    protected $noLog = 'saveTemplateData,';
    /**
     * 获取模板分类列表
     * @param Request $request
     * @param SaasCategoryRepository $cate
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTemplateThemeList(Request $request, SaasCategoryRepository $cate)
    {
        try {
            $cateId = $request->input('type_id');
            $mchId  = $request->input('sp_id');

            $where = ['cate_uid' => 'template'];
            if (!empty($mchId)) {
                $where['mch_id'] = [ZERO , $mchId];
            } else {
                $where['mch_id'] =ZERO;
            }
            $list = $cate->getRows($where, 'sort', 'desc');
            $return['list'] = [];

            foreach ($list as $k=>$v) {
                $return['list'][$k]['id'] = $v['cate_id'];
                $return['list'][$k]['name'] = $v['cate_name'];
            }

            return $this->success([$return]);
        } catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }


    }

    /**
     * 获取模板列表
     * @param GetTemplateListRequest $request
     * @param SaasMainTemplatesRepository $template
     * @param SaasCategoryRepository $cate
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTemplateList(GetTemplateListRequest $request, SaasMainTemplatesRepository $template,SaasCategoryRepository $cate)
    {
        try {
            $params = $request->all();
            if (!empty($params['sp_id'])) {
                $where['mch_id'] = [ZERO, $params['sp_id']];
            } else {
                $where['mch_id'] = ZERO;
            }
            //所属规格
            if (!empty($params['size_id'])) {
                $where['specifications_id'] = $params['size_id'];
            }
            //所属模板分类
            if (!empty($params['theme_id']) && $params['theme_id']!=-1) {
                $where['main_temp_theme_id'] = $params['theme_id'];
            }

            //如果有all_state ,则返回已审核的模板
            if (empty($params['all_state'])) {
                $where['main_temp_check_status'] = TEMPLATE_STATUS_VERIFYED;
            }

            $offset = $params['index'] * $params['size'];
            $tempList = $template->getRowsPage($where, $offset, $params['size'], 'main_temp_sort', 'desc');

            $cateList = $cate->getTList('template');
            $catKv = array_column($cateList, 'cate_name', 'cate_id');

            $tpUrl = config('template.material.upload.tp_url');
            $staticUrl = config('common.static_url');
            $list['list']       = [];
            $list['total_page'] = 0;
            //格式化接口返回数据
            foreach ($tempList['list'] as $k=>$v) {
                $list['list'][$k]['id']                 = $v['main_temp_id'];
                $list['list'][$k]['index']              = $v['main_temp_sort'];
                $list['list'][$k]['name']               = $v['main_temp_name'];
                $list['list'][$k]['theme_id']           = $v['main_temp_theme_id'];
                $list['list'][$k]['theme_name']         = isset($catKv[$v['main_temp_theme_id']]) ? $catKv[$v['main_temp_theme_id']]:'';
                $list['list'][$k]['thumb_url']          = Helper::getRealUrl($v['main_temp_thumb'],$staticUrl);//!empty($v['main_temp_thumb']) ? $staticUrl.'/'.$v['main_temp_thumb']:'';
                $list['list'][$k]['mask_total_count']   = empty($v['main_temp_photo_count']) ? 0 : $v['main_temp_photo_count'];
            }
            $list['total_page'] = ceil($tempList['count']/$params['size']);

            return $this->success([$list]);
        }catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }

    }

    /**
     * 获取单个模板信息
     * @param Request $request
     * @param TemplateCommon $serviceTemp
     * @param Info $serviceGoods
     * @param SaasCategoryRepository $cate
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTemplate(Request $request, TemplateCommon $serviceTemp, Info $serviceGoods, SaasCategoryRepository $cate)
    {
        try {
            $params = $request->all();
            if (empty($params['tid'])) {
                Helper::EasyThrowException('50031',__FILE__.__LINE__);
            }

            $tid = $params['tid'];
            //如果skuId为0则表示未关联货品，表示后台打开模板设计
            $skuId  = isset($params['product_id']) ? $params['product_id'] : 0;
            $skuId = $params['product_id'] = $skuId == -1 ? 0 : $skuId;
            $pageCount = isset($params['page_count']) ? $params['page_count'] :0;
            $pageCount = $params['page_count'] = $pageCount == -1 ? 0 : $pageCount;
            $params['start_year'] = empty($params['start_year']) ? 0 : $params['start_year'];
            $params['start_month'] = empty($params['start_month']) ? 0 : $params['start_month'];

            $realInfo = $serviceTemp->getRealTempIdInfo($tid);
            $tid        = $realInfo['tid'];
            $tempType   = $realInfo['type'];
            $detail = $serviceTemp->getTemplateDetail($tid, $tempType, $params);

            $basic = $detail['main'];
            $pages = $detail['pages'];
            $sizeId = $basic['specifications_id'];

            //
            $cateId = $basic['goods_type_id'];
            $cateInfo = $cate->getById($cateId);

            //无需书脊的分类
            if (in_array($cateInfo['cate_flag'], config("common.goods_cate_no_spine"))) {
                $thickness = 0;
            } else {
                //取书脊的情况
                if ($tempType == TEMPLATE_PAGE_MAIN && !empty($skuId)) {
                    $thickness = $serviceGoods->getGoodsSpineThickness($skuId, $pageCount);
                } else {
                    $thickness = config('common.goods_default_thickness');
                }
            }
            $goodsInfo = $serviceGoods->getGoodsBySku($skuId);
            $goodsId = empty($goodsInfo)?0 :$goodsInfo['prod_id'];
            //规格数据
            $sizeInfo = $serviceGoods->getGoodSizeInfo($sizeId, $goodsId);
            $sizeItems =  $this->formatSizeInfo($sizeInfo);

            $cateList = $cate->getTList('template');
            $catKv = array_column($cateList, 'cate_name', 'cate_id');

            $standardInfo = $this->standardTempInfo($tempType, $basic);

            if (!isset($catKv[$standardInfo['temp_cate_id']])) {
                Helper::EasyThrowException('50031',__FILE__.__LINE__);
            }
            //组装数据
            $return['name'] = $standardInfo['name'];
            $return['thickness'] =$thickness;
            $return['first_year'] =isset($standardInfo['start_year']) ? $standardInfo['start_year'] : '';
            $return['theme_id'] =$standardInfo['temp_cate_id'];
            $return['theme_name'] =$catKv[$standardInfo['temp_cate_id']];
            $return['thumb_url'] =config('template.material.upload.tp_url').'/'.$standardInfo['thumb'];
            $return['size_item'] = $sizeItems;
            $return['pages']    = $this->formatPages($tempType, $pages);

            return $this->success([$return]);

        }catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }

    }

    /**
     * 获取单子页的数据
     * @param Request $request
     * @param TemplateCommon $serviceTemp
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTemplatePageData(Request $request, TemplateCommon $serviceTemp)
    {
        try {
            $id = $request->input('id');
            if (empty ($id)) {
                Helper::EasyThrowException('50033',__FILE__.__LINE__);
            }
            //获取类型和id
            $realInfo = $serviceTemp->getRealTempIdInfo($id);
            $tid        = $realInfo['tid'];
            $tempType   = $realInfo['type'];

            $repoTemp = $serviceTemp->getTempPageRepo($tempType);
            $info = $repoTemp->getByIdFromCache($tid);
            if (empty($info)) {
                Helper::EasyThrowException('50033',__FILE__.__LINE__);
            }
            //格式化子页数据
            $page = $this->formatPages($tempType, [$info]);
            return $this->success($page);

        }catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    /**
     * 保存模板子页数据
     * @param saveTemplateDataRequest $request
     * @param TemplateCommon $serviceTemp
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveTemplateData(saveTemplateDataRequest $request, TemplateCommon $serviceTemp)
    {
        try {
            $tid       =   $request->input('tid');
            $pages     =   $request->input('pages');
            $maskCount = $request->input('mask_total_count');

            \DB::beginTransaction();
            //获取真实模板类型和模板id
            $realInfo = $serviceTemp->getRealTempIdInfo($tid);
            $tid        = $realInfo['tid'];
            $tempType   = $realInfo['type'];
            //获取模板及已有子页数据
            $tempInfo = $serviceTemp->getTemplateDetail($tid, $tempType, []);

            $arrPages = json_decode($pages, true);
            //$arrPages = $this->test();

            if (empty($arrPages)) {
                Helper::EasyThrowException('50035', __FILE__.__LINE__);
            }
            //组织数据更新
            $photo_count = 0;
            $int_pages = 0;
            if ($tempType == TEMPLATE_PAGE_PAGE) { //封面
                $updateData[0]['cover_temp_id'] = $arrPages[0]['id'];
                $updateData[0]['mch_id']        = $tempInfo['main']['mch_id'];
                $updateData[0]['cover_real_page_w'] = $arrPages[0]['page_width'];
                $updateData[0]['cover_real_page_h'] = $arrPages[0]['page_height'];
                $updateData[0]['cover_temp_dpi'] = $arrPages[0]['dpi'];
                $updateData[0]['cover_temp_dpi'] = $arrPages[0]['dpi'];
                $updateData[0]['cover_temp_photo_count'] = $arrPages[0]['mask_count'];
                $updateData[0]['cover_temp_stage'] = $arrPages[0]['stage_content'];
                $tableName = 'saas_cover_templates';
            } elseif($tempType == TEMPLATE_PAGE_INNER) { //内页
                $tableName = 'saas_inner_templates_pages';
                $arr_tid = array_column($tempInfo['pages'], 'inner_page_id');
                foreach($arrPages as $k=>$v){
                    if(!in_array($v['id'],$arr_tid)){
                        Helper::EasyThrowException('50034', __FILE__.__LINE__);
                    }
                    $updateData[$k]['inner_page_id'] = $v['id'];
                    $updateData[$k]['mch_id'] = $tempInfo['main']['mch_id'];
                    $updateData[$k]['inner_page_real_w'] = $v['page_width'];
                    $updateData[$k]['inner_page_real_h'] = $v['page_height'];
                    $updateData[$k]['inner_page_dpi'] = $v['dpi'];
                    $updateData[$k]['inner_page_photo_count'] = $v['mask_count'];
                    $updateData[$k]['inner_page_stage'] = $v['stage_content'];
                }
                $mainData['inner_temp_photo_count'] = $maskCount;
                $serviceTemp->getTempRepo($tempType)->update(['inner_temp_id'=> $tempInfo['main']['inner_temp_id']], $mainData);

            } else {  //主模板保存
                $tableName = 'saas_main_templates_pages';
                $arr_tid = array_column($tempInfo['pages'], 'main_temp_page_id');
                foreach($arrPages as $k=>$v){
                    if(!in_array($v['id'],$arr_tid)){
                        Helper::EasyThrowException('50034', __FILE__.__LINE__);
                    }
                    $updateData[$k]['main_temp_page_id'] = $v['id'];
                    $updateData[$k]['mch_id'] = $tempInfo['main']['mch_id'];
                    $updateData[$k]['main_temp_page_real_w'] = $v['page_width'];
                    $updateData[$k]['main_temp_page_real_h'] = $v['page_height'];
                    $updateData[$k]['main_temp_page_dpi'] = $v['dpi'];
                    $updateData[$k]['main_temp_page_photo_count'] = $v['mask_count'];
                    $updateData[$k]['main_temp_page_stage'] = $v['stage_content'];

                    //计算模板平均图片数量
                    if(!empty($v['mask_count'])) {
                        $photo_count+=$v['mask_count'];
                        $int_pages++;
                    }

                }
                $avg_photo = floatval($photo_count/$int_pages);
                $mainData['main_temp_avg_photo'] = $avg_photo;
                $mainData['main_temp_photo_count'] = $maskCount;
                $serviceTemp->getTempRepo($tempType)->update(['main_temp_id'=> $tempInfo['main']['main_temp_id']], $mainData);

            }
            $serviceTemp->getTempRepo($tempType)->batchUpdate($tableName,$updateData);
            \DB::commit();
            return $this->success([]);
        }catch (CommonException $e) {
            \DB::rollBack();
            return $this->error($e->getCode(), $e->getMessage());
        }

    }

    /**
     * 保存模板预览图
     * @param Request $request
     * @param TemplateCommon $serviceTemp
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveTemplateThumb(Request $request, TemplateCommon $serviceTemp)
    {
        try {
            $id = $request->input('id');
            $tid = $request->input('tid');

            if (empty($id)) {
                Helper::EasyThrowException('10022', __FILE__.__LINE__);
            }
            $realInfo = $serviceTemp->getRealTempIdInfo($tid);
            $tid        = $realInfo['tid'];
            $tempType   = $realInfo['type'];
            $stream = file_get_contents("php://input");
            $path =  config("template.material.upload.dir").'/'.config("template.material.upload.temp_view_pic").'/'.$tempType.'/'.$id;
            $ret = Helper::saveImageStream($path, $stream);

            if ($ret) {
                $url =config("template.material.upload.temp_view_pic").'/'.$tempType.'/'.$id.'/'.$ret;
                if ($tempType == TEMPLATE_PAGE_PAGE) { //封面
                    $where['cover_temp_id'] = $id;
                    $data['cover_temp_thumb'] = $url;
                }elseif($tempType == TEMPLATE_PAGE_INNER) { //内页
                    $where['inner_page_id'] = $id;
                    $data['inner_page_thumb'] = $url;
                }else {  //主模板
                    $where['main_temp_page_id'] = $id;
                    $data['main_temp_page_thumb'] = $url;
                }

                $updateRes = $serviceTemp->getTempPageRepo($tempType)->update($where, $data);
                if (empty($updateRes)) {
                    Helper::EasyThrowException('50037', __FILE__.__LINE__);
                }
                return $this->success([]);

            } else {
                Helper::EasyThrowException('50036', __FILE__.__LINE__);
            }

        }catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }

    }

    /**
     * 创建模板数据
     * @param Request $request
     */
    public function createTemplate(Request $request, TemplateCommon $temp, SaasMainTemplatesRepository $repoTemp)
    {
        $sizeId= $request->input('size_id');
        $name= $request->input('name');
        $thumb= $request->input('thumb_url');
        $pageCount = $request->input('page_count');

        $tempInfo = $repoTemp->getRow(['main_temp_name' => $name, 'specifications_id' =>$sizeId  ]);

        if (!empty($tempInfo)) {
            return $this->success([['tid' => $tempInfo['main_temp_id'], 'existed' => 1]]);
        }

        $tid = $temp->createBlankTemplate($sizeId,$name, $thumb, $pageCount);

        return $this->success([['tid' => $tid]]);
    }

    protected function test()
    {
        return $arr_pages = [
            [
                'id' => '9', 'mask_count' => 5, 'page_width' => 210, 'page_height' => 210, 'dpi'       => 300, 'stage_content' => '191919'
            ],
            [
                'id' => '10', 'mask_count' => 5, 'page_width' => 210, 'page_height' => 210, 'dpi'       => 300, 'stage_content' => '181818'
            ],
            [
                'id' => '11', 'mask_count' => 5, 'page_width' => 210, 'page_height' => 210, 'dpi'       => 300, 'stage_content' => '171717'
            ],
            [
                'id' => '12', 'mask_count' => 5, 'page_width' => 210, 'page_height' => 210, 'dpi'       => 300, 'stage_content' => '161616'
            ],
            [
                'id' => '13', 'mask_count' => 5, 'page_width' => 210, 'page_height' => 210, 'dpi'       => 300, 'stage_content' => '151515'
            ],
            [
                'id' => '14', 'mask_count' => 5, 'page_width' => 210, 'page_height' => 210, 'dpi'       => 300, 'stage_content' => '141414'
            ],
            [
                'id' => '15', 'mask_count' => 5, 'page_width' => 210, 'page_height' => 210, 'dpi'       => 300, 'stage_content' => '131313'
            ],
            [
                'id' => '16', 'mask_count' => 5, 'page_width' => 210, 'page_height' => 210, 'dpi'       => 300, 'stage_content' => '121212'
            ],[
                'id' => '17', 'mask_count' => 5, 'page_width' => 210, 'page_height' => 210, 'dpi'       => 300, 'stage_content' => '111111'
            ],
            [
                'id' => '18', 'mask_count' => 5, 'page_width' => 210, 'page_height' => 210, 'dpi'       => 300, 'stage_content' => '101010'
            ],
            [
                'id' => '19', 'mask_count' => 5, 'page_width' => 210, 'page_height' => 210, 'dpi'       => 300, 'stage_content' => '99999'
            ],
//            [
//                'id' => '89', 'mask_count' => 5, 'page_width' => 210, 'page_height' => 210, 'dpi'       => 300, 'stage_content' => '88888'
//            ],
//            [
//                'id' => '90', 'mask_count' => 5, 'page_width' => 210, 'page_height' => 210, 'dpi'       => 300, 'stage_content' => '7777'
//            ],
//            [
//                'id' => '91', 'mask_count' => 5, 'page_width' => 210, 'page_height' => 210, 'dpi'       => 300, 'stage_content' => '6666'
//            ],
//            [
//                'id' => '92', 'mask_count' => 5, 'page_width' => 210, 'page_height' => 210, 'dpi'       => 300, 'stage_content' => '555'
//            ],
//            [
//                'id' => '93', 'mask_count' => 5, 'page_width' => 210, 'page_height' => 210, 'dpi'       => 300, 'stage_content' => '555'
//            ],
//            [
//                'id' => '94', 'mask_count' => 5, 'page_width' => 210, 'page_height' => 210, 'dpi'       => 300, 'stage_content' => '4444'
//            ],
//            [
//                'id' => '95', 'mask_count' => 5, 'page_width' => 210, 'page_height' => 210, 'dpi'       => 300, 'stage_content' => '3333'
//            ],
//            [
//                'id' => '96', 'mask_count' => 5, 'page_width' => 210, 'page_height' => 210, 'dpi'       => 300, 'stage_content' => '2222'
//            ],
//            [
//                'id' => '134', 'mask_count' => 5, 'page_width' => 210, 'page_height' => 210, 'dpi'       => 300, 'stage_content' => '111111'
//            ],
        ];
    }
}