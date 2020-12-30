<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * DMS权限菜单规则表
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020/04/27
 */
class DmsAuthRule extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'dms_auth_rule_id';
    protected $table = 'dms_auth_rule';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = true;         //关闭时间timestamp
    protected $dateFormat = 'U';

}
