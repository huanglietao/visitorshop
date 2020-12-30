<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 商品对应的供货商
 *
 * 商品对应支持的供货商模型
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/20
 */

class SaasProdSuppliers extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'prod_sup_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_prod_suppliers';
}