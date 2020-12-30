<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SaasMaterial extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'material_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_material';

    //连表查素材图片
    public function materAttach()
    {
        return $this->hasMany('App\Models\SaasMaterialAttachment','material_atta_id','attachment_id');
    }

    

}