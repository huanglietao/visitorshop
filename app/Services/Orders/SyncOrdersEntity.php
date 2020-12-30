<?php
namespace App\Services\Orders;

use App\Exceptions\CommonException;
use App\Services\Goods\Price;

/**
 * 同步订单实体类
 * 同步订单操作使用此类
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/10/22
 */
class SyncOrdersEntity extends OrdersAbstract
{
    /**
     * 用于标准化数据格式
     * @param $data
     * @return array $data 处理后的数据
     * @throws CommonException
     */
    public function setOrdersData($data)
    {
        // TODO: Implement setOrdersData() method.
        if(!is_array($data['items']) || !is_array($data['receiver_info'])) {
            //throw new CommonException(__('exception.order_create_less_params'),'80001');
            app(\App\Services\Exception::class)->throwException('80001',__FILE__.__LINE__);
        }

        return $data;
    }



    /**
     * 额外的处理
     */
    public function extraProcess()
    {
        // TODO: Implement extraProcess() method.
    }

    /**
     * 获取商品价格
     */
    public function getGoodsPrice()
    {
        $standardData = $this->standardData;

        foreach ($standardData['items'] as $k=>$v) {
//            $singlePrice = app(Price::class)->getChanelPrice($v['product_id'], $this->userInfo['cust_lv_id'], $v['page_num']);
//            $goodsPrice  = $singlePrice * $v['buy_num'];
//            //比对传过来的价格和计算价格是否一致。
//            if(!empty($v['real_fee']) && abs($goodsPrice - $v['real_fee']) > 0.01) {
//                app(\App\Services\Exception::class)->throwException('70024',__FILE__.__LINE__);//传入的商品价格有误
////                $this->easyThrowException('70024',__FILE__.__LINE__); //传入的商品价格有误
//            }

            $this->standardData['items'][$k]['goods_price'] = $v['price'];
        }

//        $totalAmount = $totalGoodsPrice + $this->standardData['post_fee'] + $this->getTaxFee();
        $totalAmount = $this->standardData['total_amount'];
        if (!empty($this->standardData['total_amount']) && abs($totalAmount - $this->standardData['total_amount']) > 0.01) {
            app(\App\Services\Exception::class)->throwException('70025',__FILE__.__LINE__);//传入的总价格有误
//            $this->easyThrowException('70025',__FILE__.__LINE__); //传入的总价格有误
        }

        $this->standardData['total_amount'] = $totalAmount;
        return true;
    }


    /**
     * 获取物流相关信息
     */
    public function getLogisticsInfo()
    {
        $standardData = $this->standardData;
        $provinceCode = $standardData['receiver_info']['province_code'];
        $cityCode = $standardData['receiver_info']['city_code'];
        $districtCode = $standardData['receiver_info']['district_code'];

        //运送方式和运费模板
        $shippingId = $standardData['shipping_id'];
        $shippingTempId = $standardData['shipping_temp_id'];

        //一种商品的情况下
        $arrGoodIds = array_unique(array_column($standardData['items'],'goods_id'));
        $deliveryFee = 0;
//        if (count($arrGoodIds) == 1 && $this->goodsInfo[$arrGoodIds[0]]['prod_express_type'] == LOGISTICS_PRICE_BY_FIXED) { //单个且固定运费情况下
//            $deliveryFee = $this->goodsInfo[$standardData['items'][0]['goods_id']]['prod_express_fee'];
//        } else {
//            if($shippingId==0 &&$shippingTempId==0){
//                $deliveryFee = $standardData['post_fee'];
//            }else{
//                //获取商品的重量
//                $totalWeight = 0;
//                foreach ($standardData['items'] as $k=>$v) {
//                    $weight =  app(Info::class)->getGoodsWeight($v['product_id'], $v['page_num']);
//                    $totalWeight += $weight*$v['buy_num'];
//                }
//                //获取运费
//                $deliveryFee = app(Logistics::class)->getDeliveryFee($shippingTempId,
//                    $shippingId,$provinceCode,$cityCode,$districtCode,$totalWeight);
//            }
//        }
        if(isset($this->standardData['post_fee'])){
            if ($this->standardData['post_fee'] != $deliveryFee) { //传入参数运费有误 !
                app(\App\Services\Exception::class)->throwException('70023',__FILE__.__LINE__);
//                $this->easyThrowException('70023',__FILE__.__LINE__);
            }
        }

        $this->standardData['post_fee'] = $deliveryFee;
        return $deliveryFee;


    }

}