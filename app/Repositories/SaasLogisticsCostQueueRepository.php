<?php
namespace App\Repositories;

use App\Models\SaasLogisticsCostQueue;
use App\Models\SaasOrderFile;

/**
 * 物流成本报表数据仓库
 * @author: cjx
 * @version: 1.0
 * @date:  2020/08/04
 */
class SaasLogisticsCostQueueRepository extends BaseRepository
{

    public function __construct(SaasLogisticsCostQueue $model,SaasOrderFile $orderFile)
    {
        $this->model =$model;
        $this->orderFilemodel =$orderFile;
    }

    /**
     * 更新物流成本
     * param：$code 物流单号 $price 运费
     */
    public function startUpdate($code,$price)
    {
        $res = $this->orderFilemodel->whereRaw("FIND_IN_SET('".$code."',delivery_code)",true)->select('order_file_id','express_cost','is_update')->first();
        if(!empty($res)){
            if ($res['is_update'] == PUBLIC_YES){
                //已更新过的记录，物流成本=当前物流成本+运费($price)
                $this->orderFilemodel->increment('express_cost',$price);
            }else{
                //更新当前物流成本为运费($price)
                $this->orderFilemodel->where(['order_file_id'=>$res['order_file_id']])->update(['express_cost'=>$price,'is_update'=>PUBLIC_YES]);
            }
            return true;
        }else{
            return false;
        }
    }


}
