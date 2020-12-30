<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 淘宝消息队列表model
 *
 * @author: hlr <1013488674@qq.com>
 * @version: 1.0
 * @date: 2020/5/29
 */
class SaasTbOrderMessage extends Model
{
    protected $primaryKey = 'tb_msg_id';
    protected $table = 'saas_tb_order_message';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
}