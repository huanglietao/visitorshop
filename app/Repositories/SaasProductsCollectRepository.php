<?php
namespace App\Repositories;
use App\Models\SaasProductsCollect;

/**
 * 仓库模板
 * 仓库模板
 * @author:
 * @version: 1.0
 * @date:
 */
class SaasProductsCollectRepository extends BaseRepository
{

    public function __construct(SaasProductsCollect $model)
    {
        $this->model =$model;
    }

}
