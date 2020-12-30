<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * 大后台权限菜单规则表
 *
 * @author: dai
 * @version: 1.0
 * @date: 2020/04/09
 */
class CmsAuthRule extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'cms_auth_rule_id';
    protected $table = 'cms_auth_rule';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';

}
