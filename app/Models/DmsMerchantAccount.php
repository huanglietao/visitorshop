<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class DmsMerchantAccount extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'dms_adm_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = true;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'dms_merchant_account';

    public function info()
    {
        return $this->hasOne('App\Models\DmsAgentInfo','agent_info_id','agent_info_id')->select('agent_info_id','agent_type','agent_name','agent_desc','cust_lv_id','agent_balance');
    }
}