<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * 商户信息表
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020/04/01
 */
class OmsMerchantInfo extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'mch_id';
    protected $table = 'oms_merchant_info';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
}
