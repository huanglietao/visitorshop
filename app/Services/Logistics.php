<?php
namespace App\Services;

use App\Http\Controllers\Api\Outer\DeliveryController;
use App\Models\SaasDeliveryQueue;
use App\Models\SaasOrders;
use App\Models\SaasOuterErpOrderCreateQueue;
use App\Models\SaasSuppliersLogisticsCosts;
use App\Repositories\SaasDeliveryRepository;
use App\Repositories\SaasDeliveryTemplateRepository;
use App\Repositories\SaasExpressRepository;
use App\Repositories\SaasOrdersRepository;
use App\Repositories\SaasSuppliersLogisticsCostsRepository;
use App\Services\Outer\Erp\Api;

/**
 * 物流相关的逻辑
 * 物流分配及运费逻辑的计算
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/21
 */

class Logistics
{
    protected $province;  //省份
    protected $city;      //城市
    protected $district;  //区县
    protected $weight;    //重量

    protected $repoExpress;  //快递仓库
    protected $repoDelivery; //运送方式
    protected $repoDelTemp;  //运费模板

    /**
     * Logistics constructor.
     * @param SaasExpressRepository $express
     * @param SaasDeliveryRepository $delivery
     * @param SaasDeliveryTemplateRepository $delTemp
     */
    public function __construct(SaasExpressRepository $express,SaasDeliveryRepository $delivery,
                                SaasDeliveryTemplateRepository $delTemp,SaasDeliveryQueue $deliveryQueueModel,SaasSuppliersLogisticsCosts $logisticsCosts)
    {
        $this->repoExpress  = $express;
        $this->repoDelivery = $delivery;
        $this->repoDelTemp  = $delTemp;
        $this->deliveryQueueModel  = $deliveryQueueModel;
        $this->logisticsCosts  = $logisticsCosts;
    }

    /**
     * 获取运费
     * @param $tempId     运费模板
     * @param $deliveryId 运送方式id
     * @param string $province 省份标识
     * @param string $city     城市标识
     * @param string $district 区/县标识
     * @param int $weight 重量 克数
     * @return mixed
     */
    public function getDeliveryFee($tempId, $deliveryId,$province= '', $city= '', $district = '', $weight=0)
    {
        $this->province = $province;
        $this->city     = $city;
        $this->district = $district;
        $this->weight   = $weight;

        $tempInfo = $this->repoDelTemp->getById($tempId);
        if (empty($tempInfo)) {
            Helper::EasyThrowException('80001',__FILE__.__LINE__);
        }

        $deliveryList = explode(',',$tempInfo['del_temp_delivery_list']);
        if(!in_array($deliveryId, $deliveryList)) {
            Helper::EasyThrowException('80002',__FILE__.__LINE__);
        }
        if (empty($tempInfo['del_temp_area_conf'])) {
            Helper::EasyThrowException('80003',__FILE__.__LINE__);
        }

        $tempAreaConf = json_decode($tempInfo['del_temp_area_conf'], true);
        return $this->getFee($deliveryId, $tempAreaConf);

    }


    /**
     * 根据$deliveryId匹配出运费
     * @param $deliveryId
     * @param $tempAreaConf
     * @return float
     */
    private function getFee($deliveryId, $tempAreaConf)
    {
       if (isset($tempAreaConf[$deliveryId])) {
            $confInfo = $tempAreaConf[$deliveryId];

            //是否存在特殊定价
           if (isset($confInfo[1])) {

               $weightKg = $this->weight/1000;

               foreach ($confInfo[1] as $k => $v) {
                    //获取区域信息
                   $arrInfo = explode(';',$v[0]);
                   $areaInfo = explode(',', $arrInfo[0]);
                   $priceInfo = $arrInfo[1];

                   $deliveryFee = $this->getWeightFee($weightKg,$priceInfo);
                   if (in_array($this->district, $areaInfo) || in_array($this->city, $areaInfo) || in_array($this->province, $areaInfo)) {
                       //var_dump();
                       return $deliveryFee;
                   }

               }

               $deliveryInfo = $confInfo[0];
               return $this->getWeightFee($weightKg,$deliveryInfo);
           } else {

           }

        } else {
           Helper::EasyThrowException('80003',__FILE__.__LINE__);
       }
    }

    /**
     * 根据重量获取最终运费
     * @param $weight  重量 kg
     * @param $priceInfo 计费规则信息 1,1,1,1  首重,首重价格,续重,续重价格
     * @return int
     */
    public function getWeightFee($weight, $priceInfo)
    {
        $arrPriceInfo = explode(',', $priceInfo);
        $firstWeight = isset($arrPriceInfo[0]) ? $arrPriceInfo[0]:0;
        $firstPrice = isset($arrPriceInfo[1]) ? $arrPriceInfo[1]:0;
        $nextWeight = isset($arrPriceInfo[2]) ? $arrPriceInfo[2]:0;
        $nextPrice = isset($arrPriceInfo[3]) ? $arrPriceInfo[3]:0;

        if ($weight <= $firstWeight) {
            return number_format($firstPrice, 2);
        } else {
            return number_format($firstPrice + (ceil($weight-$firstWeight)/$nextWeight)*$nextPrice, 2);
        }

    }

    /**
     * //插入物流信息回写队列流程处理
     * Author: LJH
     * Date: 2020/6/10
     * Time: 15:39
     * @param $Queuedata
     */
    public function insertDeliveryQueue($Queuedata)
    {
        //组织数据插入队列表
        $delivery_queue_data = [
            'mch_id' => $Queuedata['mch_id'],                //商户id
            'agent_code'=>$Queuedata['agent_code'],          //分销商编号
            'order_id'=>$Queuedata['order_id'],              //订单id
            'delivery_name' => $Queuedata['delivery_name'],  //物流简称代号
            'delivery_code'=>$Queuedata['delivery_code'],    //运单号
            'created_at'=>time()
        ];

        $ret = $this->deliveryQueueModel->insertGetId($delivery_queue_data);
        if(empty($ret)){
            $retData = [
                'status'=>'fail',
                'msg'=>'插入队列表失败！'
            ];
            return $retData;
        }
        $retData = [
            'status'=>'success',
            'msg'=>'成功插入！'
        ];
        return $retData;
    }

    //获取即将跑物流信息回写队列创建订单的数组
    public function runReadyDeliveryQueue()
    {
        //获取每次跑队列条数
        $limit = config("common.queue_limit.delivery_queue");
        $QueueInfo = $this->deliveryQueueModel->where(['delivery_push_status' => 'ready'])->limit($limit)->get()->toArray();
        $this->runDeliveryQueue($QueueInfo);
    }

    //跑物流信息回写队列
    public function runDeliveryQueue($QueueInfo)
    {
        $orderModel = app(SaasOrders::class);
        $agentCodeList = config("common.agent_code");
        foreach ($QueueInfo as $qk => $qv){
            $this->deliveryQueueModel->where(['delivery_push_id'=>$qv['delivery_push_id']])->update(['start_time' => time()]);
            $orderInfo = $orderModel->where(['order_id'=>$qv['order_id']])->get()->toArray();
            if(empty($orderInfo)){
                $data['error_msg'] = "订单不存在,找不到对应的订单信息";
                $data['times'] = ++$qv['times'];
                $data['delivery_push_status'] = 'error';
                $data['updated_at'] = time();
                $this->deliveryQueueModel->where(['delivery_push_id'=>$qv['delivery_push_id']])->update($data);
                continue;
            }

            if (empty($orderInfo[0]['user_id'])){
                $data['error_msg'] = "用户id未找到";
                $data['times'] = ++$qv['times'];
                $data['delivery_push_status'] = "error";
                $data['updated_at'] = time();
                $this->deliveryQueueModel->where(['delivery_push_id'=>$qv['delivery_push_id']])->update($data);
                continue;
            }

            if(empty($orderInfo[0]['order_relation_no'])){
                $data['error_msg'] = "外部订单号为空";
                $data['times'] = ++$qv['times'];
                $data['delivery_push_status'] = 'error';
                $data['updated_at'] = time();
                $this->deliveryQueueModel->where(['delivery_push_id'=>$qv['delivery_push_id']])->update($data);
                continue;
            }

            //外部订单号
            $orderNo = $orderInfo[0]['order_relation_no'];
            //物流运单号
            $deliveryCode = $qv['delivery_code'];
            //物流简称
            $deliveryName = $qv['delivery_name'];
            //分销id
            $agent_id = $orderInfo[0]['user_id'];

            //判断回写信息接口走哪个接口
            $namespace = "App\\Services\\Outer\\Delivery";

            if (isset($agentCodeList[$qv['agent_code']])) {
                $className = $agentCodeList[$qv['agent_code']]['flag'];


                $ret = app($namespace."\\".ucfirst($className))->deliveryReturn($orderNo,$deliveryCode,$deliveryName,$agent_id,$qv['agent_code']);

                if($ret['success']=='true'&& $ret['result']['shipping']['is_success']==true){
                    $data['error_msg'] = "";
                    $data['delivery_push_status'] = 'finish';
                    $data['updated_at'] = time();
                    $data['end_time'] = time();
                    $this->deliveryQueueModel->where(['delivery_push_id'=>$qv['delivery_push_id']])->update($data);
                }else {
                    $data['error_msg'] = $ret['err_msg'];
                    $data['times'] = ++$qv['times'];
                    $data['delivery_push_status'] = 'error';
                    $data['updated_at'] = time();
                    $this->deliveryQueueModel->where(['delivery_push_id'=>$qv['delivery_push_id']])->update($data);
                }
            }


        }
    }

    /**
     * @author: cjx
     * @version: 1.0
     * @date: 2020/06/30
     * @remark: 外协订单发货回写
     * @param $order_no订单号 $express_num 物流单号 $express_type 物流方式(如:yto、sto...)
     * @return
     */
    public function deliveryWriteBack($order_no, $express_num, $express_type)
    {
        //RepositoryAndModel
        $ordersRepository = app(SaasOrdersRepository::class);
        $deliveryController = app(DeliveryController::class);

        if(empty($order_no)){
            //缺少必要参数
            Helper::EasyThrowException(10023,__FILE__.__LINE__);
        }

        if(empty($express_num)){
            //快递单号不能为空
            Helper::EasyThrowException(70094,__FILE__.__LINE__);
        }

        if(empty($express_type)){
            //物流方式不能为空
            Helper::EasyThrowException(70095,__FILE__.__LINE__);
        }

        $order_info = $ordersRepository->getOrderInfo('',$order_no);

        if(empty($order_info)){
            //该订单记录不存在
            Helper::EasyThrowException(70030,__FILE__.__LINE__);
        }

        //组装数据
        $param = [
            'order_no'  =>  $order_no,
            'express_num'  =>  $express_num,
            'express_type'  =>  $express_type,
        ];
        $post_data = $deliveryController->getData($param);

        if(!is_array($post_data)){
            //非外协订单直接返回成功
            return json_encode(["code"=>1,"message"=>"成功"]);
        }
        //请求接口
        file_put_contents('/tmp/outer_erp_order_delivery.log',var_export($post_data,true),FILE_APPEND);
        $result = app(Api::class)->request(config('erp.interface_url').config('erp.outer_delivery_write_back'),$post_data);
        file_put_contents('/tmp/outer_erp_order_delivery.log',var_export($result,true),FILE_APPEND);
        return $result;

    }




    /**
     * 获取物流成本
     * @param $supId      物流成本模板
     * @param $deliveryId 运送方式id
     * @param string $province 省份标识
     * @param string $city     城市标识
     * @param string $district 区/县标识
     * @param int $weight 重量 克数
     * @return mixed
     */
    public function getLogisticsCosts($supId, $deliveryId,$province= '', $city= '', $district = '', $weight=0)
    {
        $this->province = $province;
        $this->city     = $city;
        $this->district = $district;
        $this->weight   = $weight;

        $tempInfo = $this->logisticsCosts->where(['sup_id'=>$supId])->first();
        if (empty($tempInfo)) {
            Helper::EasyThrowException('70097',__FILE__.__LINE__);
        }

        if (empty($tempInfo['sup_log_cos_area_conf'])) {
            Helper::EasyThrowException('70099',__FILE__.__LINE__);
        }

        $tempAreaConf = json_decode($tempInfo['sup_log_cos_area_conf'], true);
        if(!array_key_exists($deliveryId, $tempAreaConf)) {
            Helper::EasyThrowException('70098',__FILE__.__LINE__);
        }
        return $this->getCosts($deliveryId, $tempAreaConf);

    }


    /**
     * 根据$deliveryId匹配出物流成本
     * @param $deliveryId
     * @param $tempAreaConf
     * @return float
     */
    private function getCosts($deliveryId, $tempAreaConf)
    {
        if (isset($tempAreaConf[$deliveryId])) {
            $confInfo = $tempAreaConf[$deliveryId];

            //是否存在特殊定价
            if (isset($confInfo[1])) {

                $weightKg = $this->weight/1000;

                foreach ($confInfo[1] as $k => $v) {
                    //获取区域信息
                    $arrInfo = explode(';',$v[0]);
                    $areaInfo = explode(',', $arrInfo[0]);
                    $priceInfo = $arrInfo[1];

                    $deliveryFee = $this->getCostsFee($weightKg,$priceInfo);
                    if (in_array($this->district, $areaInfo) || in_array($this->city, $areaInfo) || in_array($this->province, $areaInfo)) {
                        //var_dump();
                        return $deliveryFee;
                    }

                }

                $deliveryInfo = $confInfo[0];
                return $this->getCostsFee($weightKg,$deliveryInfo);
            } else {

            }

        } else {
            Helper::EasyThrowException('70099',__FILE__.__LINE__);
        }
    }

    /**
     * 根据重量获取最终物流成本
     * @param $weight  重量 kg
     * @param $priceInfo 计费规则信息 1,1,1,1  首重,首重价格,续重,续重价格
     * @return int
     */
    public function getCostsFee($weight, $priceInfo)
    {
        $arrPriceInfo = explode(',', $priceInfo);
        $firstWeight = isset($arrPriceInfo[0]) ? $arrPriceInfo[0]:0;
        $firstPrice = isset($arrPriceInfo[1]) ? $arrPriceInfo[1]:0;
        $nextWeight = isset($arrPriceInfo[2]) ? $arrPriceInfo[2]:0;
        $nextPrice = isset($arrPriceInfo[3]) ? $arrPriceInfo[3]:0;

        if ($weight <= $firstWeight) {
            return number_format($firstPrice, 2);
        } else {
            return number_format($firstPrice + (ceil($weight-$firstWeight)/$nextWeight)*$nextPrice, 2);
        }

    }

}