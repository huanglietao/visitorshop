<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SaasService extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'job_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = true;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_job';

    public function orderInfo()
    {
        return $this->hasOne('App\Models\SaasOrders','order_no','order_no')
            ->select('order_id',"order_no","order_real_total",'order_exp_fee','order_status','order_delivery_id','order_rcv_user','order_rcv_phone','order_rcv_zipcode','order_rcv_address','order_rcv_province','order_rcv_city','order_rcv_area','cha_id','user_id','delivery_code');
    }
}
