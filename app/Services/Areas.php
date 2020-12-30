<?php
namespace App\Services;

use App\Repositories\SaasAreasRepository;

/**
 * 地址库相关服务
 *
 * 使用到地址库相关逻辑功能编写
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/18
 */
class Areas
{
    protected $repoAreas ;
    public function __construct(SaasAreasRepository $areas)
    {
        $this->repoAreas = $areas;
    }

    /**
     * @param $areaId
     * @return array
     */
    public function getAreaById($areaId)
    {
        $info = $this->repoAreas->getRow(['area_id'=>$areaId]);
        $return = [];
        if (!empty($info)) {
            $return = [
                'id' => $info['area_id'],
                'name' => $info['area_name']
            ];
        }

        return $return;
    }
}