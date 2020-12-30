<?php
namespace App\Http\Controllers\Agent;


use App\Http\Controllers\Api\Outer\ErpController;
use App\Http\Controllers\Backend\Printer;
use App\Models\DmsAgentInfo;
use App\Models\SaasCategory;
use App\Models\SaasDiyAssistant;
use App\Models\SaasOrders;
use App\Models\SaasProducts;
use App\Models\SaasProductsSku;
use App\Repositories\DmsAgentInfoRepository;
use App\Repositories\SaasDiyAssistantRepository;
use App\Repositories\SaasMainTemplatesRepository;
use App\Repositories\SaasProductsPrintRepository;
use App\Repositories\SaasProductsSkuRepository;
use App\Repositories\SaasProjectsOrderTempRepository;
use App\Repositories\SaasProjectsRepository;
use App\Services\Factory;
use App\Services\Helper;
use App\Services\Works\Sync;
use App\Services\Works\WorksAbstract;
use Illuminate\Http\Request;
use App\Exceptions\CommonException;


/**
 * Created by PhpStorm.
 * Name: lietao
 * Date: 2020/5/26
 */

class DiyAssistantController extends BaseController
{
    protected $viewPath = 'agent.diyassistant';  //当前控制器所的view所在的目录
    protected $modules = 'diy';        //当前控制器所属模块
    protected $noCookie = '__construct,index,table,workOperate,changeWorksNum,workDelete';
    public function __construct(DmsAgentInfoRepository $dmsAgentInfoRepository,SaasDiyAssistantRepository $diyAssistantRepository,DmsAgentInfo $dmsAgentInfo)
    {
        parent::__construct();
        $this->dmsAgentInfoRepository = $dmsAgentInfoRepository;
        $this->diyAssistantRepository = $diyAssistantRepository;
        $this->dmsAgentInfo = $dmsAgentInfo;
    }
    //列表展示页面
    public function index()
    {
        $agentID = \request()->get('aid');
        $orderNo  = \request()->get('order_no');

        if (empty($agentID)){
            echo  "链接出错,请确认链接是否正确";die;
        }
        //判断分销id是否为数字
        if (!preg_match("/^[1-9][0-9]*$/" ,$agentID)){
            echo "链接出错,请确认链接是否正确";die;
        }



        //获取店铺名称
        $shop_name = $this->dmsAgentInfo->where(['agent_info_id'=>$agentID])->get()->toArray();

        if (empty($shop_name))
        {
            echo "该商家不存在";
            die;
        }else{
            $shop_name = $shop_name[0]['agent_name'];
        }
        if ($this->isMobile()){
            //手机端
            return view("agent.diyassistant.mobile_index",['shop_name' => $shop_name,'workList' => [],'agent_id' => $agentID,'order_no' => $orderNo]);
        }else{
            //pc端
            return view("agent.diyassistant.index",['shop_name' => $shop_name,'workList' => [],'agent_id' => $agentID,'order_no' => $orderNo]);
        }
    }
    //ajax方式获取列表
    public function table(Request $request)
    {
        try{
            $inputs = $request->all();

            $prodAllInfo = $this->diyAssistantRepository->getWorkInfo($inputs['order_no'],$inputs['agent_id']);


            //获取所属商户id
            $mch_id = app(DmsAgentInfo::class)->where(['agent_info_id' => $inputs['agent_id']])->value('mch_id');
            if (isset($prodAllInfo['code']) && $prodAllInfo['code']==0)
            {
                $prodAllInfo = [];
            }
            if ($this->isMobile()){
                //手机端
                $tempOrderRepository = app(SaasProjectsOrderTempRepository::class);
                $diyAssistantModel = app(SaasDiyAssistant::class);
                $orderModel = app(SaasOrders::class);

                //查看该订单是否已经创建订单
                $isOrder = $orderModel->where('order_relation_no',$inputs['order_no'])->exists();
                if (empty($isOrder)){
                    $isOrder = 0;
                }else{
                    $isOrder = 1;
                }
                foreach ($prodAllInfo as $k => $v){
                    $pc = ZERO;
                    if (isset($v['pc'])){
                        $pc = $v['pc'];
                    }
                    //获取制作的作品情况
                    $workInfo = $tempOrderRepository->getTbOrderWorksInfo($v['order_no'],$v['sku_id'],[],$pc);
                    $prodAllInfo[$k]['work_info'] = $workInfo;
                }
                $htmlContents = $this->renderHtml('agent.diyassistant.mobile_table',['workList' => $prodAllInfo,'order_no'=>$inputs['order_no'],'agent_id' => $inputs['agent_id'],'mch_id' => $mch_id,'agent_url' => config("app.agent_url"),'is_order'=>$isOrder]);
            }else{
                $htmlContents = $this->renderHtml('',['workList' => $prodAllInfo,'order_no'=>$inputs['order_no'],'agent_id' => $inputs['agent_id'],'mch_id' => $mch_id,]);
            }


            return $this->jsonSuccess(['html' => $htmlContents,]);
        }catch (CommonException $e){
            $this->jsonFailed($e->getMessage());
        }
    }
    //作品操作表
    public function workOperate(Request $request)
    {
       $post = $request->post();
       $tempOrderRepository = app(SaasProjectsOrderTempRepository::class);
       $diyAssistantModel = app(SaasDiyAssistant::class);
       $orderModel = app(SaasOrders::class);
       $skuModel = app(SaasProductsSku::class);
        $productsModel = app(SaasProducts::class);
        $categoryModel = app(SaasCategory::class);

       //获取作品信息
        $workInfo = $tempOrderRepository->getTbOrderWorksInfo($post['order_no'],$post['sku_id']);
        foreach ($workInfo as $k => $v){
            $prod_id = $skuModel->where(['prod_sku_id' => $post['sku_id']])->value('prod_id');
            //判断商品是否是冲印类商品
            $prodCate = $productsModel->where(['prod_id' => $prod_id])->value('prod_cate_uid');
            //获取分类
            $cateName = $categoryModel->where(['cate_id' => $prodCate])->value('cate_flag');

            $workInfo[$k]['is_single'] = 0;
            $workInfo[$k]['temp_id'] = '';
            $workInfo[$k]['prod_id'] = $prod_id;
            $workInfo[$k]['sku_id'] = $post['sku_id'];
            if ($cateName == GOODS_DIY_CATEGORY_SINGLE)
            {
                //冲印类
                $workInfo[$k]['is_single'] = 1;
                //冲印类商品的编辑器地址不同

                //获取规格id
                $printInfo = app(SaasProductsPrintRepository::class)->getRow(['prod_id' => $prod_id]);

                $sizeId = $printInfo['prod_size_id'] ?? 0;
                if (!empty ($sizeId)) {
                    //获取模板id SaasMainTemplatesRepository
                    $tempInfo = app(SaasMainTemplatesRepository::class)->getRow(['specifications_id' => $sizeId,'main_temp_check_status' => TEMPLATE_STATUS_VERIFYED], ['main_temp_id']);
                    if (!empty($tempInfo)) {
                        $workInfo[$k]['temp_id'] = $tempInfo['main_temp_id'];
                    }
                }

                //判断淘宝货号是否带有冲印标识
                $firstStr = strstr($post['sku_sn'],SINGLE_SN);
                //判断是否有冲印商品的货号标识'-'
                if ($firstStr){
                    //含有冲印货号标识的商品，截取货号标识后面的字符串作为p数
                    $pc = substr($post['sku_sn'],strripos($post['sku_sn'],SINGLE_SN)+1);
                }
                if (!isset($pc) || empty($pc)){
                    $pc = ZERO;
                }
                $workInfo[$k]['pc'] = $pc;

            }
        }
        //获取作品一共可做的数量
        $projectsAllNum = $diyAssistantModel->where(['order_no' => $post['order_no'],'sku_id'=>$post['sku_id']])->value('prod_num');
        //获取随机数，避免多开弹窗
        $unique = uniqid();
        //查看该订单是否已经创建订单
        $isOrder = $orderModel->where('order_relation_no',$post['order_no'])->exists();
        if (empty($isOrder)){
            $isOrder = 0;
        }else{
            $isOrder = 1;
        }


        //获取所属商户id
        $mch_id = app(DmsAgentInfo::class)->where(['agent_info_id' => $post['agent_id']])->value('mch_id');
        $htmlContents = $this->renderHtml('agent.diyassistant.works_table',['workList' => $workInfo,'order_no'=>$post['order_no'],'agent_id' => $post['agent_id'],'mch_id' => $mch_id,'agent_url' => config("app.agent_url"),'projects_all_num' => $projectsAllNum,'unique'=>$unique,'is_order' => $isOrder]);

        return $this->jsonSuccess(['html' => $htmlContents,]);
    }

    //修改作品数量
    public function changeWorksNum(Request $request)
    {
        $post = $request->post();
        try{
            $res = $this->diyAssistantRepository->changeWorksNum($post);
            return $res;
        }catch (CommonException $e){
            return [
                'code' => 3,
                'msg'  => $e->getMessage()
            ];
        }


    }
    //删除作品
    public function workDelete(Request $request)
    {
        $projectRepository = app(SaasProjectsRepository::class);
        $project_id = $request->route('id');
        //判断作品是否已下单，已下单无法删除
        $pri_info = $projectRepository->getProjectInfo($project_id);

        if (!empty($pri_info) && $pri_info[0]['prj_status'] == WORKS_DIY_STATUS_ORDER)
        {
            return $this->jsonFailed("该作品已生成订单,无法删除");
        }

        $res = $projectRepository->delete($project_id);
        if($res) {
            return $this->jsonSuccess(['']);
        } else {
            return $this->jsonFailed("");
        }
    }

}