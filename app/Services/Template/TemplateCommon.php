<?php
namespace App\Services\Template;
use App\Repositories\SaasCategoryRepository;
use App\Repositories\SaasCoverTemplatesRepository;
use App\Repositories\SaasInnerTemplatesPagesRepository;
use App\Repositories\SaasInnerTemplatesRepository;
use App\Repositories\SaasMainTemplatesPagesRepository;
use App\Repositories\SaasMainTemplatesRepository;
use App\Repositories\SaasMaterialRepository;
use App\Repositories\SaasProductsSizeRepository;
use App\Repositories\SaasTemplatesAttachmentRepository;
use App\Services\Helper;

/**
 * 模板通用逻辑
 *
 * 封面/内面/主模板的通用逻辑
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/5/3
 */
class TemplateCommon
{
    public $repoMain;   //主模板仓库
    public $repoInner;  //内页模板仓库
    public $repoCover;  //封面模板仓库
    public $repoMaterial; //素材仓库
    public $repoAtta ;    //模板附件
    public $repoMainPage;  //模板子页
    public $repoInnerPage;  //内页子大页
    public $repoSize;    //规格仓库
    public $repoCateGoods; //分类仓库

    /**
     * TemplateCommon constructor.
     * @param SaasMainTemplatesRepository $main
     * @param SaasInnerTemplatesRepository $inner
     * @param SaasCoverTemplatesRepository $cover
     * @param SaasMainTemplatesPagesRepository $repoMainPage
     * @param SaasInnerTemplatesPagesRepository $repoInnerPage
     * @param SaasMaterialRepository $material
     * @param SaasTemplatesAttachmentRepository $attachment
     */
    public function __construct(SaasMainTemplatesRepository $main, SaasInnerTemplatesRepository $inner,
        SaasCoverTemplatesRepository $cover,SaasMainTemplatesPagesRepository $repoMainPage, SaasInnerTemplatesPagesRepository $repoInnerPage,
        SaasMaterialRepository $material, SaasTemplatesAttachmentRepository $attachment,SaasProductsSizeRepository $size,SaasCategoryRepository $cateGoods)
    {
        $this->repoMain     = $main;
        $this->repoInner    = $inner;
        $this->repoCover    = $cover;
        $this->repoMaterial = $material;
        $this->repoAtta     = $attachment;
        $this->repoMainPage = $repoMainPage;
        $this->repoInnerPage= $repoInnerPage;
        $this->repoSize     = $size;
        $this->repoCateGoods= $cateGoods;
    }


    /**
     * 通过不同形式tid获取真实模板id  inpagexxx 内页 coverxxx 封面 xxx主模板
     * @param $tid
     * @return array
     */
    public function getRealTempIdInfo($tid)
    {
        if(is_numeric($tid)) {
            return ['type'=>TEMPLATE_PAGE_MAIN, 'tid' =>$tid];
        }

        //取字母
        preg_match( '/([a-z]{1,10})(\d+)/',$tid,$match);
        $qz = $match[1];
        $real_tid = $match[2];

        if (empty($real_tid)) {

        }

        if($qz == 'cover') {
            return ['type'=>TEMPLATE_PAGE_PAGE, 'tid' =>$real_tid];
        } else {
            return ['type'=>TEMPLATE_PAGE_INNER, 'tid' =>$real_tid];
        }
    }

    /**
     * @param $tid 模板id
     * @param $type 类型 封面/内页/主模板
     * @param int $page_size 每页显示数量
     * @param int $index     当前面
     * @return  array
     */
    public function getTemplateMaterial($tid, $type, $page_size=0, $index = 0, $where=[])
    {
        $realIdInfo = $this->getRealTempIdInfo($tid);

        $offset = $index * $page_size;

        $where['temp_attach_type']              = $realIdInfo['type'];
        $where['temp_attach_material_type']     = $type;
        $where['temp_attach_tid']               = $realIdInfo['tid'];
        $list = $this->repoAtta->getRowsPage($where, $offset, $page_size, 'temp_attach_id', 'desc');

        return $list;
    }

    public function getMaterial($where, $page_size, $index)
    {
        $offset = $index * $page_size;
        $list = $this->repoMaterial->getMaterialWithAttachment($where, $offset, $page_size, 'material_id', 'desc');
        return $list;
    }

    /**
     * 获取素材路径详细信息
     * @param $path 素材原始路径
     * @param $type 素材类型
     * @return array
     */
    public function getMaterialPathInfo($path, $type)
    {
        //画框处理
        $return = [];
        $tpUrl = config('template.material.upload.tp_url');
        if ($type == MATERIAL_TYPE_FRAME) {
            $frameHz = config('template.material.upload.frame_hz');
            if(strstr($path,$frameHz)) { //蒙版的那张图
                $border_name = str_replace($frameHz,'',$path);
                $return['thumb_url'] = $tpUrl.'/'.$type.'/sml/'.$border_name;
                $return['frame_url'] = $tpUrl.'/'.$type.'/mid/'.$border_name;
                $return['frame_mask_url'] = $tpUrl.'/'.$type.'/mid/'.$path;

            } else {
                $border_name = str_replace('.',$frameHz.'.',$path);
                $return['thumb_url'] = $tpUrl.'/'.$type.'/sml/'.$path;
                $return['frame_url'] = $tpUrl.'/'.$type.'/mid/'.$path;
                $return['frame_mask_url'] = $tpUrl.'/'.$type.'/mid/'.$border_name;
            }
        } else {
            $return['thumb_url'] = $tpUrl.'/'.$type.'/sml/'.$path;
            $return['url'] = $tpUrl.'/'.$type.'/mid/'.$path;
        }
        return $return;
    }

    /**
     * 获取单个模板的详细信息 主页+子页的信息
     * @param $tid 模板id
     * @param $type 模板类型
     * @param $condition 附加条件
     * @return array
     */
    public function getTemplateDetail($tid, $type, $condition)
    {
        switch ($type) {
            case TEMPLATE_PAGE_PAGE :   //封面模板库
                $objTemp = $this->repoCover;
                $where['cover_temp_id'] = $tid;
                break;
            case TEMPLATE_PAGE_INNER :
                $objTemp = $this->repoInner;
                $where['inner_temp_id'] = $tid;
                $info = $this->getInnerPages($tid,$condition);
                break;
            case TEMPLATE_PAGE_MAIN :
                $objTemp = $this->repoMain;
                $where['main_temp_id'] = $tid;
                $info = $this->getMainPages($tid,$condition);
                break;
            default :
                $objTemp = $this->repoMain;
                $where['main_temp_id'] = $tid;
                $info = $this->getMainPages($tid,$condition);
                break;
        }

        $basicInfo = $objTemp->getRow($where);

        if (empty($basicInfo)) {
            Helper::apiThrowException('50039',__FILE__.__LINE__);
        }

        $basicInfo = $basicInfo->toArray();

        if($type == TEMPLATE_PAGE_PAGE) {
            $info =  [$basicInfo];
        }

        return ['main' =>$basicInfo, 'pages' => $info];
    }

    /**
     * 获取主模板的子页数据
     * @param $tid
     * @param $condition
     */
    public function getMainPages($tid,$condition)
    {
        //如果page_count为空则全部返回
        $objTemp = $this->repoMainPage;
        $where['main_temp_page_tid'] = $tid;
        //前端制作时相关
        if (!empty ($condition['page_count']) && !empty($condition['product_id'])) {
            //如果是台挂历并存在起始年份
            $faceWhere['main_temp_page_type'] = [GOODS_SIZE_TYPE_COVER];
            $faceWhere['main_temp_page_tid'] = $tid;
            if(isset($condition['start_year']) && !empty($condition['start_year'])) {
                $faceWhere['main_temp_page_year'] = $condition['start_year'];  //只取当年的封面
            }
            //取封面数据,这里最好改成whereIn的形式
            $faceData = $objTemp->getRow($faceWhere);

            if (!empty($faceData)) {
                $faceData = $faceData->toArray();
            } else {
                $faceWhere['main_temp_page_type'] = [GOODS_SIZE_TYPE_COVER_BACK];
                $faceData = $objTemp->getRow($faceWhere);
                if (!empty($faceData)) {
                    $faceData = $faceData->toArray();
                } else{
                    $faceData = [];
                }

            }

            //取模板数据
            $innerWhere['main_temp_page_tid']   = $tid;
            $innerWhere['main_temp_page_type']  = GOODS_SIZE_TYPE_INNER;
            $innerData = $objTemp->getRows($innerWhere, 'main_temp_page_sort', 'asc', $condition['page_count'])->toArray();
            if(!empty($faceData)) {
                array_unshift($innerData,$faceData);
            }

            $pages = $innerData;
        } else {
            $pages = $objTemp->getRows($where, 'main_temp_page_sort', 'asc')->toArray();
        }
        return $pages;
    }

    /**
     * 获取内页模板子页数据
     * @param $tid
     * @param $condition
     */
    public function getInnerPages($tid,$condition)
    {
        $objTemp = $this->repoInnerPage;
        if (!empty ($condition['page_count'])) {
            if(isset($condition['start_year']) && !empty($condition['start_year'])) {
                $where['inner_page_tid'] = $tid;
                $where['inner_page_year'] = $condition['start_year'];
            } else {
                $where['inner_page_tid'] = $tid;
            }
            $pages = $objTemp->getRows($where, 'inner_page_sort', 'asc', $condition['page_count'])->toArray();
        } else {
            $where['inner_page_tid'] = $tid;
            $pages = $objTemp->getRows($where, 'inner_page_sort', 'asc')->toArray();
        }
        return $pages;
    }

    /**
     * 通过type获取使用模板的仓库
     * @param $type
     * @return SaasCoverTemplatesRepository|SaasInnerTemplatesPagesRepository
     * |SaasInnerTemplatesRepository|SaasMainTemplatesRepository
     */
    public function getTempRepo($type)
    {
        switch ($type) {
            case TEMPLATE_PAGE_PAGE :   //封面模板库
                $objTemp = $this->repoCover;
                break;
            case TEMPLATE_PAGE_INNER :
                $objTemp = $this->repoInner;
                break;
            case TEMPLATE_PAGE_MAIN :
                $objTemp = $this->repoMain;
                break;
            default :
                $objTemp = $this->repoMain;
                break;
        }
        return $objTemp;
    }

    /**
     * @param $type
     * @return SaasCoverTemplatesRepository|SaasInnerTemplatesPagesRepository|SaasMainTemplatesPagesRepository
     */
    public function getTempPageRepo($type)
    {
        switch ($type) {
            case TEMPLATE_PAGE_PAGE :   //封面模板库
                $objTemp = $this->repoCover;
                break;
            case TEMPLATE_PAGE_INNER :
                $objTemp = $this->repoInnerPage;
                break;
            case TEMPLATE_PAGE_MAIN :
                $objTemp = $this->repoMainPage;
                break;
            default :
                $objTemp = $this->repoMainPage;
                break;
        }
        return $objTemp;
    }
    /**
     * 根据原始路径获取画框另一个路径
     * @param $path 原始路径
     * @return  string
     */
    private function getOtherFrameByPath($path)
    {
        return '';
    }

    /**
     * 创建空模板，返回模板id
     * @param $size , $name, $thumb, $page_count
     * @return $tid
     */
    public function createBlankTemplate($size , $name, $thumb, $page_count)
    {
        $productSize = $this->repoSize->getById($size);
         $data = [
             'main_temp_name'=>$name,
             'goods_type_id'=>$productSize['size_cate_id'],
             'main_temp_theme_id'=>106,
             'specifications_id'=>$size,
             'main_temp_thumb'=>$thumb,
             'main_temp_photo_count'=>$page_count,
         ];
        $tid = $this->repoMain->saveBlankTemp($data);
        return $tid;
    }




}