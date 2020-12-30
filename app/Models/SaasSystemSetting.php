<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * 系统基础设置表
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020/03/30
 */
class SaasSystemSetting extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'setting_id';
    protected $table = 'saas_system_setting';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
}
