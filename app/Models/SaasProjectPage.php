<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @author: cjx
 * @version: 1.0
 * @date: 2020/05/07
 */

class SaasProjectPage extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'prj_page_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = true;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_projects_page';
}