<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 订单异常记录
 * @author: cjx
 * @version: 1.0
 * @date: 2020/05/27
 */

class SaasOrderException extends Model
{
    use SoftDeletes;
    protected $table = 'saas_order_exception';
    protected $primaryKey="ord_exception_id";
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $dates = ['deleted_at'];

}