<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SaasOuterErpOrderCreateQueue extends Model
{
    protected  $table = 'saas_outer_erp_order_create_queue';
    use SoftDeletes;
    protected $primaryKey = 'outer_queue_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';

    public function createQueue()
    {
        return $this->hasOne('App\Models\SaasOuterErpOrderCreate','outer_order_id','order_create_id');
    }
}