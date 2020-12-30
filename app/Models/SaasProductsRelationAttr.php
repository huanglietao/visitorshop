<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020/4/22
 */
class SaasProductsRelationAttr extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'rel_attr_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_products_relation_attr';

    public function attr()
    {
        return $this->hasOne('App\Models\SaasProductsAttribute','attr_id','attr_id')
                ->select('attr_id','attr_name');
    }

    public function attrValue()
    {
        return $this->hasOne('App\Models\SaasAttributeValues','attr_val_id','attr_val_id')
                ->select('attr_val_id','attr_val_name');
    }
}
