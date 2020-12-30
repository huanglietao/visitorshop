<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 * 客户等级定价模型
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/22
 */

class SaasSkuToCustlevelPrice extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'sku_cust_lv_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = true;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_sku2custlevel_price';
}