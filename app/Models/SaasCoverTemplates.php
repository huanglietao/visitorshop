<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SaasCoverTemplates extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'cover_temp_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_cover_templates';

    public function sizeInfo()
    {
        return $this->hasMany('App\Models\SaasSizeInfo','size_id','specifications_id')
            ->whereIn('size_type',[1,2]);
    }


}