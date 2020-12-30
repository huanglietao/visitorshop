<?php
namespace App\Services;

use App\Repositories\DmsAgentInfoRepository;
use App\Repositories\SaasCustomerLevelRepository;
use App\Repositories\SaasSalesChanelRepository;
use App\Repositories\SaasUserRepository;

/**
 * 渠道、会员等级、会员等逻辑服务
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/22
 */

class ChanelUser
{
    protected $repoChanel;
    protected $repoUser;
    protected $repoAgent;
    protected $repoGroup;

    /**
     * ChanelUser constructor.
     * @param SaasSalesChanelRepository $chanel
     * @param SaasUserRepository $user
     * @param DmsAgentInfoRepository $agent
     * @param SaasCustomerLevelRepository $group
     */
    public function __construct(SaasSalesChanelRepository $chanel, SaasUserRepository $user,
        DmsAgentInfoRepository $agent, SaasCustomerLevelRepository $group)
    {
        $this->repoUser    = $user;
        $this->repoChanel  = $chanel;
        $this->repoAgent   = $agent;
        $this->repoGroup   = $group;
    }


    /**
     * 通过渠道和用户得到用户组
     * @param $chanelId
     * @param $userId
     */
    public function getUserGroup($chanelId, $userId)
    {
        $info = $this->getUserRepository($chanelId)->getById($userId);
        return $info;
    }

    /**
     * 获取组别折扣(得到小数形式 如98折为0.98)
     * @param $groupId 组别id
     * @return float
     */
    public function getGroupDiscount($groupId)
    {
        $info = $this->repoGroup->getById($groupId);
        if (empty($info)) {
            Helper::EasyThrowException('10010',__FILE__.__LINE__); //记录不存在
        }

        return $info['cust_lv_discount']/100;
    }

    /**
     * 通过chanelId获取到使用的会员仓库
     * @param $chanelId
     * @return object
     */
    public function getUserRepository($chanelId)
    {
        $chanelInfo = $this->repoChanel->getById($chanelId);
        if (empty($chanelInfo)) {
            Helper::EasyThrowException('10010',__FILE__.__LINE__);
        }

        switch ($chanelInfo['cha_flag']) {
            case CHANEL_TERMINAL_AGENT:
                return $this->repoAgent;
                break;
            case CHANEL_TERMINAL_USER:
                return $this->repoUser;
                break;
            default :
                return $this->repoUser;
                break;
        }
    }

    /**
     * 获取会员/分销相关信息
     * @param $userId   用户id
     * @param $userType 用户类型
     * @return mixed
     */
    public function getUserInfo($userId, $userType)
    {
        if ($userType == CHANEL_TERMINAL_AGENT) {
            $info = $this->repoAgent->getById($userId);
        } else {
            $info = $this->repoUser->getById($userId);
        }

        return $info;
    }

    /**
     * 更新会员余额
     * @param $userId
     * @param $userType
     * @param $money  变动的金额 正数为增加 负数为减少
     * @return float
     */
    public function updateBalance($userId, $userType, $money)
    {
        //余额考虑到并发安全性问题，写原生的
        $absMoney = abs($money);
        $time = time();
        $version = uniqid();
        if ($userType == CHANEL_TERMINAL_AGENT) {
            if ($money <0)
                $sql = "UPDATE dms_agent_info SET updated_at=$time,version='$version',agent_balance=agent_balance-$absMoney WHERE agent_info_id=? AND agent_balance >= ?";
            else
                $sql = "UPDATE dms_agent_info SET updated_at=$time,version='$version',agent_balance=agent_balance+$absMoney WHERE agent_info_id=? AND agent_balance >= ?";

            $params = [$userId, 0-$money];
            $affected = \DB::update($sql, $params);

        } else {
            if ($money <0)
                $sql = "UPDATE saas_user SET updated_at=$time,version='$version',balance=balance-$absMoney WHERE user_id=? AND balance >= ?";
            else
                $sql = "UPDATE saas_user SET updated_at=$time,version='$version',balance=balance+$absMoney WHERE user_id=? AND balance >= ?";

            $params = [$userId, 0-$money];
            $affected = \DB::update($sql, $params);
        }
        return $affected;
    }

    /**
     * 获取渠道信息
     * @param $where
     * @return array
     */
    public function getChanelInfo($where)
    {
        return $this->repoChanel->getRow($where);
    }

}