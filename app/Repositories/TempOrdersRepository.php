<?php
namespace App\Repositories;

use App\Models\ErpTempOrderItems;
use App\Models\ErpTempOrders;
use App\Services\Outer\Erp\Api;

/**
 * 临时订单仓库
 * 记录临时订单信息，主要用于打单及发货
 * @author: yanxs(541139655@qq.com)
 * @version: 1.0
 * @date: 2020/2/12
 */
class TempOrdersRepository extends BaseRepository
{
    protected $areasModel;
    protected $orderItemsModel;
    protected $apiErrRepos;
    public function __construct(ErpTempOrders $model, AreasRepository $area,
                                ErpTempOrderItems $orderItems,SassApiErrLogRepository $apiErrLogRepository)
    {
        $this->model =$model;
        $this->areasModel = $area;
        $this->orderItemsModel = $orderItems;
        $this->apiErrRepos = $apiErrLogRepository;
    }
    /**
     * 从api中获取数据源
     * @param $order_no 外部订单号或项目号
     * @return array $result
     */
    public function getOrdersInfoByApi($order_no)
    {
        //todo 请求api获取订单详细信息
        $res_arr = new Api();
        $data = [
            'sale_order_name' => $order_no,
        ];
        $result_arr  = $res_arr->request(config('erp.interface_url').config('erp.logistic_order'),$data);

        if (isset($result_arr['code']) && $result_arr['code'] == 1)
        {
            //成功
            $order_info = $result_arr['data'];
        }else{
            //获取失败
            $order_info = [];
        }
        return $order_info;



        /*$result = [
            'order_no'     => '813UI34678311',
            'consignee'    => '张三11',
            'mobile'       => '15915774779',
            'address'      => '河南省漯河市源汇区 建设路泰山路交叉口向南50米路西',
            'items'        => [
                [
                    'item_no'       => '813UI34678311_2_1_1',
                    'erp_order_no'  => '17534128761'
                ],
                [
                    'item_no'       => '813UI34678311_2_2_1',
                    'erp_order_no'  => '17534128762'
                ],
            ]
        ];

        return $result_arr;*/
    }


    /**
     * 通过发货接口进行发货操作
     * @param $deliveryData 发货数据
     * @return boolean
     */
    public function deliveryByApi($deliveryData)
    {
        //todo 请求api进行发货操作
        $api = new Api();

        $params = [
            'partner_number' => $deliveryData['order_no'],
            'express_num' => $deliveryData['waybill_code'],
            'express_type' => strtolower($deliveryData['company']),
        ];
        $url = config('erp.interface_url').config('erp.logistic_record');
        $resultApi  = $api->request($url,$params);
        //$resultApi = $api->request($url, $params);

        if($resultApi['code'] == 1) {  //成功
            return true;
        } else {
            $data = [
                'url' => $url,
                'params' => json_encode($params),
                'err_msg' => $resultApi['message'],
                'created_at' => time()
            ];
            $this->apiErrRepos->insert($data);
            return false;
        }
    }

    /**
     * 获取订单详情信息
     * @param $erpOrderNo erp订单号或行项目号
     * @return array
     */
    public function orderItemInfo($erpOrderNo)
    {
        $orderItems = ErpTempOrderItems::where('erp_order_no',$erpOrderNo)->first();
        return $orderItems;
    }

    /**
     * 获取订单全部详情信息
     * @param $sys_order_no 外部订单号
     * @return array
     */
    public function orderItemAll($sys_order_no)
    {
        $orderItems = ErpTempOrderItems::where('sys_order_no', $sys_order_no)->get();
        return $orderItems;
    }

    /**
     * 获取主订单信息
     * @param $sys_order_no 外部订单号
     * @return array
     */
    public function getOrder($sys_order_no)
    {
        $orderInfo = ErpTempOrders::where('sys_order_no', $sys_order_no)->first();
        return $orderInfo;
    }

    /**
     * 获取订单信息
     * @param $orderNo 订单号
     * @return array
     */
    public function orderInfo($orderNo)
    {
        $ordersInfo = ErpTempOrders::where('sys_order_no',$orderNo)->first();
        return $ordersInfo;
    }

    /**
     * 创建订单信息
     * @param $data 订单数据
     * @throws \Throwable
     */
    public function createOrders($data)
    {
        \DB::transaction(function() use ($data) {

            $orderNo = $this->generateOrderNo();

            $addressInfo = $this->areasModel->parseDetailAddress($data['address']);


            if (empty($addressInfo)) {
                $this->returnJson(1,"收货地址解析失败!");
            }


            $orderData = [
                'sys_order_no'   => $orderNo,
                'cus_order_no'   => $data['order_no']??'',
                'consignee'      => $data['consignee'],
                'mobile'         => $data['mobile'],
                'province'       => $addressInfo['p'],
                'city'       => $addressInfo['c'],
                'area'       => $addressInfo['a'],
                'address'       => $addressInfo['d'],
                'goods_num'     => count($data['items']),
                'sender_person' => $data['sender_person'],
                'sender_phone'  => $data['sender_phone'],
                'sender_address' => $data['sender_address'],
                'assign_express_type' => $data['assign_express_type'],
                'created_at'    =>time()
            ];

            $this->model->create($orderData);

            //生成子表数据
            foreach ($data['items'] as $k=>$v) {
                $orderItemsData = [
                    'sys_order_no' => $orderNo,
                    'erp_order_no' => $v['erp_order_no'],
                    'cus_project_sn' => $v['item_no']??'',
                    'sys_project_sn' => $orderNo.'-'. count($data['items']).'-'.++$k,
                    'created_at'     => time()
                ];
                $this->orderItemsModel->create($orderItemsData);
            }

        });


    }

    /**
     * 更新订单相关信息
     * @param $orderNo erp订单号或项目号
     * @param $data    更新的数据
     */
    public function updateOrders($orderNo,$data)
    {
        $itemInfo = $this->orderItemInfo($orderNo);
        $sysOrderNo = $itemInfo['sys_order_no'];

        $this->model->where('sys_order_no', $sysOrderNo)->update($data);
    }

    /**
     * 更新订单详情
     * @param $id 订单项目id
     * @param $data 更新的数据
     */
    public function updateOrderItems($id, $data)
    {
        $this->orderItemsModel->where('id', $id)->update($data);
    }

    /**
     * 判断是否已经集货完成
     * @param $sysOrderNo
     * @return boolean
     */
    public function isStocked($sysOrderNo)
    {
        $count = $this->orderItemsModel->where('sys_order_no', $sysOrderNo)->where('is_stocked', 0)->count();
        if (empty($count)) {
            //更新主表is_stocked状态
            $this->model->where("sys_order_no", $sysOrderNo)->update(['is_stocked'=>1]);
            return true;
        }
        return false;
    }


    /**
     * 生成订单号
     * @return string 返回订单串
     */
    private function generateOrderNo()
    {
        return 'E'.date('ymdHis').mt_rand(1111,9999);
    }

    //快递方式转换
    //接口快递方式

    public function transLogistics($companyArr,$str,$type)
    {
        //接口快递方式
        /*$api_arr = [
            'yto' => '圆通快递',
            'sto' => '申通快递',
            'zto' => '中通快递',
            'yunda' => '韵达快递',
            'best' => '百世快递',
            'sf'   => '顺丰快递',
            'ems'  => '中国邮政快递包裹',
        ];
        //我们快递方式
        $this->company = [
            'yto' => '圆通快递',
            'sf' => '顺丰快递',
            'yunda' => '韵达快递',
            'sto'=>'申通快递',
            'eyb' => '中国邮政快递包裹',
            'htky' => '百世快递'
        ];*/
        /*这里对比后发现接口两个不同
        1.接口的中通是我们是没有的
        2.百世的简称不一样
        3.邮政的简称不一样
        所以需要对这两个快递进行特殊处理*/
        if ($str == "zto")
        {
            //中通
            $company = "查无此快递方式";
            $company_str = "";
        }elseif ($str == "best"){
            //百世快递
            $company = $companyArr['htky'];
            $company_str = "htky";
        }elseif($str == "ems"){
            //邮政
            $company = $companyArr['eyb'];
            $company_str = "eyb";
        }elseif ($str == "sfj" || $str == "sfd"){
            //顺丰到付跟寄付（这里正常情况只有sfj能进来）
            $company = $companyArr['sf'];
            $company_str = "sf";
        }else{
            //其他快递
            if (!isset($companyArr[$str]))
            {
                //但快递方式为空时，取链接上的快递方式
                if ($str == "" || $str == 0 || $str == null || $str == "empty")
                {
                    $company = $companyArr[$type]??"";
                    if ($company == ""){
                        $company = "查无此快递方式";
                        $company_str = "";
                    }else{
                        $company_str = $type;
                    }
                }else{
                    $company = "查无此快递方式";
                    $company_str = "";
                }
            }else{
                $company = $companyArr[$str];
                $company_str = $str;
            }

        }
        $result['describe'] = $company;
        $result['label'] = $company_str;
        return $result;
    }
    /**
     * 从api中获取数据源
     * @param $order_no 外部订单号或项目号
     * @return array $result
     */
    public function getTradeOrdersInfoByApi($order_no)
    {
        $redis = app('redis.connection');
        //todo 请求api获取订单详细信息
        $res_arr = new Api();
        $data = [
            'trade_stock_move_name' => $order_no,
        ];
        $result_arr  = $res_arr->request(config('erp.interface_url').config('erp.logistic_trade_order'),$data);

        if (isset($result_arr['code']) && $result_arr['code'] == 1)
        {
            //成功
            $result = $result_arr['data'];
            $order_info['data'] = [
                'order_no'            => $result['trade_stock_move_name'],
                'assign_express_type' => $result['assign_express_type'],
                'consignee'           => $result['recipient_person'],
                'mobile'              => $result['recipient_phone'],
                'address'             => $result['recipient_address'],
                'sender_person'       => $result['sender_person'],
                'sender_phone'        => $result['sender_phone'],
                'sender_address'      => $result['sender_address'],
                'items'        => [
                    [
                        'item_no'       => $result['trade_stock_move_name'].'_1_1_1',
                        'erp_order_no'  => $result['trade_stock_move_name']
                    ],
                ]

            ];
            $order_info['msg'] = "";

            //将客户单号写进redis
            $redis->set('partner_number' , $result['partner_number']);

        }elseif(isset($result_arr['code']) && $result_arr['code'] != 1){
            //获取失败
            $order_info['data'] = [];
            $order_info['msg'] = $result_arr['message'];
        }else{
            //获取失败
            $order_info['data'] = [];
            $order_info['msg'] = "";
        }
        return $order_info;

        /*//模拟接口放回数据
        $result = [
            'trade_stock_move_name'     => '3422856234783894',
            'assign_express_type'       => 'sf',
            'recipient_person'          => '张三11',
            'recipient_phone'           => '15915774779',
            'recipient_address'         => '广东省广州市天河区 1101号',
            'sender_person'             => '一键生成',
            'sender_phone'              => '02787101355',
            'sender_address'            => '武汉'
        ];
        $order_info['data'] = [
            'order_no'            => $result['trade_stock_move_name'],
            'assign_express_type' => $result['assign_express_type'],
            'consignee'           => $result['recipient_person'],
            'mobile'              => $result['recipient_phone'],
            'address'             => $result['recipient_address'],
            'sender_person'       => $result['sender_person'],
            'sender_phone'        => $result['sender_phone'],
            'sender_address'      => $result['sender_address'],
            'items'        => [
                [
                    'item_no'       => $result['trade_stock_move_name'].'_1_1_1',
                    'erp_order_no'  => $result['trade_stock_move_name']
                ],
            ]

        ];
        //将客户单号写进redis
        $redis->set('partner_number' , "3422856234783894");
        $order_info['msg'] = "";
        $order_info['data']['assign_express_type'] = 'sfj';





        return $order_info;*/
    }

    /**
     * 贸易订单发货接口进行发货操作
     * @param $deliveryData 发货数据
     * @return boolean
     */
    public function deliveryByTradeApi($deliveryData)
    {
        //todo 请求api进行发货操作
        $api = new Api();

        $params = [
            'trade_stock_move_name' => $deliveryData['order_no'],
            'express_num' => $deliveryData['waybill_code'],
            'express_type' => strtolower($deliveryData['company']),
            'is_update_express' => $deliveryData['is_update_express'],
        ];
        file_put_contents('/tmp/trade_delivery.log',var_export($params,true),FILE_APPEND);
        $url = config('erp.interface_url').config('erp.logistic_trade_order_record');
        $resultApi  = $api->request($url,$params);
        //$resultApi = $api->request($url, $params);

        if($resultApi['code'] == 1) {  //成功
            return true;
        } else {
            $data = [
                'url' => $url,
                'params' => json_encode($params),
                'err_msg' => $resultApi['message'],
                'created_at' => time()
            ];
            $this->apiErrRepos->insert($data);
            return false;
        }
    }
    /**
     * @param int $status 状态码
     * @param string $msg 返回信息
     * @param string $data 数据
     */
    private function returnJson($status = 0, $msg = '', $data = '')
    {
        echo json_encode(['status' => $status, 'msg' => $msg, 'content' => $data]);
        exit();
    }
}
