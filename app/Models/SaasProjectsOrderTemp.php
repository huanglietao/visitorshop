<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 功能简介
 *
 * 功能详细说明
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date: 2020/5/06
 */

class SaasProjectsOrderTemp extends Model
{
    use SoftDeletes;
    protected $table = 'saas_projects_order_temp';
    protected $primaryKey = 'prj_info_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
}