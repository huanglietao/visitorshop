<?php
namespace App\Http\Controllers\Api\Sync\Taobao;

use App\Exceptions\CommonException;
use App\Http\Controllers\Api\Sync\BaseController;
use App\Http\Requests\Api\Sync\OfflineSendRequest;
use App\Repositories\SaasSyncOrderConfRepository;
use App\Services\Helper;
use Illuminate\Http\Request;

/**
 * 物流相关接口
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/5/19
 */
class LogisticsController extends BaseController
{
    /**
     * 发货物流回写接口
     * @param OfflineSendRequest $request
     * @param SaasSyncOrderConfRepository $repoSyncConf
     * @return \Illuminate\Http\JsonResponse
     */
    public function offlineSend(OfflineSendRequest $request, SaasSyncOrderConfRepository $repoSyncConf)
    {
        try {
            $orderNo = $request->input('order_no');
            $agentId = $request->input('agent_id');
            $deliveryCode = $request->input('out_sid');
            $deliveryCompCode = strtoupper($request->input('company_code'));

            $settingInfo = $this->getSyncSetting($repoSyncConf, $agentId);


            $req = new \LogisticsOfflineSendRequest;
            $req->setTid($orderNo);
            $req->setOutSid($deliveryCode);
            $req->setCompanyCode($deliveryCompCode);

            $c = $settingInfo['handle'];
            $resp = $c->execute($req, $settingInfo['session_key']);
            if (isset($resp['code'])){ //请求接口错误
                Helper::apiThrowException('21002',__FILE__.__LINE__);
            }

            return $this->success($resp);

        } catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }

    }

    /**
     * 物流重新回写接口
     * @param OfflineSendRequest $request
     * @param SaasSyncOrderConfRepository $repoSyncConf
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(OfflineSendRequest $request, SaasSyncOrderConfRepository $repoSyncConf)
    {
        try {
            $orderNo = $request->input('order_no');
            $agentId = $request->input('agent_id');
            $deliveryCode = $request->input('out_sid');
            $deliveryCompCode = strtoupper($request->input('company_code'));

            $settingInfo = $this->getSyncSetting($repoSyncConf, $agentId);


            $req = new \LogisticsConsignResendRequest();
            $req->setTid($orderNo);
            $req->setOutSid($deliveryCode);
            $req->setCompanyCode($deliveryCompCode);

            $c = $settingInfo['handle'];
            $resp = $c->execute($req, $settingInfo['session_key']);
            if (isset($resp['code'])){ //请求接口错误
                Helper::apiThrowException('21002',__FILE__.__LINE__);
            }

            return $this->success($resp);

        } catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }

    }

    public function test(Request $request,SaasSyncOrderConfRepository $repoSyncConf)
    {
        $orderId = $request->input('order_no');
        $agentId = $request->input('agent_id');
        $settingInfo = $this->getSyncSetting($repoSyncConf, $agentId);

        $req = new \MarketPictureGetuserpicturesRequest;
        $req->setTid($orderId);

        $c = $settingInfo['handle'];
        $resp = $c->execute($req, $settingInfo['session_key']);

    }

}