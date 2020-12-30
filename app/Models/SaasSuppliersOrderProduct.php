<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 供货商订单详情模型
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020/05/07
 */

class SaasSuppliersOrderProduct extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'sp_ord_prod_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = true;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_suppliers_order_products';
}