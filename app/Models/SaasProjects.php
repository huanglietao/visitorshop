<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 功能简介
 *
 * 功能详细说明
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/19
 */

class SaasProjects extends Model
{
    use SoftDeletes;
    protected $table = 'saas_projects';
    protected $primaryKey = 'prj_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';


    public function prjTemp()
    {
        return $this->hasOne('App\Models\SaasProjectsOrderTemp','prj_id','prj_id')->withTrashed();
    }

    public function prod()
    {
        return $this->hasOne('App\Models\SaasProducts','prod_id','prod_id');
    }

    public function prodSku()
    {
        return $this->hasOne('App\Models\SaasProductsSku','prod_sku_id','sku_id');
    }

}