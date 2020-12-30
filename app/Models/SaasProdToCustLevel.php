<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SaasProdToCustLevel extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'ptc_lv_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = true;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_prod2cust_level';


}