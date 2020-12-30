<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * DMS权限菜单规则表
 *
 * @author: liujh
 * @version: 1.0
 * @date: 2020/04/28
 */
class DmsFinanceDoc extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'finance_doc_id';
    protected $table = 'dms_finance_doc';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = true;         //关闭时间timestamp
    protected $dateFormat = 'U';

}
