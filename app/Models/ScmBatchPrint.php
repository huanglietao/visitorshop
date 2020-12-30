<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 供货商批量打单表
 * @author: cjx
 * @version: 1.0
 * @date: 2020/06/09
 */

class ScmBatchPrint extends Model
{
    use SoftDeletes;
    protected $table = 'scm_batch_print';
    protected $primaryKey="batch_print_id";
    protected $guarded = [];            //save的黑名单
    public $timestamps = true;
    protected $dateFormat = 'U';
    protected $dates = ['deleted_at'];

}