<?php
namespace App\Repositories;

use App\Models\SaasSyncOrderConf;

/**
 * 同步配置相关仓库
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/5/18
 */
class SaasSyncOrderConfRepository extends BaseRepository
{
    public function __construct(SaasSyncOrderConf $model)
    {
        $this->model =$model;
    }
}