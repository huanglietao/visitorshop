<?php
namespace App\Http\Controllers\Merchant;

use App\Models\DmsAgentInfo;
use App\Models\SaasUser;
use App\Repositories\DmsAgentInfoRepository;
use App\Repositories\SaasArticleRepository;
use App\Repositories\SaasProductsRepository;
use Illuminate\Http\Request;
use App\Exceptions\CommonException;
use App\Services\Helper;
use App\Repositories\SaasOrdersRepository;
/**
 * 控制台
 *
 * 展示统计及报表的相关数据
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/10/12
 */

class DashboardController extends BaseController
{
    public function index()
    {
        return view('merchant.dashboard.index',['userInfo'=>$this->userInfo,'greet'=>$this->getGreet()]);
    }


    //获取控制台数据
    public function getConsoleData(SaasOrdersRepository $ordersRepository,DmsAgentInfo $agentInfoModel,SaasUser $UserModel,SaasArticleRepository $articleRepository,
                                    SaasProductsRepository $productsRepository
        )
    {

        try{
            //获取商家id
            $mchId = $this->userInfo['mch_id']??"";
            if ($mchId == ""){
                Helper::EasyThrowException('22001', __FILE__.__LINE__);
            }
            //获取订单销售额
            $orderData = $ordersRepository->salesAmount($mchId);
            $data['order_amount']['last_year'] = $orderData[1]; //去年每个月的销售额
            $data['order_amount']['this_year'] = $orderData[2]; //今年每个月的销售额
            //获取订单今天的销售额
            $data['today_amount'] = $ordersRepository->getTodayAmount($mchId);
            //获取今日的订单数
            $data['today_order_count'] = $ordersRepository->getTodayOrderCount($mchId);
            //评论数暂时还没有 默认为0
            $data['today_order_comment'] = 0;
            //获取商家总数
            $data['merchant_count'] = $agentInfoModel->where('mch_id',$mchId)->count();
            //获取会员总数
            $data['user_count'] = $UserModel->where('mch_id',$mchId)->count();
            //获取平台公告
            $platformList = $articleRepository->getTableNewsList(['art_sign' => GOODS_MAIN_CATEGORY_ANNOUNCE,'limit'=>5,'mch_id'=>$mchId],'created_at desc')->toArray();
            //转换公告的创建时间
            foreach ($platformList['data'] as $k => $v){
                $data['platform_list'][$k] = $v;
                $data['platform_list'][$k]['create_time'] = date('m/d',$v['created_at']);
            }
            //获取该商家添加的两个最近的商品
            $productList = $productsRepository->getTableList(['mch_id' => $mchId,'limit'=>2],'created_at desc');
            $data['products_list'] = $productList['data'];
            //获取该商家的标准化商品跟自定义商品个数
            $data['products_count'] = $productsRepository->getMerchantProductsCount($mchId);
            //获取各状态的订单数目
            $data['order_count'] = $ordersRepository->getOrderCount($mchId);
            //获取商户后台与分销后台的地址
            $data['mch_url']   =  env('MERCHANT_URL');
            $data['agent_url'] =  env('AGENT_URL').'?mid='.Helper::easyEncrypt($mchId);
            $data['scm_url']   =  env('FACTORY_URL');

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
}