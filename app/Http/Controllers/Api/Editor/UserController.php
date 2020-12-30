<?php
namespace App\Http\Controllers\Api\Editor;

use App\Exceptions\CommonException;
use App\Repositories\CmsAdminRepository;
use App\Repositories\OmsMerchantAccountRepository;
use App\Services\ChanelUser;
use App\Services\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

/**
 * 用户相关的接口(会员/分销/其他渠道)
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/28
 */

class UserController extends BaseController
{

    /**
     * 获取分销商信息
     * @param Request $request
     * @param ChanelUser $chanelUser
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAgentInfo(Request $request, ChanelUser $chanelUser)
    {
        try {
            //参数验证,简单验证不走自定义request
            if (empty ($request->input('agent_id'))){
                return $this->error('','参数错误!');
            }
            $agentId = intval($request->input('agent_id'));

            $agentInfo = $chanelUser->getUserInfo($agentId, CHANEL_TERMINAL_AGENT);

            //分销不可用或不存在
            if (empty($agentInfo) || empty($agentInfo['agent_status'])) {
                Helper::EasyThrowException('11101',__FILE__.__LINE__);
            }

            $info = [
                'agent_id'      => $agentId,
                'group_id'      => $agentInfo['cust_lv_id'],
                'true_name'     => $agentInfo['agent_linkman'],
                'shop_name'     => $agentInfo['agent_name'],
                'shop_url'      => '',
                'shop_type_id'  => $agentInfo['agent_type'],
                'mobile'        => $agentInfo['mobile'],
                'qq'            => '',
                'email'         => $agentInfo['email'],
                'status'        => PUBLIC_YES,
            ];

            $data['info'] = $info;

            return $this->success([$data]);

        } catch (CommonException $e) {
            return $this->error($e->getCode(),$e->getMessage());
        }
    }

    /**
     * 获取管理员信息
     * @param Request $request
     * @param CmsAdminRepository $admin
     * @param OmsMerchantAccountRepository $mchAccount
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAdminInfo(Request $request, CmsAdminRepository $admin, OmsMerchantAccountRepository $mchAccount)
    {
        try {
            Redis::select(config("database.redis.session.database"));
            $token = $request->input('token');
            $mchId = $request->input('sp_id');
            $info = Redis::get("laravel_cache:".$token);
            $adminInfo = unserialize(unserialize($info));

            //未登录情况
            if (empty($adminInfo)) {
                Helper::apiThrowException('20002',__FILE__.__LINE__);
            }

            //传入了商户id的情况取商户登录
            if (!empty ($mchId)) {
                if (!isset($adminInfo['admin']['oms_adm_id'])) {
                    Helper::apiThrowException('20002',__FILE__.__LINE__);
                }
                $userId = $adminInfo['admin']['oms_adm_id'];
                $admInfo = $mchAccount->getRow(['oms_adm_id' => $userId]);
                if (empty($admInfo)) {
                    Helper::apiThrowException('20002',__FILE__.__LINE__);
                }
                $groupId = $admInfo['oms_adm_group_id'];
                $trueName = $admInfo['oms_adm_nickname'];
                $status   = $admInfo['oms_adm_status'] == 'normal' ? 1 : 0;

            } else {
                if (!isset($adminInfo['admin']['cms_adm_id'])) {
                    $userId = $adminInfo['admin']['oms_adm_id'] ?? -1;
                    $admInfo = $mchAccount->getRow(['oms_adm_id' => $userId]);
                    if (empty($admInfo)) {
                        Helper::apiThrowException('20002',__FILE__.__LINE__);
                    }
                    $groupId = $admInfo['oms_adm_group_id'];
                    $trueName = $admInfo['oms_adm_nickname'];
                    $status   = $admInfo['oms_adm_status'] == 'normal' ? 1 : 0;

                } else {
                    $userId = $adminInfo['admin']['cms_adm_id'];
                    $admInfo = $admin->getRow(['cms_adm_id' => $userId]);
                    $groupId = $admInfo['cms_adm_group_id'];
                    $trueName = $admInfo['cms_adm_nickname'];
                    $status   = $admInfo['cms_adm_status'] == 'normal' ? 1 : 0;
                }

            }

            $return = [];

            $return['info']['user_id'] = $userId;
            $return['info']['group_id'] =  $groupId;
            $return['info']['true_name'] = $trueName;
            $return['info']['mobile'] = '';
            $return['info']['status'] = $status;
            return $this->success([$return]);

        } catch (CommonException $e) {
            return $this->error($e->getCode(),$e->getMessage());
        }
    }
}