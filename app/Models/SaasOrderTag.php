<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 订单标签
 * @author: cjx
 * @version: 1.0
 * @date: 2020/05/06
 */

class SaasOrderTag extends Model
{
    use SoftDeletes;
    protected $table = 'saas_order_tag';
    protected $primaryKey="tag_id";
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $dates = ['deleted_at'];

}