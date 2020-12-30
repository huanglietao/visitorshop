<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * 商户角色组表
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020/04/08
 */
class OmsAuthGroup extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'oms_group_id';
    protected $table = 'oms_auth_group';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = true;         //关闭时间timestamp
    protected $dateFormat = 'U';
}
