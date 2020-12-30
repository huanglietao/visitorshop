<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SaasInnerTemplatesPages extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'inner_page_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_inner_templates_pages';

    //联表规格详情
    public function innerSizeInfo()
    {
        return $this->hasMany('App\Models\SaasSizeInfo','size_info_id','spec_info_id')
            ->where('size_type',GOODS_SIZE_TYPE_INNER);
    }



}

