<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SaasOrders extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'order_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = true;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_orders';

    public function item()
    {
        return $this->hasMany('App\Models\SaasOrderProducts','ord_id','order_id')
                    ->select('ord_id','order_no','prod_id','sku_id','prod_num','prod_sale_price','ord_prj_item_no','delivery_id','delivery_code');
    }

    public function chanel()
    {
        return $this->hasOne('App\Models\SaasSalesChanel','cha_id','cha_id')->select('cha_id','cha_name');
    }

    //三表联合查询写法，主表(order)、中间表(order_product)、关联表(product_sku)
    public function sku()
    {
        return $this->belongsToMany('App\Models\SaasProductsSku','saas_order_products','ord_id','sku_id','order_id','prod_sku_id')->select('prod_sku_id','prod_sku_sn');
    }
}