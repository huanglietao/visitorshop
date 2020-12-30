<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 供货商订单模型
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020/05/07
 */

class SaasSuppliersOrders extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'sp_ord_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = true;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_suppliers_orders';

    public function item()
    {
        return $this->hasMany('App\Models\SaasSuppliersOrderProduct','sp_ord_id','sp_ord_id')
            ->select('sp_ord_id','ord_prod_id','ord_id','prod_id','sku_id','sp_nums','prod_price','prj_type');
    }
}