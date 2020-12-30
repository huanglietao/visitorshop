<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * 商户账号表
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020/04/03
 */
class OmsMerchantAccount extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'oms_adm_id';
    protected $table = 'oms_merchant_account';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
}
