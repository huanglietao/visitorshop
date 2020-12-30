<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 供货商订单下载队列模型
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020/05/08
 */

class SaasSpDownloadQueue extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'sp_down_queue_id';
    protected $dates = ['deleted_at'];  //软删除标识
    protected $guarded = [];            //save的黑名单
    public $timestamps = true;         //关闭时间timestamp
    protected $dateFormat = 'U';
    protected $table = 'saas_sp_download_queue';

    /**
     * 更新订单的下载状态
     * @param $qid 下载队列id
     */
    public function updateOrderStatus($qid)
    {
        $info = $this->where(['sp_down_queue_id'=>$qid])->first();

        //当前item_id下的队列是否都下载完成了
        $no_down_list = $this->where(['mch_id' => $info['mch_id'], 'sp_id'=>$info['sp_id'] ,'ord_prod_id'=>$info['ord_prod_id'], 'download_status' => ['ready','progress', 'error']])->get();
        $no_down = count($no_down_list);

        if(empty($no_down)) {  //单个item_id的文件全部下载完了
            $spOrderProduct = SaasSuppliersOrderProduct::where(['ord_prod_id' => $info['ord_prod_id'], 'supplier_id' => $info['sp_id']])->update(['sp_download_status' => ONE]);
        }

        //当前订单的文件是否全部下载完了
        $no_down_order_list = $this->where(['mch_id' => $info['mch_id'], 'sp_id'=>$info['sp_id'] ,'ord_id'=>$info['ord_id'], 'download_status' => ['ready','progress', 'error']])->get();
        $no_down_order = count($no_down_order_list);

        if(empty($no_down_order)) {
            $spOrders = SaasSuppliersOrders::where(['ord_id' => $info['ord_id'], 'mch_id' => $info['mch_id'], 'supplier_id'=> $info['sp_id']])->update(['sp_produce_status' => 1, 'sp_order_status'=>3]);
        }

    }


}