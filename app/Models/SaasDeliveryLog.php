<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * 订单发货日志表
 */
class SaasDeliveryLog extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'delivery_log_id';
    protected $table = 'saas_delivery_log';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = true;         //关闭时间timestamp
    protected $dateFormat = 'U';

}
