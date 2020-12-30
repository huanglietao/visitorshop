<?php
namespace App\Repositories;

use App\Models\SaasSkuSupPrice;

/**
 * 商品对供应商仓库
 *
 * 商品对供应商仓库
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/21
 */
class SaasSkuSupPriceRepository extends BaseRepository
{
    public function __construct(SaasSkuSupPrice $model)
    {
        $this->model = $model;
    }
}