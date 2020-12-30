<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SaasProductSize extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'size_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_product_size';

    //连表获取所属分类
    public function categoryAttach()
    {
        return $this->hasOne('App\Models\SaasCategory','cate_id','size_cate_id');
    }
}
