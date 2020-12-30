<?php
namespace App\Http\Controllers\Api\Sync\Taobao;

use App\Exceptions\CommonException;
use App\Http\Controllers\Api\Sync\BaseController;
use App\Repositories\SaasSyncOrderConfRepository;
use App\Services\Helper;
use Illuminate\Http\Request;

/**
 * 消息订阅相关接口
 *
 * 功能详细说明
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/5/19
 */
class SubscribeController extends BaseController
{
    /**
     * @param Request $request
     * @param SaasSyncOrderConfRepository $repoConf
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGroup(Request $request, SaasSyncOrderConfRepository $repoConf)
    {
        try{
            $agentId = $request->input('agent_id');
            if (empty($agentId)) {
                Helper::apiThrowException("10023",__FILE__.__LINE__);
            }
            $settingInfo = $this->getSyncSetting($repoConf, $agentId);
            $req = new \TmcGroupsGetRequest;
            $c = $settingInfo['handle'];

            if(!empty($request->input('page_no'))) {
                $req->setPageNo($request->input('page_no'));
            }

            if(!empty($request->input('page_size'))) {
                $req->setPageSize(page_size);
            }

            if(!empty($request->input('group_names'))) {
                $req->setGroupNames($request->input('group_names'));
            }

            $resp = $c->execute($req, $settingInfo['session_key']);

            return $this->success($resp);

        }catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }

    }


    /**
     * @param Request $request
     * @param SaasSyncOrderConfRepository $repoConf
     * @return \Illuminate\Http\JsonResponse
     */
    public function consumeMessage(Request $request, SaasSyncOrderConfRepository $repoConf)
    {
        try {
            $groupName = $request->input('group_name');
            $agentId = $request->input('agent_id');
            if (empty($agentId) || empty($groupName)) {
                Helper::apiThrowException("10023",__FILE__.__LINE__);
            }

            $settingInfo = $this->getSyncSetting($repoConf, $agentId);
            $req = new \TmcMessagesConsumeRequest;
            $c = $settingInfo['handle'];

            $req->setGroupName($groupName);
            $req->setQuantity(10);
            $resp = $c->execute($req, $settingInfo['session_key']);
            var_dump($resp);exit;
        }catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }

    }
}