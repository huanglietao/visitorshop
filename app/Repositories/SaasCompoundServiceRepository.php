<?php
namespace App\Repositories;

use App\Models\SaasCompoundService;

/**
 * 合成服务器仓库表
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/5/12
 */

class SaasCompoundServiceRepository extends BaseRepository
{
    public function __construct(SaasCompoundService $model)
    {
        $this->model = $model;
    }
}