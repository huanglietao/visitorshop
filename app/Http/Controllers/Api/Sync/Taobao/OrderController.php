<?php
namespace App\Http\Controllers\Api\Sync\Taobao;

use App\Exceptions\CommonException;
use App\Http\Controllers\Api\Sync\BaseController;
use App\Repositories\SaasSyncOrderConfRepository;
use App\Services\Helper;
use Illuminate\Http\Request;

/**
 * 淘宝订单相关接口
 *
 * 订单信息获取、订单状态返写、订阅消息消费等
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/5/18
 */
class OrderController extends BaseController
{
    /**
     * @param Request $request
     * @param SaasSyncOrderConfRepository $repoConf
     * @return \Illuminate\Http\JsonResponse
     */
    public function info(Request $request, SaasSyncOrderConfRepository $repoConf)
    {
        try {
            //参数 order_no淘宝订单号 agent_id 分销id
            $orderNo = $request->input('order_no');
            $agentId = $request->input('agent_id');
            if (empty($orderNo) || empty($agentId)) {
                Helper::apiThrowException("10023",__FILE__.__LINE__);
            }

            $settingInfo = $this->getSyncSetting($repoConf, $agentId);


            $fields = "tid,status,shipping_type,";
            //商品金额,交易创建时间,付款时间,交易修改时间
            $fields .= "total_fee,created,pay_time,modified,";
            //买家留言,支付宝交易号,卖家备注，发票抬头，发票类型
            $fields .= "buyer_message,alipay_no,seller_memo,invoice_name,invoice_type,";
            //买家昵称，买家邮件地址，收货人的姓名
            $fields .= "buyer_nick,buyer_email,receiver_name,seller_nick,";
            //收货人省份，收货人城市，收货人地区
            $fields .= "receiver_state,receiver_city,receiver_district,";
            //收货人地址，收货人邮编，收货人手机，收货人电话
            $fields .= "receiver_address,receiver_zip,receiver_mobile,receiver_phone,";
            //邮费，实付金额，买家支付宝账号，订单列表
            $fields .= "post_fee,payment,buyer_alipay_no,orders";
            //商品金额,交易创建时间,付款时间,交易修改时间

            $req = new \TradeFullinfoGetRequest();
            $req->setFields($fields);
            $req->setTid($orderNo);
            $c = $settingInfo['handle'];
            $resp = $c->execute($req, $settingInfo['session_key']);

            if (isset($resp->code)){ //请求接口错误
                Helper::apiThrowException('21002',__FILE__.__LINE__);
            }

            return $this->success($resp);

        }catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }

    }

    /**
     * 修改交易订单备注
     * @desc 传入淘宝订单号，添加/修改卖家订单备注
     * @return array $info 原样返回淘宝返回的数据
     */
    public function updateMemo(Request $request,SaasSyncOrderConfRepository $repoSyncConf)
    {
        try {
            //参数 order_no淘宝订单号 agent_id 分销id new_seller_memo 更新的备注信息
            $orderNo = $request->input('order_no');
            $agentId = $request->input('agent_id');
            $new_seller_memo = $request->input('new_seller_memo');
            $flag = 2;
            $settingInfo = $this->getSyncSetting($repoSyncConf, $agentId);

            $req = new \TradeMemoUpdateRequest;
            $req->setTid($orderNo);
            $req->setMemo($new_seller_memo);
            $req->setFlag($flag);

            $c = $settingInfo['handle'];
            $resp = $c->execute($req, $settingInfo['session_key']);

        } catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }

        return $this->success($resp);
    }

    /**
     * 获取订单图片信息
     * @desc 传入淘宝订单号，分销id
     * @return array $info 原样返回淘宝返回的数据
     */
    public function getPictureInfo(Request $request,SaasSyncOrderConfRepository $repoSyncConf)
    {
        try {
            //参数 order_no淘宝订单号 agent_id 分销id
            $orderId = $request->input('order_no');
            $agentId = $request->input('agent_id');
            $settingInfo = $this->getSyncSetting($repoSyncConf, $agentId);

            $req = new \MarketPictureGetuserpicturesRequest;
            $req->setOrderId($orderId);

            $c = $settingInfo['handle'];
            $resp = $c->execute($req, $settingInfo['session_key']);

        } catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
        return $this->success($resp);
    }
}