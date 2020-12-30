<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 所有api出错的日志表
 *
 * 可以使用这个表里的记录对api进行重新请求
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/8/1
 */
class SassApiErrLog extends Model
{
    protected $table = 'sass_api_err_log';
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
}
