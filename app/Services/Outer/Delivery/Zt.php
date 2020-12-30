<?php
namespace App\Services\Outer\Delivery;

use App\Exceptions\CommonException;
use App\Models\SaasExpress;
use App\Services\Outer\JsonParamsApi;
use App\Services\Outer\TbApi;

/**
 * Created by Saas.
 * Author: yanxs
 * Date: 2020/7/25
 * Time: 16:05
 */
class Zt extends PartnerBase
{
    protected $url;
    public function deliveryReturn($orderNo,$deliveryCode,$deliveryName,$agent_id,$agent_code,$ext = null)
    {
        try{
            //根据物流简称查找物流信息
            $expressModel = app(SaasExpress::class);
            $express = $expressModel->where(['express_code'=>$deliveryName])->first();
            if(empty($express)){
                $err_data = [
                    'success'=>'false',
                    'err_msg'=>"找不到对应物流公司的信息"
                ];
                return $err_data;
            }
            //请求接口的数据封装
            $data = [
                'order_no'            => $orderNo,                  // 订单号
                'delivery_code'         => $deliveryCode,             // 物流单号
                'coop_code'       =>    '108',    // 物流id
                'logistic_code'  => $deliveryName,             // 物流简称
                'logistics_name'       => $express['express_name'],  // 物流名称
                'delivery_time'       => time(),  // 这个得改
            ];

            //生成签名
            $agentCodeList = config("common.agent_code");
            $data['sign'] = $this->getSignature($data, $agentCodeList[$agent_code]['key']);

            //获取请求的url
            $url = $this->getUrl($orderNo);

            //请求接口回写物流信息
            $api = app(JsonParamsApi::class);
            $ret = $api->request($url,$data,'POST');

            //如果请求接口并回写成功
            if($ret['code'] == '10000'){
                $retData = [
                    'shipping'=>[
                        'is_success'=>true
                    ]
                ];
            }else{
                $err_data = [
                    'success'=>'false',
                    'err_msg'=>$ret['msg']
                ];
                return $err_data;
            }
            return ['success' => 'true', 'result' => $retData];
            return $this->success($retData);

        }catch (CommonException $e){
            return ['success' => 'false','err_code'=>$e->getCode(),  'err_msg' => $e->getMessage()];
            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    protected function getUrl($orderNo)
    {
        return 'http://api.szy.cn/albumprint/order/deliverynotify/v1';
    }
}
