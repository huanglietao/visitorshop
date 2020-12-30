<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 商品印刷模型
 *
 * 商品的印刷相关的属性数据
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/21
 */

class SaasProductsPrint extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'prod_pt_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_products_print';
}