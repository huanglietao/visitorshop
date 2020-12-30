<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 供货商订单模型
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020/06/22
 */

class SaasNewSuppliersOrders extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'new_sp_ord_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = true;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_new_supplier_orders';

    public function prosku()
    {
        return $this->hasMany('App\Models\SaasProductsSku','prod_sku_id','sku_id')
            ->select('prod_sku_id','prod_supplier_sn');
    }

    public function product()
    {
        return $this->hasMany('App\Models\SaasProducts','prod_id','prod_id')
            ->select('prod_id','prod_name');
    }


}