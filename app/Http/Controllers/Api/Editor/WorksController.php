<?php
namespace App\Http\Controllers\Api\Editor;

use App\Exceptions\CommonException;
use App\Http\Requests\Api\Works\SaveComlWorkDataRequest;
use App\Http\Requests\Api\Works\SaveWorkDataRequest;
use App\Models\DmsAgentInfo;
use App\Models\SaasDiyAssistant;
use App\Models\SaasMainTemplates;
use App\Models\SaasOrderSyncQueue;
use App\Models\SaasProjectsOrderTemp;
use App\Repositories\DmsAgentInfoRepository;
use App\Repositories\SaasCategoryRepository;
use App\Repositories\SaasDiyAssistantRepository;
use App\Repositories\SaasMainTemplatesRepository;
use App\Repositories\SaasProductsSkuRepository;
use App\Repositories\SaasProjectPageRepository;
use App\Repositories\SaasProjectsOrderTempRepository;
use App\Repositories\SaasProjectsRepository;
use App\Services\Admin;
use App\Services\Areas;
use App\Services\ChanelUser;
use App\Services\Goods\Info;
use App\Services\Helper;
use App\Services\Works\Common;
use App\Services\Works\Sync;
use Illuminate\Http\Request;

/**
 * diy作品相关接口
 *
 * 获取作品信息、保存作品信息、保存缩略图
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/5/9
 */
class WorksController extends BaseController
{
    protected $common = 'common';
    protected $noLog = 'saveWorkData';
    /**
     * 获取作品id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWorkId()
    {
        try {
            $worksId = app(Common::class)->getWorksId();
            $return ['wid'] = strval($worksId);
            return $this->success([$return]);
        } catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }

    }

    public function getWorks(Request $request, SaasProjectsRepository $repoWorks, SaasProjectsOrderTempRepository $worksExt ,
         SaasMainTemplatesRepository $templates,SaasCategoryRepository $repoCate, SaasProjectPageRepository $repoPages)
    {
        try {
            $wid = $request->input('wid');
            if (empty($wid)) {
                Helper::apiThrowException('10022',__FILE__.__LINE__);
            }
            //作品信息
            $worksInfo = $repoWorks->getRow(['prj_id' => $wid]);
            if (empty($worksInfo)) {
                Helper::apiThrowException('10010',__FILE__.__LINE__);
            }

            //作品辅助信息
            $extInfo = $worksExt->getRow(['prj_id'=>$wid]);
            //模板信息
            $tempInfo = $templates->getRow(['main_temp_id' => $worksInfo['prj_tpl_id']]);
            if (empty($tempInfo)) {
                Helper::apiThrowException('50039',__FILE__.__LINE__);
            }

            //模板分类
            $cateInfo = $repoCate->getRow(['cate_id' =>$tempInfo['main_temp_theme_id']]);

            //子页信息
            $pagesInfo = $repoPages->getRows(['prj_id' => $wid], 'prj_page_id', 'asc');
            if (empty($pagesInfo)) {
                Helper::apiThrowException('50039',__FILE__.__LINE__);
            }

            //获取舞台数据
            $chaInfo = app(ChanelUser::class)->getChanelInfo(['cha_id' =>$worksInfo['cha_id']]);
            if (empty($chaInfo)) {
                $flag = $this->common;
            } else {
                $flag = $chaInfo['short_name'];
            }
            $nameSpace = "App\\Services\\Works\\";
            $stage = app($nameSpace.ucfirst($flag))->getWorksStage($wid);


            //作品额外信息
            $extra = [];
            if (!empty($extInfo)) {
                $extra['user_name'] = $extInfo['prj_outer_account'];
                $extra['order_id'] = $extInfo['order_no']??'';
                $extra['tel_num'] = $extInfo['prj_rcv_phone']??'';
                $extra['remark'] = $extInfo['remark']??'';
                $extra['full_name'] = $extInfo['prj_rcv_user']??'';
                $extra['address_detail'] = $extInfo['prj_rcv_address']??'';

                $pinfo = app(Areas::class)->getAreaById($extInfo['prj_province']);
                $cinfo = app(Areas::class)->getAreaById($extInfo['prj_city']);
                $dinfo = app(Areas::class)->getAreaById($extInfo['prj_district']);
                $arrArea = [
                    'p' => $pinfo,
                    'c' => $cinfo,
                    'd' => $dinfo,
                ];
                $extra['location_id_str'] = json_encode(array_values($arrArea));

                //如果省市区全部为空返回空字符串
                if (empty($pinfo) && empty($cinfo) && empty($dinfo)) {
                    $extra['location_id_str'] = '';
                }

                $extra['buy_quantity'] = $extInfo['ord_quantity'];

            }

            $sizeInfo = app(Info::class)->getGoodSizeInfo($tempInfo['specifications_id'], $worksInfo['prod_id']);

            $sizeItems =  $this->formatSizeInfo($sizeInfo);

            //格式化数据返回
            $tpUrl = config('template.material.upload.tp_url');
            $return['thumb_url'] = $tpUrl.$worksInfo['thumb'];
            $return['work_name'] = $worksInfo['prj_name'];
            $return['goods_id'] = $worksInfo['prod_id'];
            $return['product_id'] = $worksInfo['sku_id'];
            $return['template_id'] = $worksInfo['prj_tpl_id'];
            $return['template_name'] = $tempInfo['main_temp_name'];
            $return['theme_id'] = $worksInfo['theme_id'];
            $return['theme_name'] = $cateInfo['cate_name'];
            $return['inpage_count'] = $worksInfo['prj_page_num'];
            $return['status'] = $worksInfo['prj_status'];
            $return['thickness'] = empty($worksInfo['prj_thickness']) ? 0 : $worksInfo['prj_thickness'];
            $return['agent_id'] = $extInfo['user_id'];
            $return['sp_id'] = $worksInfo['mch_id'];
            $return['uid'] = $worksInfo['user_id'];
            $return['work_extra_info'] = $extra;

            $return['pages'] = [];

            $return['sub_system'] = $chaInfo['short_name'];
            $return['pages'] = [];
            // $return['inpage_count'] = $works_info['works_name'];

            //子页数据
            foreach ($pagesInfo as $k => $v) {
                $return['pages'][$k]['id'] = $v['prj_page_id'];
                $return['pages'][$k]['type'] = $v['prj_page_type'] == 1 ? 0 : 1;
                $return['pages'][$k]['name'] = $v['prj_page_name'];
                $return['pages'][$k]['tid'] = $v['main_temp_page_id'];
                $return['pages'][$k]['mask_count'] = $v['prj_page_photo_count'];
                $return['pages'][$k]['index'] = $v['prj_page_sort'];
                $return['pages'][$k]['stage_content'] = $stage[$v['prj_page_sort']];
            }
            $return['size_item'] = $sizeItems;
            return $this->success([$return]);

        }catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    /**
     * 保存作品数据
     * @param SaveWorkDataRequest $request
     * @param SaasMainTemplatesRepository $templates
     * @param SaasProductsSkuRepository $repoSku
     * @param SaasProjectsRepository $repoWorks
     * @param SaasProjectPageRepository $repoPages
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveWorkData(SaveWorkDataRequest $request, SaasMainTemplatesRepository $templates,
         SaasProductsSkuRepository $repoSku, SaasProjectsRepository $repoWorks, SaasProjectPageRepository $repoPages)
    {
        try {
            $params = $request->all();
            $tempId = $params['tsid'];
            $pages = $params['pages'];
            $skuId = $params['pid'];
//            $pages = $this->test_stage();
            //作品检查是否已提交，如果提交不再给操作提交
            $projInfo = $repoWorks->getRow(['prj_id'=>$params['wid'],'prj_status'=>ONE]);
           /* $projModel = $repoWorks->getModel();
            $projInfo = $projModel->where(['prj_id'=>$params['wid']])
                ->where(function ($query) {
                    $query->where('prj_status', 2)
                        ->orWhere('prj_status', '=',3);
                })
                ->first();*/
            if (empty($projInfo)) {
                Helper::apiThrowException('60048',__FILE__.__LINE__);
//                $cookie = $_COOKIE;
//                $token = $cookie['laravel_session'] ?? '';
//                $isAdmin = app(Admin::class)->isAdministrator($token);
//
//                if ($params['is_submit'] == 1) {
//                    Helper::apiThrowException('60048',__FILE__.__LINE__);
//                } else {  //在作品状态为2,3的情况下保存，判定为提交操作
//                    if (empty($isAdmin)) {  //非管理员
//                        Helper::apiThrowException('60048',__FILE__.__LINE__);
//                    }
//                    $params['is_submit'] = 1;
//                }


            }

            //模板检查
            $tempInfo = $templates->getRow(['main_temp_id' => $tempId]);
            if (empty($tempInfo)) {
                Helper::apiThrowException('60030',__FILE__.__LINE__);
            }
            //子页数据检查
            $arrPages = json_decode($pages, true);
            if (empty($arrPages)) {
                Helper::apiThrowException('60031',__FILE__.__LINE__);
            }
            //获取货品数据
            $skuInfo = $repoSku->getRow(['prod_sku_id' => $skuId]);
            if (empty($skuInfo)) {
                Helper::apiThrowException('60032',__FILE__.__LINE__);
            }
            \DB::beginTransaction();
            $source = isset($params['sub_system']) ? $params['sub_system'] : $this->common;
            //获取调用的服务类
            $servicesWorks = $this->getWorksServices($source);
            $params['theme_id'] = $tempInfo['main_temp_theme_id'];
            $params['pages'] = $arrPages;
            $servicesWorks->worksGetP($arrPages);
            $servicesWorks->formatParams($params);

            //保存作品主表
            $works_id = $servicesWorks->saveWorksMain();

            //保存作品子表
            $arrStage = $servicesWorks->saveWorksChild($works_id,$arrPages);

            $jsonStage = json_encode($arrStage);
            //查询作品信息
            $worksInfo = $repoWorks->getRow(['prj_id' => $works_id]);

            $saveType = empty($worksInfo['is_mongo_save'])?1 : 2;
            $is_save = $servicesWorks->saveDataByMongo($works_id ,$arrStage, $saveType);

            if($is_save) {  //保存成功
                $repoWorks->update(['prj_id' => $works_id], ['is_mongo_save' => 1]);
            } else {
                Helper::EasyThrowException('60034', __FILE__.__LINE__);
            }

            //填充一些辅助表数据
            if (!empty($params['work_extra_info'])) {
                $servicesWorks->saveExtraInfo();
            }

            //一些特殊需求的处理
            if(isset($params['flag'])) {
                //作品保存后处理
                $servicesWorks->afterSave($params['flag']);
            }


            //判断是否需要插入队列
            if ($params['is_submit'] == 1){
                if (!empty($params['work_extra_info'])) {
                    $worksExtra = json_decode($params['work_extra_info'], true);
                    $order_no = $worksExtra['order_id'];
                    $workCount = count($arrPages);
                    //作品数量判断
                    $res = $this->checkProjectNum($order_no,$params['agent_id'],$skuId,$workCount);
                    /*if ($res['code'] == 0)
                    {
                        //订单号信息不存在
                        Helper::EasyThrowException('60038', __FILE__.__LINE__);
                    }*/
                    if ($res['code'] == 6)
                    {
                        //该作品p数异常,无法制作 针对冲印类商品被动修改了p数属性
                        Helper::EasyThrowException('60045', __FILE__.__LINE__);
                    }
                    if ($res['code'] == 7)
                    {
                        //该作品p数异常,无法制作 针对冲印类商品被动修改了p数属性
                        Helper::EasyThrowException('60046', __FILE__.__LINE__);
                    }
                    if ($res['code'] == 8)
                    {
                        //该作品p数异常,无法制作 针对冲印类商品被动修改了p数属性
                        Helper::EasyThrowException('60047', __FILE__.__LINE__);
                    }
                    if ($res['code'] == 5)
                    {
                        //作品数量超过该货品的作品数量
                        Helper::EasyThrowException('60044', __FILE__.__LINE__);
                    }
                    if ($res['code'] == 2)
                    {
                        //作品数量超过订单作品数量
                        Helper::EasyThrowException('60039', __FILE__.__LINE__);
                    }
                    if ($res['code'] == 3)
                    {
                        //货号异常，请确认货号是否存在或者该商品类目是否存在
                        Helper::EasyThrowException('60040', __FILE__.__LINE__);
                    }

                    $syncService = app(Sync::class);
                    $syncService->saveOrderSyncQueue($order_no,$params['agent_id']);
                }
                //更新主模板使用次数字段，每提交一次算热度1次 by dai
                $new_use_times = $tempInfo['main_temp_use_times']+1;
                $templates->update(['main_temp_id' => $tempId], ['main_temp_use_times' => $new_use_times]);
            }
            $worksPages = $repoPages->getRows(['prj_id'=>  $works_id], 'prj_page_id', 'asc');

            $return = [];

            foreach($worksPages as $k=>$v){
                $return['page_ids'][$k]['id'] = $v['prj_page_id'];
                $return['page_ids'][$k]['pid'] = $v['relation_id'];
                $return['page_ids'][$k]['tid'] = $v['main_temp_page_id'];
            }

            //添加操作日志
            if ($params['is_submit'] == 0)
            {
                //判断是否插入日志
                $workLog = [
                    'user_id'    => $params['agent_id'],
                    'works_id'   => $works_id,
                    'action'     => "保存作品",
                    'note'       => "",
                    'createtime' => time(),
                    'operator'   => "",
                ];

            } else {
                $workLog = [
                    'user_id'    => $params['agent_id'],
                    'works_id'   => $works_id,
                    'action'     => "提交作品",
                    'note'       => "",
                    'createtime' => time(),
                    'operator'   => "",
                ];
            }
            $servicesWorks->insertActLog('diy_works_log',$workLog);

            \DB::commit();
            return $this->success([$return]);
        } catch (CommonException $e) {
            \DB::rollBack();
            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    /**
     * 保存作品缩略图
     * @param Request $request
     * @param SaasProjectPageRepository $pages
     * @return \Illuminate\Http\JsonResponse
     */
    public function SaveWorkThumb(Request $request, SaasProjectPageRepository $pages, SaasProjectsRepository $project)
    {
        $id = $request->input('id');
        if (empty ($id)) {
            Helper::apiThrowException('10022', __FILE__.__LINE__);
        }
        $thumb = $request->input('thumb');


        if(isset($thumb)) {

            list(, $img_body) = explode(',', $thumb);

            $stream = base64_decode($img_body);
        } else {
            $stream = file_get_contents("php://input");
        }

        $ml = date('Ymd');
        $path =  config("template.material.upload.dir").'/'.config("template.material.upload.works_view_pic").'/'.$ml.'/'.$id;
        $ret = Helper::saveImageStream($path, $stream);
        if (!empty($ret)) {
            $url = config('template.material.upload.tp_url').'/'.config("template.material.upload.works_view_pic").'/'.$ml.'/'.$id. '/' . $ret;
            $pages->update(['prj_page_id' => $id], ['prj_page_thumb' => $url]);
            $info = $pages->getrow(['prj_page_id' => $id]);
            if ($info['prj_page_type'] == 1) {
                $project->update(['prj_id'=>$info['prj_id']], ['prj_image' => $url]);
            }

            return $this->success([]);
        }else {
            return $this->error('40007', '保存预览图失败');
        }

    }

    /**
     * 根据渠道判断所要使用的services
     * @param $source
     * @return object
     */
    private function getWorksServices($source)
    {
        //通过简称获取渠道
        $chaInfo = app(ChanelUser::class)->getChanelInfo(['short_name' =>$source ]);
        if (empty($chaInfo)) {
            $flag = $this->common;
        } else {
            $flag = $source;
        }

        $nameSpace = "App\\Services\\Works\\";
        //var_dump($nameSpace.$flag);exit;
        return app($nameSpace.ucfirst($flag));
    }



    private function test_stage(){
        $stage = json_encode(array(array("element_type"=>"photo", "id"=>"key334444", "x"=>61, "y"=>61,"width"=>202, "height"=>202),array("element_type"=>"photo", "id"=>"key2", "x"=>161, "y"=>161,"width"=>302, "height"=>302)));

        $pages = array(
            array('id'=>1,'name' => 'aaa1', 'pid'=>'111','spread'=>0,'type'=>0,'fid'=>11,'tid'=>40,'index'=>1,'mask_count'=>21,'stage_content'=>$stage),
            array('id'=>2,'name' => 'aaa2','pid'=>'112','spread'=>1,'type'=>1,'fid'=>15,'tid'=>41,'index'=>2,'mask_count'=>31,'stage_content'=>$stage),
            array('id'=>3,'name' => 'aaa3','pid'=>'113','spread'=>1,'type'=>1,'fid'=>26,'tid'=>42,'index'=>3,'mask_count'=>3,'stage_content'=>$stage),
            array('id'=>4,'name' => 'aaa4','pid'=>'114','spread'=>1,'type'=>1,'fid'=>27,'tid'=>43,'index'=>5,'mask_count'=>4,'stage_content'=>$stage),
            array('id'=>5,'name' => 'aaa5','pid'=>'115','spread'=>1,'type'=>1,'fid'=>28,'tid'=>44,'index'=>4,'mask_count'=>5,'stage_content'=>$stage),
            array('id'=>6,'name' => 'aaa6','pid'=>'116','spread'=>1,'type'=>1,'fid'=>29,'tid'=>45,'index'=>6,'mask_count'=>7,'stage_content'=>$stage),
            array('id'=>7,'name' => 'aaa7','pid'=>'117','spread'=>1,'type'=>1,'fid'=>30,'tid'=>46,'index'=>7,'mask_count'=>3,'stage_content'=>$stage),
            array('id'=>8,'name' => 'aaa8','name' => 'aaa','pid'=>'118','spread'=>1,'type'=>1,'fid'=>31,'tid'=>47,'index'=>8,'mask_count'=>3,'stage_content'=>$stage),
            array('id'=>9,'name' => 'aaa9','pid'=>'119','spread'=>1,'type'=>1,'fid'=>32,'tid'=>48,'index'=>9,'mask_count'=>3,'stage_content'=>$stage),
            array('id'=>10,'name' => 'aaa10','pid'=>'120','spread'=>1,'type'=>1,'fid'=>33,'tid'=>49,'index'=>10,'mask_count'=>3,'stage_content'=>$stage),
            array('id'=>11,'name' => 'aaa11','pid'=>'121','spread'=>1,'type'=>1,'fid'=>34,'tid'=>50,'index'=>11,'mask_count'=>3,'stage_content'=>$stage),
            //array('id'=>12,'name' => 'aaa12','pid'=>'122','spread'=>1,'type'=>1,'fid'=>35,'tid'=>51,'index'=>12,'mask_count'=>3,'stage_content'=>$stage),
            array('id'=>13,'name' => 'aaa13','pid'=>'123','spread'=>1,'type'=>1,'fid'=>36,'tid'=>52,'index'=>13,'mask_count'=>3,'stage_content'=>$stage),
            array('id'=>0,'name' => 'aaa14','pid'=>'124','spread'=>1,'type'=>1,'fid'=>36,'tid'=>52,'index'=>13,'mask_count'=>3,'stage_content'=>$stage)
        );

        return json_encode($pages);
    }


    //提交作品检查作品数量
    public function checkProjectNum($order_no,$agent_id,$sku_id=0,$workCount=0)
    {
        $agentInfoModel = app(DmsAgentInfo::class);
        $helper = app(Helper::class);
        $res = $helper->getSyncOrderIndo($order_no,$agent_id);
        //获取所属商户id
        $mch_id = $agentInfoModel->where(['agent_info_id' => $agent_id])->value('mch_id');

        /*$res = $this->getDemoData();
        $res = json_decode($res,true);*/

        if ($res['success'] == 'true' && isset($res['result']['trade']))
        {
            //成功获取订单
            $orderArr = $res['result']['trade']['orders']['order'];
            //作品数量
            $num = 0;
            $skuRepository = app(SaasProductsSkuRepository::class);
            $diyAssistantRepository = app(SaasDiyAssistantRepository::class);
            $diyAssistant = app(SaasDiyAssistant::class);

            $sync = app(Sync::class);

            $error = [];
            foreach ($orderArr as $k => $v)
            {
                if (isset($v['outer_sku_id']))
                {
                    //获取当前商品类型（实物不操作同步队列）
                    $prod_type_res = $skuRepository->getGoodstype($v['outer_sku_id'],$mch_id);
                    if ($prod_type_res['code'] == 1 )
                    {
                        //判断是否为冲印
                        if ($prod_type_res['third_type'] == GOODS_DIY_CATEGORY_SINGLE && !empty($workCount) && $sku_id == $prod_type_res['sku_id'])
                        {
                            $is_exist = 0;//是否存在该p数的冲印商品
                            $s_prj_num =0;//该冲印商品可以做的作品数量
                            //为冲印商品
                            $singleArr = $diyAssistant->where(['order_no' => $order_no])->select('sku_id','sku_sn','prod_num')->get()->toArray();
                            foreach ($singleArr as $s_k => $s_v)
                            {
                                //判断淘宝货号是否带有冲印标识
                                $firstStr = strstr($s_v['sku_sn'],SINGLE_SN);
                                //判断是否有冲印商品的货号标识'-'
                                if ($firstStr){
                                    //含有冲印货号标识的商品，截取货号标识后面的字符串作为p数存进数组
                                    $p_str = substr($s_v['sku_sn'],strripos($s_v['sku_sn'],SINGLE_SN)+1);
                                    if ($p_str == $workCount && $s_v['sku_id'] == $sku_id){
                                        $is_exist = 1; //该p数的冲印商品可以正常制作
                                        $s_prj_num = $s_v['prod_num']; //该p数的商品可以做几本
                                        break;
                                    }
                                }
                            }
                            if ($is_exist)
                            {
                                //存在
                                //获取该作品已制作了多少个
                                $single_proj_num = $sync->getPeddingProNum($order_no,[WORKS_DIY_STATUS_WAIT_CONFIRM,WORKS_DIY_STATUS_ORDER],$sku_id,$workCount);
                                //比较数量
                                if (!empty($single_proj_num) && !empty($s_prj_num)){
                                    if ($single_proj_num>$s_prj_num){
                                        //该冲印商品此p数的制作数量已达到最大值，无法制作
                                        $return = [
                                            'code' => 8,
                                            'msg'  => "该冲印商品此p数的制作数量已达到最大值，无法制作"
                                        ];
                                        return $return;
                                    }
                                }else{
                                    //该订单的冲印商品p数出错
                                    $return = [
                                        'code' => 7,
                                        'msg'  => "该商品作品p数错误，无法制作"
                                    ];
                                    return $return;
                                }
                            }else{
                                //不存在，则视为该p数无法制作(恶意修改p数)
                                $return = [
                                    'code' => 6,
                                    'msg'  => "该商品作品p数错误，无法制作"
                                ];
                                return $return;
                            }
                        }
                        $good_type_arr[] = $prod_type_res['goods_type'];
                        if ($prod_type_res['goods_type']==GOODS_MAIN_CATEGORY_PRINTER)
                        {
                            //印品统计作品数量
                            $num += $v['num'];
                            if (!empty($sku_id) && $sku_id == $prod_type_res['sku_id']){
                                if ($prod_type_res['third_type'] == GOODS_DIY_CATEGORY_SINGLE && !empty($workCount)){
                                    //冲印类
                                    $sku_num = $sync->getPeddingProNum($order_no,[WORKS_DIY_STATUS_WAIT_CONFIRM,WORKS_DIY_STATUS_ORDER],$sku_id,$workCount);
                                }else{
                                    $sku_num = $sync->getPeddingProNum($order_no,[WORKS_DIY_STATUS_WAIT_CONFIRM,WORKS_DIY_STATUS_ORDER],$sku_id);
                                }
                                //获取该sku_id可做的作品数量
                                $can_do_num = $diyAssistant->where(['order_no' => $order_no,'sku_id' => $sku_id,'sku_sn'=>$v['outer_sku_id']])->value('prod_num');
                                if (empty($can_do_num)){
                                    $can_do_num = $v['num'];
                                }
                                //比较作品数量
                                if ($sku_num <= $can_do_num){
                                    //正常情况，作品数量小于订单数量（还没做完），等于订单数量(该货品作品已做完)
                                    $return = [
                                        'code' => 1,
                                        'msg'  => 'ok'
                                    ];
                                    return $return;
                                }elseif ($sku_num>$can_do_num){
                                    $return = [
                                        'code' => 5,
                                        'msg'  => "该商品作品数量已达到最大值,请先删除原有作品再进行制作"
                                    ];
                                    return $return;
                                }
                            }
                        }
                    }else{
                        $error[] = $prod_type_res['msg'];
                    }
                }
            }
            if (!empty($error)){
                $return = [
                    'code' => 3,
                    'msg'  => "货号异常，请确认货号是否存在或者该商品类目是否存在"
                ];
                return $return;
            }


            //获取已提交作品子表数量
            $projectOrderTemp = app(SaasProjectsOrderTemp::class);
            $sync = app(Sync::class);
            $proj_num = $sync->getPeddingProNum($order_no,[WORKS_DIY_STATUS_WAIT_CONFIRM,WORKS_DIY_STATUS_ORDER]);
            /*$proj_num = $projectOrderTemp
                ->where(['saas_projects_order_temp.order_no' => $order_no])
                ->leftJoin('saas_projects', 'saas_projects_order_temp.prj_id', '=', 'saas_projects.prj_id')
                ->whereIn('saas_projects.prj_status',[WORKS_DIY_STATUS_WAIT_CONFIRM,WORKS_DIY_STATUS_ORDER])
                ->whereNull('saas_projects.deleted_at')
                ->whereNull('saas_projects_order_temp.deleted_at')
                ->select('saas_projects_order_temp.prj_id','saas_projects.prj_status','saas_projects_order_temp.ord_quantity')
                ->sum('saas_projects_order_temp.ord_quantity');*/

            //比较数量
            if ($proj_num!=0 && $proj_num>$num)
            {
                $return = [
                    'code' => 2,
                    'msg'  => "该订单作品数量已达到最大值,请先删除原有作品再进行制作"
                ];
                return $return;
            }

            if ($proj_num!=0 && $proj_num == $num)
            {
                $return = [
                    'code' => 4,
                    'msg'  => "订单数量达到最大值，可同步了"
                ];
                return $return;
            }


            //都为实物商品或者作品数量还没超过订单作品数量
            $return = [
                'code' => 1,
                'msg'  => 'ok'
            ];
            return $return;

        }else{
            $return = [
                'code' => 0,
                'msg'  => "订单号不存在"
            ];

            return $return;
        }
    }

    //商业印刷作品保存
    public function SaveComlWorkData(SaveComlWorkDataRequest $request,DmsAgentInfoRepository $dmsAgentRepo,SaasProjectsRepository $repoWorks, SaasProjectsOrderTempRepository $repoProjTemp)
    {
        try {
            $params    = $request->all();
            $worksId   = $params['works_id'];
            $origData  = base64_decode($params['orig_data']);//会员id-商品id-货品id
            if(empty($origData)){
                return $this->error('44001','参数不能为空');
            }
            $origArr = explode('-',$origData);
           
            if(empty($origArr[0]) || empty($origArr[1]) || empty($origArr[2])){
                return $this->error('44002','原样字符串不匹配');
            }
            $agentInfo = $dmsAgentRepo->getById(['agent_info_id' => $origArr[0]]);
            if(!isset($agentInfo[0]['agent_info_id'])){
                return $this->error('44003','该分销商信息不存在');
            }

            //组装数据
            $data['mch_id'] = $agentInfo[0]['mch_id'];
            $data['cha_id'] = $agentInfo[0]['agent_type'];
            $data['user_id'] = $origArr[0];
            $data['prod_id'] = $origArr[1];
            $data['sku_id'] = $origArr[2];
            $data['prj_name'] = $params['works_name'];
            $data['prj_status'] = $params['is_submit']==1 ? 2 :1;
            $data['prj_tpl_id'] = $params['temp_id'];
            $data['is_mobile'] = $params['is_mobile'];
            $data['coml_works_id'] = $worksId;
            $data['submit_time'] = time();

            //操作作品主表
            $worksInfo = $repoWorks->getRow(['coml_works_id' => $worksId]);

            \DB::beginTransaction();
            if (empty($worksInfo)) {//添加
                $data['created_at'] = time();
                $projSn = app(Common::class)->createWorksNo();
                $data['prj_sn'] = $projSn;
                $repoWorks->insert($data);

            }else{ //更新
                $data['updated_at'] = time();
                $repoWorks->update(['coml_works_id'=>$worksId],$data);
            }

            //操作附表
            if(!empty($params['work_extra'])){
                //当主表存在记录时才操作临时订购表
                $ProTemp = $repoProjTemp->getRow(['prj_id'=>$worksInfo['prj_id']]);

                if(empty($ProTemp)){
                    $workExtraArr = json_decode($params['work_extra'],true); //json转数组

                    if(empty($workExtraArr['recevier']) || empty($workExtraArr['mobile']) || empty($workExtraArr['location_str']) || empty($workExtraArr['address'])){
                        return $this->error('44004','请完善收货人信息');
                    }

                    $locationArr = explode('|',$workExtraArr['location_str']);//字符串转数组
                    if(empty($locationArr[0]) || empty($locationArr[1]) || empty($locationArr[2])){
                        return $this->error('44005','收货地址不完整');
                    }

                    $tempData['user_id']         = $worksInfo['user_id'];
                    $tempData['prj_id']          = $worksInfo['prj_id'];
                    $tempData['prj_rcv_user']    = $workExtraArr['recevier'];
                    $tempData['prj_rcv_phone']   = $workExtraArr['mobile'];
                    $tempData['prj_province']    = $locationArr[0];
                    $tempData['prj_city']        = $locationArr[1];
                    $tempData['prj_district']    = $locationArr[2];
                    $tempData['prj_rcv_address'] = $workExtraArr['address'];
                    $tempData['ord_quantity']    = $workExtraArr['quantity'];
                    $tempData['created_at']      = time();
                    $repoProjTemp->insert($tempData);
                }
            }
            \DB::commit();

            return $this->success([]);
        } catch (CommonException $e) {
            \DB::rollBack();
            return $this->error($e->getCode(), $e->getMessage());
        }
    }




}