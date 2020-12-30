<?php
/**
 * Created by sass.
 * Author: LJH
 * Date: 2020/7/3
 * Time: 14:18
 */

namespace App\Services\Orders;
use App\Exceptions\CommonException;
use App\Models\SaasDeliveryDoc;
use App\Models\SaasDeliveryLog;
use App\Models\SaasExpress;
use App\Models\SaasOrderErpPushQueue;
use App\Models\SaasOrderFileDetail;
use App\Models\SaasSuppliers;
use App\Repositories\SaasOrderFileDetailRepository;
use App\Repositories\SaasOrderFileRepository;
use App\Repositories\SaasOrderProductsRepository;
use App\Repositories\SaasOrdersRepository;
use App\Repositories\SaasProductsRepository;
use App\Services\Logistics;
use App\Models\SaasDiyAssistant;
use App\Models\SaasOrderFile;
use App\Models\SaasSalesChanel;
use App\Models\DmsAgentInfo;

/**
 * 订单发货归档类，跑订单发货表，将订单信息归档
 * Author: LJH <vali12138@163.com>
 * Date: 2020/7/3
 * Time: 14:18
 * @package App\Services\Orders
 */
class OrderFile
{

    public function __construct(SaasDeliveryDoc $deliveryDoc,SaasDeliveryLog $deliveryLog,SaasOrdersRepository $ordersRepository,
                                SaasSuppliers $suppliers,SaasExpress $express,SaasOrderProductsRepository $orderProductsRepository,
                                SaasOrderFileRepository $orderFileRepository,SaasOrderFileDetailRepository $orderFileDetailRepository,
                                Logistics $logistics,SaasProductsRepository $productsRepository,SaasOrderErpPushQueue $orderErpPushQueue,
                                SaasDiyAssistant $diyAssistant,DmsAgentInfo $agentInfo,SaasOrderFile $orderFile, SaasSalesChanel $chanel,
                                SaasOrderFileDetail $orderFileDetail)
    {
        $this->deliveryDocModel  = $deliveryDoc;
        $this->deliveryLog  = $deliveryLog;
        $this->suppliers  = $suppliers;
        $this->express  = $express;
        $this->logistics  = $logistics;
        $this->ordersRepository  = $ordersRepository;
        $this->orderProductsRepository  = $orderProductsRepository;
        $this->orderFileRepository  = $orderFileRepository;
        $this->orderFileDetailRepository  = $orderFileDetailRepository;
        $this->productsRepository  = $productsRepository;
        $this->orderErpPushQueue  = $orderErpPushQueue;
        $this->diyAssistantModel  = $diyAssistant;
        $this->agentInfoModel  = $agentInfo;
        $this->orderFileModel  = $orderFile;
        $this->chanelModel  = $chanel;
        $this->orderFileDetail  = $orderFileDetail;
    }

    //获取订单发货归档队列状态为ready的记录
    public function runReadyOrderFileQueue()
    {
        //获取每次跑队列条数
        $limit = config("common.queue_limit.order_file_queue");
        $QueueInfo = $this->deliveryDocModel->where(['queue_status' => 'ready'])->limit($limit)->get()->toArray();
        $this->runOrderFileQueue($QueueInfo);
    }

    //跑订单发货归档队列
    public function runOrderFileQueue($QueueInfo)
    {
        //获取供货商id 默认为长荣
        $sup_id = config("common.default_sup_id");
        foreach ($QueueInfo as $key => $value) {
            \DB::beginTransaction();
            //订单信息
            $orderInfo = $this->ordersRepository->orderInfo($value['ord_id'])->toArray();
            if (empty($orderInfo)) {
                //失败
                $data['err_msg']        = "获取不到订单信息";
                $data['times']          = ++$value['times'];
                $data['queue_status']   = "error";
                $data['updated_at']     = time();

                $this->deliveryDocModel->where(['del_doc_id' => $value['del_doc_id']])->update($data);
                \DB::commit();
                continue;
            }
            //订单发货日志信息
            $orderLogInfo = $this->deliveryLog->where(['ord_id' => $value['ord_id']])->get()->groupBy('delivery_code')->toArray();

            $express_list         = []; //快递名称
            $delivery_code_list   = []; //快递单号
            $product_info_list    = []; //商品信息
            $sp_info_list         = []; //供货商信息
            $product_cost         = 0;  //商品成本
            $express_cost         = 0;  //物流成本
            $product_nums         = 0;  //商品数量
            $product_weight       = 0;  //商品总重量

            //提交生产时间
            $submit_time = $this->orderErpPushQueue->where(['mch_id'=>$value['mch_id'],'order_id'=>$value['ord_id']])->select("start_time")->first();
            if (empty($submit_time)) {
                //失败
                $data['err_msg']        = "获取不到erp推送订单的信息";
                $data['times']          = ++$value['times'];
                $data['queue_status']   = "error";
                $data['updated_at']     = time();
                $this->deliveryDocModel->where(['del_doc_id' => $value['del_doc_id']])->update($data);
                \DB::commit();
                continue;
            }

            //订单归档表数据标准化
            $order_file_data = [
                'mch_id'                => $orderInfo['mch_id'],                        //商家id
                'user_id'               => $orderInfo['user_id'],                       //分销id
                'order_no'              => $orderInfo['order_no'],                      //订单号
                'order_relation_no'     => $orderInfo['order_relation_no'],             //外部订单号
                'order_create_time'     => (int)$orderInfo['created_at'],               //下单时间
                'rcv_province'          => $orderInfo['order_rcv_province'],            //省id
                'rcv_city'              => $orderInfo['order_rcv_city'],                //市id
                'rcv_area'              => $orderInfo['order_rcv_area'],                //区id
                'province_name'         => $orderInfo['province_name'],                 //省
                'city_name'             => $orderInfo['city_name'],                     //市
                'area_name'             => $orderInfo['area_name'],                     //区
                'rcv_address'           => $orderInfo['province_name'] . "-" . $orderInfo['city_name'] . "-" .
                                           $orderInfo['area_name'] . "-" . $orderInfo['order_rcv_address'], //收货地址
                'rcv_user'              => $orderInfo['order_rcv_user'],                //收货人
                'rcv_mobile'            => $orderInfo['order_rcv_phone'],               //收货号码
                'order_amount'          => $orderInfo['order_real_total'],              //订单金额
                'pay_name'              => $orderInfo['pay_name'],                      //支付方式
                'shipping_time'         => (int)$orderInfo['order_shipping_time'],      //发货时间
                'shop_info'             => $orderInfo['agent_name'],                    //店铺来源
                'cha_info'              => $orderInfo['cha_name'],                      //渠道来源
                'product_amount'        => $orderInfo['prod_amount'],                   //商品总额（不包括运费和优惠）
                'express_fee'           => $value['freight'],                           //配送费用
                'discount_fee'          => $orderInfo['discount_amount'],               //优惠金额
                'pay_amount'            => $orderInfo['order_real_total'],              //已支付金额
                'submit_time'           => (int)$submit_time['start_time']??0           //提交生产时间
            ];

            $flag = true;
            //订单发货日志记录
            foreach ($orderLogInfo as $keys => $values) {
                $order_file = $this->orderFileRepository->getList(['order_no'=>$values[0]['order_no']])->toArray();
                if(empty($order_file)){
                    //物流单号
                    $delivery_code_list[] = $keys;
                    //订单商品信息
                    $orderProduct = $this->orderProductsRepository->orderProductInfo($values[0]['order_no']);//获取对应的订单商品信息 可能为空
                    if (count($orderProduct)==0) {
                        \DB::rollBack();
                        //失败
                        $data['err_msg']        = "获取不到对应的订单商品信息";
                        $data['times']          = ++$value['times'];
                        $data['queue_status']   = "error";
                        $data['updated_at']     = time();
                        $this->deliveryDocModel->where(['del_doc_id' => $value['del_doc_id']])->update($data);
                        $flag = false;
                        break;
                    }

                    foreach ($orderProduct as $k => $v) {
                        //供货商
                        if (!empty($v['sp_id'])) {
                            $sp = $this->suppliers->where(['sup_id' => $v['sp_id']])->select('sup_name')->first();
                            if (empty($sp)) {
                                \DB::rollBack();
                                //失败
                                $data['err_msg']        = "获取不到供应商信息";
                                $data['times']          = ++$value['times'];
                                $data['queue_status']   = "error";
                                $data['updated_at']     = time();
                                $this->deliveryDocModel->where(['del_doc_id' => $value['del_doc_id']])->update($data);
                                $flag = false;
                                break 2;
                            }
                            $sp_info_list[] = $sp['sup_name'];
                        }

                        $product_info_list[] =  "商品编号:" . $v['prod_sn'] . " 商品名称:" . $v['prod_name'] . " 货号:"
                            . $v['sku_sn'] . " 属性:" . $v['prod_attr_str'] . " 数量:" . $v['prod_num'];//商品信息
                        $product_cost       +=  $v['prod_cost'];           //商品成本
                        $product_weight     +=  $v['prod_sku_weight'] * $v['prod_num'];         //商品总重量
                        $product_nums       +=  $v['prod_num'];                                 //商品数量

                        //快递名称
                        $express = $this->express->where(['express_id' => $values[0]['express_id']])->select('express_name')->first();
                        if (empty($express)) {
                            \DB::rollBack();
                            //失败
                            $data['err_msg']        = "获取不到快递信息1";
                            $data['times']          = ++$value['times'];
                            $data['queue_status']   = "error";
                            $data['updated_at']     = time();
                            $this->deliveryDocModel->where(['del_doc_id' => $value['del_doc_id']])->update($data);
                            $flag = false;
                            break 2;
                        }
                        $express_list[] = $express['express_name'];//快递方式

                        //判断该商品是否是冲印
                        $page_num = 1;
                        $single = $this->productsRepository->isSingle($v['prod_id']);
                        if($single['status']){
                            if($single['flag']=="single" && !empty($single['pt'])){
                                $page_num = $v['prod_pages'];
                            }
                        }else{
                            \DB::rollBack();
                            //失败
                            $data['err_msg']        = $single['msg'];
                            $data['times']          = ++$value['times'];
                            $data['queue_status']   = "error";
                            $data['updated_at']     = time();
                            $this->deliveryDocModel->where(['del_doc_id' => $value['del_doc_id']])->update($data);
                            $flag = false;
                            break 2;
                        }
                        //订单归档详情表数据标准化
                        $order_file_detail_data = [
                            'mch_id'                => $orderInfo['mch_id'],                    //商家id
                            'user_id'               => $orderInfo['user_id'],                   //分销id
                            'order_no'              => $v['order_no'],                          //订单号
                            'order_item_no'         => $v['ord_prj_item_no'],                   //订单项目号
                            'express_name'          => $express['express_name'],                //快递名称
                            'delivery_code'         => $keys,                                   //快递单号
                            'rcv_address'           => $orderInfo['province_name'] . "-" . $orderInfo['city_name'] . "-" .
                                $orderInfo['area_name'],                 //收货地址
                            'product_name'          => $v['prod_name'],                         //商品名称
                            'product_sku_sn'        => $v['sku_sn'],                            //货号
                            'product_process_code'  => $v['prod_process_code'],                 //工艺码
                            'product_attr'          => $v['prod_attr_str'],                     //商品属性
                            'product_nums'          => $v['prod_num'],                          //商品数量
                            'product_weight'        => $v['prod_sku_weight'] * $v['prod_num'],  //商品总重量
                            'product_cost'          => $v['prod_cost'],                         //商品成本
//                        'express_cost'          => $cost,                                   //物流成本
                            'product_page_num'      => $page_num,                               //张数
                            'shipping_time'         => $orderInfo['order_shipping_time']??0,    //发货时间
                            'order_create_time'     => $orderInfo['created_at'],                //下单时间
                            'shop_info'             => $orderInfo['agent_name'],                //店铺来源
                            'cha_info'              => $orderInfo['cha_name'],                  //渠道来源
                            'sp_info'               => $sp['sup_name']                          //渠道来源
                        ];

                        //插入订单归档详情表
                        $detail_ret = $this->orderFileDetailRepository->save($order_file_detail_data);
                        if (!$detail_ret) {
                            \DB::rollBack();
                            //失败
                            $data['err_msg']        = "插入订单归档详情表出错";
                            $data['times']          = ++$value['times'];
                            $data['queue_status']   = "error";
                            $data['updated_at']     = time();
                            $this->deliveryDocModel->where(['del_doc_id' => $value['del_doc_id']])->update($data);
                            $flag = false;
                            break 2;
                        }
                    }
                    try {
                        //获取物流成本
                        $express_cost = $this->logistics
                            ->getLogisticsCosts($sup_id, $value['delivery_id'], $orderInfo['order_rcv_province'],
                                $orderInfo['order_rcv_city'], $orderInfo['order_rcv_area'],$product_weight);
                    } catch (CommonException $e) {
                        \DB::rollBack();
                        //失败
                        $data['err_msg']        = $e->getMessage();
                        $data['times']          = ++$value['times'];
                        $data['queue_status']   = "error";
                        $data['updated_at']     = time();
                        $this->deliveryDocModel->where(['del_doc_id' => $value['del_doc_id']])->update($data);
                        $flag = false;
                        break 2;
                    }
                    if($flag){
                        $order_file_data['express_name']    = implode(",", array_unique($express_list));        //快递名称
                        $order_file_data['delivery_code']   = implode(",", array_unique($delivery_code_list));  //快递单号
                        $order_file_data['sp_info']         = implode(",", array_unique($sp_info_list));        //供货商来源
                        $order_file_data['product_info']    = implode(";", $product_info_list);                 //商品信息
                        $order_file_data['express_cost']    = $express_cost;                                         //物流成本
                        $order_file_data['product_cost']    = $product_cost;                                         //商品成本
                        $order_file_data['product_nums']    = $product_nums;                                         //商品数量
                        $order_file_data['product_weight']  = $product_weight;                                       //商品总重量

                        //插入订单归档表
                        $file_ret = $this->orderFileRepository->save($order_file_data);
                        if (!$file_ret) {
                            \DB::rollBack();
                            //失败
                            $data['err_msg']        = "插入订单归档表出错";
                            $data['times']          = ++$value['times'];
                            $data['queue_status']   = "error";
                            $data['updated_at']     = time();
                            $this->deliveryDocModel->where(['del_doc_id' => $value['del_doc_id']])->update($data);
                            $flag = false;
                            break 2;
                        }
                    }
                }else{
                    //获取快递名称
                    $express = $this->express->where(['express_id' => $values[0]['express_id']])->select('express_name')->first();
                    if (empty($express)) {
                        \DB::rollBack();
                        //失败
                        $data['err_msg']        = "获取不到快递信息2";
                        $data['times']          = ++$value['times'];
                        $data['queue_status']   = "error";
                        $data['updated_at']     = time();
                        $this->deliveryDocModel->where(['del_doc_id' => $value['del_doc_id']])->update($data);
                        $flag = false;
                        break;
                    }
                    try {
                        //获取物流成本
                        $express_cost = $this->logistics
                            ->getLogisticsCosts($sup_id, $value['delivery_id'], $orderInfo['order_rcv_province'],
                                $orderInfo['order_rcv_city'], $orderInfo['order_rcv_area'],$product_weight);
                    } catch (CommonException $e) {
                        \DB::rollBack();
                        //失败
                        $data['err_msg']        = $e->getMessage();
                        $data['times']          = ++$value['times'];
                        $data['queue_status']   = "error";
                        $data['updated_at']     = time();
                        $this->deliveryDocModel->where(['del_doc_id' => $value['del_doc_id']])->update($data);
                        $flag = false;
                        break;
                    }
                    //快递名称
                    $order_file[0]['express_name'] = $order_file[0]['express_name'].",".$express['express_name'];
                    //快递成本
                    $order_file[0]['express_cost'] = $order_file[0]['express_cost']+$express_cost;
                    //物流单号
                    $order_file[0]['delivery_code'] = $order_file[0]['delivery_code'].",".$values[0]['delivery_code'];
                    $ret = $this->orderFileRepository->save($order_file[0]);
                    $det_data = [
                        'express_name'=>$order_file[0]['express_name'],
                        'delivery_code'=>$order_file[0]['delivery_code'],
                        'updated_at'=>time()
                    ];
                    $det_ret = $this->orderFileDetail->where(['order_no'=>$values[0]['order_no']])->update($det_data);
                    if (!$ret && !$det_ret) {
                        \DB::rollBack();
                        //失败
                        $data['err_msg']        = "更新订单归档表或订单归档详情表出错";
                        $data['times']          = ++$value['times'];
                        $data['queue_status']   = "error";
                        $data['updated_at']     = time();
                        $this->deliveryDocModel->where(['del_doc_id' => $value['del_doc_id']])->update($data);
                        $flag = false;
                        break;
                    }
                }
            }
            if($flag){
                //更新队列状态
                $data['queue_status'] = "finish";
                $data['err_msg']        = "";
                $data['updated_at']     = time();
                $this->deliveryDocModel->where(['del_doc_id' => $value['del_doc_id']])->update($data);
                \DB::commit();
            }
        }
    }

    /**
     * @author: cjx
     * @version: 1.0
     * @date: 2020/07/07
     * @remark: 将diy_assistant中特殊订单同步到订单归档表
     */
    public function specialOrderFile()
    {
        $limit = config("common.queue_limit.special_order_queue");
        $data = $this->diyAssistantModel->where(['is_special'=>PUBLIC_YES,'is_file'=>PUBLIC_NO])->orderBy('created_at')->take($limit)->get()->toArray();

        if(!empty($data)){
            try{
                try{
                    foreach ($data as $k=>$v){

                        $is_exist = $this->orderFileModel->where(['order_no'=>$v['order_no'],'order_relation_no'=>$v['order_no']])->get()->toArray();
                        if(!empty($is_exist)){
                            //存在则跳出循环，不重复插入
                            continue;
                        }

                        //淘宝订单信息
                        $tb_order_info = json_decode($v['order_info'],true);

                        //分销信息
                        $agent_info = $this->agentInfoModel->where(['agent_info_id'=>$v['agent_id']])->select('mch_id','agent_name')->first();

                        //商品数量
                        $total_num = 0;
                        foreach ($tb_order_info['result']['trade']['orders']['order'] as $key=>$val){
                            $total_num += $val['num'];
                        }

                        //供货商
                        $sp_name = $this->suppliers->where(['sup_id'=>SUPPLIER_DEFAULT_ID])->value('sup_name');

                        //渠道
                        $chanel = $this->chanelModel->where(['short_name'=>AGENT_CHANNEL])->value('cha_name');

                        $file_data = [
                            'mch_id'                =>  $agent_info['mch_id'],
                            'user_id'               =>  $v['agent_id'],
                            'order_no'              =>  $v['order_no'], //特殊订单不生成系统订单，用淘宝订单号作为order_no
                            'order_relation_no'     =>  $v['order_no'],
                            'order_create_time'     =>  $v['created_at'],
                            'province_name'         =>  $tb_order_info['result']['trade']['receiver_state'],
                            'city_name'             =>  $tb_order_info['result']['trade']['receiver_city'],
                            'area_name'             =>  $tb_order_info['result']['trade']['receiver_district'],
                            'rcv_user'              =>  $tb_order_info['result']['trade']['receiver_name'],
                            'rcv_address'           =>  $tb_order_info['result']['trade']['receiver_address'],
                            'rcv_mobile'            =>  $tb_order_info['result']['trade']['receiver_mobile'],
                            'order_amount'          =>  $tb_order_info['result']['trade']['total_fee'],
                            'pay_name'              =>  '预存款',
                            'product_info'          =>  $v['order_prod_name'],
                            'shop_info'             =>  $agent_info['agent_name'],
                            'cha_info'              =>  $chanel,
                            'sp_info'               =>  $sp_name,
                            'pay_amount'            =>  $tb_order_info['result']['trade']['payment'],
                            'product_nums'          =>  $total_num,
                            'created_at'            =>  time(),
                        ];

                        \DB::beginTransaction();

                        $this->orderFileRepository->insert($file_data);

                        //更新为已归档状态
                        $this->diyAssistantModel->where(['diy_ass_id'=>$v['diy_ass_id']])->update(['is_file'=>PUBLIC_YES]);

                        \DB::commit();
                    }
                }catch (\Exception $e){
                    \DB::rollBack();
                    app(\App\Services\Exception::class)->throwException('70102',__FILE__.__LINE__);
                }
            }catch (CommonException $exception){
                return $exception->getMessage();
            }

        }
    }

}