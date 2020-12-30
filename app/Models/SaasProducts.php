<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SaasProducts extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'prod_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_products';


    //连表获取所属分类
    public function printAttach()
    {
        return $this->hasOne('App\Models\SaasProductsPrint','prod_id','prod_id');
    }

}