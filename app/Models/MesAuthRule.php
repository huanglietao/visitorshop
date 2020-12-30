<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * 供货商后台权限菜单规则表
 */
class MesAuthRule extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'scm_auth_rule_id';
    protected $table = 'scm_auth_rule';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';

}
