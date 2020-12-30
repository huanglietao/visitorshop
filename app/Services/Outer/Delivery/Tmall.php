<?php
/**
 * Created by sass.
 * Author: LJH
 * Date: 2020/6/10
 * Time: 16:05
 */

namespace App\Services\Outer\Delivery;

use App\Services\Helper;
use App\Services\Outer\TbApi;

/**
 * Class Tmall 淘宝/天猫 物流信息回写
 * Created by sass.
 * Author: LJH
 * Date: 2020/6/10
 * Time: 18:31
 * @package App\Services\Outer\Delivery
 */
class Tmall implements DeliveryReturnInterface
{
    /**
     * Author: LJH
     * Date: 2020/6/10
     * Time: 16:45
     * @param 淘宝订单号 $orderNo
     * @param 物流运单号 $deliveryCode
     * @param 物流公司简称 $deliveryName
     * @param 分销商id $agent_id
     * @param null $ext
     * @return mixed|void
     */
    public function deliveryReturn($orderNo,$deliveryCode,$deliveryName,$agent_id,$agent_code,$ext = null)
    {
        // TODO: Implement deliveryReturn() method.
        $helper = app(Helper::class);
        $tbConfig = $helper->getTbConfig($agent_id);
        if(empty($tbConfig)){
            $err_data = [
                'success'=>'false',
                'err_msg'=>"找不到淘宝配置信息"
            ];
            return $err_data;
        }

        $orderNos = explode(",",$orderNo);
        foreach ($orderNos as $k=>$v){
            $data = [
                'order_no'=>$v,
                'agent_id' => $agent_id,
                'out_sid'=>$deliveryCode,
                'company_code'=>$deliveryName
            ];

            $api = app(TbApi::class);
            $ret = $api->request($tbConfig['sdk_cnf_domain'].'/tb/logistics/offline-send',$data,'POST');
            if($ret['success']=='false'){
                return $ret;
            }
        }

        return $ret;
    }
}