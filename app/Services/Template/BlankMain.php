<?php
namespace App\Services\Template;

use App\Exceptions\CommonException;
use App\Models\SaasCoverTemplates;
use App\Models\SaasMainTemplates;
use App\Models\SaasMainTemplatesPages;
use App\Models\SaasSizeInfo;
use App\Models\SaasInnerTemplatesPages;
use App\Models\SaasTemplatesAttachment;
use Illuminate\Support\Facades\DB;

/**
 *  主模板信息相关逻辑
 *
 * 主模板库的相关逻辑处理
 * @author: dai
 * @version: 1.0
 * @date: 2020/4/19
 *
 */
class BlankMain
{
    /**
     * 获取年份列表
     * @param int $start 开始年份
     * @param int $limit 列表数量
     * @return array $return
     *
     */
    public function getCalendarYear($start = 0, $limit = 5)
    {
        if(empty ($start)) {
            $start = date('Y');
        }

        $return = [];
        for ($i = 0 ; $i<$limit ; $i++) {
            $return[$start+$i] = $start+$i;
        }

        return $return;
    }


    /**
     * 通过封面和内面模板组装主模板子页
     * @param $main_id 主模板id
     * @param $cover_id 封面模板id
     * @param $inner_id 内页模板id
     * @param int $mid  商户id
     * @return mixed
     *
     *  David 加规格详情id和dpi
     */


    public function addChild($main_id, $cover_id, $inner_id, $size_id, $mid = 0)
    {
        if(empty($cover_id) && empty($inner_id)) {
            return false;
        }

        if(!empty($cover_id)) {

            //获取封面模板相应的数据
            $coverData = SaasCoverTemplates::where('cover_temp_id', $cover_id)->first();
            $sizeInfo =  SaasSizeInfo::where(['size_id'=>$size_id,'goods_id'=>ZERO])->whereIn('size_type',[1,2])->get()->toArray();
            //先插入子页数据
            $mainChild = new SaasMainTemplatesPages();

            $mainChild->mch_id = $mid;
            $mainChild->main_temp_page_name = '封面';
            $mainChild->main_temp_page_type = $sizeInfo[0]['size_type'];
            $mainChild->specifications_id = $coverData['specifications_id'];
            $mainChild->main_temp_page_tid = $main_id;
            $mainChild->spec_info_id = $sizeInfo[0]['size_info_id'];
            $mainChild->main_temp_page_year = $coverData['cover_temp_start_year'];
            $mainChild->base_temp_id = $cover_id;
            $mainChild->main_temp_page_dpi = $sizeInfo[0]['size_info_dpi'];
            $mainChild->main_temp_page_sort = 0;
            $mainChild->main_temp_page_thumb = $coverData['cover_temp_thumb'];
            $mainChild->main_temp_page_photo_count = $coverData['cover_temp_photo_count'];
            if(!Config('is_use_mongo')) {
                $mainChild->main_temp_page_stage = $coverData['cover_temp_stage'];
            }

            $ret = $mainChild->save();
            //dd($ret);die;
          /*  //如果启用mongodb
            if(Config('is_use_mongo')) {
                $stage = json_decode($coverData['stage'], true);

                $data = ['temp_id'=> $main_id, 'child_id' =>$ret , 'stage' => $stage];
                $mongo = new \app\common\library\Mongo();
                $mongo->insert('temp_stage', $data);
            }*/

        }

        if(!empty($inner_id)) {
            //获取内页的子页数据
            $innerData = SaasInnerTemplatesPages::where('inner_page_tid',$inner_id)->get()->toArray();
            $sizeInfo =  SaasSizeInfo::where(['size_id'=>$size_id,'goods_id'=>ZERO])->where('size_type',GOODS_SIZE_TYPE_INNER)->first();

            foreach ($innerData as $k=>$v) {
                $mainChild = new SaasMainTemplatesPages();
                $mainChild->mch_id = $mid;
                $mainChild->main_temp_page_name = $v['inner_page_name'];
                $mainChild->main_temp_page_type = GOODS_SIZE_TYPE_INNER;
                $mainChild->main_temp_page_tid = $main_id;
                $mainChild->spec_info_id = $sizeInfo['size_info_id'];
                $mainChild->specifications_id = $v['specifications_id'];
                $mainChild->base_temp_id = $inner_id;
                $mainChild->main_temp_page_year = $v['inner_page_year'];
                $mainChild->main_temp_page_dpi = $sizeInfo['size_info_dpi'];
                $mainChild->main_temp_page_sort = $k+1;
                $mainChild->main_temp_page_thumb = $v['inner_page_thumb'];
                $mainChild->main_temp_page_photo_count = $v['inner_page_photo_count'];
                $mainChild->main_temp_page_stage = $v['inner_page_stage'];

                $ret = $mainChild->save();

              /*  //如果启用mongodb
                if(Config('is_use_mongo')) {
                    $stage = json_decode($v['stage'], true);

                    $data = ['temp_id'=> $main_id, 'child_id' =>$ret , 'stage' => $stage];
                    $mongo = new \app\common\library\Mongo();
                    $mongo->insert('temp_stage', $data);
                }*/
            }
        }
        return true;
    }

   /** Dai 加mid判断是哪个后台克隆的 */
    /**
     * 主模板复制
     * @param $temp_id 模板id
     * @param int $mid 商户id
     * @param int $page_type 页面类型
     * @throws MyException
     */

    public function TemplateCopy($temp_id, $mid = 0, $page_type = TEMPLATE_PAGE_MAIN)
    {
        if(empty($temp_id)) {
            return $this->jsonFailed('模板复制失败');
        }

        //获取主表信息
        $main_info = SaasMainTemplates::where(['main_temp_id'=>$temp_id])->first();
        if(empty($main_info)) {
            return $this->jsonFailed('原模板不存在');
        }

        DB::beginTransaction();

        //$mid = empty(session::get('admin.mid'))? 0 :session::get('admin.mid');

        $oldId = $main_info['main_temp_id'];
        //复制主表记录
        unset($main_info['main_temp_id'],$main_info['created_at'],$main_info['updated_at']);
        try{
            $mainTemp = new SaasMainTemplates();
            $mainTemp->main_temp_name = '克隆_'.$main_info['main_temp_name'];
            $mainTemp->goods_type_id = $main_info['goods_type_id'];
            $mainTemp->specifications_id = $main_info['specifications_id'];
            $mainTemp->main_temp_theme_id = $main_info['main_temp_theme_id'];
            $mainTemp->inner_temp_id = $main_info['inner_temp_id'];
            $mainTemp->cover_temp_id = $main_info['cover_temp_id'];
            $mainTemp->main_temp_thumb = $main_info['main_temp_thumb'];
            $mainTemp->main_temp_check_status = TEMPLATE_STATUS_DOING;
            $mainTemp->mch_id = $mid;
            $mainTemp->main_temp_is_vip = ZERO;
            $mainTemp->main_temp_sort = ZERO;
            $mainTemp->created_at = time();
            $mainTemp->save();
            $newId = $mainTemp->main_temp_id;


            //复制子表记录
            $pages = SaasMainTemplatesPages::where('main_temp_page_tid',$oldId)->get();

            foreach ($pages as $k => $v) {
                $objPages = new SaasMainTemplatesPages();
                $objPages->mch_id = $mid;
                $objPages->main_temp_page_type = $v['main_temp_page_type'];
                $objPages->main_temp_page_name = $v['main_temp_page_name'];
                $objPages->main_temp_page_tid = $newId;
                $objPages->specifications_id = $v['specifications_id'];
                $objPages->spec_info_id = $v['spec_info_id'];
                $objPages->base_temp_id = $v['base_temp_id'];
                $objPages->main_temp_page_sort = $v['main_temp_page_sort'];
                $objPages->main_temp_page_photo_count = $v['main_temp_page_photo_count'];
                $objPages->main_temp_page_stage = $v['main_temp_page_stage'];
                $objPages->main_temp_page_dpi = $v['main_temp_page_dpi'];
                $objPages->main_temp_page_year = $v['main_temp_page_year'];

                $objPages->save();
            }


            //关联上相关关素材
            $relationAtta = SaasTemplatesAttachment::where(['temp_attach_type'=>$page_type,'temp_attach_tid'=>$oldId])->get()->toArray();

            foreach ($relationAtta as $k=>$v) {
                $attachment = new SaasTemplatesAttachment();
                unset($v['temp_attach_id']);
                $v['temp_attach_tid'] = $newId;

                // $attachment->data($v);
                $attachment->insert($v);
            }

            DB::commit();
        }catch (CommonException $e){
            DB::rollBack();
            return $this->jsonFailed('克隆失败');
        }

    }


    /**
     * 内页台挂历子页复制逻辑
     * @param $temp_page
     * @param int $pages
     * @param int $mid
     */
    public function copyCalendar($temp_page,$pages = 1, $mid = 0)
    {
        //单双页
        $startSort = $temp_page['inner_page_sort'];

        $spec = SaasSizeInfo::where(['size_id'=>$temp_page['specifications_id'],'goods_id'=>ZERO,'size_type'=>GOODS_SIZE_TYPE_INNER])->first();
        $year = $temp_page['inner_page_year'];

        if(preg_match('/(\d+)/', $temp_page['inner_page_name'], $match)) {
            $max = $match[1];
        } else {
            $max = 1;
        }
        $count = SaasInnerTemplatesPages::where('inner_page_tid',$temp_page['inner_page_tid'])->count();
        for ($i = 0; $i<$pages ; $i++) {
            $startSort++;
            if($spec['size_is_2faced'] == 0) { //单页
               if($max == 12) {
                   $max = 1;
                   $year++;  //每12个月加一年
               } else {
                   $max++;
               }

                $data['inner_page_name'] = str_replace($match[1], $max, $temp_page['inner_page_name']);
            } else {
               if($count %2 === 0) {
                   $current = $max +1;
                   if($current > 12) {
                       $current = 1;
                       $max = 0;
                       $year++;
                   }
                   if ($i%2 === 0) {
                       $name = $current.__('Month');
                   } else {
                       $name = $current.__('Month');
                       $max++;
                   }
                   $data['inner_page_name'] = $name;
               } else {
                   $current = $max ;
                   if($current > 12) {
                       $current = 1;
                       $max = 1;
                       $year++;
                   }
                   if ($i%2 === 0) {
                       $name = $current.__('Month');
                       $max++;
                   } else {
                       $name = $current.__('Month');
                   }
                   $data['inner_page_name'] = $name;
               }

            }

            $data['inner_page_tid'] = $temp_page['inner_page_tid'];
            $data['inner_page_year'] = $year;
            $data['specifications_id'] = $temp_page['specifications_id'];
            $data['inner_page_sort'] = $startSort;
            $data['mch_id'] = $mid;
            $data['spec_info_id'] = $temp_page['spec_info_id'];
            $data['inner_page_dpi'] = $temp_page['inner_page_dpi'];
            $data['inner_base_temp_id'] = $temp_page['inner_base_temp_id'];
            $obj = new SaasInnerTemplatesPages();
            $obj->insert($data);
        }

    }

    /**
     * 内页模板复制照片书子页
     * @param $temp_page 源子页数据
     * @param int $pages  复制份数
     * $param int $mid 商户id
     */
    public function copyPhotoBook($temp_page,$pages = 1, $mid = 0)
    {
        $startSort = $temp_page['inner_page_sort'];
        //解析名字格式，按顺序添加
        $is_page = 0;
        if(strstr($temp_page['inner_page_name'], '-') || strstr($temp_page['inner_page_name'], '-')) {
            if(preg_match('/(\d+)\-(\d+)/', $temp_page['inner_page_name'], $match)) {
                $max = $match[2];
                $fg = '-';
                $is_page = 1;
            } else if (preg_match('/(\d+)\-(\d+)/', $temp_page['inner_page_name'], $match)) {
                $max = $match[2];
                $fg = '-';
                $is_page = 1;
            } else {
                $is_page = 0;
            }
        } else {
            if(preg_match('/(\d+)/', $temp_page['inner_page_name'], $match)) {
                $max = $match[1];
            } else {
                $default_name = 'copy';
            }
        }

        //子页插入
        for ($i=0; $i<$pages;$i++) {
            $startSort++;
            if ($is_page) { //自动生成对应的名称,例如 第1-2页名称规则
                $new = ($max+1).$fg.($max+2);
                $data['inner_page_name'] = str_replace($match[0], $new, $temp_page['inner_page_name']);

                $max = $max + 2;
            } else {  //单页的，例如 第1页名称规则
                $data['inner_page_name'] = str_replace($match[1], $max+1, $temp_page['inner_page_name']);
                $max = $max + 1;
            }

            if (empty($data['inner_page_name'])) {
                $data['inner_page_name'] = $default_name;
            }

            $data['inner_page_tid'] = $temp_page['fid'];
            $data['specifications_id'] = $temp_page['specifications_id'];
            $data['inner_page_sort'] = $startSort;
            $data['mch_id'] = $mid;
            $data['spec_info_id'] = $temp_page['spec_info_id'];
            $data['inner_page_dpi'] = $temp_page['inner_page_dpi'];
            $data['inner_base_temp_id'] = $temp_page['inner_base_temp_id'];
            $obj = new SaasInnerTemplatesPages();
            $obj->insert($data);
        }

    }

    /**
     * 主模板台挂历子页复制逻辑
     * @param $temp_page
     * @param int $pages
     * @param int $mid
     */
    public function copyMainCalendar($temp_page,$pages = 1, $mid = 0)
    {
        //单双页
        $startSort = $temp_page['main_temp_page_sort'];
        $spec = SaasSizeInfo::where(['size_id'=>$temp_page['specifications_id'],'goods_id'=>ZERO,'size_type'=>GOODS_SIZE_TYPE_INNER])->first();

        $year = $temp_page['main_temp_page_year'];

        if(preg_match('/(\d+)/', $temp_page['main_temp_page_name'], $match)) {
            $max = $match[1];
        } else {
            $max = 1;
        }
        $count = SaasMainTemplatesPages::where('main_temp_page_tid',$temp_page['main_temp_page_tid'])->count();

        for ($i = 0; $i<$pages ; $i++) {
            $startSort++;
            if($spec['size_is_2faced'] == 0) { //单页
                if($max == 12) {
                    $max = 1;
                    $year++;  //每12个月加一年
                } else {
                    $max++;
                }

                $data['main_temp_page_name'] = str_replace($match[1], $max, $temp_page['main_temp_page_name']);
            } else {
                if($count %2 === 0) {
                    $current = $max +1;
                    if($current > 12) {
                        $current = 1;
                        $max = 0;
                        $year++;
                    }
                    if ($i%2 === 0) {
                        $name = $current.__('Month');
                    } else {
                        $name = $current.__('Month');
                        $max++;
                    }
                    $data['main_temp_page_name'] = $name;
                } else {
                    $current = $max ;
                    if($current > 12) {
                        $current = 1;
                        $max = 1;
                        $year++;
                    }
                    if ($i%2 === 0) {
                        $name = $current.__('Month');
                        $max++;
                    } else {
                        $name = $current.__('Month');
                    }
                    $data['main_temp_page_name'] = $name;
                }

            }

            $data['main_temp_page_tid'] = $temp_page['main_temp_page_tid'];
            $data['specifications_id'] = $temp_page['specifications_id'];
            $data['main_temp_page_sort'] = $startSort;
            $data['mch_id'] = $mid;
            $data['main_temp_page_year'] = $year;
            $data['spec_info_id'] = $spec['size_info_id'];
            $data['main_temp_page_dpi'] = $temp_page['main_temp_page_dpi'];
            $data['base_temp_id'] = $temp_page['base_temp_id'];
            $obj = new SaasMainTemplatesPages();
            $obj->insert($data);
        }
    }


    /**
     * 主模板复制照片书子页
     * @param $temp_page 源子页数据
     * @param int $pages  复制份数
     * $param int $mid 商户id
     */
    public function copyMainPhotoBook($temp_page,$pages = 1, $mid = 0)
    {
        $startSort = $temp_page['main_temp_page_sort'];
        //解析名字格式，按顺序添加
        $is_page = 0;
        if(strstr($temp_page['main_temp_page_name'], '-') || strstr($temp_page['main_temp_page_name'], '-')) {
            if(preg_match('/(\d+)\s*\-\s*(\d+)/', $temp_page['main_temp_page_name'], $match)) {
                $max = $match[2];
                $fg = '-';
                $is_page = 1;
            } else if (preg_match('/(\d+)\s*\-\s*(\d+)/', $temp_page['main_temp_page_name'], $match)) {
                $max = $match[2];
                $fg = '-';
                $is_page = 1;
            } else {
                $is_page = 0;
            }
        } else {
            if(preg_match('/(\d+)/', $temp_page['main_temp_page_name'], $match)) {
                $max = $match[1];
            } else {
                $default_name = 'copy';
            }
        }

        //子页插入

        for ($i=0; $i<$pages;$i++) {
            $startSort++;
            if ($is_page) { //自动生成对应的名称,例如 第1-2页名称规则
                $new = ($max+1).$fg.($max+2);
                $data['main_temp_page_name'] = str_replace($match[0], $new, $temp_page['main_temp_page_name']);

                $max = $max + 2;
            } else {  //单页的，例如 第1页名称规则
                if(isset($match[1])) {
                    $data['main_temp_page_name'] = str_replace($match[1], $max+1, $temp_page['main_temp_page_name']);
                    $max = $max + 1;
                } else {
                    $data['main_temp_page_name'] = '';
                }


            }

            if (empty($data['main_temp_page_name'])) {
                $data['main_temp_page_name'] = $default_name;
            }

            $data['main_temp_page_tid'] = $temp_page['main_temp_page_tid'];
            $data['specifications_id'] = $temp_page['specifications_id'];
            $data['main_temp_page_sort'] = $startSort;
            $data['mch_id'] = $mid;
            $data['spec_info_id'] = $temp_page['spec_info_id'];
            $data['main_temp_page_dpi'] = $temp_page['main_temp_page_dpi'];
            $data['base_temp_id'] = $temp_page['base_temp_id'];
            $obj = new SaasMainTemplatesPages();
            $obj->insert($data);
        }

    }


    /**
     * 内页自动添加一年的台历子页
     * @param $params   提交的参数
     * @param $inner_id 内页模板id
     * @param int $mid  商户id
     */
    public function addCalendarPages($params, $inner_id, $mid = 0)
    {
        //根据提交的规格id查询规格具体参数
        $specParam = SaasSizeInfo::where(['size_id'=>$params['specifications_id'],'goods_id'=>ZERO,'size_type'=>GOODS_SIZE_TYPE_INNER])
            ->first();
        //0单页 1双页
        $is_turn = $specParam['size_is_2faced'];

        //1月、2月、3月、4月 生成一年的
        if($is_turn == 0) {
            //生成日历名称
            for($i = 0; $i<12;$i++){
                $params['child_name'] = ($i+1)."月";
                $params['sort'] = $i+1;
                $params['mid'] = $mid;
                $params['year'] = $params['inner_temp_start_year'];
                $params['spec_info_id'] = $specParam['size_info_id'];
                $params['size_info_dpi'] = $specParam['size_info_dpi'];
                $this->addCalendarPage($params ,$inner_id);
            }
        } else { //1月正、1月反、2月正、2月反
            for($i = 0; $i<24;$i++){

                if ($i%2 === 0) {  //反面
                    $name = ceil(($i+1)/2)."月反";
                } else {            //正面
                    $name = ceil(($i+1)/2)."月正";
                }

                $params['child_name'] = $name;
                $params['sort'] = $i+1;
                $params['mid'] = $mid;
                $params['year'] = $params['inner_temp_start_year'];
                $params['spec_info_id'] = $specParam['size_info_id'];
                $params['size_info_dpi'] = $specParam['size_info_dpi'];
                $this->addCalendarPage($params, $inner_id);
            }
        }

    }

    /**
     * 添加一个日历子页
     * @param $data
     */
    private function addCalendarPage($data, $fid){

        $pagesData['inner_page_name'] = $data['child_name'];
        $pagesData['mch_id'] = $data['mid'];
        $pagesData['inner_page_tid'] = $fid;
        $pagesData['specifications_id'] = $data['specifications_id'];
        $pagesData['spec_info_id'] = $data['spec_info_id'];
        $pagesData['inner_page_sort'] = $data['sort'];
        $pagesData['inner_page_year'] = $data['year'];
        $pagesData['inner_page_dpi'] = $data['size_info_dpi'];

        $inner = new SaasInnerTemplatesPages();
        $inner->insert($pagesData);
    }


    /**
     *  .Dai 添加
     * 内页模板自动添加1页的其他商品类型的子页 （全部默认是单页）
     * @param $params   提交的参数
     * @param $inner_id 内页模板id
     * @param int $mid  商户id
     */
    public function addOtherPages($params,$inner_id, $mid = 0)
    {
        //根据提交的规格id查询规格具体参数
        $specParam = SaasSizeInfo::where(['size_id'=>$params['specifications_id'],'goods_id'=>ZERO,'size_type'=>GOODS_SIZE_TYPE_INNER])
            ->first();

        $startSort = 0;
        $tempPage = config('goods.goods_type')[3];
        $data = [];
        //子页插入
        for ($i=0; $i<$tempPage;$i++) {
            $startSort++;
            //例如 第1页名称规则
            $data['inner_page_name'] = "第".($i+1)."页";
            $data['mch_id'] = $mid;
            $data['inner_page_tid'] = $inner_id;
            $data['specifications_id'] = $params['specifications_id'];
            $data['spec_info_id'] = $specParam['size_info_id'];
            $data['inner_page_sort'] = $startSort;
            $pagesData['inner_page_dpi'] = $specParam['size_info_dpi'];
        }

        $obj = new SaasInnerTemplatesPages();
        $obj->insert($data);

    }

    /**
     *
     * 内页模板自动添加22p的照片书子页
     * @param $params   提交的参数
     * @param $inner_id 内页模板id
     * @param int $mid  商户id
     */
    public function addPhotoBookPages($params,$inner_id, $mid = 0)
    {
        //根据提交的规格id查询规格具体参数
        $specParam = SaasSizeInfo::where(['size_id'=>$params['specifications_id'],'goods_id'=>ZERO,'size_type'=>GOODS_SIZE_TYPE_INNER])
            ->first();

        $startSort = 0;
       // $is_page = 0;
        $add_pages = config('goods.goods_type')[1];//获取配置的p数
        $is_turn = $specParam['size_is_2faced'];//是否单双页

        if($is_turn==1){ //双页
            $add_pages  = $add_pages/2;
            //$is_page = 1;
        }

        //子页插入
        for ($i=0; $i<$add_pages;$i++) {
            $startSort++;
            if ($specParam['size_is_cross'] == 1) { //自动生成对应的名称,例如 第1-2页名称规则
                $data['inner_page_name'] = "第".(($i*2)+1)."-".(($i*2)+2)."页";
            } else {  //单页的，例如 第1页名称规则
                $data['inner_page_name'] = "第".($i+1)."页";
            }

            $data['inner_page_tid'] = $inner_id;
            $data['specifications_id'] = $specParam['size_id'];
            $data['inner_page_sort'] = $startSort;
            $data['mch_id'] = $mid;
            $data['inner_page_dpi'] = $specParam['size_info_dpi'];
            $data['spec_info_id'] = $specParam['size_info_id'];//规格详情主键id

            $obj = new SaasInnerTemplatesPages();
            $obj->insert($data);
        }

    }

    /**
     *
     * 添加主模板时自动添加22p的照片书子页
     * @param $params   提交的参数
     * @param $inner_id 内页模板id
     * @param int $mid  商户id
     */
    public function addBanlkMainPhotoBookPages($params,$main_id,$mid = 0)
    {
        /**
         * 如果没有提交封面和内页模板时才自动添加子页
         */

        //根据提交的规格id查询规格具体参数
        $specParam = SaasSizeInfo::where(['size_id'=>$params['specifications_id'],'goods_id'=>ZERO])->get()->toArray();

        $data = [];
        $startSort = 0;
        $tempPage = $params['main_temp_photo_count'];
        $ii = 0;
        //dd($specParam);
        foreach ($specParam as $k => $v) {

            if (isset($specParam[$k]['size_type']) && $v['size_type'] == 1) {
                //例如 封面名称规则
                $data[$ii]['main_temp_page_name'] = "封面";
                $data[$ii]['main_temp_page_tid'] = $main_id;
                $data[$ii]['main_temp_page_type'] = 1;
                $data[$ii]['specifications_id'] = $v['size_id'];
                $data[$ii]['main_temp_page_sort'] = $startSort;
                $data[$ii]['mch_id'] = $mid;
                $data[$ii]['main_temp_page_dpi'] = $v['size_info_dpi'];
                $data[$ii]['created_at'] = time();
            }
            if (isset($specParam[$k]['size_type']) && $v['size_type'] == 2) {
                //例如 封面名称规则
                $data[$ii]['main_temp_page_name'] = "封面/封底";
                $data[$ii]['main_temp_page_tid'] = $main_id;
                $data[$ii]['main_temp_page_type'] = 2;
                $data[$ii]['specifications_id'] = $v['size_id'];
                $data[$ii]['main_temp_page_sort'] = $startSort;
                $data[$ii]['mch_id'] = $mid;
                $data[$ii]['main_temp_page_dpi'] = $v['size_info_dpi'];
                $data[$ii]['created_at'] = time();
            }

            if (isset($specParam[$k]['size_type']) && $v['size_type'] == 3) {
                if ($v['size_is_cross'] == 0) { //单页的处理逻辑
                    //内页子页插入
                    for ($i = 0; $i < $tempPage; $i++) {
                        $startSort++;
                        //例如 第1页名称规则
                        $data[$ii]['main_temp_page_name'] = "第" . ($i + 1) . "页";
                        $data[$ii]['main_temp_page_tid'] = $main_id;
                        $data[$ii]['main_temp_page_type'] = 3;
                        $data[$ii]['specifications_id'] = $v['size_id'];
                        $data[$ii]['main_temp_page_sort'] = $startSort;
                        $data[$ii]['mch_id'] = $mid;
                        $data[$ii]['main_temp_page_dpi'] = $v['size_info_dpi'];
                        $data[$ii]['created_at'] = time();
                        $ii++;
                    }
                } else { //双页的逻辑
                    //内页子页插入
                    for ($i = 0; $i < $tempPage / 2; $i++) {
                        $startSort++;
                        //例如 第1-2页名称规则
                        $data[$ii]['main_temp_page_name'] = "第" . (($i * 2) + 1) . "-" . (($i * 2) + 2) . "页";
                        $data[$ii]['main_temp_page_tid'] = $main_id;
                        $data[$ii]['main_temp_page_type'] = 3;
                        $data[$ii]['specifications_id'] = $v['size_id'];
                        $data[$ii]['main_temp_page_sort'] = $startSort;
                        $data[$ii]['mch_id'] = $mid;
                        $data[$ii]['main_temp_page_dpi'] = $v['size_info_dpi'];
                        $data[$ii]['created_at'] = time();
                        $ii++;
                    }
                }
            }

            if (isset($specParam[$k]['size_type']) && $v['size_type'] == 4) {
                //例如 底面名称规则
                $data[$ii]['main_temp_page_name'] = "封底";
                $data[$ii]['main_temp_page_tid'] = $main_id;
                $data[$ii]['main_temp_page_type'] = 4;
                $data[$ii]['specifications_id'] = $v['size_id'];
                $data[$ii]['main_temp_page_sort'] = $startSort + 1;
                $data[$ii]['mch_id'] = $mid;
                $data[$ii]['main_temp_page_dpi'] = $v['size_info_dpi'];
                $data[$ii]['created_at'] = time();
                // $ii++;
            }

            $obj = new SaasMainTemplatesPages();
            $obj->insert($data);

        }

    }

    /**
     *  .Dai 添加
     * 添加主模板时自动添加1页的其他商品类型的子页 （全部默认是单页）
     * @param $params   提交的参数
     * @param $inner_id 内页模板id
     * @param int $mid  商户id
     */
    public function addMainOtherPages($params,$main_id,$cover_id,$inner_id,$mid = 0)
    {
        //根据提交的规格id查询规格具体参数
        $specParam = SaasSizeInfo::where(['size_id'=>$params['specifications_id'],'goods_id'=>ZERO])->get()->toArray();

        $startSort = 0;
        $tempPage = config('goods.goods_type')[3];
        $data = [];
        $ii = 0;
        foreach ($specParam as $k => $v) {

            if (isset($specParam[$k]['size_type']) && $v['size_type'] == 1) {
                //例如 封面名称规则
                $data[$ii]['main_temp_page_name'] = "封面";
                $data[$ii]['main_temp_page_tid'] = $main_id;
                $data[$ii]['main_temp_page_type'] = 1;
                $data[$ii]['specifications_id'] = $v['size_id'];
                $data[$ii]['main_temp_page_sort'] = $startSort;
                $data[$ii]['mch_id'] = $mid;
                $data[$ii]['main_temp_page_dpi'] = $v['size_info_dpi'];
                $data[$ii]['created_at'] = time();
            }
            if (isset($specParam[$k]['size_type']) && $v['size_type'] == 2) {
                //例如 封面名称规则
                $data[$ii]['main_temp_page_name'] = "封面/封底";
                $data[$ii]['main_temp_page_tid'] = $main_id;
                $data[$ii]['main_temp_page_type'] = 2;
                $data[$ii]['specifications_id'] = $v['size_id'];
                $data[$ii]['main_temp_page_sort'] = $startSort;
                $data[$ii]['mch_id'] = $mid;
                $data[$ii]['main_temp_page_dpi'] = $v['size_info_dpi'];
                $data[$ii]['created_at'] = time();
            }

            if (isset($specParam[$k]['size_type']) && $v['size_type'] == 3) {
                if ($v['size_is_cross'] == 0) { //单页的处理逻辑
                    //内页子页插入
                    for ($i = 0; $i < $tempPage; $i++) {
                        $startSort++;
                        //例如 第1页名称规则
                        $data[$ii]['main_temp_page_name'] = "第" . ($i + 1) . "页";
                        $data[$ii]['main_temp_page_tid'] = $main_id;
                        $data[$ii]['main_temp_page_type'] = 3;
                        $data[$ii]['specifications_id'] = $v['size_id'];
                        $data[$ii]['main_temp_page_sort'] = $startSort;
                        $data[$ii]['mch_id'] = $mid;
                        $data[$ii]['main_temp_page_dpi'] = $v['size_info_dpi'];
                        $data[$ii]['created_at'] = time();
                        $ii++;
                    }
                } else { //双页的逻辑
                    //内页子页插入
                    for ($i = 0; $i < $tempPage / 2; $i++) {
                        $startSort++;
                        //例如 第1-2页名称规则
                        $data[$ii]['main_temp_page_name'] = "第" . (($i * 2) + 1) . "-" . (($i * 2) + 2) . "页";
                        $data[$ii]['main_temp_page_tid'] = $main_id;
                        $data[$ii]['main_temp_page_type'] = 3;
                        $data[$ii]['specifications_id'] = $v['size_id'];
                        $data[$ii]['main_temp_page_sort'] = $startSort;
                        $data[$ii]['mch_id'] = $mid;
                        $data[$ii]['main_temp_page_dpi'] = $v['size_info_dpi'];
                        $data[$ii]['created_at'] = time();
                        $ii++;
                    }
                }
            }

            if (isset($specParam[$k]['size_type']) && $v['size_type'] == 4) {
                //例如 底面名称规则
                $data[$ii]['main_temp_page_name'] = "封底";
                $data[$ii]['main_temp_page_tid'] = $main_id;
                $data[$ii]['main_temp_page_type'] = 4;
                $data[$ii]['specifications_id'] = $v['size_id'];
                $data[$ii]['main_temp_page_sort'] = $startSort + 1;
                $data[$ii]['mch_id'] = $mid;
                $data[$ii]['main_temp_page_dpi'] = $v['size_info_dpi'];
                $data[$ii]['created_at'] = time();
                // $ii++;
            }

            $obj = new SaasMainTemplatesPages();
            $obj->insert($data);

        }
       /* //子页插入只有一页
        for ($i=0; $i<$tempPage;$i++) {
            //例如 第1页名称规则
            $data['main_temp_page_name'] = "第".($i+1)."页";
            $data['main_temp_page_tid'] = $main_id;
            $data['specifications_id'] = $specParam['size_id'];
            $data['main_temp_page_sort'] = $i;
            $data['mch_id'] = $mid;
            $obj = new SaasMainTemplatesPages();
            $obj->insert($data);
        }*/

    }

    /**
     *  .Dai 添加
     * 主模板页自动添加两年的台历子页 (没有选择封面和内页)
     * @param $params   提交的参数
     * @param $inner_id 内页模板id
     * @param $cover_id 内页模板id
     * @param int $mid  商户id
     */
    public function addBanlkMainCalendarPages($params,$main_id,$mid = 0)
    {

        $specParam = SaasSizeInfo::where(['size_id'=>$params['specifications_id'],'goods_id'=>ZERO])->get()->toArray();

        $data = [];
        $ii = 0;
        $tempPage = config('goods.goods_type')[2];
        //$tempPage2 = config('template.goods_type')[4];

        foreach ($specParam as $k=>$v) {
            if($v['size_type'] == 1) {
                $data[$ii]['main_temp_page_name'] = $params['main_temp_start_year']."封面";
                $data[$ii]['main_temp_page_type'] = $v['size_type'];
                $data[$ii]['main_temp_page_tid'] = $main_id;
                $data[$ii]['specifications_id'] = $v['size_id'];
                $data[$ii]['main_temp_page_sort'] = $ii;
                $data[$ii]['mch_id'] = $mid;
                $data[$ii]['main_temp_page_dpi'] = $v['size_info_dpi'];
                $data[$ii]['created_at'] = time();
                $ii++;
            } elseif ($v['size_type'] == 3) {
                $tempPage = $tempPage/2-1;
                $data[$ii]['main_temp_page_type'] =$v['size_type'] ;
                if($v['size_is_2faced'] == 0) {
                   $nums = range(1,$tempPage);
                   $month = array_map( function($n){
                        return $n.'月';
                   }, $nums);
                   //次年的封面
                   $nextface[] = ($params['main_temp_start_year']+1)."封面";
                   $new_month = $month;
                   //把两年合并在一起
                   $result_month = array_values(array_merge($month,$nextface,$new_month));

                   foreach ($result_month as $kk=>$vv) {
                       $data[$ii]['main_temp_page_name'] = $vv;
                       $data[$ii]['main_temp_page_tid'] = $main_id;
                       $data[$ii]['specifications_id'] = $v['size_id'];
                       $data[$ii]['main_temp_page_sort'] = $ii;
                       $data[$ii]['mch_id'] = $mid;
                       $data[$ii]['main_temp_page_dpi'] = $v['size_info_dpi'];
                       $data[$ii]['created_at'] = time();
                        if($kk != $tempPage) {
                            $data[$ii]['main_temp_page_type'] = 3;
                        } else {
                            $data[$ii]['main_temp_page_type'] = 1;
                        }

                        $ii++;
                   }

                } else {
                    $nums = range(1,$tempPage*2);
                    $month = array_map( function($v){
                        if($v%2 == 0) {
                            return ceil($v/2).'月正';
                        } else {
                            return ceil($v/2).'月反';
                        }

                    }, $nums);

                    //次年的封面
                    $nextface[] = ($params['main_temp_start_year']+1)."封面";
                    $new_month = $month;
                    //把两年合并在一起
                    $result_month = array_values(array_merge($month,$nextface,$new_month));

                    foreach ($result_month as $kk=>$vv) {
                        $data[$ii]['main_temp_page_name'] = $vv;
                        $data[$ii]['main_temp_page_tid'] = $main_id;
                        $data[$ii]['specifications_id'] = $v['size_id'];
                        $data[$ii]['main_temp_page_sort'] = $ii;
                        $data[$ii]['mch_id'] = $mid;
                        $data[$ii]['main_temp_page_dpi'] = $v['size_info_dpi'];
                        $data[$ii]['created_at'] = time();

                        if($kk != $tempPage*2) {
                            $data[$ii]['main_temp_page_type'] = 3;
                        } else {
                            $data[$ii]['main_temp_page_type'] = 1;
                        }
                        $ii++;
                    }
                }
            }else{
                if($v['size_is_2faced'] == 1){
                    $data[$ii]['main_temp_page_name'] = '封底';
                    $data[$ii]['main_temp_page_type'] = 4;
                    $data[$ii]['main_temp_page_tid'] = $main_id;
                    $data[$ii]['specifications_id'] = $v['size_id'];
                    $data[$ii]['main_temp_page_sort'] = $ii;
                    $data[$ii]['mch_id'] = $mid;
                    $data[$ii]['main_temp_page_dpi'] = $v['size_info_dpi'];
                    $data[$ii]['created_at'] = time();
                    $ii++;
                }
            }
        }
        $obj = new SaasMainTemplatesPages();
        $obj->insert($data);
    }


    /**
     *  .Dai 主模板跨规格复制子页的舞台数据
     *  @param $params_id   提交的已有舞台数据的id
     * @param $params_nid   提交需要更新复制舞台数据的id
     * @remark 根据提交的参数id查询后更新数据
     */
    public function CopyMainChildStage($params_id,$params_nid)
    {
        $temp_pages = collection(MainTemplatesPages::where('fid',$params_id)->select())->toArray();
        $temp_newpages = collection(MainTemplatesPages::where('fid',$params_nid)->select())->toArray();

        $old_pages = [];
        $new_pages = [];

        foreach ($temp_pages as $k=>$v) {
            $old_pages[$v['type']][$k] = $v;
        }
        foreach ($temp_newpages as $k=>$v) {
            $new_pages[$v['type']][$k] = $v;
        }

        foreach ($old_pages as $k=>$v) {
           foreach (array_values($v) as $kk=>$vv) {

                if(isset($new_pages[$k])) { //存在对应类型
                    $new_bbb = array_values($new_pages[$k]);

                    if(isset($new_bbb[$kk])) {  //存在这个下标
                        //复制
                        $old_stage = $vv['stage'];

                        $obj = new MainTemplatesPages();
                        $obj->update(['stage'=>$old_stage], ['id' => $new_bbb[$kk]['id']]);
                        //MainTemplatesPages::update(['stage'=>$old_stage], ['id' => $new_bbb[$kk]['id']]);

                    }
                }
           }
        }


    }

    /**
     * 获取作品的规格信息
     * @param int $spec_id  规格id
     * @param int $goods_id 商品id
     * @return array
     */
    public function getSpecifications($spec_id, $goods_id = 0)
    {
        $standInfo = GoodsSpecifications::where('id', $spec_id)->find()->toArray();

        if(empty($standInfo)) {
            return false;
        }
        //是否存在商品自定义规格
        $params = [];
        if(!empty($goods_id)) {
            $params = Collection(GoodsSpecificationsParam::where('spec_id',$spec_id)
                ->where('goods_id', $goods_id)->select())->toArray();

        }

        if (empty ($params)) {
            $params = Collection(GoodsSpecificationsParam::where('spec_id',$spec_id)
                ->where('goods_id', 0)->select())->toArray();
        }

        foreach ($params as $k => $v) {  //针对台历做特殊处理
            if($standInfo['goods_type'] == 2) {
                if($v['is_turn'] == 0) {  //单面
                    $params[$k]['front_and_back'] = 0;
                } else {  //正反面
                    $params[$k]['front_and_back'] = 1;
                }
                $params[$k]['is_turn'] = 0;

            } else {
                $params[$k]['front_and_back'] = 0;
            }
        }

        return ['basic' =>$standInfo, 'params' =>  $params];

    }
}