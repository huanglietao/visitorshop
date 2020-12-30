<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;


class SaasMainTemplates extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'main_temp_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_main_templates';

    public function mainPages()
    {
        return $this->hasMany('App\Models\SaasMainTemplatesPages','main_temp_page_tid','main_temp_id');
            //->select(DB::raw('count(main_temp_page_id) as mpages'));
            //->select('main_temp_page_id','main_temp_page_name');
    }

}