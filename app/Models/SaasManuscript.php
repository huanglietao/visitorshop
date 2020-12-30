<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 通过稿件上传的仓库
 *
 * 包括内部稿件上传和外部的稿件上传
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/19
 */

class SaasManuscript extends Model
{
    use SoftDeletes;
    protected $table = 'saas_manuscript';
    protected $primaryKey = 'script_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
}