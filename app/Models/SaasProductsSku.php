<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 * @author: hlt
 * @version: 1.0
 * @date: 2020/4/17
 */
class SaasProductsSku extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'prod_sku_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_products_sku';
}
