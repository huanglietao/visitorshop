<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 合成队列模型
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/23
 */
class SaasCompoundQueue extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'comp_queue_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_compound_queue';


    public function project()
    {
        return $this->hasMany('App\Models\SaasProjects','prj_id','works_id')->withTrashed()
            ->select('prj_id','prj_name','prj_sn');
    }




}