<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SaasDeliveryQueue extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'delivery_push_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_delivery_queue';

    public function morder()
    {
        return $this->hasMany('App\Models\SaasOrders','order_id','order_id')
        ->select('order_id','order_no');
    }
}
