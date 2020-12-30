<?php
namespace App\Repositories;

use App\Models\SassApiErrLog;

/**
 * 对应SassApiErrLog的model数据仓库
 * @author: yanxs
 * @version: 1.0
 * @date: 2020/2/13
 */
class SassApiErrLogRepository extends BaseRepository
{
    public function __construct(SassApiErrLog $model)
    {
        $this->model =$model;
    }
}
