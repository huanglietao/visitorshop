<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * 功能简介
 *
 * 功能详细说明
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/8/1
 */
class NewsInfo extends Model
{
    protected $table = 'news_Info';

    const UPDATED_AT = 'updatetime';
    const CREATED_AT = 'createtime';
    protected $dateFormat = 'U';

    protected $casts = [
        'createtime'   => 'date:Y-m-d H:i:s',
        'updatetime'   => 'datetime:Y-m-d H:is',
    ];

    protected $fillable = [
        'mid', 'title', 'user_id', 'is_read','type','id','createtime'
    ];

}