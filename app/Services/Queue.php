<?php
namespace App\Services;

use App\Exceptions\CommonException;
use App\Models\SaasCompoundService;
use App\Repositories\SaasCompoundQueueRepository;
use App\Repositories\SaasCompoundServiceRepository;
use App\Repositories\SaasLogisticsCostQueueRepository;
use App\Repositories\SaasOrderProduceQueueRepository;
use App\Repositories\SaasOrderProductsRepository;
use App\Repositories\SaasOrdersRepository;
use App\Services\Orders\Production;
use App\Services\Orders\Status;

/**
 * 数据库队列处理服务
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/23
 */
class Queue
{
    protected $repoService;  //合成服务器仓库
    protected $repoCompQueue;  //合成队列仓库
    public function __construct(SaasCompoundServiceRepository $service, SaasCompoundQueueRepository $compQueue,
                                SaasOrderProduceQueueRepository $ordProdQueue,SaasOrdersRepository $orders,
                                SaasOrderProductsRepository $orderProductsRepository,SaasLogisticsCostQueueRepository $logisticsCostQueueRepository)
    {
        $this->repoService = $service;
        $this->repoCompQueue = $compQueue;
        $this->ordProdQueue = $ordProdQueue;
        $this->order = $orders;
        $this->orderProduct = $orderProductsRepository;
        $this->logisticsCostQueueRepository = $logisticsCostQueueRepository;
    }

    /**
     * 队列处理服务器调度
     * @param $key
     * @return int
     */
    public function dispatch($key = 1)
    {
        return 1;
    }

    /**
     * @param $condition
     * @return array
     */
    public function getCompoundQueue($condition)
    {
        $serviceList = $this->getServiceList();
        $serveListKv = array_column($serviceList, 'comp_serv_id', 'svc_code');
        //条件判断,调度服务器
        if(!empty($condition['server_flag'])) {
            $serverId = $serveListKv[$condition['server_flag']];
            if (empty($serverId)) {
                Helper::apiThrowException("10004",__FILE__.__LINE__);
            }
            $condition['server_id'] = $serverId;

        }

        $res =  $this->repoCompQueue->getQueueList($condition);

        if (!empty($condition['size'])) {
            return $res;
        }

        $list = $res['list'];
        if (empty($list)) {
            return [];
        }
        $no = array_rand($list);
        $return = $list[$no];
        return $return;

    }

    /**
     * 获取合成
     * @param $condition
     * @return array
     */
    protected function getServiceList($condition = [])
    {
        $list = $this->repoService->getRows($condition, 'comp_serv_id', 'asc')->toArray();

        if (empty ($list)) {
            return [];
        }

        return $list;

    }

    /**
     * 自动提交生产
     * @param
     * @return
     */
    public function autoProduce()
    {
        try{
            try{
                $list = $this->ordProdQueue->getList(['produce_queue_status'=>'ready'],'created_at','asc')->toArray();

                if(!empty($list)){
                    foreach ($list as $k=>$v){

                        $row= $v;
                        if($v['produce_queue_type'] == ORDER_PRODUCE_TYPE_AUTO){
                            //自动提交

                            //暂停所有自动提交,手动提交可过(2020-07-22)
//                            continue;

                            if($v['produce_queue_flag'] != 0){
                                //有标签队列不做处理
                                continue;
                            }

                            if(time() < $v['created_at'] + 600){
                                //ready状态下10分钟后再处理
                                continue;
                            }

                            //一订单多商品情况不做自动提交
                            $ord_prod_num = $this->orderProduct->getList(['ord_id'=>$v['order_id']])->count();
                            if($ord_prod_num > 1){
                                continue;
                            }

                        }

                        //更新生产队列状态(progress)
                        $this->ordProdQueue->update(['produce_queue_id'=>$v['produce_queue_id']],['produce_queue_status'=>'progress','start_time'=>time(),'times'=>1]);

                        \DB::beginTransaction();

                        //提交生产处理
                        app(Production::class)->submit($v['order_id']);

                        //自动提交情况下记订单日志
                        if($v['produce_queue_type'] == ORDER_PRODUCE_TYPE_AUTO){
                            //添加订单日志
                            $order_info = $this->order->getOrderInfo($v['order_id']);
                            $log_data = [
                                'ord_id'        =>      $v['order_id'],
                                'operater'      =>      'admin',
                                'platform'      =>      config('common.sys_abbreviation')['merchant'],
                                'action'        =>      '自动提交生产',
                                'note'          =>      '订单号【'.$order_info['order_no'].'】自动提交生产',
                            ];
                            $this->order->recordOrderLog($log_data);
                        }

                        //更新生产队列状态(finish)
                        $this->ordProdQueue->update(['produce_queue_id'=>$v['produce_queue_id']],['produce_queue_status'=>'finish','end_time'=>time()]);

                        \DB::commit();
                    }
                }
            }catch (\Exception $e){
                \DB::rollBack();
                //更新生产队列状态(error)
                $error_msg = !empty($e->getMessage()) ? $e->getMessage() : '自动提交生产出错';
                $this->ordProdQueue->update(['produce_queue_id'=>$row['produce_queue_id']],['produce_queue_status'=>'error','produce_queue_err_msg'=>$error_msg,'end_time'=>time()]);

                if(!empty($e->getMessage())){
                    //记录订单异常
                    $exception_data = [
                        'order_id'               => $row['order_id'],
                        'ord_exception_type'     => ORDER_EXCEPTION_TYPE_PRODUCE,
                        'ord_exception_msg'      => "code:".$e->getCode()."===>".$e->getMessage(),
                        'created_at'             => time()
                    ];
                    $this->order->recordOrderException($exception_data);
                    var_dump($e->getMessage().'===>'.$e->getCode().'||order_id:'.$row['order_id']);
                }else{
                    //订单自动提交生产出错
                    app(\App\Services\Exception::class)->throwException('70077',__FILE__.__LINE__);
                }
            }
        }catch (CommonException $exception){
            //记录订单异常
            $exception_data = [
                'order_id'               => $row['order_id'],
                'ord_exception_type'     => ORDER_EXCEPTION_TYPE_PRODUCE,
                'ord_exception_msg'      => "code:".$exception->getCode()."===>".$exception->getMessage(),
                'created_at'             => time()
            ];
            $this->order->recordOrderException($exception_data);
            var_dump($exception->getMessage().'===>'.$exception->getCode().'||order_id:'.$row['order_id']);
        }
    }

    /**
     * author：cjx
     * date：2020-08-04
     * 更新物流成本
     * @param
     * @return
     */
    public function updateDeliveryCost()
    {
        try{
            try{
                //物流成本队列，每次更新2000条记录
                $list = $this->logisticsCostQueueRepository->getRows(['cost_queue_status'=>'ready'],'cost_queue_id','asc',config('queue_limit.logistics_cost_queue'))->toArray();

                if(!empty($list)){
                    foreach ($list as $k=>$v){
                        $row= $v;

                        //更新队列状态(progress)
                        $this->logisticsCostQueueRepository->update(['cost_queue_id'=>$v['cost_queue_id']],['cost_queue_status'=>'progress','start_time'=>time()]);

                        \DB::beginTransaction();

                        //物流成本更新处理
                        $res = app(SaasLogisticsCostQueueRepository::class)->startUpdate($v['cost_delivery_code'],$v['cost_price']);

                        if($res){
                            //更新生产队列状态(finish)
                            $this->logisticsCostQueueRepository->update(['cost_queue_id'=>$v['cost_queue_id']],['cost_queue_status'=>'finish','end_time'=>time()]);
                        }else{
                            //订单归档表无对应快递单号，将队列状态更新为ready
                            $this->logisticsCostQueueRepository->update(['cost_queue_id'=>$v['cost_queue_id']],['cost_queue_status'=>'ready','start_time'=>null]);
                        }

                        \DB::commit();
                    }
                }
            }catch (\Exception $e){
                \DB::rollBack();
                //更新队列状态(error)
                $this->logisticsCostQueueRepository->update(['cost_queue_id'=>$row['cost_queue_id']],['cost_queue_status'=>'error','end_time'=>time()]);

                if(!empty($e->getMessage())){
                    var_dump($e->getMessage().'===>'.$e->getCode());
                }else{
                    //物流成本更新出错
                    app(\App\Services\Exception::class)->throwException('15001',__FILE__.__LINE__);
                }
            }
        }catch (CommonException $exception){
            var_dump($exception->getMessage().'===>'.$exception->getCode());
        }
    }

    /**
     * 通过淘宝订阅信息进行判断是否加入同步队列
     * @param $orderNo
     * @param $agentId
     */
    public function tcmSyncOrder($orderNo = '', $agentId = 0)
    {
        //获取
    }
}