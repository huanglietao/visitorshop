<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SaasUserReceivingAddress extends Model
{
    /*
     * 规格详细参数模型
      */
    use SoftDeletes;
    protected $primaryKey = 'rcv_addr_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_user_receiving_address';
}