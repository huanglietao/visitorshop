<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * N8订单表模型
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020/05/25
 */
class SaasOuterOrderCreateLog extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'out_order_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_outer_order_create_log';

}