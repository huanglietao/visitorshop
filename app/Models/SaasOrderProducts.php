<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SaasOrderProducts extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'ord_prod_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = true;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_order_products';

    public function spDownload()
    {
        return $this->hasMany('App\Models\SaasSpDownloadQueue','ord_prod_id','ord_prod_id');
        // ->select('ord_id','prod_id','sku_id','prod_num','prod_sale_price','delivery_id','delivery_code');
    }

    public function order()
    {
        return $this->hasOne('App\Models\SaasOrders','order_id','ord_id');
    }

    public function prod()
    {
        return $this->hasOne('App\Models\SaasProducts','prod_id','prod_id')
            ->select('prod_id','prod_name');
    }

    public function prodSku()
    {
        return $this->hasOne('App\Models\SaasProductsSku','prod_sku_id','sku_id')
            ->select('prod_sku_id','prod_sku_sn','prod_sku_price');
    }

}