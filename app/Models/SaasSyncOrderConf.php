<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 同步配置model
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/5/18
 */
class SaasSyncOrderConf extends Model
{
    protected $primaryKey = 'sdk_cnf_id';
    protected $table = 'saas_sync_order_conf';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
}