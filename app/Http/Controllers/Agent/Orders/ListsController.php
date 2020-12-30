<?php
namespace App\Http\Controllers\Agent\Orders;

use App\Exceptions\CommonException;
use App\Http\Controllers\Agent\BaseController;
use App\Models\DmsAgentInfo;
use App\Models\SaasOrderProducts;
use App\Models\SaasProjects;
use App\Repositories\SaasCartRepository;
use App\Repositories\SaasCompoundQueueRepository;
use App\Repositories\SaasOrdersRepository;
use App\Repositories\SaasOrderTagRepository;
use App\Repositories\SaasPaymentRepository;
use App\Repositories\SaasSalesChanelRepository;
use App\Repositories\SaasSyncOrderConfRepository;
use App\Services\Helper;
use App\Services\Orders\OrdersEntity;
use App\Services\Orders\Status;
use Illuminate\Http\Request;
use App\Repositories\SaasUserReceivingAddressRepository;
use App\Repositories\DmsAgentInfoRepository;
use PHPUnit\Util\PHP\AbstractPhpProcess;
use App\Services\Common\Mongo;


/**
 * 分销订单列表
 * Created by PhpStorm.
 * Name: lietao
 * Date: 2019/8/7
 */

class ListsController extends BaseController
{

    protected $viewPath = 'agent.orders.list';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块

    public function __construct(SaasOrdersRepository $ordersRepository,SaasOrderTagRepository $orderTagRepository,SaasCompoundQueueRepository $compoundQueueRepository,
                        DmsAgentInfoRepository $dmsAgentInfoRepository,SaasSyncOrderConfRepository $syncOrderConfRepository
    )
    {
        parent::__construct();
        $this->repositories = $ordersRepository;
        $this->tagRepositories = $orderTagRepository;
        $this->compoundQueueRepository = $compoundQueueRepository;
        $this->dmsAgentInfoRepository = $dmsAgentInfoRepository;
        $this->syncOrderConfRepository = $syncOrderConfRepository;
        $this->mch_id = session("admin")['mch_id'];
        $this->agent_id = isset(session('admin')['agent_info_id']) ? session('admin')['agent_info_id'] : '';


    }

    //列表展示页面
    public function index()
    {
        $setting = $this->getSetting();
        $pageLimit = $setting['default_pages_limit'];
        return view("agent.orders.list.index",['pageLimit'=>$pageLimit]);
    }

    //ajax方式获取列表
    public function table(Request $request)
    {
        $inputs = $request->all();
        $list = $this->repositories->getTableList($inputs);
        $htmlContents = $this->renderHtml('',['list' =>$list['data']]);
        $total = $list['total'];

        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
    }

    //订单详情
    public function detail(Request $request)
    {
        $sync_sdk = $this->syncOrderConfRepository->getList(['agent_id'=>$this->agent_id])->toArray();
        if(!$sync_sdk){
            $sync_sdk = ZERO;
        }else{
            $sync_sdk = ONE;
        }

        $id = $request->id;
        $data = $this->repositories->orderInfo($id);

        return view("agent.orders.list.detail",['data'=>$data,'sync_sdk'=>$sync_sdk]);
    }

    //取消订单
    public function cancelOrder(Request $request)
    {
        try{
            try{
                \DB::beginTransaction();

                $order_id = $request->id;
                $res = $this->repositories->cancelOrder($order_id,session("admin")["dms_adm_username"],config('common.sys_abbreviation')['agent'],session("admin")["dms_adm_id"]);

                if($res){
                    \DB::commit();
                    return $this->jsonSuccess('订单取消成功');
                }
            } catch (\Exception $e) {
                \DB::rollBack();
                if(!empty($e->getMessage())){
                    return $this->jsonFailed($e->getMessage());
                }else{
                    //订单取消出错
                    app(\App\Services\Exception::class)->throwException('70084',__FILE__.__LINE__);
                }
            }

        }catch (CommonException $e){
            return $this->jsonFailed($e->getMessage());
        }
    }

    //确认收货
    public function confirmReceiver(Request $request)
    {
        $id = $request->post('order_id');
        try{
            $order_info = $this->repositories->getOrderInfo($id);
            if($order_info['order_status'] != ORDER_STATUS_WAIT_RECEIVE){
                //确认收货失败
                Helper::EasyThrowException('70043',__FILE__.__LINE__);
            }
           $res = app(Status::class)->updateToFinish($id);
           if($res){
               return $this->jsonSuccess('确认收货成功');
           }
        }catch (\Exception $e){
            return $this->jsonFailed($e->getMessage());
        }
    }

    //设置标签
    public function orderTag(Request $request)
    {
        $order_id = $request->id;
        try{
            try{
                if($request->post())
                {
                    \DB::beginTransaction();

                    $param = $request->all();
                    $res = $this->repositories->setTag($order_id,$param);
                    if($res){
                        \DB::commit();
                        return $this->jsonSuccess('设置成功',202);
                    }
                }else{
                    $tag_list = $this->tagRepositories->getTagList();

                    if(strpos($order_id,'[') !== false){
                        //批量标记
                        $htmlContents = $this->renderHtml('merchant.order.list.tag',['order_id'=>$order_id,'tag_list'=>$tag_list]);
                    }else{
                        //单个标记
                        $order_info = $this->repositories->getById($order_id);
                        $tag_arr = explode(',',$order_info['order_tag_id']);
                        $htmlContents = $this->renderHtml('merchant.order.list.tag',['order_id'=>$order_id,'tag_list'=>$tag_list,'tag'=>$tag_arr]);
                    }

                    return $this->jsonSuccess(['html' => $htmlContents]);
                }
            } catch (\Exception $e) {
                \DB::rollBack();
                if(!empty($e->getMessage())){
                    return $this->jsonFailed($e->getMessage());
                }else{
                    //订单标签设置出错
                    app(\App\Services\Exception::class)->throwException('70049',__FILE__.__LINE__);

                }
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }
    }

    //审核文件
    public function checkFile(Request $request)
    {
        try{
            $project_no = $request->number;
            $data = $this->repositories->checkFile($project_no);

            return view('merchant.order.list.check',['data'=>$data,'project_no'=>$project_no]);
        }catch (CommonException $e) {
            echo ($e->getMessage());
            return $this->jsonFailed($e->getMessage());
        }
    }

    //重新出图
    public function reloadImg(Request $request)
    {
        try{
            $project_no = $request->all('project_no');
            $result = $this->compoundQueueRepository->update(['project_sn'=>$project_no],['comp_queue_status'=>'ready']);

            if($result==1){
                return $this->jsonSuccess('');
            }else{
                return $this->jsonFailed('操作出错');
            }
        }catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }
    }

    //下载前检查文件
    public function downloadCheck(Request $request)
    {
        try{
            $param = $request->all();
            $res = $this->repositories->downloadFileCheck($param['ord_prod_id']);

            return $this->jsonSuccess($res);

        }catch (CommonException $e){
            return $this->jsonFailed($e->getMessage());
        }
    }

    //下载文件
    public function downloadFile(Request $request)
    {
        ini_set('memory_limit', '512M');
        $arr = $request->all();

        if(isset($arr['url']) && !empty($arr['url'])){
            //能打开文件即可开始下载
            $this->repositories->startDownload($arr['url']);
        }else{
            echo '出错了';
            die;
        }
    }

    //购物车
    public function shoppingCart()
    {
        $setting = $this->getSetting();
        $pageLimit = $setting['default_pages_limit'];
        return view("agent.orders.shoppingcart.index",compact('pageLimit'));
    }

    //ajax方式获取购物车列表
    public function cartTable(Request $request)
    {
        $htmlContents = $this->renderHtml('agent.orders.shoppingcart._table');

        return response()->json(['status' => 200, 'html' => $htmlContents,'total' => 56]);
    }

    //创建订单（视图）
    public function create(Request $request)
    {
        $post = $request->all();
        $cart_id = 0;
        $cartInfoArr = [];
        $isFast = 0;
        //组织sku与作品数组
        if (isset($post['is_fast']) && $post['is_fast'] == ONE)
        {
            //快速购买流程
            $isFast = 1;
            $cart_id = $post['cart_id'];
            $redis = app('redis.connection');
            $cartArr = $redis->get($cart_id);
            if (empty($cartArr)){
                echo "找不到对应作品信息，请重新购买";die;
            }
            $cartArr = json_decode($cartArr,true);
            foreach ($cartArr as $k => $v)
            {
                foreach ($v['sku_id'] as $kk => $vv)
                {
                    $cartInfoArr['sku_id'][$kk] = $vv;
                    $cartInfoArr['proj_id'][$kk] = $v['proj_id'][$kk];
                    $cartInfoArr['num'][$kk] = $v['num'][$kk];
                }
            }
        }else if (isset($post['is_fast']) &&  $post['is_fast']!= ONE){
            if (empty($cartArr)){
                echo "找不到对应作品信息，请重新购买";die;
            }
        }else{
            //正常购物车流程
            $cartArr = json_decode($post['cart_id'],true);
            if (empty($cartArr)){
                echo "找不到对应作品信息，请重新购买";die;
            }
            foreach ($cartArr as $k => $v)
            {
                $cart_id = $k;
                foreach ($v['sku_id'] as $kk => $vv)
                {
                    $cartInfoArr['sku_id'][$kk] = $vv;
                    $cartInfoArr['proj_id'][$kk] = $v['proj_id'][$kk];
                }
            }
        }

        //获取商品跟作品信息
        $workInfo = $this->repositories->getProjectInfo($cartInfoArr,$cart_id,$isFast);
        $totalPrice = 0;


        $channleRepository = app(SaasSalesChanelRepository::class);
        $chaId = $channleRepository->getAgentChannleId();
        //获取用户收货地址列表
        $userAdressRepository = app(SaasUserReceivingAddressRepository::class);
        $addressList = $userAdressRepository->getAddressList($this->agent_id,$chaId);

        //获取商品总价
        foreach ($workInfo as $k => $v)
        {
            $v['total_price'] = str_replace(',','',$v['total_price']);
            $totalPrice += $v['total_price'];
        }
        //获取商品数量
        $prod_count = count($workInfo);
        //获取商家设置的支付方式
        $paymentRepository = app(SaasPaymentRepository::class);
        $payment = $paymentRepository->getPaymentList(['mch_id'=>$this->mch_id ]);

        //获取用户余额
        $agentInfoModel = app(DmsAgentInfo::class);
        $balance = $agentInfoModel->where(['agent_info_id' => $this->agent_id])->value('agent_balance');


        return view("agent.orders.list.create",[
            'addressList' => $addressList,
            'workInfo'    => $workInfo,
            'cart_id'     => $cart_id,
            'payment'     => $payment,
            'balance'     => $balance,
            'prod_count'  => $prod_count,
            'totalPrice'  => $totalPrice,
            'is_fast'     => $isFast

        ] );
    }

    //获取物流方式与运费
    public function getCreateDeliveryPrice(Request $request)
    {

        $post = $request->post();
        $ret= $this->repositories->getCreateDeliveryPrice($post,$this->mch_id);
        if (isset($ret['code'])&&$ret['code'] == 0){
            return $this->jsonFailed($ret['msg']);
        }else{
            $HtmlContents = $this->renderHtml('agent.orders.list.delivery_select',[
                'deliveryList'               => $ret['deliveryList'],
            ]);
            return $this->jsonSuccess($HtmlContents,200);
        }
    }

    //创建订单
    public function orderCreate(Request $request)
    {
        $param = $request->post();
        $cart_id = 0;
        $cartInfoArr = [];
        $projectArr = [];
        $projectIdArr = [];
        //获取sku数组与作品数组
        foreach ($param['cart_arr'] as $k => $v)
        {
            $cart_id = $v['cart_id'];
            $cartInfoArr['sku_id'][$k] = $v['sku_id'];
            $cartInfoArr['proj_id'][$k] = $v['proj_id'];
            $projectIdArr[] = $v['proj_id'];
            if ($param['is_fast'])
            {
                //快速购买页面，只针对单个商品
                $redis = app('redis.connection');
                $cartRedisArr = $redis->get($cart_id);
                if (empty($cartRedisArr)){
                    echo "找不到对应作品信息，请重新购买";die;
                }
                $cartRedisArr = json_decode($cartRedisArr,true);
                $cartInfoArr['num'][$k] = $cartRedisArr[0]['num'][0];
            }
            //需删除的购物车数据
            $projectArr[$v['cart_id']][$k] =$v['sku_id'];

        }

        //获取商品跟作品信息
        $workInfo = $this->repositories->getProjectInfo($cartInfoArr,$cart_id,$param['is_fast']);
        $prodTotalPrice = 0;
        //获取商品总价
        foreach ($workInfo as $k => $v)
        {
            $v['total_price'] = str_replace(',','',$v['total_price']);
            $prodTotalPrice += $v['total_price'];
        }

        $param['prod_total_price'] = $prodTotalPrice;
        //组织创建订单所需数组
        $post_data = $this->repositories->getCreateData($param,$workInfo);
        $ordersEntity = app(OrdersEntity::class);
        $result = $ordersEntity->create($post_data);
        if($result['status']=='failed'){
            return $this->jsonFailed($result['msg']);
        }
        else if($result['status']=='success'){
            $mongo = new Mongo();
            //查看是否需要存进地址表
            if ($param['address']['type'] == '1')
            {
                //新增地址庫
                $addressRepositories = app(SaasUserReceivingAddressRepository::class);
                $channleRepository = app(SaasSalesChanelRepository::class);
                $cha_id = $channleRepository->getAgentChannleId();
                $addressRepositories->newAddress($param['address'],$this->agent_id,$cha_id);
            }

            $orderProductModel = app(SaasOrderProducts::class);
            $projectModel = app(SaasProjects::class);
            if (!empty($projectIdArr)){
                //反写作品状态 并记录操作日志
                foreach ($projectIdArr as $k => $v)
                {
                    $workLog = [
                        'user_id'    => $this->agent_id,
                        'works_id'   => $v,
                        'action'     => "创建订单成功",
                        'note'       => "订单号为【".$result['data']."】",
                        'createtime' => time(),
                        'operator'   => "购物车",
                    ];
                    $mongo->insert('diy_works_log',$workLog);
                    $projectModel->where('prj_id',$v)->where(['prj_status' => WORKS_DIY_STATUS_WAIT_CONFIRM])->update(['prj_status' => WORKS_DIY_STATUS_ORDER,'updated_at'=>time()]);
                }
            }
            if ($param['is_fast'])
            {
                //删除redis缓存数组 快速购买只针对一个商品
                $redis->del($cart_id);
            }else{
                //更新购物车数据
                $cartRepositories = app(SaasCartRepository::class);
                $cartRepositories->batchDelCartGoods($projectArr);
            }

            return $this->jsonSuccess([]);
        }
    }

    //物流信息
    public function logistics(Request $request)
    {
        try{
            $order_id = $request->id;
            $data = $this->repositories->getLogistics($order_id);

            $htmlContents = $this->renderHtml('agent.orders.list.logistics',['data'=>$data]);
            return $this->jsonSuccess(['html' => $htmlContents]);

        }catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }
    }

    /**
     * 返回成功的json
     * @param $data
     * @return array
     */
    protected function jsonSuccess($data,$status=201)
    {
        return response()->json(['status' =>$status , 'success' => 'true', 'data' => $data]);
    }


}