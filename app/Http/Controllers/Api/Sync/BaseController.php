<?php
namespace App\Http\Controllers\Api\Sync;

use App\Repositories\SaasSyncOrderConfRepository;
use App\Services\Helper;

/**
 * 同步接口基础类
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/5/18
 */
class BaseController extends \App\Http\Controllers\Api\BaseController
{
    /**
     * 获取聚石塔配置信息
     * @param SaasSyncOrderConfRepository $repoConf
     * @param $agentId
     * @return array
     */
    public function getSyncSetting(SaasSyncOrderConfRepository $repoConf, $agentId)
    {
        $info = $repoConf->getRow(['agent_id' => $agentId]);
        if (empty($info)) {
            Helper::apiThrowException("10010",__FILE__.__LINE__);
        }
        $arrSetting = json_decode($info['sdk_cnf_info'], true);

        if (empty($arrSetting)) {
            Helper::apiThrowException("21001",__FILE__.__LINE__);
        }

        $c = new \TopClient();

        $c->appkey = $arrSetting['appkey'];
        $c->secretKey  = $arrSetting['secretKey'];

        return ['handle' => $c, 'session_key' => $arrSetting['sessionKey']];
    }
}