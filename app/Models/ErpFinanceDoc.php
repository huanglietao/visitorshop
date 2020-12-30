<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ErpFinanceDoc extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'erp_finance_doc';

    public function setCreateTimeAttribute($value)
    {
        $this->attributes['createtime'] = is_int($value) ? $value : strtotime($value);
    }

    public function getCreateTimeAttribute()
    {
        if (empty($this->attributes['createtime'])){
            return " ";
        }
        return date('Y-m-d H:i:s', $this->attributes['createtime']);
    }

    public function setFinishTimeAttribute($value)
    {
        $this->attributes['finishtime'] = is_int($value) ? $value : strtotime($value);
    }

    public function getFinishTimeAttribute()
    {
        if (empty($this->attributes['finishtime'])){
            return " ";
        }
        return date('Y-m-d H:i:s', $this->attributes['finishtime']);
    }
}