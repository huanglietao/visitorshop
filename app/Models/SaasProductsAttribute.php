<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SaasProductsAttribute extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'attr_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_products_attribute';



    //连表获取属性值
    public function attributeValueAttach()
    {
        return $this->hasMany('App\Models\SaasAttributeValues','attr_id','attr_id');
    }
    //连表获取所属分类
    public function categoryAttach()
    {
        return $this->hasOne('App\Models\SaasCategory','cate_id','cate_id');
    }
}


