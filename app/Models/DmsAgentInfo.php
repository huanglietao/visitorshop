<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class DmsAgentInfo extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'agent_info_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'dms_agent_info';

    public function account()
    {
        return $this->hasOne('App\Models\DmsMerchantAccount','agent_info_id','agent_info_id')->select('agent_info_id','dms_adm_username');
    }
}