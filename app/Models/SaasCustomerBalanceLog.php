<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 客户金额变动日志
 *
 * 记录余额变动日志详情
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/23
 */
class SaasCustomerBalanceLog extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'cus_balance_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_customer_balance_log';
}