<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SaasOrderErpPushQueue extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'order_erp_push_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_order_erp_push_queue';

    public function orderProduct()
    {
        return $this->hasMany('App\Models\SaasOrderProducts','ord_id','order_id')
           ->select('ord_id','ord_prod_id','ord_prj_item_no','prod_num');
          // ->select('ord_id','prod_id','sku_id','prod_num','prod_sale_price','delivery_id','delivery_code');
    }

    public function morder()
    {
        return $this->hasMany('App\Models\SaasOrders','order_id','order_id')
            ->select('order_id','order_no');
    }


}