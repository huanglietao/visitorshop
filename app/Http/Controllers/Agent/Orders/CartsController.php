<?php
namespace App\Http\Controllers\Agent\Orders;



use App\Http\Controllers\Backend\Printer;
use App\Models\SaasCustomerLevel;
use App\Repositories\DmsAgentInfoRepository;
use App\Repositories\SaasCartRepository;
use App\Repositories\SaasOrdersRepository;
use App\Repositories\SaasProjectsRepository;
use App\Repositories\SaasSalesChanelRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Agent\BaseController;


/**
 * Created by PhpStorm.
 * Name: lietao
 * Date: 2019/8/14
 */

class CartsController extends BaseController
{
    protected $viewPath = 'backend.goods.products';  //当前控制器所的view所在的目录
    protected $modules = 'order';        //当前控制器所属模块
    public function __construct(SaasCartRepository $cartRepository, SaasCustomerLevel $customerLevel,DmsAgentInfoRepository $dmsAgentInfoRepository)
    {
        parent::__construct();
        $this->repositories = $cartRepository;
        $this->userInfo = session('admin');
        //获取用户id
        $this->userId = $this->userInfo['agent_info_id'];
        //获取用户等级id
        $this->userLevelId = $dmsAgentInfoRepository->getCustLvId($this->userId);
        //获取用户类型
        $this->userType = $customerLevel->where(['cust_lv_id'=>$this->userLevelId])->value('cust_lv_type');

    }


    //购物车列表展示页面
    public function index()
    {
        return view("agent.orders.shoppingcart.index");
    }


    //ajax方式获取列表
    public function table(Request $request)
    {

        $inputs = $request->all();
        //获取分销渠道id
        $channleRepository = app(SaasSalesChanelRepository::class);
        $cha_id = $channleRepository->getAgentChannleId();

        $inputs['user_id'] = $this->userId;
        $inputs['user_type'] = $this->userType;
        $inputs['cha_id'] = $cha_id;
        $list = $this->repositories->getTableList($inputs);


        $htmlContents = $this->renderHtml('agent.orders.shoppingcart._table',['list' =>$list]);
        return $this->jsonSuccess(['html' => $htmlContents,'total' => 56]);
    }

    //更新商品数量
    public function changeCartGoodsNum(Request $request)
    {
        $post = $request->post();
        $this->repositories->changeCartGoodsNum($post);
        return $this->jsonSuccess([]);
    }
    //删除购物车商品
    public function delCartGoods(Request $request)
    {

        $data = $request->all();
        $this->repositories->delCartGoods($data['cid'],$data['pid'],$data['sid']);
        return $this->jsonSuccess([]);
    }
    //收藏商品
    public function collectCartGoods(Request $request)
    {

        $post = $request->post();
        $res = $this->repositories->collectCartGoods($post['sku_id'],$post['cart_id']);

        if (isset($res['code'])&&$res['code']==0)
        {
            return $this->jsonFailed("收藏失败");
        }else{
            return $this->jsonSuccess([]);
        }

    }
    //批量删除商品
    public function batchDelCartGoods(Request $request)
    {
        $data = $request->all('data');
        $delArr = json_decode($data['data'],true);
        $res = $this->repositories->batchDelCartGoods($delArr);

        if (isset($res['code'])&&$res['code']==0)
        {
            return $this->jsonFailed("收藏失败");
        }else{
            return $this->jsonSuccess([]);
        }
    }


}