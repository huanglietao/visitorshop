<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 下载队列模型
 *
 * 功能详细说明
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/23
 */
class SaasDownloadQueue extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'download_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_download_queue';

    public function item()
    {
        return $this->hasOne('App\Models\SaasOrderProducts','ord_prod_id','order_prod_id')
                    ->select('ord_prod_id','mch_id','ord_id','prod_id','sku_id','ord_prj_item_no','prod_num','ord_prj_item_no','prod_pages');

    }
}