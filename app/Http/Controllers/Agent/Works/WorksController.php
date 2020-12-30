<?php
namespace App\Http\Controllers\Agent\Works;

use App\Exceptions\CommonException;
use App\Http\Controllers\Agent\BaseController;
use App\Presenters\CommonPresenter;
use App\Repositories\DmsAgentInfoRepository;
use App\Repositories\OmsAgentDeployRepository;
use App\Repositories\SaasAreasRepository;
use App\Repositories\SaasCartRepository;
use App\Repositories\SaasCustomerBalanceLogRepository;
use App\Repositories\SaasDeliveryRepository;
use App\Repositories\SaasDeliveryTemplateRepository;
use App\Repositories\SaasOrderProductsRepository;
use App\Repositories\SaasOrdersRepository;
use App\Repositories\SaasPaymentRepository;
use App\Repositories\SaasProductsPrintRepository;
use App\Repositories\SaasProductsRelationAttrRepository;
use App\Repositories\SaasProductsRepository;
use App\Repositories\SaasProjectPageRepository;
use App\Repositories\SaasProjectsOrderTempRepository;
use App\Repositories\SaasProjectsRepository;
use App\Repositories\SaasSalesChanelRepository;
use App\Repositories\SaasSyncOrderConfRepository;
use App\Services\alipay\AlipayNotify;
use App\Services\Common\Mongo;
use App\Services\Goods\Info;
use App\Services\Goods\Price;
use App\Services\Helper;
use App\Services\Logistics;
use App\Services\Orders\OrdersEntity;
use App\Services\Orders\SyncOrdersEntity;
use App\Services\Outer\TbApi;
use App\Services\Works\Sync;
use App\Services\Works\WorksAbstract;
use Illuminate\Http\Request;
use Yansongda\LaravelPay\Facades\Pay;

/**
 * 消息管理控制器
 *
 * 实现列表查看功能
 * @author: daiyd
 * @version: 1.0
 * @date: 2019/8/6
 */
class WorksController extends BaseController
{

    protected $viewPath = 'agent.works';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块

    public function __construct(SaasProjectsRepository $projectsRepository,SaasOrderProductsRepository $orderProductsRepository,
                                Info $info,Price $price,DmsAgentInfoRepository $dmsAgentInfoRepository,Logistics $logistics,
                                SaasPaymentRepository $paymentRepository,SaasProductsRelationAttrRepository $relationAttrRepository,
                                SaasDeliveryRepository $deliveryRepository,SaasDeliveryTemplateRepository $templateRepository,
                                SaasCustomerBalanceLogRepository $balanceLogRepository,OrdersEntity $ordersEntity,WorksAbstract $worksAbstract,
                                OmsAgentDeployRepository $omsAgentDeployRepository,SaasCartRepository $cartRepository,
                                SaasSyncOrderConfRepository $syncOrderConfRepository,Helper $helper,SaasOrdersRepository $ordersRepository,
                                SaasAreasRepository $areasRepository,SyncOrdersEntity $syncOrdersEntity,SaasProductsPrintRepository $productsPrintRepository,
                                SaasProjectsOrderTempRepository $projectsOrderTempRepository,SaasProductsRepository $productsRepository,
                                SaasProjectPageRepository $projectPageRepository)
    {
        parent::__construct();
        $this->merchantID = empty(session('admin')) == false ? session('admin')['mch_id'] : ' ';
        $this->agentID = empty(session('admin')) == false ? session('admin')['agent_info_id'] : ' ';
        $this->repositories = $projectsRepository;
        $this->info = $info;
        $this->price = $price;
        $this->helper = $helper;
        $this->logistics = $logistics;
        $this->dmsAgentInfoRepository = $dmsAgentInfoRepository;
        $this->paymentRepository = $paymentRepository;
        $this->relationAttrRepository = $relationAttrRepository;
        $this->deliveryRepository = $deliveryRepository;
        $this->templateRepository = $templateRepository;
        $this->balanceLogRepository = $balanceLogRepository;
        $this->ordersEntity = $ordersEntity;
        $this->syncOrdersEntity = $syncOrdersEntity;
        $this->worksAbstract = $worksAbstract;
        $this->cartRepository = $cartRepository;
        $this->ordersRepository = $ordersRepository;
        $this->areasRepository = $areasRepository;
        $this->orderProductsRepository = $orderProductsRepository;
        $this->projectsOrderTempRepository = $projectsOrderTempRepository;
        $this->productsRepository = $productsRepository;
        $this->productsPrintRepository = $productsPrintRepository;
        $this->projectPageRepository = $projectPageRepository;
        $this->prjStatus = config("agent.project_status");
        $this->shopping_car = $omsAgentDeployRepository->getDeployInfo($this->merchantID)['shopping_car'];
        $this->sync_sdk = $syncOrderConfRepository->getList(['agent_id'=>$this->agentID])->toArray();
        if(!$this->sync_sdk){
            $this->sync_sdk = ZERO;
        }else{
            $this->sync_sdk = ONE;
        }
        //订单标签
        $this->prjLabel = [
            '1'=>'ID不对',
            '2'=>'还差一本',
            '3'=>'还差作品',
            '4'=>'待退款重拍',
            '5'=>'待确认：数量',
            '6'=>'待确认：发货地址',
            '7'=>'待确认：作品',
            '8'=>'已确认：请下单',
            '9'=>'未付款或无订单',
            '10'=>'订单有部分退款',
            '11'=>'作品与订单不符合'
        ];

        if (session('prj_status')) {
            $this->firstType = session('prj_status');
        }else{
            $this->firstType = "all";
        }
    }

    //列表展示页面
    public function index()
    {
        $prjLabel = Helper::getChooseSelectData($this->prjLabel);
        $defaultKey = $this->firstType;
        $statusCount = $this->statusCount();
        return view("agent.works.index",['statusCount'=>$statusCount,'prjLabel'=>$prjLabel,'defaultKey'=>$defaultKey,'sync_sdk'=>$this->sync_sdk]);
    }

    //各状态作品数量统计
    public function statusCount()
    {
        $project_status = ['all'=>'全部作品']+$this->prjStatus;
        $statusCount = $this->repositories->projectStatusCount();
        foreach ($project_status as $key=>$value){
            if($key=='all'){
                $project_status['all'] = $value."(".$statusCount[0].")";
            }else{
                $project_status[$key] = $value."(".$statusCount[$key].")";
            }
        }
        if(\request()->ajax()){
            return $this->jsonSuccess($project_status);
        }
        else{
            return $project_status;
        }
    }


    //ajax方式获取列表
    public function table(Request $request)
    {
        try{
            $inputs = $request->all();
            if(isset($inputs['status'])){
                $inputs['prj_status'] = $inputs['status'];
                unset($inputs['status']);
            }

            //获取分类标识
            if (session('prj_status')&&!isset($inputs['prj_status'])){
                //情况1：页面刷新时
                $inputs['prj_status'] = session('prj_status');
            }else{
                $inputs['prj_status'] = $inputs['prj_status']??$this->firstType;
                if($inputs['prj_status']=="all"){
                    $inputs['prj_status'] = null;
                }
            }

            //全部作品和制作中按id倒序排序
            if($inputs['prj_status']==null || $inputs['prj_status']==WORKS_DIY_STATUS_MAKING){
                $order = "prj_id desc";
            }
            //已下单和回收站按更新时间倒序排序
            else if($inputs['prj_status']==WORKS_DIY_STATUS_ORDER || $inputs['prj_status']==WORKS_DIY_STATUS_DELETE){
                $order = "updated_at desc";
            }
            //待确认按作品提交时间倒序排序
            else if($inputs['prj_status']==WORKS_DIY_STATUS_WAIT_CONFIRM){
                $order = "submit_time desc";
            }

            session(['prj_status' => $inputs['prj_status']]);

            $inputs['user_id'] = session('admin')['agent_info_id'];

            $list = $this->repositories->getTableList($inputs,$order)->toArray();
            //获取特殊分类的IDS 冲印和摆台&插画&框画
            $specialIds = config('goods.special_ids');
            foreach ($list['data'] as $k=>$v){
                $prod_info = $this->productsRepository->getProductInfo($this->merchantID,$v['prod_id']);
                //分类id
                $prod_cate_uid = $prod_info[0]['prod_cate_uid'];
                if(in_array($prod_cate_uid,$specialIds)){
                    //如果是冲印类的
                    if($prod_cate_uid==$specialIds['single']){
                        $list['data'][$k]['url'] = "http://".config("app.agent_url")."/printer/index.html?w=".$v['prj_id']."&sp=".$this->merchantID;
                    }else{
                        $list['data'][$k]['url'] = "http://".config("app.agent_url")."/ds/ed.html?w=".$v['prj_id']."&sp=".$this->merchantID;
                    }
                }else{
                    $list['data'][$k]['url'] = "http://".config("app.agent_url")."/ds/ed.html?w=".$v['prj_id']."&sp=".$this->merchantID;
                }
                //手机预览
                $list['data'][$k]['mobile_url'] = "http://".config("app.agent_url")."/ds_m?w=".$v['prj_id']."&sp=".$this->merchantID;
                if(!empty($v['coml_works_id'])){
                    $orgig = base64_encode($v['user_id'].'-'.$v['prod_id'].'-'.$v['sku_id']);
                    $list['data'][$k]['url'] = config("template.coml_pc_url")."/design?id=".$v['coml_works_id']."&mode=user&uprodsku=".$orgig;
                    $list['data'][$k]['mobile_url'] = config("template.coml_mobile_url")."/userDesign/".$v['coml_works_id']."?uprodsku=".$orgig;
                }

                $list['data'][$k]['prj_label'] = explode(",",$v['prj_label']);
           }

            $shopping_car = $this->shopping_car;
            $htmlContents = $this->renderHtml('agent.works._table',['list'=>$list['data'],'prjStatus'=>$this->prjStatus,'prjLabel'=>$this->prjLabel,'shopping_car'=>$shopping_car,'sync_sdk'=>$this->sync_sdk]);

            $total = $list['total'];
            return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
        }catch (CommonException $e){
            $this->jsonFailed($e->getMessage());
        }
    }

    //获取制作链接
    public function makeUrl(Request $request)
    {
        $params = $request->all();
        $prj_id = $params['prj_id'];
        $prod_id = $params['prod_id'];
        $uid = isset($params['user_id'])? $params['user_id'] : 0;
        $skuid = isset($params['sku_id'])? $params['sku_id'] : 0;

        //获取特殊分类的IDS 冲印和摆台&插画&框画
        $specialIds = config('goods.special_ids');
        $prod_info = $this->productsRepository->getProductInfo($this->merchantID,$prod_id);
        //分类id
        $prod_cate_uid = $prod_info[0]['prod_cate_uid'];
        if(in_array($prod_cate_uid,$specialIds)){
            //如果是冲印类的
            if($prod_cate_uid==$specialIds['single']){
                $list['pc_url'] = "http://".config("app.agent_url")."/printer/index.html?w=".$prj_id."&sp=".$this->merchantID;
                $list['mob_url'] = "http://".config("app.agent_url")."/ds_m?w=".$prj_id."&sp=".$this->merchantID;
            }else{
                $list['pc_url'] = "http://".config("app.agent_url")."/ds/ed.html?w=".$prj_id."&sp=".$this->merchantID;
                $list['mob_url'] = "http://".config("app.agent_url")."/ds_m?w=".$prj_id."&sp=".$this->merchantID;
            }
        }else{
            $projInfo = $this->repositories->getRow(['coml_works_id'=>$prj_id]);
            if($projInfo['coml_works_id']==$prj_id){
                $orgig = base64_encode($uid.'-'.$prod_id.'-'.$skuid);
                $list['pc_url'] = config("template.coml_pc_url")."/design?id=".$prj_id."&mode=user&uprodsku=".$orgig;
                $list['mob_url'] = config("template.coml_mobile_url")."/userDesign/".$prj_id."?uprodsku=".$orgig;
            }else{
                $list['pc_url'] = "http://".config("app.agent_url")."/ds/ed.html?w=".$prj_id."&sp=".$this->merchantID;
                $list['mob_url'] = "http://".config("app.agent_url")."/ds_m?w=".$prj_id."&sp=".$this->merchantID;
            }
        }

        $htmlContents = $this->renderHtml('agent.works._makeUrl',['row'=>$list]);
        return $this->jsonSuccess(['html' => $htmlContents]);
    }
    
    //修改作品页面
    public function edit(Request $request)
    {
        try{
            $prj_id = $request->get('prj_id');
            $project = $this->repositories->getTableList(['prj_id'=>$prj_id])->toArray();
            $status = $this->prjStatus;
            unset($status[WORKS_DIY_STATUS_ORDER]);
            $statusList = Helper::getChooseSelectData($status);
            $htmlContents = $this->renderHtml('agent.works._edit',['project'=>$project['data'][0],'statusList'=>$statusList]);
            return $this->jsonSuccess(['html' => $htmlContents]);
        }catch (CommonException $e){
            $this->jsonFailed($e->getMessage());
        }
    }

    //修改作品
    public function save(Request $request)
    {
        try{
            \DB::beginTransaction();
            $params = $request->all();
            $project = $this->repositories->getById($params['prj_id'])->toArray();
            if($project['prj_status']==WORKS_DIY_STATUS_ORDER){
                return $this->jsonFailed('作品'.$project['prj_name'].'的状态为已下单,不允许修改信息');
            }
            $ret = $this->repositories->editSave($params);
            //修改作品状态为待确认插入同步队列
            if($params['prj_status']==WORKS_DIY_STATUS_WAIT_CONFIRM && $ret){
                $prj_temp = $this->projectsOrderTempRepository->getList(['prj_id'=>$params['prj_id']])->toArray();
                if(!empty($prj_temp) && !empty($prj_temp[0]['order_no'])){
                    $syncServices = app(Sync::class);
                    $syncServices->saveOrderSyncQueue($prj_temp[0]['order_no'],$this->agentID);
                }
            }

            $workLog = [
                'user_id'    => $this->agentID,
                'works_id'   => $params['prj_id'],
                'action'     => "修改作品信息",
                'note'       => "",
                'createtime' => time(),
                'operator'   => session('admin')['dms_adm_username'],
            ];
            if ($ret) {
                \DB::commit();
                $mongo = new Mongo();
                $mongo->insert('diy_works_log',$workLog);
                return $this->jsonSuccess([]);
            } else {
                return $this->jsonFailed('');
            }
        }catch (CommonException $e){
            \DB::rollBack();
            $this->jsonFailed($e->getMessage());
        }

    }

    //标签作品修改
    public function labelSave(Request $request)
    {
        try{
            \DB::beginTransaction();
            $params = $request->all();
            $prj_ids = explode(",",$params['prj_id']);
            $label = $params['prj_label'];
            foreach ($prj_ids as $k=>$v){
                $ret = $this->repositories->labelSave($v,$label);
                if(!$ret){
                    return $this->jsonFailed('');
                }
                $workLog[] = [
                    'user_id'    => $this->agentID,
                    'works_id'   => $v,
                    'action'     => "标签作品",
                    'note'       => "",
                    'createtime' => time(),
                    'operator'   => session('admin')['dms_adm_username'],
                ];
            }
            \DB::commit();
            $mongo = new Mongo();
            foreach ($workLog as $k=>$v){
                $mongo->insert('diy_works_log',$v);
            }
            return $this->jsonSuccess([]);
        }catch (CommonException $e){
            \DB::rollBack();
            $this->jsonFailed($e->getMessage());
        }

    }
    //标签作品页面
    public function remarks(Request $request)
    {
        try{
            $params = $request->all();
            $presenter = app(CommonPresenter::class);
            $prj_ids = explode(",",$params['prj_id']);
            $projectList = [];
            foreach ($prj_ids as $pk=>$pv){
                $project = $this->repositories->getTableList(['prj_id'=>$pv])->toArray();
                if(count($prj_ids)==1){
                    $projectList['prj_label'] = $project['data'][0]['prj_label'];
                    $projectList['prj_label_list'] = explode(",",$project['data'][0]['prj_label']);
                }else{
                    $projectList['prj_label'] = "";
                    $projectList['prj_label_list'] = explode(",","");
                }
                $projectList['prj_name'][] = $project['data'][0]['prj_name'];
                $projectList['prj_sn'][] = $project['data'][0]['prj_sn'];
                $projectList['prj_outer_account'][] = $project['data'][0]['prj_temp']['prj_outer_account'];
                $projectList['created_at'][] = $presenter->exchangeTime($project['data'][0]['created_at']);
                $projectList['prod_sku_sn'][] = $project['data'][0]['prod_sku']['prod_sku_sn'];
            }

            $projectList['prj_name'] = implode(",",$projectList['prj_name']);
            $projectList['prj_sn'] = implode(",",$projectList['prj_sn']);
            $projectList['prj_outer_account'] = implode(",",$projectList['prj_outer_account']);
            $projectList['created_at'] = implode(",",$projectList['created_at']);
            $projectList['prod_sku_sn'] = implode(",",$projectList['prod_sku_sn']);
            $projectList['prj_id'] = $params['prj_id'];

            $htmlContents = $this->renderHtml('agent.works._remarks',['project'=>$projectList,'prjLabel'=>$this->prjLabel]);
            return $this->jsonSuccess(['html' => $htmlContents]);
        }catch (CommonException $e){
            $this->jsonFailed($e->getMessage());
        }
    }

    //克隆作品页面
    public function cloneWorks(Request $request)
    {
        try{
            $prj_id = $request->get('prj_id');
            $project = $this->repositories->getTableList(['prj_id'=>$prj_id])->toArray();
            $project = $project['data'][0];
            $htmlContents = $this->renderHtml('agent.works._clone_works',['project'=>$project]);
            return $this->jsonSuccess(['html' => $htmlContents]);
        }catch (CommonException $e){
            $this->jsonFailed($e->getMessage());
        }
    }

    //克隆作品
    public function worksClone(Request $request)
    {
        $params = $request->all();
        $clone=isset($params['clone'])?$params['clone']:ONE;
        $prj_ids = explode(",",$params['prj_id']);
        try{
            \DB::beginTransaction();
            foreach ($prj_ids as $k=>$v){
                $project = $this->repositories->getProjectInfo($v);
                $project = $project[0];

                for($i=1;$i<=$clone;$i++){
                    $projectClone = $project;
                    unset($projectClone['prj_id']);
                    $projectClone['prj_sn'] =$this->worksAbstract->createWorksNo();
                    $projectClone['clone_id'] = $v;
                    $projectClone['prj_name'] = $project['prj_name']."_".$i;
                    $projectClone['prj_status'] = WORKS_DIY_STATUS_MAKING;
                    $projectClone['prj_label'] = null;
                    $projectClone['updated_at'] = null;
                    $new_works_id = $this->repositories->save($projectClone);

                    //克隆作品临时订购信息
                    if(isset($projectClone['clone_id'])){
                        $projectsOrderTempRepository = app(SaasProjectsOrderTempRepository::class);
                        $projectPageRepository = app(SaasProjectPageRepository::class);
                        //克隆作品临时订购信息
                        $temp_ret = $projectsOrderTempRepository->cloneOrderTemp($v,$new_works_id);
                        //克隆作品页面
                        $page_ret = $projectPageRepository->clonePage($v,$new_works_id);
                    }

                    //复制
                    $mongo = new Mongo();
                    //获取原作品的作品文件
                    $works_id = strval($projectClone['clone_id']);
                    $query = new \MongoDB\Driver\Query(['works_id' => $works_id], []);
                    $config = config('common.mongo');
                    $cursor = $mongo->manager->executeQuery($config['db'].'.prj_stage', $query);
                    $cursor->setTypeMap(['root' => 'array', 'document' => 'array']);
                    $arrInfo = $cursor->toArray();
                    $stage = $arrInfo[0]['stage'];
                    $works_data = [
                        'works_id' => strval($new_works_id),
                        'stage' => $stage
                    ];

                    $mongo->insert('prj_stage', $works_data);

                    $workLog = [
                        'user_id'    => $this->agentID,
                        'works_id'   => $new_works_id,
                        'action'     => "克隆作品",
                        'note'       => "",
                        'createtime' => time(),
                        'operator'   => session('admin')['dms_adm_username'],
                    ];

                    $mongo->insert('diy_works_log',$workLog);
                }
            }

            if($new_works_id && $temp_ret && $page_ret){
                \DB::commit();
                return $this->jsonSuccess([]);
            }
        }catch (CommonException $e){
            \DB::rollBack();
            return $this->jsonFailed($e->getMessage());
        }
    }

    //恢复作品
    public function regain(Request $request)
    {
        try{
            $prj_ids = $request->get('prj_id');
            $prj_id = explode(",",$prj_ids);
            $mongo = new Mongo();
            foreach ($prj_id as $k=>$v){
                $data = [
                    'prj_id'=>$v,
                    'prj_status'=>WORKS_DIY_STATUS_MAKING,
                    'updated_at'=>time()
                ];
                $ret = $this->repositories->save($data);

                $workLog = [
                    'user_id'    => $this->agentID,
                    'works_id'   => $v,
                    'action'     => "恢复作品",
                    'note'       => "",
                    'createtime' => time(),
                    'operator'   => session('admin')['dms_adm_username'],
                ];

                $mongo->insert('diy_works_log',$workLog);
            }
            if ($ret) {
                return $this->jsonSuccess([]);
            } else {
                return $this->jsonFailed('');
            }
        }catch (CommonException $e){
            return $this->jsonFailed($e->getMessage());
        }
    }

    //删除作品
    /**
     * 删除(软删除)
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $project = $this->repositories->getProjectInfo($id);
        if(!empty($project) && $project[0]['prj_status']==WORKS_DIY_STATUS_ORDER){
            return $this->jsonFailed('作品'.$project[0]['prj_name'].'的状态为已下单,不允许删除');
        }
        $ret = $this->repositories->delete($id);
        if($ret){
            return $this->jsonSuccess([]);
        }
        return $this->jsonFailed('');
    }



    /**
     * 删除(软删除)
     * @param $id
     * @return bool
     */
    public function deleteIds(Request $request)
    {
        try{
            \DB::beginTransaction();
            $prj_ids = $request->get('prj_id');
            $prj_id = explode(",",$prj_ids);
            foreach ($prj_id as $k=>$v){
                $project = $this->repositories->getProjectInfo($v);
                if(!empty($project) && $project[0]['prj_status']==WORKS_DIY_STATUS_ORDER){
                    return $this->jsonFailed('作品'.$project[0]['prj_name'].'的状态为已下单,不允许删除');
                }
                $ret = $this->repositories->delete($v);
                if(!$ret){
                    return $this->jsonFailed('');
                }
            }
            if ($ret) {
                \DB::commit();
                return $this->jsonSuccess([]);
            }
        }catch (CommonException $e){
            \DB::rollBack();
            return $this->jsonFailed($e->getMessage());
        }
    }

    //审核作品
    public function review(Request $request)
    {
        try{
            \DB::beginTransaction();
            $prj_ids = $request->get('prj_id');
            $prj_id = explode(",",$prj_ids);
            foreach ($prj_id as $k=>$v){
                $project = $this->repositories->getById($v)->toArray();
                if($project['prj_status']!=WORKS_DIY_STATUS_MAKING){
                    return $this->jsonFailed('作品'.$project['prj_name'].'的状态不为制作中,请刷新列表');
                }
                $data = [
                    'prj_id'=>$v,
                    'prj_status'=>WORKS_DIY_STATUS_WAIT_CONFIRM,
                    'prj_file_status'=>ZERO,
                    'updated_at'=>time()
                ];
                $ret = $this->repositories->save($data);

                if($ret){
                    //审核通过之后插入同步队列
                    $prj_temp = $this->projectsOrderTempRepository->getList(['prj_id'=>$v])->toArray();
                    if(!empty($prj_temp) && !empty($prj_temp[0]['order_no'])){
                        $syncServices = app(Sync::class);
                        $syncServices->saveOrderSyncQueue($prj_temp[0]['order_no'],$this->agentID);
                    }
                }else{
                    \DB::rollBack();
                    return $this->jsonFailed('审核失败');
                }

                $workLog = [
                    'user_id'    => $this->agentID,
                    'works_id'   => $v,
                    'action'     => "审核作品",
                    'note'       => "",
                    'createtime' => time(),
                    'operator'   => session('admin')['dms_adm_username'],
                ];
                $mongo = new Mongo();
                $mongo->insert('diy_works_log',$workLog);
            }
            if ($ret) {
                \DB::commit();
                return $this->jsonSuccess([]);
            } else {
                \DB::rollBack();
                return $this->jsonFailed('');
            }
        }catch (CommonException $e){
            \DB::rollBack();
            return $this->jsonFailed($e->getMessage());
        }

    }

    //当商家开启购物车功能时，直接将商品加入购物车，否则打开订购作品页面
    public function order(Request $request)
    {
        try{
            $prj_ids = $request->get('prj_id');
            //看是否开启了购物车 或者订单详情中再次订购，看是否包含实物
            if($this->shopping_car==ONE || strpos($prj_ids,'-') !== false){
                $car_data = $this->cartRepository->shoppingCar($prj_ids);
                if($car_data['status']){
                    $return = $this->cartRepository->addCartGoods($car_data['data']);
                    if($return){
                        return $this->jsonSuccess(['message'=>"",'cart'=>ONE]);
                    }else{
                        return $this->jsonSuccess(['message'=>"作品订购失败",'cart'=>ONE]);
                    }
                }else{
                    return $this->jsonSuccess(['message'=>$car_data['msg'],'cart'=>ONE]);
                }
            }else{
                //获取商户余额
                $agentInfo = $this->dmsAgentInfoRepository->getTableList(['agent_info_id'=>$this->agentID])->toArray();
                $now_balance = $agentInfo['data'][0]['agent_balance'];
                $is_open_pay = $agentInfo['data'][0]['is_open_pay'];
                $prj_id = explode(",",$prj_ids);
                $projectInfo = [];
                $total_price = ZERO;
                foreach ($prj_id as $k=>$v){
                    //订单详情中再次订购，看是否是稿件
                    if(substr($v,-1)=='U'){
                        $id = substr($v,0,strlen($v)-1);
                        //获取稿件信息
                        $project = $this->repositories->getTableList(['manuscript_id'=>$id])->toArray();
                        $prj_id[$k] =  $project['data'][0]['prj_id'];
                    }else{
                        //获取作品信息
                        $project = $this->repositories->getTableList(['prj_id'=>$v])->toArray();
                    }
                    if(empty($project)){
                        return $this->jsonFailed("找不到对应的作品信息");
                    }
                    $project = $project['data'][0];
                    //获取作品中货品的属性
                    $sku_attr = $this->relationAttrRepository->getProductAttr($project['sku_id']);
                    $project['sku_attr'] = explode("，",$sku_attr);
                    //如果属性存在P数的话，转换成作品信息中的p数
                    foreach ($project['sku_attr'] as $sk=>$sv){
                        if(strpos($sv,"P数")!==false){
                            $project['sku_attr'][$sk] = "P数：".$project['prj_page_num']."P";
                        }
                    }
                    //获取货品的重量
                    $sku_weight = $this->info->getGoodsWeight($project['sku_id'],$project['prj_page_num']);
                    $project['sku_weight'] = $sku_weight;
                    //获取货品的价格
                    $cust_lv_id = $this->dmsAgentInfoRepository->getCustLvId($this->agentID);
                    $sku_price = $this->price->getChanelPrice($project['sku_id'],$cust_lv_id,$project['prj_page_num']);
                    $project['sku_price'] = $sku_price;
                    //总价
                    if(!empty($project['prj_temp']['ord_quantity'])){
                        $project['total_price'] = $project['prj_temp']['ord_quantity']*$sku_price;
                    }else{
                        $project['total_price'] = $project['sku_price'];
                    }
                    //所有作品的总价格
                    $total_price +=$project['total_price'];
                    $projectInfo[$k] = $project;
                }

                //支付方式id
                $order_pay_id = config('common.order_pay_id');
//                $payment = $this->paymentRepository->getPayment($this->merchantID);
                $payment[$order_pay_id]['pay_name'] = "余额支付";
                $payment[$order_pay_id]['pay_class_name'] = "balance";
                $prj_ids = implode(",",$prj_id);
                $htmlContents = $this->renderHtml('agent.works._order',['ids'=>$prj_ids,'projectInfo'=>$projectInfo,'payment'=>$payment,'totalprice'=>$total_price,'now_balance'=>$now_balance,'is_open_pay'=>$is_open_pay]);
                return $this->jsonSuccess(['html' => $htmlContents,'cart'=>ZERO]);
            }
        }catch (CommonException $e){
            return $this->jsonFailed($e->getMessage());
        }
    }

    //淘宝订单订购
    public function ajaxtongbu(Request $request)
    {
        try{
            $prj_ids = $request->get('prj_id');
            //看是否开启了购物车 或者订单详情中再次订购，看是否包含实物
            if($this->shopping_car==ONE || strpos($prj_ids,'-') !== false){
                $car_data = $this->cartRepository->shoppingCar($prj_ids);
                if($car_data['status']){
                    $return = $this->cartRepository->addCartGoods($car_data['data']);
                    if($return){
                        return $this->jsonSuccess(['message'=>"",'cart'=>ONE]);
                    }else{
                        return $this->jsonSuccess(['message'=>"作品订购失败",'cart'=>ONE]);
                    }
                }else{
                    return $this->jsonSuccess(['message'=>$car_data['msg'],'cart'=>ONE]);
                }
            }else{
                //获取商户余额
                $agentInfo = $this->dmsAgentInfoRepository->getTableList(['agent_info_id'=>$this->agentID])->toArray();
                $now_balance = $agentInfo['data'][0]['agent_balance'];
                $is_open_pay = $agentInfo['data'][0]['is_open_pay'];
                $prj_id = explode(",",$prj_ids);
                $outer_account = [];//外部旺旺昵称
                $projectInfo = [];
                foreach ($prj_id as $k=>$v){
                    //订单详情中再次订购，看是否是稿件
                    if(substr($v,-1)=='U'){
                        $id = substr($v,0,strlen($v)-1);
                        //获取稿件信息
                        $project = $this->repositories->getTableList(['manuscript_id'=>$id])->toArray();
                        $prj_id[$k] =  $project['data'][0]['prj_id'];
                    }else{
                        //获取作品信息
                        $project = $this->repositories->getTableList(['prj_id'=>$v])->toArray();
                    }
                    if(empty($project)){
                        return $this->jsonFailed("找不到对应的作品信息");
                    }
                    $project = $project['data'][0];
                    if(count($outer_account)>=ONE){
                        foreach ($outer_account as $oak => $oav){
                            if($project['prj_temp']['prj_outer_account']!=$oav){
                                return $this->jsonSuccess(['message'=>"用户信息不同，不允许同步",'cart'=>ONE]);
                            }
                        }
                    }
                    $outer_account[] = $project['prj_temp']['prj_outer_account'];
                    //获取作品中货品的属性
                    $sku_attr = $this->relationAttrRepository->getProductAttr($project['sku_id']);
                    $project['sku_attr'] = explode("，",$sku_attr);
                    //如果属性存在P数的话，转换成作品信息中的p数
                    foreach ($project['sku_attr'] as $sk=>$sv){
                        if(strpos($sv,"P数")!==false){
                            $project['sku_attr'][$sk] = "P数：".$project['prj_page_num']."P";
                        }
                    }
                    //获取货品的重量
                    $sku_weight = $this->info->getGoodsWeight($project['sku_id'],$project['prj_page_num']);
                    $project['sku_weight'] = $sku_weight;
                    //获取货品的价格
                    $cust_lv_id = $this->dmsAgentInfoRepository->getCustLvId($this->agentID);
                    $sku_price = $this->price->getChanelPrice($project['sku_id'],$cust_lv_id,$project['prj_page_num']);
                    $project['sku_price'] = $sku_price;
                    //总价
                    if(!empty($project['prj_temp']['ord_quantity'])){
                        $project['total_price'] = $project['prj_temp']['ord_quantity']*$sku_price;
                    }else{
                        $project['total_price'] = $project['sku_price'];
                    }

                    $projectInfo[$k] = $project;
                }
                //获取商家设置的支付方式
                //支付方式id
                $order_pay_id = config('common.order_pay_id');
//                $payment = $this->paymentRepository->getPayment($this->merchantID);
                $payment[$order_pay_id]['pay_name'] = "余额支付";
                $payment[$order_pay_id]['pay_class_name'] = "balance";
                $prj_ids = implode(",",$prj_id);
                $htmlContents = $this->renderHtml('agent.works._tongbu',['ids'=>$prj_ids,'projectInfo'=>$projectInfo,'payment'=>$payment,'now_balance'=>$now_balance,'is_open_pay'=>$is_open_pay]);
                return $this->jsonSuccess(['html' => $htmlContents,'cart'=>ZERO]);
            }
        }catch (CommonException $e){
            return $this->jsonFailed($e->getMessage());
        }
    }


    //淘宝同步外部订单
    public function outerNo(Request $request)
    {
        $params = $request->all();
        $agent_id = $this->agentID;
        $outer_no = $params['outer_no'];
        $account = $params['outer_account'];
        $items = [];
        //请求淘宝订单接口
        $syncServices = app(Sync::class);
        $tb_order = $syncServices->setOrdersData($outer_no,$agent_id,ONE);
        if($tb_order['status']=="failed" && $tb_order['code']==ONE){
            return $this->jsonFailed($tb_order['msg']);
        }
        else{
            $data = $tb_order['data'];
            $receiver_data = $tb_order['receiver_data'];
            $items = $tb_order['items'];
//            if($data['buyer_nick']!=$account){
//                return $this->jsonFailed("订单号：".$outer_no."<br/>"."买家信息(".$data['buyer_nick'].")与作品买家信息(".$account.")不一致，不允许同步！");
//            }

            $items = json_encode($items);
            $tb_order_status = config("agent.tb_order_status");
        }
        return $this->jsonSuccess(['receiver_data'=>$receiver_data,'outer_data'=>$data,'tb_order_status'=>$tb_order_status,'items'=>$items]);
    }


    //获取快递运费
    public function getprice(Request $request)
    {
        try{
            $params = $request->all();
            //作品id
            $works_id = explode(",",$params['works_id']);
            //快递模板id
            $temp_ids = explode(",",$params['temp_id']);
            $temp_id = array_unique($temp_ids);
            $deliveryList = [];
            if(count($works_id)==ONE){
                $prodInfo = $this->repositories->getProdExpressType($works_id[0]);
            }
            //如果模板id不存在的话，商品为固定运费取值
            if(count($temp_id)==ONE && empty($temp_id[0])){
                $prodInfo = $this->repositories->getProdExpressType($works_id[0]);
                if(empty($prodInfo['prod_express_fee'])){
                    $prodInfo['prod_express_fee']=ZERO;
                }
                $deliveryInfo = [
                    'delivery_id'=>ZERO,
                    'delivery_name'=>'固定运费',
                    'delivery_show_name'=>'固定运费',
                    'delivery_desc'=>'运费为固定值',
                    'deli_price' =>$prodInfo['prod_express_fee'],
                    'del_temp_id'=>ZERO
                ];
                $deliveryList[0] = $deliveryInfo;
                return $deliveryList;
            }

            //省id
            $pro_id = $params['pro_id'];
            //市id
            $city_id = $params['city_id'];
            //区id
            $area_id = $params['area_id'];
            //商品重量
            $weight = $params['total_weight'];
            $deli_temp = $this->templateRepository->getTemplate($temp_id,$this->merchantID);
            //获取快递运送方式
            $delivery_list = explode(",",$deli_temp['del_temp_delivery_list']);
            $deliveryList = [];
            //获取运送方式的运费
            foreach($delivery_list as $k=>$v){
                $deli_list = $this->deliveryRepository->getTableList(['delivery_id'=>$v])->toArray();
                $deliveryInfo = $deli_list['data'][0];
                $deli_price = $this->logistics->getDeliveryFee($deli_temp['del_temp_id'],$v,$pro_id,$city_id,$area_id,$weight);
                if(count($works_id)==ONE && $prodInfo['prod_express_type']== LOGISTICS_PRICE_BY_FIXED){
                    $deliveryInfo['deli_price'] = $prodInfo['prod_express_fee'];
                }else{
                    $deliveryInfo['deli_price'] = $deli_price;
                }

                $deliveryList[$k] = $deliveryInfo;
                $deliveryList[$k]['del_temp_id'] = $deli_temp['del_temp_id'];
            }
            return $deliveryList;
        }catch (CommonException $e){
            return $this->jsonFailed($e->getMessage());
        }

    }


    //订购作品
    public function orderSave(Request $request)
    {
        try{
            $params = $request->all();
            //封装创建订单所需的数据
            $post_data = $this->orderCreate($params);
            if(isset($params['sync'])){
                //同步订单
                $result = $this->syncOrdersEntity->create($post_data);
                //备注信息回写到淘宝
                $isUpdate = config('common.is_update_tb_memo');
                if($isUpdate){
                    if($result['status']=='success'){
                        //生成的订单号
                        $orderNo = $result['data'];
                        //淘宝返回数据的备注
                        $seller_memo = $params['buyer_memo']??"";
                        //淘宝备注
                        $date_time = date('Y-m-d H:i:s');
                        if($params['orderCount']==ONE){
                            $new_seller_memo = $seller_memo.'  一单/'.$orderNo.'/'.$date_time;
                        }else{
                            $new_seller_memo = $seller_memo.'  合单/'.$orderNo.'/'.$date_time;
                        }

                        $orderNos = explode(",",$params['order_no']);
                        foreach ($orderNos as $k=>$v){
                            $data = [
                                'order_no'=>$v,
                                'agent_id' => $this->agentID,
                                'new_seller_memo'=>$new_seller_memo
                            ];
                            try{
                                $tbConfig = $this->helper->getTbConfig($this->agentID);
                                $api = app(TbApi::class);
                                $api->request($tbConfig['sdk_cnf_domain'].'/tb/order/update-memo',$data,'POST');
                            }catch (CommonException $e){

                            }
                        }
                    }
                }

            }else{
                $result = $this->ordersEntity->create($post_data);
            }

            if($result['status']=='failed'){
                return $this->jsonFailed($result['msg']);
            }
            else if($result['status']=='success'){
                $prj_ids = explode(",",$params['ids']);
                foreach ($prj_ids as $id)
                {
                    $prj_ret =  $this->repositories->save(['prj_id'=>$id,'prj_status'=>WORKS_DIY_STATUS_ORDER,'updated_at'=>time()]);
                    $workLog = [
                        'user_id'    => $this->agentID,
                        'works_id'   => $id,
                        'action'     => "订购作品",
                        'note'       => "订单号为【".$result['data']."】",
                        'createtime' => time(),
                        'operator'   => session('admin')['dms_adm_username'],
                    ];
                    $mongo = new Mongo();
                    $mongo->insert('diy_works_log',$workLog);
                }

                if($prj_ret){
                    return $this->jsonSuccess([]);
                }
            }
        }catch (CommonException $e){
            return $this->jsonFailed($e->getMessage());
        }

//        if(empty($result)){
//            //付费金额
//            $amount = $params['order_real_total'];
//            //支付方式id
//            $type = $params['order_pay_id'];
//            $paymentinfo = $this->paymentRepository->getPayInfo($type);
//            $class_name = $paymentinfo[0]['pay_class_name'];
//            //支付备注
//            if ($params['ext_info'])
//            {
//                $note = $params['ext_info'];
//            }else{
//                $note = "";
//            }
//            //交易流水号
//            $trade_no = $params['order_no'];
//            //支付宝
//            if($class_name == "alipay")
//            {
//                //支付金额
//                $amount = round($amount, 2);
//                //交易流水号
//                $out_trade_no = $trade_no;
//                //获取支付宝配置信息
//                $alipay_config = config('common.alipay_agt');
//                //支付宝旧接口测试
//                $alipayOld = new Create($alipay_config);
//                //参数配置
//                $parameter = array(
//                    "service" => $alipay_config['service'],
//                    "partner" => $alipay_config['partner'],
//                    "seller_id" => $alipay_config['seller_id'],
//                    "payment_type" => $alipay_config['payment_type'],
//                    "notify_url" => "http://agent.my.com/works/alipaynotify",
//                    "return_url" => "http://agent.my.com/works/alipayreturn",
//                    "anti_phishing_key" => $alipay_config['anti_phishing_key'],
//                    "exter_invoke_ip" => $alipay_config['exter_invoke_ip'],
//                    "out_trade_no" => $out_trade_no,
//                    "subject" => '订单支付',
//                    "total_fee" => $amount,
//                    "body" => '',
//                    "_input_charset" => trim(strtolower($alipay_config['input_charset']))
//                );
//
//                //建立请求
//                $html_text = $alipayOld->buildRequestForm($parameter, "get", "确认");
//
//                echo $html_text;
//
//            }
//            //微信
//            else if($class_name == "wxpay"){
//                //支付金额
//                $amount = round($amount, 2);
//                //交易流水号
//                $out_trade_no = $trade_no;
//                //构建订单信息
//                $order = [
//                    'out_trade_no' =>$out_trade_no,//你的订单号
//                    'body'         => '订单支付',
//                    'total_fee'    => $amount*100,//单位为分
//                    'spbill_create_ip' => '47.92.79.166',
//                    //'attach'   =>$this->mid,
//                ];
//                //创建微信订单
//                $pay = Pay::wechat()->scan($order)->toArray();
//
//                return view('agent.works.wxpay',['amount'=>$amount,'qr_code'=>$pay['code_url'],'order_no'=>$out_trade_no]);
//            }
//        }else{
//            return $this->jsonFailed($result['msg']);
//        }


    }

    //验证支付密码是否正确
    public function checkPayword(Request $request)
    {
        $params = $request->post('payword');
        $agentInfo = $this->dmsAgentInfoRepository->getById(['agent_info_id'=>$this->agentID])->toArray();
        $payword = $this->dmsAgentInfoRepository->setPassword($params,$agentInfo[0]['payword_salt']);
        if($payword==$agentInfo[0]['payword']){
            return $this->jsonSuccess([]);
        }else{
            return $this->jsonFailed('');
        }
    }


    //构建创建订单数据
    public function orderCreate($data)
    {
        $prjIds = explode(",",$data['ids']);
        foreach ($prjIds as $key=>$value){
            //作品信息
            $prjInfo = $this->repositories->getTableList(['prj_id'=>$value])->toArray();
            $prjInfo = $prjInfo['data'][0];
            //作品类型 1：diy 2:稿件
            $file_type=WORKS_FILE_TYPE_DIY;
            if($prjInfo['prj_file_type']==WORKS_FILE_TYPE_UPLOAD){
                $file_type=WORKS_FILE_TYPE_UPLOAD;
                $value = $prjInfo['manuscript_id'];
            }
            $items[$key] = [
                'goods_id'   =>$prjInfo['prod_id'],
                'product_id' =>$prjInfo['sku_id'],
                'works_id'   =>$value,
                'file_type'  =>$file_type,
                'file_info'  => [
//                                'file_url' => 'http://xxxxx/xxx.pdf||http://xxxxx/xxx.pdf',  //封面||内页||封底这样排
                                'pages_num'  => $prjInfo['prj_page_num']  //冲印张数或照片书内页数
                                ],
                'price_mod'  => 1,   //1正常按本/个计价 2按张数计价
                'buy_num'    => $data['number'][$key],  //购买数量 必须
                'real_fee'   => $data['sku_price'][$key]*$data['number'][$key],  // 价格  商品单价*数量
                'price'      => $data['cot_prices'][$key], //最终商品价格 非必须，如果有，则需要验证正确性 非必须
            ];
        }

        //渠道id
        $salesChanel = app(SaasSalesChanelRepository::class);
        $cha_id = $salesChanel->getAgentChannleId();
        //合作代码
        $parent_code =$this->dmsAgentInfoRepository->getCodeById($this->agentID);
        if(isset($data['items'])){
            //淘宝订单可能存在实物
            $item = json_decode($data['items'],true);
            if(!empty($item)){
                foreach ($item as $k=>$v){
                    $items[] = $v;
                }
            }
        }

        //收货信息
        $receiver_info = [
            'consignee'      => $data['order_rcv_user'],     //必须 收货人
            'ship_mobile'    => $data['order_rcv_phone'],    //必须 收货人电话
            'province_code'  => $data['province'],           //省id
            'city_code'      => $data['city'],               //市id
            'district_code'  => $data['district'],           //区id
            'ship_addr'      => $data['prj_rcv_address'],    //收货地址
            'ship_tel'       => $data['telephone'],           //电话
            'ship_zip'       => $data['zip_code'],            //邮编
        ];

        $post_data = [
            'items'            =>$items,
            'receiver_info'    =>$receiver_info,
            'outer_order_no'   => $data['order_no'],     //关联的第三方单号 选填
            'shipping_temp_id' =>  $data['delivery_temp_id'],          //快递模板id 必须
            'shipping_id'      =>  $data['order_delivery_id'],          //快递id 必须
            'partner_code'     =>  $parent_code,    //合作代码，以些代码开头生成订单号

            'total_amount'          =>  $data['order_real_total'],      //订单总金额，实际价格 含运费 ,如果提交了，则会验算 非必填
            'post_fee'         =>  $data['order_exp_fee'],             //运费  选填
            'mch_id'           =>  session("admin")['mch_id'],          //商家id,必须
            'chanel_id'        =>  $cha_id,           //渠道id,必须
            'buyer_type'       =>  CHANEL_TERMINAL_AGENT,         // 终端用户类型 1代表分销 2代表会员，其他无效 必须
            'user_id'          =>  session('admin')['agent_info_id'],          //用户id,必须
            'note'             => $data['buyer_memo'],  //用户备注  选填
            //支付信息
            'pay_info'         =>[  //支付信息 必填
                   'pay_id' => $data['order_pay_id'], //余额、支付宝、微信等支付对应的id 必须
            ],
        ];

        return $post_data;
    }



    //作品操作日志页面
    public function log(Request $request)
    {
        $params = $request->all();
        $prj_id = $params['prj_id'];

        $mongo = new Mongo();
        //获取作品文件的操作日志
        $works_id = strval($prj_id);
        $ids[] = (int)$works_id;
        $ids[] = $works_id;
        $query = new \MongoDB\Driver\Query(['works_id' => ['$in'=>$ids]], []);
        $config = config('common.mongo');
        $cursor = $mongo->manager->executeQuery($config['db'].'.diy_works_log', $query);
        $cursor->setTypeMap(['root' => 'array', 'document' => 'array']);
        $arrInfo = $cursor->toArray();

        $htmlContents = $this->renderHtml('agent.works._log',['log_info'=>$arrInfo]);
        return $this->jsonSuccess(['html' => $htmlContents,'total' => 10]);
    }



    public function projectsError(Request $request)
    {
        $params = $request->all();
        $projectPage = $this->projectPageRepository->getList(['prj_id'=>$params['prj_id']])->toArray();
        $errorList = [];
        if(!empty($projectPage)){
            foreach ($projectPage as $k=>$v){
                if($v['mask_empty_count']>0 || $v['maks_badpx_count']>0){
                    $errorList[] = [
                        'prj_page_name'=>$v['prj_page_name'],
                        'mask_empty_count'=>$v['mask_empty_count'],
                        'maks_badpx_count'=>$v['maks_badpx_count'],
                    ];
                }
            }
            return $this->jsonSuccess($errorList);
        }else{
            return $this->jsonFailed("找不到对应的作品页面信息");
        }

    }




//    /**
//     * 支付宝支付异步通知返回
//     */
//    public function alipaynotify(){
//        //计算得出通知验证结果
//        $alipay_config = config('common.alipay_agt');
//        //file_put_contents('/data/tmp/1.log', var_export($_POST,true));
//
//        $alipayNotify = new AlipayNotify($alipay_config);
//        $verify_result = $alipayNotify->verifyNotify();
//
//        if($verify_result) {//验证成功
//            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//            //商户订单号
//            $out_trade_no = $_POST['out_trade_no'];
//            if(stripos($out_trade_no,'recharge') !== false)
//            {
//                $tradenoArray = explode('recharge',$out_trade_no);
//                $recharge_no  = isset($tradenoArray[1]) ? $tradenoArray[1] : 0;
//            }
//            //支付宝交易号
//            $trade_no = $_POST['trade_no'];
//            //交易状态
//            $trade_status = $_POST['trade_status'];
//            //写支付日志
//            //file_put_contents($PAYLOG_PATH.$recharge_no.'-'.$trade_no.'-'.date('YmdHis').'.log', var_export($_POST,true));
//            //Log::write(var_export($_REQUEST,true),$type = 'log', $force = true);
//            if($_POST['trade_status'] == 'TRADE_FINISHED') {
//                $this->_doNotify($_POST);
//                //判断该笔订单是否在商户网站中已经做过处理
//                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
//                //请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
//                //如果有做过处理，不执行商户的业务程序
//
//                //注意：
//                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
//
//                //调试用，写文本函数记录程序运行情况是否正常
//                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
//            }
//            else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
//                $this->_doNotify($_POST);
//                //判断该笔订单是否在商户网站中已经做过处理
//                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
//                //请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
//                //如果有做过处理，不执行商户的业务程序
//
//                //注意：
//                //付款完成后，支付宝系统发送该交易状态通知
//
//                //调试用，写文本函数记录程序运行情况是否正常
//                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
//            }
//
//            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
//            echo "success";		//请不要修改或删除
//
//            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//        }
//        else {
//
//            //验证失败
//            echo "fail";
//            //file_put_contents(PAYLOG_ERROR_PATH.'/alipay_error.log', date('YmdHis').':fail',FILE_APPEND);
//            //调试用，写文本函数记录程序运行情况是否正常
//            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
//        }
//    }
//
//    private function _doNotify($data)
//    {
//        $name_date = date('YmdHis');
//        //商户订单号
//        $out_trade_no = $data['out_trade_no'];
//        if (stripos($out_trade_no, 'recharge') !== false) {
//            $tradenoArray = explode('recharge', $out_trade_no);
//            $recharge_no = isset($tradenoArray[1]) ? $tradenoArray[1] : 0;
//        }
//        //支付宝交易号
//        $trade_no = $data['trade_no'];
//        //交易状态
//        $trade_status = $data['trade_status'];
//        //充值金额，不包含手续费
//        $total_fee = floatval($data['total_fee']);
//
//        //1、查找支付单号是否存在
//        $payment_info = $this->dmsFinanceDocRepository->getByRechargeNo($out_trade_no);
////        $payment_info=json_decode(DB::table("dms_finance_doc")->where(['recharge_no' => $out_trade_no])->get(),true);
//
////        DB::beginTransaction();
//        try
//        {
//            file_put_contents('/tmp/auto_entry_account_error.log','ddd',FILE_APPEND);
//            if ($payment_info) {
//                //判断支付单未支付
//                if ($payment_info[0]['status'] != '1') {
//                    //金额匹配
//                    if (floatval($total_fee) != floatval($payment_info[0]['recharge_fee'])) {
//                        //Log::write(var_export($data,true),$type = 'log', $force = true);
//                        //file_put_contents(PAYLOG_ERROR_PATH . $out_trade_no . '-' . $trade_no . '-.log', $name_date . '金额不匹配(' . $total_fee . '---' . floatval($payment_info->account) . ')' . "\t\n", FILE_APPEND);
//                        die('success1');
//                    }
//                    //更新支付单状态
//                    //事务开始
//                    $updateData = [
//                        'status' => '1',
//                        'trade_no' => $trade_no,
//                        'finishtime' => time()
//                    ];
//                    $update_result = $this->dmsFinanceDocRepository->updateFinanceDoc($out_trade_no,$updateData);
////                    $update_result = DB::table("dms_finance_doc") ->where(['recharge_no'=>$out_trade_no])->update(['status' => '1', 'trade_no' => $trade_no,'finishtime' => time()]);
//                    if (!$update_result) {
//                        //file_put_contents(PAYLOG_ERROR_PATH . $recharge_no . '-' . $trade_no . '-.log', $name_date . 'payment update 失败' . "\t\n", FILE_APPEND);
//                        //Log::write($recharge_no . '-' . $trade_no , $name_date . 'RechargeDoc update 失败',$type = 'log', $force = true);
//                        throw new CommonException("success2");
//                    }
////                    DB::commit();
//                    /* $this->success();*/
//                }
//            } else {
//                //如果支付单号不存在，邮件通知技术员
//                /*$this->error();*/
//            }
//            //没有出错的情况将分销店铺的余额更新
////            $agentInfo = $this->dmsAgentInfoRepository->getTableList(['agent_info_id'=>$this->agentInfo['agent_info_id']])->toArray();
////            $agent_balance = $agentInfo['data'][0]['agent_balance'];
////            $agent_now_balance = $agent_balance+$total_fee;
////            $this->dmsAgentInfoRepository->save(['agent_info_id'=>$this->agentInfo['agent_info_id'],'agent_balance'=>$agent_now_balance]);
//        }
//        catch (CommonException $e)
//        {
//            file_put_contents('/tmp/auto_entry_account_error.log',var_export($e->getMessage(),true),FILE_APPEND);
////            DB::rollBack();
//            /*$this->error($e->getMessage());*/
//        }
//    }
//
//    //成功返回界面
//    /**
//     * 支付成功的返回
//     */
//    public  function alipayreturn()
//    {
//        return view("agent.works.alipayreturn", [
//            'message'  => "充值成功",
//            'jumpTime' => "3",
//            'url'      => "/#works",
//            'jumpText' => "充值",
//        ]);
//    }
//
//
//    /**
//     * 微信支付异步通知
//     */
//    public function wxpaynotify()
//    {
//        //初始化日志
////        $config = config('pay.wechat');
//        $pay = Pay::wechat();
////        $data = $pay->verify(); // 是的，验签就这么简单
//        if (!$pay) {
//            echo '签名错误';
//            return;
//        }
//        //你可以直接通过$pay->verify();获取到相关信息
//        //支付宝可以获取到out_trade_no,total_amount等信息
//        //微信可以获取到out_trade_no,total_fee等信息
//        $data = $pay->verify();
//
//        $out_trade_no = $data['out_trade_no'];
//
//        if(stripos($out_trade_no,'recharge') !== false) {
//            $tradenoArray = explode('recharge', $out_trade_no);
//            $recharge_no = isset($tradenoArray[1]) ? $tradenoArray[1] : 0;
//            //1、查找支付单号是否存在
//            $payment_info = $this->dmsFinanceDocRepository->getByRechargeNo($recharge_no);
////            $financeinfo=Db::table('dms_finance_doc')->where(['recharge_no'=> $recharge_no])->get();
////            $payment_info = json_decode($financeinfo,true);
////            $this->RechargeDocModel = model('RechargeDoc');
////            $payment_info = collection($this->RechargeDocModel->where(['recharge_no' => $recharge_no])->select())->toArray();
//
//            //收款单是否存在
//            if ($payment_info) {
//                if ($payment_info[0]['status'] == '1') {//已支付
//                    exit();
//                } else {
//                    //未支付：查询订单，并更新相关操作
//                    //查询订单，判断订单真实性
//                    if ($data['result_code'] != 'SUCCESS' && $data['return_code'] != 'SUCCESS'){
//                        exit();
//                    }
//                    $total_fee = floatval($data['total_fee']) / 100;
//                    if ($total_fee != floatval($payment_info[0]['account'])) {
//                        exit();
//                    }
//                    //更新支付单状态
//                    //事务开始
//                    $trade_no = $data['transaction_id'];
//
////                    Db::startTrans();
//                    try {
//                        $member_id = $payment_info[0]['user_id'];
//                        //更新支付单状态
//                        //事务开始
//                        $updateData = [
//                            'status' => '1',
//                            'trade_no' => $trade_no,
//                            'finishtime' => time()
//                        ];
//                        $update_result = $this->dmsFinanceDocRepository->updateFinanceDoc($recharge_no,$updateData);
//
//
////                        $update_result=Db::table('dms_finance_doc')->where(['recharge_no'=> $recharge_no])->update(['status' => '1', 'trade_no' => $trade_no]);
////                        $update_result = $this->RechargeDocModel->where(['recharge_no' => $recharge_no])->setField(['status' => '1', 'trade_no' => $trade_no]);
//                        if (!$update_result) {
//                            exit();
//                        }
//
//                        //没有出错的情况将分销店铺的余额更新
//                        $agentInfo = $this->dmsAgentInfoRepository->getTableList(['agent_info_id'=>$this->agentInfo['agent_info_id']])->toArray();
//                        $agent_balance = $agentInfo['data'][0]['agent_balance'];
//                        $agent_now_balance = $agent_balance+$total_fee;
//                        $balance_result = $this->dmsAgentInfoRepository->save(['agent_info_id'=>$this->agentInfo['agent_info_id'],'agent_balance'=>$agent_now_balance]);
//
//
////                        $this->AgentUserModels = model('AgentUser');
////                        $AgentUser = collection($this->AgentUserModels->where(['id'=>$member_id])->select())->toArray();
////                        $this->AgentUserInfoModels = model('AgentUserInfo');
////                        $mid=$AgentUser[0]['agent_id'];
////                        $admin_id=$AgentUser[0]['id'];
////                        $username=$AgentUser[0]['username'];
////
////                        $row = $this->AgentUserInfoModels->get($mid);
////                        //当前余额
////                        $now_balance = $row->balance + $total_fee;
////
////                        $balance_result = $this->AgentUserInfoModels->where(['id' => $mid])->setField(['balance' => $now_balance]);
//
//                        if (!$balance_result) {
//                            exit();
//                        }
////                        $amount_type = 0;
////                        $event = 1;
////                        $balance = $agent_balance;
////                        $desc = '账号[' . $username . ']系统操作 充值到余额￥' . $total_fee;
////                        $dataset[] = [
////                            'mid' => $AgentUser[0]['mid'],
////                            'admin_id' => $admin_id,
////                            'amount_type' => $amount_type,
////                            'event' => $event,
////                            'amount' => $total_fee,
////                            'amount_log' => $balance,
////                            'frozen_money' => 0,
////                            'frozen_money_log' => 0,
////                            'desc' => $desc,
////                            'trade_no' => $recharge_no,
////                            'business_number' => $trade_no,
////                            'operator'=> $username,
////                            'agent_id'=> $mid,
//////                            'm_username' => $username,
////                            'a_username' => $username,
////                        ];
//
////                        $recharge_mdl = model('AgentCapitalChange')->saveAll($dataset);
//
////                        if (!$recharge_mdl) {
////                            exit();
////                        }
//
////                        Db::commit();
//                        echo $pay->success();
//                        return ;
//                    } catch (CommonException $e) {
//
////                        Db::rollback();//事务回滚
////                        Log::write(date("Y-m-d H:i:s")."eee".$e->getMessage());
//                        $this->error($e->getMessage());
//                    }
//                }
//
//            } else {
//
//                exit();
//            }
//        }
//    }
//    /**
//     * 微信支付异步通知
//     */
//
//    public function ajax_check_recharge(Request $request)
//    {
//        $request = $request->all();
//        $change_doc = $this->dmsFinanceDocRepository->getByRechargeNo($request['order_no']);
////        $financeinfo=Db::table('dms_finance_doc')->where(['recharge_no'=> $request['order_no']])->get();
////        $change_doc = json_decode($financeinfo,true);
//
//        if ($change_doc[0]['status']==1){
//            return json_encode(['status' => 200, 'msg' => "支付成功"],JSON_UNESCAPED_UNICODE);
//        }else{
//            return json_encode(['status' => 201, 'msg' => "等待支付"],JSON_UNESCAPED_UNICODE);
//        }
//        //你可以在这里定义你的提示信息,但切记不可在此编写逻辑
//        //$this->success("恭喜你！支付成功!", addon_url("/finance/recharge/alipayreturn?ref=addtabs"));
//    }










}