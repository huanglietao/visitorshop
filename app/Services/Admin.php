<?php
namespace App\Services;

use Illuminate\Support\Facades\Redis;
/**
 * 后台账号、登录验证、权限相关
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/8/4
 */

class Admin
{
    /**
     * 根据头传过来的cookie中的laravel_session来判断
     * 管理员是否已经登录
     * @param $token cookie中的laravel_session
     * @return boolean
     */
    public function isAdministrator($token)
    {
        Redis::select(config("database.redis.session.database"));
        $info = Redis::get("laravel_cache:".$token);
        $adminInfo = unserialize(unserialize($info));

        $cmsAdmin = $adminInfo['admin']['cms_adm_id'] ?? 0;
        $omsAdmin =  $adminInfo['admin']['oms_adm_id'] ?? 0;

        if (empty($cmsAdmin) && empty($omsAdmin)) {
            return false;
        } else {
            return true;
        }
    }
}