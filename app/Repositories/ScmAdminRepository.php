<?php
namespace App\Repositories;
use App\Models\ScmAdmin;
use App\Services\Helper;
use Illuminate\Support\Facades\DB;

/**
 * 仓库模板
 * 供货商账户仓库模板
 * @author: david
 * @version: 1.0
 * @date:  2020/6/09
 */
class ScmAdminRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存

    public function __construct(ScmAdmin $model)
    {
        $this->model =$model;
    }



}
