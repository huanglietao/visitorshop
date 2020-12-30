<?php
namespace App\Http\Controllers\Backend;

use App\Models\DmsAgentInfo;
use App\Models\OmsMerchantInfo;
use App\Models\SaasMainTemplates;
use App\Models\SaasMaterial;
use App\Models\SaasProducts;
use App\Models\SaasProjects;
use App\Models\SaasTemplatesLayout;
use App\Models\SaasUser;
use App\Repositories\DmsAgentInfoRepository;
use App\Repositories\SaasArticleRepository;
use App\Repositories\SaasCompoundQueueRepository;
use App\Repositories\SaasMainTemplatesRepository;
use App\Repositories\SaasOrderErpPushQueueRepository;
use App\Repositories\SaasOrderFileRepository;
use App\Repositories\SaasProductsRepository;
use App\Repositories\SaasProjectsRepository;
use Illuminate\Http\Request;
use App\Exceptions\CommonException;
use App\Services\Helper;
use App\Repositories\SaasOrdersRepository;
/**
 * 控制台
 *
 * 展示统计及报表的相关数据
 * @author: hlt <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/7/7
 */

class DashboardController extends BaseController
{



    public function index()
    {
        $merchantInfo = app(OmsMerchantInfo::class);
        //获取所有商户
        $data['merchant_user'] = $merchantInfo->select('mch_id','mch_name')->get()->toArray();
        $data['week_array'] = config('dashboard.search_time');


        return view('backend.dashboard.index',['userInfo'=>$this->userInfo,'greet'=>$this->getGreet(),'data'=>$data]);
    }
    //异步获取控制台部分渲染数据（分担渲染压力）
    public function getBaseDate(SaasOrdersRepository $ordersRepository,DmsAgentInfo $agentInfoModel,SaasUser $UserModel,
                                   SaasProductsRepository $productsRepository,SaasMainTemplates $mainTemplates,
                                   SaasTemplatesLayout $templatesLayout,SaasMaterial $material,SaasOrderFileRepository $fileRepository
    )
    {
        try{
            //获取昨天凌晨的时间戳
            $yesterday_start = strtotime(date("Y-m-d",strtotime("-1 day")));
            //获取昨天结束的时间戳
            $yesterday_end = $yesterday_start+24 * 60 * 60-1;
            //获取订单今天的销售额
            $data['today_amount'] = $ordersRepository->getTodayAmount();
            //获取订单昨天的销售额
            $data['yesterday_amount'] = $ordersRepository->getTodayAmount(null,$yesterday_start,$yesterday_end);
            //获取今日的订单数
            $data['today_order_count'] = $ordersRepository->getTodayOrderCount();
            //获取昨日的订单数
            $data['yesterday_order_count'] = $ordersRepository->getTodayOrderCount(null,$yesterday_start,$yesterday_end);
            //获取今日的订单发货数
            $data['today_order_shipping'] = $fileRepository->getAllOrderDeliveryCount();
            //获取昨日的订单发货数
            $data['yesterday_order_shipping'] = $fileRepository->getAllOrderDeliveryCount(null,$yesterday_start,$yesterday_end);
            //获取商家总数
            $data['merchant_count'] = $agentInfoModel->count();
            //获取会员总数
            $data['user_count'] = $UserModel->count();
            //获取所有商家的标准化商品跟自定义商品个数
            $data['products_count'] = $productsRepository->getMerchantProductsCount();
            //获取模板数量，布局数量与素材数量
            $data['template_count'] = $mainTemplates->count();
            $data['layout_count'] = $templatesLayout->count();
            $data['material_count'] = $material->count();

            //获取各个状态的订单数量
            $data['order_status_count'] = $ordersRepository->getOrderCount();
            return [
                'code' => 1,
                'data' => $data
            ];
        }catch (CommonException $e){
            return [
                'code' => 0,
                'msg'  => $e->getMessage()
            ];
        }
    }


    //获取控制台部分数据-排行性质的数据（分担渲染压力）
    public function getConsoleData(SaasMainTemplatesRepository $mainTemplatesRepository,SaasProducts $products,DmsAgentInfoRepository $agentInfoRepository
        )
    {
        try{
            //获取商品销量
            $data['sale_products_list'] = $products->orderBy('prod_sale_num','desc')->limit(10)->select('prod_id','prod_name','prod_sale_num')->get()->toArray();

            //获取模板使用量数据
            $data['template_list'] = $mainTemplatesRepository->getPopularTemplate();
            //获取大客户的订单量
            $data['agent_sale_order'] = $agentInfoRepository->getAgentSaleOrder();
            return [
                'code' => 1,
                'data' => $data
            ];

        }catch (CommonException $e){
            return [
                'code' => 0,
                'msg'  => $e->getMessage()
            ];
        }

    }
    //订单销售额
    public function getOrderSalesCount(SaasOrdersRepository $ordersRepository)
    {
        try{
            //获取订单销售额
            $orderData = $ordersRepository->salesAmount();
            $data['order_amount']['last_year'] = $orderData[1]; //去年每个月的销售额
            $data['order_amount']['this_year'] = $orderData[2]; //今年每个月的销售额
            return [
                'code' => 1,
                'data' => $data
            ];
        }catch (\Exception $e){
            return [
                'code' => 0,
                'msg'  => $e->getMessage()
            ];
        }
    }


    /**
     * 返回问候语
     * @return array
     */
    public function getGreet()
    {
        //获取星期
        $weekArray = array("日","一","二","三","四","五","六");
        $data['week'] = date("Y年m月d日")."星期".$weekArray[date("w")];

        //问好
        $hours = date("H");
        $data['greet'] = '您好!';
        if($hours < 11) {
            $data['greet'] = '早上好!';
        } else if ($hours < 13) {
            $data['greet'] = '中午好!';
        } else if ($hours < 19 ){
            $data['greet'] = '下午好!';
        } else {
            $data['greet'] = '晚上好';
        }

        return $data;
    }

    //根据订单跟商户获取订单数据
    public function getOrderTrend(Request $request)
    {
        $post = $request->post();
        $helper = app(Helper::class);
        $orderRepository = app(SaasOrdersRepository::class);
        $timeArr = $helper->getAllTimestamp($post['time_type']);
        $orderTrendTime = $timeArr['time_arr'];
        //获取订单趋势
        $orderTrendInfo = $orderRepository->getOrderTrendInfo($orderTrendTime,$post['merchant']);
        //获取商家规定时间内的订单量
        $orderCount = $orderRepository->getTrendOrderCount($timeArr['start_time'],$timeArr['end_time'],$post['merchant']);
        return [
            'order_trend_info' => $orderTrendInfo,
            'mid_order_count'  => $orderCount
        ] ;
    }
    //根据商家获取作品数据
    public function getWorkMonitor(Request $request)
    {
        $projectRepository = app(SaasProjectsRepository::class);
        $compoundRepository = app(SaasCompoundQueueRepository::class);
        $pushRepository = app(SaasOrderErpPushQueueRepository::class);
        $post = $request->post();
        $timeArr = $this->getTimeStamp();
        //获取作品监控状态
        $data['works_monitor'] = $projectRepository->peddingHandleCount($timeArr,$post['merchant']);
        //获取作品合成状态
        $data['work_compound'] = $compoundRepository->projectCompoundCount($timeArr,$post['merchant']);
        //推送生产实时监控
        $data['push_monitor']  = $pushRepository->pushMonitorCount($timeArr,$post['merchant']);
        return $data;
    }

    //根据时间获取发货统计
    public function getDeliveryMonitor(Request $request)
    {
        $post = $request->post();
        $helper = app(Helper::class);
        $orderFileRepository = app(SaasOrderFileRepository::class);
        $timeArr = $helper->getAllTimestamp($post['time_type']);
        $orderTrendTime = $timeArr['time_arr'];
        //订单发货统计
        $data['delivery_count'] = $orderFileRepository->getOrderDeliveryInfo($orderTrendTime);
        //订单交期统计
        $data['delivery_date'] = $orderFileRepository->getOrderDeliveryDateCount($timeArr['start_time'],$timeArr['end_time']);
        //订单发货区域统计
        $data['delivery_area'] = $orderFileRepository->getOrderDeliveryAreaCount($timeArr['start_time'],$timeArr['end_time']);

        return $data;

    }
    //返回距离现在2小时前，4小时前，6小时前，12小时前，24小时前的时间戳
    public function getTimeStamp()
    {
        $timeArr = [];
        $timeArr['two_hours']  = time()-2*60*60;
        $timeArr['four_hours'] = time()-4*60*60;
        $timeArr['six_hours']  = time()-6*60*60;
        $timeArr['tew_hours']  = time()-12*60*60;
        $timeArr['tf_hours']   = time()-24*60*60;
        return $timeArr;
    }
}