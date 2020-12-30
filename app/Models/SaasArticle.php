<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SaasArticle extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'art_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_article';


    public function linkNews()
    {
        return $this->hasMany('App\Models\OmsNews','articles_id','art_id');
    }

    public function linkMchNews()
    {
        return $this->hasMany('App\Models\DmsNews','articles_id','art_id');
    }


}