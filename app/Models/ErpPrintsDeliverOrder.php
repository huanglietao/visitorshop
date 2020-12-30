<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ErpPrintsDeliverOrder extends Model
{
    protected $table = 'erp_prints_deliver_order';
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
}
