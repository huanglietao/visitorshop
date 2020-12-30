<?php
namespace App\Repositories;

use App\Models\SaasDiyImages;

/**
 * 客户上传图片仓库
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/5/8
 */
class SaasDiyImagesRepository extends BaseRepository
{
    public function __construct(SaasDiyImages $model)
    {
        $this->model = $model;
    }
}