<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 商品对应供货商价格表
 *
 * 商品对应供货商价格表
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/21
 */
class SaasSkuSupPrice extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'sku_sup_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = true;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_sku2sup_price';
}