<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SassProductsMedia extends Model
{
    use SoftDeletes;
    protected $primaryKey="prod_md_id";
    protected $dates = ['deleted_at'];
    protected $guarded = [];
    public $timestamps = false;
    protected $dateFormat = 'U';
}
