<?php
namespace App\Http\Controllers\Merchant\Works;

use App\Http\Controllers\Merchant\BaseController;
use App\Http\Requests\Merchant\Works\WorksListRequest;
use App\Repositories\SaasProductsRepository;
use App\Repositories\SaasProjectPageRepository;
use App\Repositories\SaasProjectsRepository;
use App\Repositories\SaasSyncOrderConfRepository;
use App\Repositories\SaasSalesChanelRepository;
use Illuminate\Http\Request;
use App\Services\Helper;
use App\Services\Common\Mongo;


/**
 * 项目说明
 * 商户作品列表
 * @author: david
 * @version: 1.0
 * @date: 2020/6/3
 */
class WorksListController extends BaseController
{
    protected $viewPath = 'merchant.works.workslist';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $mid = '';
    protected $prjStatus = [];
    public function __construct(SaasProjectsRepository $Repository,SaasProductsRepository $productsRepository,
                                SaasSyncOrderConfRepository $syncOrderConfRepository,
                                SaasSalesChanelRepository $chanelRepository,SaasProjectPageRepository $projectPageRepository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->channelRepositories = $chanelRepository;
        $this->projectPageRepository = $projectPageRepository;
        $this->productsRepository = $productsRepository;

        $this->mid = empty(session('admin')) == false ? session('admin')['mch_id'] : ' ';
        $this->sync_sdk = $syncOrderConfRepository->getList(['mch_id'=>$this->mid])->toArray();
        $this->prjStatus = config("agent.project_status");
        //获取所属的渠道
        $channelList = $this->channelRepositories->getList()->toArray();
        $this->channelList = Helper::ListToKV('cha_id','cha_name',$channelList);
        if (session('prj_status')) {
            $this->firstType = session('prj_status');
        }else{
            $this->firstType = "all";
        }
        if(!$this->sync_sdk){
            $this->sync_sdk = ZERO;
        }else{
            $this->sync_sdk = ONE;
        }
        //作品标签
        $this->prjLabel = [
            '1'=>'ID不对',
            '2'=>'还差一本',
            '3'=>'还差作品',
            '4'=>'待退款重拍',
            '5'=>'待确认：数量',
            '6'=>'待确认：发货地址',
            '7'=>'已确认：请下单',
            '8'=>'未付款或无订单',
            '9'=>'订单有部分退款',
            '10'=>'作品与订单不符合'
        ];

    }

    //列表展示页面
    public function index()
    {
        $prjLabel = Helper::getChooseSelectData($this->prjLabel);
        $defaultKey = $this->firstType;
        $statusCount = $this->statusCount();
        $channelList = Helper::getChooseSelectData($this->channelList);
        return view("merchant.works.workslist.index",
            ['statusCount'=>$statusCount,
                'prjLabel'=>$prjLabel,
                'defaultKey'=>$defaultKey,
                'sync_sdk'=>$this->sync_sdk,
                'channelList'=>$channelList
            ]);
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
            session(['prj_status' => $inputs['prj_status']]);

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


            $inputs['mch_id'] = $this->mid;

            $list = $this->repositories->getTableList($inputs,$order)->toArray();
            //获取特殊分类的IDS 冲印和摆台&插画&框画
            $specialIds = config('goods.special_ids');
            foreach ($list['data'] as $k=>$v){
                $prod_info = $this->productsRepository->getProductInfo($this->mid,$v['prod_id']);
                //分类id
                $prod_cate_uid = $prod_info[0]['prod_cate_uid'];
                if(in_array($prod_cate_uid,$specialIds)){
                    //如果是冲印类的
                    if($prod_cate_uid==$specialIds['single']){
                        $list['data'][$k]['url'] = "http://".config("app.agent_url")."/printer/index.html?w=".$v['prj_id']."&sp=".$this->mid;
                    }else{
                        $list['data'][$k]['url'] = "http://".config("app.agent_url")."/ds/ed.html?w=".$v['prj_id']."&sp=".$this->mid;
                    }
                }else{
                    $list['data'][$k]['url'] = "http://".config("app.agent_url")."/ds/ed.html?w=".$v['prj_id']."&sp=".$this->mid;
                }
                //手机预览
                $list['data'][$k]['mobile_url'] = "http://".config("app.agent_url")."/ds_m?w=".$v['prj_id']."&sp=".$this->mid;
                if(!empty($v['coml_works_id'])){
                    $orgig = base64_encode($v['user_id'].'-'.$v['prod_id'].'-'.$v['sku_id']);
                    $list['data'][$k]['url'] = config("template.coml_pc_url")."/design?id=".$v['coml_works_id']."&mode=user&uprodsku=".$orgig;
                    $list['data'][$k]['mobile_url'] = config("template.coml_mobile_url")."/userDesign/".$v['coml_works_id']."?uprodsku=".$orgig;
                }

                $list['data'][$k]['prj_label'] = explode(",",$v['prj_label']);
            }

//            foreach ($list['data'] as $k=>$v){
//                $list['data'][$k]['prj_label'] = explode(",",$v['prj_label']);
//                //$list['data'][$k]['url'] = "http://".config("app.agent_url")."/ds/ed.html?w=".$v['prj_id']."&sp=".$this->merchantID."&a=".$this->agentID."";
//                $list['data'][$k]['url'] = "http://".config("app.agent_url")."/ds/ed.html?w=".$v['prj_id']."&sp=".$this->mid."";
//            }
            //$shopping_car = $this->shopping_car;
            $htmlContents = $this->renderHtml('',['list'=>$list['data'],'prjStatus'=>$this->prjStatus,'prjLabel'=>$this->prjLabel,'sync_sdk'=>$this->sync_sdk]);

            $total = $list['total'];
            return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
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

//                    $works_data = [
//                        'works_id' => strval($new_works_id),
//                        'stage' => "12333"
//                    ];

                    $mongo->insert('prj_stage', $works_data);

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
            foreach ($prj_id as $k=>$v){
                $data = [
                    'prj_id'=>$v,
                    'prj_status'=>WORKS_DIY_STATUS_MAKING,
                    'updated_at'=>time()
                ];
                $ret = $this->repositories->save($data);
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

    //修改作品页面
    public function edit(Request $request)
    {
        try{
            $prj_id = $request->get('prj_id');
            $project = $this->repositories->getTableList(['prj_id'=>$prj_id])->toArray();
            $statusList = Helper::getChooseSelectData($this->prjStatus);
            $htmlContents = $this->renderHtml('agent.works._edit',['project'=>$project['data'][0],'statusList'=>$statusList]);
            return $this->jsonSuccess(['html' => $htmlContents]);
        }catch (CommonException $e){
            $this->jsonFailed($e->getMessage());
        }
    }

   //添加/编辑操作
    public function save(WorksListRequest $request)
    {
        $ret = $this->repositories->save($request->all());
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
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

        $htmlContents = $this->renderHtml('merchant.works.workslist._log',['log_info'=>$arrInfo]);
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




}