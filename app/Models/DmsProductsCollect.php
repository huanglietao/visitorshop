<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * 分销商品收藏表
 *
 * @author: liujh
 * @version: 1.0
 * @date: 2020/07/21
 */
class DmsProductsCollect extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'prod_col_id';
    protected $table = 'dms_products_collect';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = true;         //关闭时间timestamp
    protected $dateFormat = 'U';

}
