<?php
/**
 * 功能简介
 *
 * 功能详细说明
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/6/11
 */

namespace App\Services\Outer\Delivery;

use App\Exceptions\CommonException;
use App\Models\SaasExpress;
use App\Services\Outer\TbApi;

class PartnerBase implements DeliveryReturnInterface
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
                'order_id'            => $orderNo,                  // 订单号
                'order_status'        => 2,                         // 订单状态;0：取消，1：生产中，2：已发货
                'shipping_no'         => $deliveryCode,             // 物流单号
                'shipping_code'       => $express['express_id'],    // 物流id
                'shipping_code_name'  => $deliveryName,             // 物流简称
                'shipping_name'       => $express['express_name'],  // 物流名称
            ];

            //生成签名
            $agentCodeList = config("common.agent_code");
            $data['hc_sig'] = $this->getSignature($data, $agentCodeList[$agent_code]['key']);

            //获取请求的url
            $url = $this->getUrl($orderNo);

            //请求接口回写物流信息
            $api = app(TbApi::class);
            $ret = $api->request($url,$data,'POST');

            //如果请求接口并回写成功
            if($ret['status']=='success' && ($ret['error_code']==200 || empty($ret['error_code']))){
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

        }catch (CommonException $e){
            return ['success' => 'false','err_code'=>$e->getCode(),  'err_msg' => $e->getMessage()];
        }
    }

    //获取请求对应的url
    protected function getUrl($orderNo){}

    /**
     * 签名
     * @param unknown $params
     * @param unknown $secret
     */
    protected function getSignature($params, $secret){
        $str = '';//待签名字符串
        //先将参数以其参数名的字典序升序进行排序
        ksort($params);
        //遍历排序后的参数数组中的每一个key/value对
        foreach($params as $k => $v){
            if ($v == '' || 'sign' == $k) {
                continue;
            }
            //为key/value对生成一个key=value格式的字符串，并拼接到待签名字符串后面
            $str .= "$k=$v";
        }
        //将签名密钥拼接到签名字符串最后面
        $str .= $secret;
        //通过md5算法为签名字符串生成一个md5签名，该签名就是我们要追加的sign参数值
        return md5($str);
    }

}