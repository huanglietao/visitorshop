<?php
namespace App\Repositories;

use App\Models\ErpPrintLog;

/**
 * 打印日志仓库
 *
 * @author:yanxs
 * @version: 1.0
 * @date:2020/2/13
 */
class ErpPrintLogRepository extends BaseRepository
{
    public function __construct(ErpPrintLog $model)
    {
        $this->model =$model;
    }

    /**
     * 查看打印记录
     * @param $sys_order_no 外部订单号
     * @return array $result
     */
    public function getPrintLog($sys_order_no)
    {
        //获取打印记录
        $count = $this->model->where('sys_order_no', $sys_order_no)->get();
        return $count;
    }

}
