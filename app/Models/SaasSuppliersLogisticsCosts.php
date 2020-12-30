<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 供货商订单模型
 *
 * @author: liujh
 * @version: 1.0
 * @date: 2020/06/16
 */

class SaasSuppliersLogisticsCosts extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'sup_log_cos_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = true;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_suppliers_logistics_costs';

}