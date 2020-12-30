<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 模板对应的附件
 *
 * 功能详细说明
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/28
 */

class SaasTemplatesAttachment extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'temp_attach_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_templates_attachment';
}