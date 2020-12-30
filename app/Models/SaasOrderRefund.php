<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 退款单模型
 *
 * 功能详细说明
 * @author: cjx
 * @version: 1.0
 * @date: 2020/06/05
 */
class SaasOrderRefund extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'refund_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_order_refund';

}