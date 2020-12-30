<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ErpPrintLog extends Model
{
    protected $table = 'erp_print_log';
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
}
