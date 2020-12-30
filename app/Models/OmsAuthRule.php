<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * 商户后台权限菜单规则表
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020/04/13
 */
class OmsAuthRule extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'oms_auth_rule_id';
    protected $table = 'oms_auth_rule';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';

}
