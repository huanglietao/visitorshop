<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 订单同步队列model
 *
 * @author: hlr <1013488674@qq.com>
 * @version: 1.0
 * @date: 2020/5/18
 */
class SaasOrderSyncQueue extends Model
{
    protected $primaryKey = 'sync_queue_id';
    protected $table = 'saas_order_sync_queue';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
}