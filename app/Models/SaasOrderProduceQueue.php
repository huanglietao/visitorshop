<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 生产队列模型
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/26
 */

class SaasOrderProduceQueue extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'produce_queue_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = true;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_order_produce_queue';



    public function morder()
    {
        return $this->hasMany('App\Models\SaasOrders','order_id','order_id')
            ->select('order_id','order_no');
    }

}