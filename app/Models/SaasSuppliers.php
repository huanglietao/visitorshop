<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SaasSuppliers extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'sup_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = false;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_suppliers';


    public function getSpDownloadQueue($mid, $sp_id, $size, $service_id = '')
    {
        $limit_time = 3600;
        $re_down_time = time()-$limit_time;

        if(!empty($mid)) {
            $where = "AND q.mch_id=$mid";
        }else {
            $where = '';
        }
        if(!empty($service_id)) {
            $where = " AND q.service_id=$service_id";
        }
        if($size==-1){
            $count_lsit = DB::select("select q.*,p.prod_name,p.prod_cate_uid from saas_new_sp_download_queue as q left join saas_products as p on q.prod_id=p.prod_id where q.deleted_at is null AND disabled=\"false\"
        $where AND q.sp_id=$sp_id AND  (q.download_status='ready' OR (q.download_status='progress' AND q.download_begin_time<$re_down_time)) ORDER BY new_sp_down_queue_id ASC");
        }else{
            $count_lsit = DB::select("select q.*,p.prod_name,p.prod_cate_uid from saas_new_sp_download_queue as q left join saas_products as p on q.prod_id=p.prod_id where q.deleted_at is null AND disabled=\"false\"
        $where AND q.sp_id=$sp_id AND  (q.download_status='ready' OR (q.download_status='progress' AND q.download_begin_time<$re_down_time)) ORDER BY new_sp_down_queue_id ASC LIMIT $size");
        }

        $count = count($count_lsit);
        //状态为progress的放在前面
        $data_p = [];  //状态为protress的
        $data_o = [];  //状态为其他的
        if(!empty($count_lsit)){
            foreach($count_lsit as $k=>$v){
                if($v->download_status == 'progress'){
                    $data_p[] = $v;
                }else{
                    $data_o[] = $v;
                }
            }
        }

        $result_sort = array_merge($data_p,$data_o);

        return ['list'=>$result_sort, 'total'=>$count];
    }


}