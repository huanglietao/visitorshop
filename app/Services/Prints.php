<?php
namespace App\Services;

use App\Models\DmsAgentInfo;
use App\Models\OmsMerchantInfo;
use App\Models\SaasOrderLog;
use App\Models\SaasOrders;
use App\Models\SaasPrintLog;
use App\Models\SaasSalesChanel;
use App\Services\Common\CallWayBillPrinter\BillPrinter;
use App\Services\Outer\TbApi;

/**
 * 面单打印相关服务
 *
 * 使用到打印面单相关逻辑功能编写
 * @author: hlt <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/5/9
 */
class Prints
{
    public function __construct()
    {

        $this->sheetTemplates = config('print.sheetTemplates');

  /*      $this->sender = $this->getDefaultMchSender();*/
        $this->customerTemplates = 'http://cloudprint.cainiao.com/print/resource/getResource.json?resourceId=1502530&status=0';
    }
    /**
     * 菜鸟打单
     * @param  array   $orderInfo       订单详情(收件人信息) 示例['id'=>'123','agent_id'=>'18','order_no'=>'3216548916181184','province' => '广东','city'=>'广州市','area'=>'天河区','address'=>'天盈创意园d1033','mobile'=>'1326544561','consignee'=>'黄某某']
     * @param  string  $tradeOrderNo    交易单号
     * @param  string  $type            快递方式（如：'YTO' 大写）
     * @param  integer $is_old          是否新单号(旧单号打印的话就发打印日志表的id)
     * @param  integer $sp_id           供应商id
     * @return array   $extraData       打单自定义内容
     */

    public function caiNiaoPrinter($orderInfo,$tradeOrderNo,$type,$is_old=0,$sp_id=SUPPLIER_DEFAULT_ID,$extraData="",$sender=[])
    {

        if (empty($sender)){
            $sender = $this->getSenderAddress($orderInfo['id']);
        }else{
            $new_sender['100'] = $sender;
            $sender = $new_sender;
        }
        $orderInfo['trader_no'] = $tradeOrderNo;
        $sheetData = $this->combSheetData($type , $sender, $orderInfo);
        //请求菜鸟打单接口
        $helper = app(Helper::class);
        $caiNiaoArr = $helper->getCaiNiaoConfig($orderInfo['agent_id']);
        $postCnUrl = $caiNiaoArr['sdk_cnf_domain'];
        $postData = [
            'agent_id' => $caiNiaoArr['agent_id'], //$mid,
            'data' => json_encode($sheetData)
        ];
        $tbApi = new TbApi();
        $tbApiRes = $tbApi->request($postCnUrl.'/tb/cainiao/print-data',$postData,'POST');
       /* $tbApiRes = $tbApi->request($postCnUrl,$postData);*/
        if($tbApiRes['success'] == 'false') {
            return [
                'code' => 0,
                'msg'  => $tbApiRes['err_msg']['sub_msg'],
            ];
        }
        $resp = $tbApiRes['result'];
        $waybillCode = $resp['modules']['waybill_cloud_print_response'][0]['waybill_code'];



        //先记入日志
        $taskId = $this->insertPrintLog($orderInfo,$waybillCode,$sp_id,$type,$tradeOrderNo);
        //发送到打印机的数据
        $printerData = $this->combPrinterData($taskId, $resp,$extraData);
        return [
            'code' => 1,
            'data' => $printerData,
        ];
    }


    /**
     * 顺丰打单
     * @param  array   $orderInfo       订单详情(收件人信息) 示例['id'=>'123','agent_id'=>'18','order_no'=>'3216548916181184','province' => '广东','city'=>'广州市','area'=>'天河区','address'=>'天盈创意园d1033','mobile'=>'1326544561','consignee'=>'黄某某']
     * @param  string  $tradeOrderNo    交易单号
     * @param  string  $type            快递方式（如：'YTO' 大写）
     * @param  integer $is_old          是否新单号(旧单号打印的话就发打印日志表的id)
     * @param  integer $sp_id           供应商id
     * @return array   $extraData       打单自定义内容
     * @return array   $sender       发件人信息
     */
    public function sfPrinter($orderInfo,$tradeOrderNo,$type,$is_old=0,$sp_id=SUPPLIER_DEFAULT_ID,$extraData="",$sender=[])
    {
        if (empty($sender)){
            $sender = $this->getSenderAddress($orderInfo['id']);
        }else{
            $new_sender['100'] = $sender;
            $sender = $new_sender;
        }
        //组织数据
        $pData = $orderInfo;
        $pData['sender_province'] = $sender['100']['province'];
        $pData['sender_city'] = $sender['100']['city'];
        $pData['sender_area'] = $sender['100']['district'];
        $pData['sender_address'] = $sender['100']['detail'];
        $pData['sender_phone'] = $sender['100']['mobile'];
        $pData['sender_person'] = $sender['100']['name'];

        //添加交易单号，避免顺丰报重复下单的错误
        $pData['order_id'] = $tradeOrderNo;

        $pData['extra_data'] = $extraData;

        $bill_printer = new BillPrinter($pData);

        $logModel = app(SaasPrintLog::class);

        //判断是否需要重新下单
        if($is_old != '0') {
            //旧单号打单
            $logData = $logModel->where('id',$is_old)->first();
            //无须下单直接进行重新打单
            $bill_printer->post_data['mailNo'] = $logData['waybill_code'];

        } else {
            //新单号打单，需重新下单
            $bill_data = $bill_printer->place_order();

            if (isset($bill_data['origincode'])&&$bill_data['origincode'] == "022"){
                //下单成功，获取运单号
                $bill_printer->post_data['mailNo'] = $bill_data['mailno'];
            }else{
                return [
                    'code' => 0,
                    'msg'  => "顺丰面单打印出错!"
                ];
            }

        }
        //获取顺丰打印所需数据
        $bill_res = $bill_printer->re_data();
        $face_info = json_decode($bill_res['post_json_data'],true);

        //先记入日志
        $waybillCode = $face_info[0]['mailNo'];
        //先记入日志
        $taskId = $this->insertPrintLog($orderInfo,$waybillCode,$sp_id,$type,$tradeOrderNo);

        //顺丰快递返回数据组织
        $sf_data = [
            'taskID' => $taskId,
            'reqURL' => $bill_res['reqURL'],
            'post_json_data' => $bill_res['post_json_data']
        ];

        return [
            'code' => 1,
            'data' => $sf_data
        ];
    }


    /**
     * @param string $deliveryType  快递类型 如YTO
     * @param array $sender          发件人信息
     * @param array $traderOrderInfo  订单相关数据信息
     * @return array $sheetData       返回请求面单的数据
     */
    private function combSheetData($deliveryType , $sender, $traderOrderInfo)
    {
        $sheetData = [
            'cp_code'                           => $deliveryType,
            'sender' => [
                'address' => [
                    'province'                  => $sender['100']['province'],
                    'city'                      => $sender['100']['city'],
                    'district'                  => $sender['100']['district'],
                    'detail'                    => $sender['100']['detail'],
                    'town'                      => '',
                ],
                'mobile'                        =>  $sender['100']['mobile'],
                'name'                          => $sender['100']['name'],
            ],

            'trade_order_info_dtos' => [   //请求面单信息
                'logistics_services'        => '',  //如不需要特殊服务，该值为空
                'object_id'                 => '1',

                'order_info'        => [    //订单信息
                    'order_channels_type' => "OTHERS",
                    'trade_order_list'    => $traderOrderInfo['trader_no']
                ],

                'package_info'      => [   //包裹信息
                    'id'                    => '1',
                    'item'          =>[
                        'count'                     => '1',
                        'name'                      => "订单编号：".$traderOrderInfo['order_no'],
                    ],
                    'volume'                    => '1',
                    'weight'                    => '1',
                ],
                'recipient'              => [  //收件人信息
                    'address'                       => [
                        'province'                  =>  $traderOrderInfo['province'],
                        'city'                      =>  $traderOrderInfo['city'],
                        'district'                  =>  $traderOrderInfo['area'],
                        'detail'                    =>  $traderOrderInfo['address'],
                        'town'                      => ''
                    ],
                    'mobile'                        => $traderOrderInfo['mobile'],
                    'name'                          => $traderOrderInfo['consignee'],
                    'phone'                         => $traderOrderInfo['mobile'],
                ],
                'template_url'                      =>$this->sheetTemplates[$deliveryType],
                'user_id'                           => '3230326467'
            ],

            'store_code'                            => '',
            'resource_code'                         => '',
            'dms_sorting'                           => 'false'
        ];

        return $sheetData;
    }

    /**
     * @param string $taskId 任务id
     * @param array $resp   菜鸟接口返回数据
     * @param  string $extraData 额外信息
     * @return array
     */
    private function combPrinterData($taskId, $resp, $extraData = '')
    {
        return [
            'cmd' => 'print',
            'requetID' => $resp['request_id'],
            'version' => '1.0',
            'task' =>
                [
                    'taskID' => "$taskId",
                    'preview' => false,
                    'documents' =>
                        [
                            0 =>
                                [
                                    'documentID' => $resp['modules']['waybill_cloud_print_response'][0]['waybill_code'],
                                    'contents' =>
                                        [
                                            0 => json_decode($resp['modules']['waybill_cloud_print_response'][0]['print_data'],true),
                                            1 => [
                                                'templateURL' => $this->customerTemplates,
                                                'data' => ['item_name'=>$extraData],
                                            ],
                                        ],
                                ],
                        ],
                ]
        ];
    }

    /**
     * 插入打印日志表
     * @param  array   $orderInfo       订单信息 示例['id'=>'123','order_no'=>'3216548916181184']
     * @param  string  $waybillCode     快递单号（如：'YTO' 大写）
     * @param  integer $sp_id           供应商id
     * @param  integer $type            快递方式 如（'YTO' 大写）
     * @return array   $tradeOrderNo    交易单号
     */
    public function insertPrintLog($orderInfo,$waybillCode,$sp_id,$type,$tradeOrderNo)
    {
        $logModel = app(SaasPrintLog::class);
        $taskId = $orderInfo['id'].'_'.$type.'_'.rand(0, 1000);
        $logInfo = $logModel->where('trade_order',$tradeOrderNo)->first();

        if(empty($logInfo)) {
            $printLogData = [
                'taskid' => $taskId,
                'order_id' => $orderInfo['id'],
                'order_no' => $orderInfo['order_no'],
                'waybill_code' => $waybillCode,
                'sp_id'   => $sp_id,
                'company' => $type,
                'created_at' => time(),
                'updated_at' => time(),
                'print_times' => 0,
                'trade_order' => $tradeOrderNo,
                'cus_pri_id'  => $orderInfo['cus_pri_id']??0
            ];
            $logId = $logModel->insertGetId($printLogData);
        } else {
            $logId = $logInfo['id'];
        }
        $taskId .= '_'.$logId;
        return $taskId;
    }

    //根据订单id获取商户的发货信息
    public function getSenderAddress($order_id)
    {
        $orderModel = app(SaasOrders::class);
        $merchantModel = app(OmsMerchantInfo::class);
        $channleModel = app(SaasSalesChanel::class);
        $agentInfoModel = app(DmsAgentInfo::class);
        $orderInfo = $orderModel->where(['order_id' => $order_id])->select('mch_id','user_id','cha_id')->first();
        if (empty($orderInfo) || empty($orderInfo['mch_id'])){
            //没有查到所属商品，返回默认的发货信息
            $sender = $this->getDefaultMchSender();
        }else{
            $mch_id = $orderInfo['mch_id'];
            $mch_sender_info = $merchantModel->where('mch_id',$mch_id)->select('mch_sender_phone','mch_sender_person','mch_sender_address')->first();
            if (empty($mch_sender_info)){
                //没有查到所属商户，返回默认的发货信息
                $sender = $this->getDefaultMchSender();
            }else{
                //发件人，电话，地址缺一不可,有一个空的都返回默认发件信息
                if (empty($mch_sender_info['mch_sender_phone']) || empty($mch_sender_info['mch_sender_person']) || empty($mch_sender_info['mch_sender_address'])){
                    //返回默认的发货信息
                    $sender = $this->getDefaultMchSender();
                }else{
                    //使用商户设置的发货信息
                    $senderInfo = explode(" ",$mch_sender_info['mch_sender_address']);
                    $sender = [
                        '100' => [
                            'province'   => $senderInfo[0]??"",
                            'city'       => $senderInfo[1]??"",
                            'district'   => $senderInfo[2]??"",
                            'detail'     => $senderInfo[3]??"",
                            'mobile'     => $mch_sender_info['mch_sender_phone'],
                            'name'       => $mch_sender_info['mch_sender_person']
                        ]
                    ];
                }


            }
        }

        if (!empty($orderInfo['user_id']) && !empty($orderInfo['cha_id']))
        {
            //判断该订单是否为分销渠道
            $cha_flag = $channleModel->where('cha_id',$orderInfo['cha_id'])->value('cha_flag');
            if (!empty($cha_flag) && $cha_flag==CHANEL_TERMINAL_AGENT)
            {
                //为分销渠道，查看用户是否设置了发件人与联系电话
                $agentInfo = $agentInfoModel->where('agent_info_id',$orderInfo['user_id'])->select('agent_sender_person','agent_sender_phone')->first();
                if (!empty($agentInfo))
                {
                    if (!empty($agentInfo['agent_sender_person']) && !empty($agentInfo['agent_sender_phone'])){
                        $sender['100']['mobile'] = $agentInfo['agent_sender_phone'];
                        $sender['100']['name'] = $agentInfo['agent_sender_person'];
                    }
                }

            }

        }


        return $sender;
    }

    public function getDefaultMchSender(){
        $info = config('print.default_sender');
        $senderInfo = explode(" ",$info['mch_sender_address']);
        $sender = [
            '100' => [
                'province'   => $senderInfo[0],
                'city'       => $senderInfo[1],
                'district'   => $senderInfo[2],
                'detail'     => $senderInfo[3],
                'mobile'     => $info['mch_sender_phone'],
                'name'       => $info['mch_sender_person']
            ]
        ];
        return $sender;
    }
}