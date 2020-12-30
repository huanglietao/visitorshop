<?php
namespace App\Services\Orders;
use App\Exceptions\CommonException;
use App\Repositories\DmsAgentInfoRepository;
use App\Repositories\OmsMerchantAccountRepository;
use App\Repositories\OmsMerchantInfoRepository;
use App\Repositories\SaasAreasRepository;
use App\Repositories\SaasCategoryRepository;
use App\Repositories\SaasManuscriptRepository;
use App\Repositories\SaasOrderProductsRepository;
use App\Repositories\SaasOrdersRepository;
use App\Repositories\SaasPaymentRepository;
use App\Repositories\SaasProductsRepository;
use App\Repositories\SaasProductsSkuRepository;
use App\Repositories\SaasProjectsRepository;
use App\Repositories\SaasSalesChanelRepository;
use App\Repositories\SaasUserRepository;
use App\Services\Goods\Info;
use App\Services\Goods\Price;
use App\Services\Helper;
use App\Services\Logistics;
use App\Services\Queue;
use App\Services\Suppliers;

/**
 * 订单抽象类
 * 处理订单通用逻辑，订单处理实体类继承此类进行特殊逻辑的实现
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/10/22
 */
abstract class OrdersAbstract
{
    protected $orderNo;        //订单号
    protected $orderStatus;    //订单状态
    protected $confirmStatus;  //确认状态
    protected $payStatus;       //支付状态
    protected $deliveryStatus;  //发货状态

    protected $createData;      //创建订单传入的数据
    protected $standardData;     //订单标准数据

    protected $requestType = 'inner'   ;   //内部请求还是外部请求.
    protected $projectId     ;  //项目id(作品或稿件id)
    protected $partnerCode = '100'   ;  //合作标识

    protected $goodsInfo;     //商品信息，以主键为键名，单条记录为值,二维
    protected $skuInfo ;   //货品信息，同商品
    protected $worksInfo    ;   //作品信息 同上
    protected $chanelInfo   ;   //渠道信息  一维
    protected $mchInfo      ;   //商家信息  一维
    protected $userInfo     ;   //用户信息  一维
    protected $areaInfo     ;   //地址信息

    protected $repoGoods;       //商品仓库
    protected $repoSku;           //sku仓库
    protected $repoOrder;         //订单仓库
    protected $repoMch  ;        //商户仓库
    protected $repoCha  ;        //渠道仓库
    protected $repoAgent;        //分销仓库
    protected $repoUser ;        //会员仓库
    protected $repoArea;         //地址
    protected $repoCate;         //分类
    protected $repoOrderProd;    //订单商品
    protected $repoPay;          //支付

    /**
     * 在构造器注入订单需依赖的仓库
     * OrdersAbstract constructor.
     * @param SaasProductsRepository $prod
     * @param OmsMerchantAccountRepository $mch
     * @param SaasProductsSkuRepository $sku
     * @param SaasOrdersRepository $order
     * @param SaasSalesChanelRepository $chanel
     * @param DmsAgentInfoRepository $agent
     * @param SaasUserRepository $user
     * @param SaasAreasRepository $area
     * @param SaasProjectsRepository $project
     * @param SaasProjectsRepository $project
     * @param SaasManuscriptRepository $manuscript
     * @param SaasCategoryRepository $cate
     * @param SaasOrderProductsRepository $orderProd
     * @param SaasPaymentRepository $pay
     */
    public function __construct(SaasProductsRepository $prod,OmsMerchantInfoRepository $mch,SaasPaymentRepository $pay,
            SaasProductsSkuRepository $sku, SaasOrdersRepository $order,SaasSalesChanelRepository $chanel,
            DmsAgentInfoRepository $agent,SaasUserRepository $user,SaasAreasRepository $area,SaasOrderProductsRepository $orderProd,
            SaasProjectsRepository $project, SaasManuscriptRepository $manuscript,SaasCategoryRepository $cate)
    {
        $this->repoGoods        = $prod;
        $this->repoSku          = $sku;
        $this->repoOrder        = $order;
        $this->repoMch          = $mch;
        $this->repoCha          = $chanel;
        $this->repoAgent        = $agent;
        $this->repoUser         = $user;
        $this->repoArea         = $area;
        $this->repoWorks        = $project;
        $this->repoManuscript   = $manuscript;
        $this->repoCate         = $cate;
        $this->repoOrderProd    = $orderProd;
        $this->repoPay          = $pay;
    }
    /**
     * 订单创建,一步一步将 $this->standardData这个
     * 用户传来的数据填充为创建订单需要的标准数据.
     * @param $data  传入的数据
     *
     *
     */
    public function create($data)
    {
        try {
            $this->createData = $data;

            //标准化数据
            $this->standardData = $this->setOrdersData($data);

            \DB::beginTransaction();
            //参数基础检测
            $this->checkParams();
            $this->checkSpecialParams();

            $this->partnerCode = $this->standardData['partner_code']??$this->partnerCode;
            //获取订单号
            if (!empty( $this->standardData['order_no'])) {
                $this->orderNo = $this->standardData['order_no'];
            } else {
                $this->orderNo = Helper::generateNo($this->partnerCode);
            }
            //订单号重复判断
            $count = $this->repoOrder->isOrderExists($this->orderNo);
            if($count > 0) {  //订单号重复。
                $this->easyThrowException('70020',__FILE__.__LINE__);
            }
            //供货商及成本价相关处理

            $priceRes = $this->getSupplierPrice();

            //快递及相关费用相关处理
            $postFee = $this->getLogisticsInfo();

            //商品渠道价格相关处理
            $this->getGoodsPrice();

            //优惠券、积分、活动营销策略处理
            $this->getOrderDiscount();
            //生成订单数据
            $orderId = $this->generateOrderData();
            //余额支付金额操作
            $userType = $this->standardData['buyer_type'];
            $userId = $this->standardData['user_id'];
            if ($this->standardData['pay_flag'] == PAYMENT_FLAG_BALANCE)
            {
                $res = app(AfterCreate::class)->balancePay($orderId,$userId ,$userType);
                if (!$res) { //余额支付失败
                    $this->easyThrowException('70028',__FILE__.__LINE__);
                }
                app(Status::class)->updateToPayed($orderId);
                $this->createLog($orderId, 2);
            }

            //作品队列(下载/转移/合成)
            $this->generateWorksQueue();
            //构造生产队列
            $this->generateProduceQueue();

            //额外操作，继承此类自行实现
            $this->extraProcess();

            \DB::commit();
            return ['status'=>'success','data'=>$this->orderNo];
        } catch (CommonException $e) {
            \DB::rollBack();
            return ['status'=>'failed','msg'=>$e->getMessage()];
            var_dump($e->getMessage());
        }

    }

    /**
     * 标准参数基础检查,检查必填参数
     */
    protected function checkParams()
    {
        $standardData = $this->standardData;

        //渠道、商家、终端有效性
        $this->checkUsersInvalid($standardData);

        //检查item里面的项
        foreach ($standardData['items'] as $k=>$v) {
            if (empty($v['goods_id']) || empty($v['product_id'])) {
                $this->easyThrowException('70002',__FILE__.__LINE__);
            }
            //作品相关信息检查
            if (!isset($v['works_id']) || !isset($v['file_type'])) {
                $this->easyThrowException('70003',__FILE__.__LINE__);
            }
            //购买数量检查
            if (empty($v['buy_num'])) {
                $this->easyThrowException('70004',__FILE__.__LINE__);
            }

            //检查商品有效性
            $this->checkGoodsInvalid($v['goods_id']);

            //检查货品(sku)的有效性
            $this->checkSkuInvalid($v['product_id']);

            //作品id存在情况下检查作品id的有效性
            $works_id = $this->checkWorksInvalid($v);
            //把标准数据里的works_id替换掉
            $standardData['items'][$k]['works_id'] = $this->standardData['items'][$k]['works_id'] = $works_id;
        }
//        var_dump($standardData);exit;
        //验证收货人信息并解析地址
        $this->checkReceiverInfo($standardData['receiver_info']);

        //支付信息校验
        if (empty ($standardData['pay_info']['pay_id'])) {
            $this->easyThrowException('70010',__FILE__.__LINE__);
        }else {
            $payId = $standardData['pay_info']['pay_id'];
            $payInfo = $this->repoPay->getById($payId);
            $payFlag = $payInfo['pay_class_name'];
            $this->standardData['pay_flag'] = $payFlag;
        }

    }

    /**
     * 用户相关有效信检查
     * @param
     */
    private function checkUsersInvalid($standardData)
    {
        //参数 mch_id必须
        if (empty ($standardData['mch_id'])) {
            $this->easyThrowException('70011',__FILE__.__LINE__);
        }
        //参数 chanel_id必须
        if (empty ($standardData['chanel_id'])) {
            $this->easyThrowException('70012',__FILE__.__LINE__);
        }

        //参数 buyer_type必须
        if (empty ($standardData['buyer_type'])) {
            $this->easyThrowException('70006',__FILE__.__LINE__);
        }
        //参数 user_id 必须
        if (empty ($standardData['user_id'])) {
            $this->easyThrowException('70013',__FILE__.__LINE__);
        }
        //获取渠道数据
        $this->chanelInfo = $this->repoCha->getById($standardData['chanel_id']);

        //渠道参数错误
        if (empty ($this->chanelInfo)) {
            $this->easyThrowException('70014',__FILE__.__LINE__);
        }

        //获取商户数据
        $this->mchInfo = $this->repoMch->getById($standardData['mch_id']);
        //商户参数错误
        if (empty($this->mchInfo)) {
            $this->easyThrowException('70015',__FILE__.__LINE__);
        }

        //分销
        if ($standardData['buyer_type'] == CHANEL_TERMINAL_AGENT) {
            $this->userInfo = $this->repoAgent->getById($standardData['user_id']);
            $this->userInfo['user_id'] = $this->userInfo['agent_info_id'];
        } else {
            $this->userInfo = $this->repoUser->getById($standardData['user_id']);

        }

        //会员不存在
        if(empty($this->userInfo['user_id'])) {
            $this->easyThrowException('70016',__FILE__.__LINE__);
        }
        $this->userInfo['user_type'] = $standardData['buyer_type'];  //1分销  2会员

    }

    /**
     * 校验收货人信息
     * @param $receiverInfo
     * @return boolean
     */
    private function checkReceiverInfo($receiverInfo)
    {
        //联系人信息与手机号码必须
        if (empty($receiverInfo['consignee']) || empty($receiverInfo['ship_mobile'])) {
            $this->easyThrowException('70005',__FILE__.__LINE__);
        }
        //收货人省份信息必须
        if(empty($receiverInfo['province_name']) && empty($receiverInfo['province_code'])) {
            $this->easyThrowException('70006',__FILE__.__LINE__);
        }
        //收货人城市信息必须
        if(empty($receiverInfo['city_name']) && empty($receiverInfo['city_code'])) {
            $this->easyThrowException('70007',__FILE__.__LINE__);
        }
        //收货人区信息必须
        if(empty($receiverInfo['district_name']) && empty($receiverInfo['district_code'])) {
            $this->easyThrowException('70008',__FILE__.__LINE__);
        }
        //详细地址必须
        if (empty($receiverInfo['ship_addr']))  {
            $this->easyThrowException('70009',__FILE__.__LINE__);
        }


        //解析具体省份为地址编码
        if (empty($receiverInfo['province_code']) && !empty ($receiverInfo['province_name'])) {
            $provinceCode = $this->repoArea->provinceNameToCode($receiverInfo['province_name']);
            $this->standardData['receiver_info']['province_code'] = $provinceCode;
        }else{
            $provinceCode = $receiverInfo['province_code'];
            $this->standardData['receiver_info']['province_code'] = $provinceCode;
        }
        if (empty($receiverInfo['city_code']) && !empty ($receiverInfo['city_name'])) {
            $cityCode= $this->repoArea->cityNameToCode($receiverInfo['city_name']);
            $this->standardData['receiver_info']['city_code'] = $cityCode;
        }else{
            $cityCode = $receiverInfo['city_code'];
            $this->standardData['receiver_info']['city_code'] = $cityCode;
        }
        if (empty($receiverInfo['district_code']) && !empty ($receiverInfo['district_name'])) {
            $districtCode= $this->repoArea->districtNameToCode($receiverInfo['district_name']);
            $this->standardData['receiver_info']['district_code'] = $districtCode;
        }else{
            $districtCode = $receiverInfo['district_code'];
            $this->standardData['receiver_info']['district_code'] = $districtCode;
        }
//        var_dump($this->standardData);
        $this->areaInfo = compact('provinceCode', 'cityCode', 'districtCode');

        return true;
    }
    /**
     * 商品参数有效性检查
     * @param $key
     * @return array|object
     */
    private function checkGoodsInvalid($key)
    {
        $this->goodsInfo[$key] = $goods_info = $this->repoGoods->getInfoOnSale(['prod_id' => $key]);
        //商品不存在或已下架
        if(empty($goods_info)) {
            $this->easyThrowException('40010',__FILE__.__LINE__);
        }

        //取出商品的分类标识
        $cateInfo = $this->repoCate->getById($goods_info['prod_cate_uid']);
        $this->goodsInfo[$key]['cate_flag'] = $cateInfo['cate_flag'];

        return $this->goodsInfo;
    }

    /**
     * sku有效性检查
     * @param $key
     */
    private function checkSkuInvalid($key)
    {

    }
    /**
     * 作品参数有效性检查
     * @param $data
     * @return int  返回作品id
     */
    private function checkWorksInvalid($data)
    {
        // 作品id为0，并且是稿件形式,则必须提供稿件的信息
        $pageNum = 0;
        $works_info = [];
        if (empty($data['works_id']) && $data['file_type'] == WORKS_FILE_TYPE_UPLOAD) {
            if (empty($data['file_info']['file_url'])) {  //稿件信息必须
                $this->easyThrowException('70018',__FILE__.__LINE__);
            }
            //处理稿件，生成一条稿件记录返回project_id
            $manuscriptData = [
                'mch_id'    => $this->mchInfo['mch_id'],
                'cha_id'    => $this->chanelInfo['cha_id'],
                'user_id'   => $this->userInfo['user_id'],
                'prod_id'   => $data['goods_id'],
                'sku_id'    => $data['product_id'],
                'script_url'=> $data['file_info']['file_url'],
                'prj_page_num' => isset($data['file_info']['pages_num']) && !empty($data['file_info']['pages_num'])?$data['file_info']['pages_num']:0,
                'is_outer'   => PUBLIC_YES,
                'is_queue'   => PUBLIC_NO,
                'created_at' => time(),
                'updated_at'  => time()
            ];
            $pageNum = $data['file_info']['pages_num']??0;
            $ManuscriptObj = $this->repoManuscript->insert($manuscriptData);
            if (empty ($ManuscriptObj)) {  //插入稿件数据失败
                $this->easyThrowException('70019',__FILE__.__LINE__);
            }
            $works_info = $ManuscriptObj;
            $works_id = $ManuscriptObj->script_id;

        } else {
            //内部稿件的情况,id存在则验证id的正确性
            if (!empty($data['works_id']) && $data['file_type'] == WORKS_FILE_TYPE_UPLOAD) {
                $works_info = $this->repoManuscript->getById($data['works_id']);
            } elseif(!empty($data['works_id']) && $data['file_type'] == WORKS_FILE_TYPE_DIY) {
                $works_info = $this->repoWorks->getById($data['works_id']);
            }
            $pageNum = isset($works_info['prj_page_num'])?$works_info['prj_page_num'] : 0;
            if (!empty ($data['works_id']) && empty ($works_info)) {
                $this->easyThrowException('70021',__FILE__.__LINE__);
            }

            $works_id = $data['works_id'];
        }

        $this->worksInfo[$works_id.'-'.$data['file_type']] = $works_info;

        return $works_id;
    }

    /**
     * 额外特殊参数校验，子类可重写
     * @return mixed
     */
    protected function checkSpecialParams()
    {
        return true;
    }

    /**
     * 获取供货商信息及价格
     */
    protected function getSupplierPrice()
    {
        $standardData = $this->standardData;
        $provinceCode = $standardData['receiver_info']['province_code'];
        $cityCode = $standardData['receiver_info']['city_code'];
        $districtCode = $standardData['receiver_info']['district_code'];

        foreach ($standardData['items'] as $k=>$v) {
            //匹配供应商
            $spId = app(Suppliers::class)->matchSupplier($v['goods_id'], $provinceCode, $cityCode, $districtCode);
            $this->standardData['items'][$k]['sp_id'] = $spId;

            //获取成本价
            $pageNum = 0;
            if ($v['file_type'] == WORKS_FILE_TYPE_UPLOAD) { //获取稿件P数
                $pageObj= $this->repoManuscript->getById($v['works_id']);
            } elseif($v['file_type'] == WORKS_FILE_TYPE_DIY) {
                $pageObj = $this->repoWorks->getById($v['works_id']);
            }

            $pageNum = isset($pageObj['prj_page_num']) ? $pageObj['prj_page_num']:0;

            $costPrice = app(Price::class)->getSupplierPrice($v['product_id'],$spId, $pageNum);

            $this->standardData['items'][$k]['cost_price'] = $costPrice * $v['buy_num'];
            $this->standardData['items'][$k]['page_num'] = $pageNum;
        }
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

        if (count($arrGoodIds) == 1 && $this->goodsInfo[$arrGoodIds[0]]['prod_express_type'] == LOGISTICS_PRICE_BY_FIXED) { //单个且固定运费情况下
            $deliveryFee = $this->goodsInfo[$standardData['items'][0]['goods_id']]['prod_express_fee'];
        } else {
            if($shippingId==0 &&$shippingTempId==0){
                $deliveryFee = $standardData['post_fee'];
            }else{
                //获取商品的重量
                $totalWeight = 0;
                foreach ($standardData['items'] as $k=>$v) {
                    $weight =  app(Info::class)->getGoodsWeight($v['product_id'], $v['page_num']);
                    $totalWeight += $weight*$v['buy_num'];
                }
                //获取运费
                $deliveryFee = app(Logistics::class)->getDeliveryFee($shippingTempId,
                    $shippingId,$provinceCode,$cityCode,$districtCode,$totalWeight);
            }
        }
        if(isset($this->standardData['post_fee'])){
            if ($this->standardData['post_fee'] != $deliveryFee) { //传入参数运费有误 !
                $this->easyThrowException('70023',__FILE__.__LINE__);
            }
        }

        $this->standardData['post_fee'] = $deliveryFee;
        return $deliveryFee;


    }

    /**
     * 获取商品价格
     */
    public function getGoodsPrice()
    {
        $standardData = $this->standardData;

        $totalGoodsPrice = 0;
        foreach ($standardData['items'] as $k=>$v) {
            $singlePrice = app(Price::class)->getChanelPrice($v['product_id'], $this->userInfo['cust_lv_id'], $v['page_num']);
            $goodsPrice  = $singlePrice * $v['buy_num'];
            //var_dump($goodsPrice);
            //比对传过来的价格和计算价格是否一致。
            if(!empty($v['real_fee']) && abs($goodsPrice - $v['real_fee']) > 0.01) {
                $this->easyThrowException('70024',__FILE__.__LINE__); //传入的商品价格有误
            }

            $this->standardData['items'][$k]['goods_price'] = $goodsPrice;
            $totalGoodsPrice += $goodsPrice;
        }

        $totalAmount = $totalGoodsPrice + $this->standardData['post_fee'] + $this->getTaxFee();
        //var_dump($totalAmount);exit;
        if (!empty($this->standardData['total_amount']) && abs($totalAmount - $this->standardData['total_amount']) > 0.01) {
            $this->easyThrowException('70025',__FILE__.__LINE__); //传入的总价格有误
        }

        $this->standardData['total_amount'] = $totalAmount;
        return true;
    }

    /**
     * 计算订单折扣,如需要，请覆盖重写
     */
    protected function getOrderDiscount()
    {
        $this->standardData['discount_fee'] = 0;
    }

    /**
     * 获取税费,如有特殊覆盖重写
     */
    protected function getTaxFee()
    {
        if (isset($this->standardData['invoice_info'])) {
            return $this->standardData['invoice_info']['fee'];
        }
        return 0;
    }

    /**
     * 插入订单主表数据
     */
    protected function generateOrderData()
    {
        $orderData = [
            'mch_id'                => $this->standardData['mch_id'],
            'user_id'               => $this->standardData['user_id'],
            'cha_id'                => $this->standardData['chanel_id'],
            'order_no'              => $this->orderNo,
            'order_status'          => ORDER_STATUS_WAIT_PAY, //未支付
            'order_prod_status'     => ORDER_NO_PRODUCE,      //待生产
            'order_comf_status'     => ORDER_CONFIRMED,       //已确认
            'order_pay_status'      => ORDER_UNPAY,           //未付款
            'order_shipping_status' =>ORDER_UNSHIPPED,        //未发货
            'order_conf_time'       => time(),
            'order_real_total'      => $this->standardData['total_amount'],
            'order_discount'        => $this->standardData['discount_fee'],
            'order_exp_fee'         => $this->standardData['post_fee'],
            'order_tax_fee'         => $this->getTaxFee(),
            'order_delivery_id'     => $this->standardData['shipping_id'],
            'order_bill_id'         => isset($this->standardData['invoice_info']) ? 1 :0,
            'order_relation_no'     => $this->standardData['outer_order_no'],
            'order_rcv_user'        => $this->standardData['receiver_info']['consignee'],
            'order_rcv_phone'       => $this->standardData['receiver_info']['ship_mobile'],
            'order_rcv_province'    => $this->standardData['receiver_info']['province_code'],
            'order_rcv_city'        => $this->standardData['receiver_info']['city_code'],
            'order_rcv_area'        => $this->standardData['receiver_info']['district_code'],
            'order_rcv_address'     => $this->standardData['receiver_info']['ship_addr'],
            'order_pay_id'          => $this->standardData['pay_info']['pay_id'],
            'order_remark_user'     => isset($this->standardData['note']) ? $this->standardData['note'] : '',
            'created_at'            =>time(),
        ];

        try {
            $orderObj = $this->repoOrder->insert($orderData);

        } catch (\Exception $e) {
            //$this->easyThrowException('70026',__FILE__.__LINE__); //插入主数据出错
            app(\App\Services\Exception::class)->throwException('70026',__FILE__.__LINE__,NULL,true,'dev',$orderData);
        }


       $order_id = $orderObj->order_id;
       $this->standardData['order_id'] = $order_id;
       $this->generateOrderDetailData($order_id);
       $this->createLog($order_id);

       return $order_id;
    }

    /**
     * 构造订单子表数据
     * @param $order_id
     * @return mixed
     */
    protected function generateOrderDetailData($order_id)
    {
        $count = count($this->standardData['items']);
        $key = 1;
        foreach ($this->standardData['items'] as $k=>$v) {
            $itemData = [
                'mch_id'            => $this->standardData['mch_id'],
                'ord_id'            => $order_id,
                'order_no'          => $this->orderNo,
                'prod_id'           => $v['goods_id'],
                'prj_type'          => $v['file_type'],
                'prj_id'            => $v['works_id'],
                'sku_id'            => $v['product_id'],
                'ord_prj_item_no'   => $this->orderNo.'-'.$count.'-'.$key,
                'prod_num'          => $v['buy_num'],
                'prod_pages'        => $v['page_num'],
                'prod_sale_price'   => $v['goods_price'],
                'prod_cost'         => $v['cost_price'],
                'coupon_id'         => isset($v['coupon_id']) ? $v['coupon_id']:0,
                'delivery_id'       =>$this->standardData['shipping_id'],
                'sp_id'             => $v['sp_id'],
                'created_at'        => time()
            ];

            try {
                $itemObj = $this->repoOrderProd->insert($itemData);
                //更新商品销量
                $this->repoGoods->increment(['prod_id'=>$v['goods_id']],'prod_sale_num',$v['buy_num']);
            } catch (\Exception $e) {
                app(\App\Services\Exception::class)->throwException('70027',__FILE__.__LINE__,NULL,true,'dev',$itemData);
            }

            $key++;
            $this->standardData['items'][$k]['project_sn'] = $itemData['ord_prj_item_no'];
            $this->standardData['items'][$k]['order_item_id'] = $itemObj->ord_prod_id;
        }

        return true;
    }

    /**
     * 订单创建日志
     * @param $order_id 订单id
     * @param $type  1创建  2 余额支付
     */
    protected function createLog($order_id, $type = 1)
    {
        $userType = $this->standardData['buyer_type'];
        $typeName = $userType == CHANEL_TERMINAL_AGENT ? __('order.order_log_agent'):__('order.order_log_user');
        $userName = $userType == CHANEL_TERMINAL_AGENT?$this->userInfo['agent_name']:$this->userInfo['user_name'];

        if ($type == ORDER_LOG_CREATE) {
            $action = __('order.create_order');
            $note   = $typeName.':'.$userName.','.__('order.create_order_success');
        }else {
            $action = __('order.pay_success');
            $note   = __('order.balance_pay_succ_note');
        }

        //日志操作
        $logData = [
            'ord_id'        => $order_id,
            'user_type'     => $this->standardData['buyer_type'],
            'user_id'       => $this->standardData['user_id'],
            'operater'      => OPERATE_TYPE_USER,
            'operater'      => $userName,
            'platform'      => '',
            'action'        => $action,
            'note'        => $note,
            'created_at'    => time()
        ];
        app(AfterCreate::class)->recordOrderLog($logData);

    }

    /**
     * 添加作品队列处理信息
     */
    protected function generateWorksQueue()
    {
        foreach ($this->standardData['items'] as $k=>$v) {
            if (!empty($v['works_id'])) {
                //合成队列
                if ($v['file_type'] == WORKS_FILE_TYPE_DIY) {
                    $index = $v['works_id'].'-'.$v['file_type'];
                    $ManuscriptInfo = $this->worksInfo[$index];
                    $type = 1; //判断是否为商业印刷
                    if(!empty($ManuscriptInfo['coml_works_id'])){
                        $type = 2;
                    }
                    $queueData = [
                        'mch_id'                => $this->standardData['mch_id'],
                        'works_id'              => $v['works_id'],
                        'order_no'              => $this->orderNo,
                        'order_prod_id'         => $v['order_item_id'],
                        'comp_queue_serv_id'    => app(Queue::class)->dispatch(),
                        'project_sn'            => $v['project_sn'],
                        'comp_queue_status'     => 'ready',
                        'created_at'            => time(),
                        'type'                  => $type
                    ];

                    app(AfterCreate::class)->createCompoundQueue($queueData);
                } elseif ($v['file_type'] == WORKS_FILE_TYPE_UPLOAD) {
                    //下载队列
                    $index = $v['works_id'].'-'.$v['file_type'];
                    $ManuscriptInfo = $this->worksInfo[$index];

                    $arrFilePath = explode('||', $ManuscriptInfo['script_url']);

                    if(count($arrFilePath) > 1) {//地址多个被认定为是pdf形式
                        $type = WORKS_MANUSCRIPT_TYPE_PDF;
                    } else {  //其他暂时都被认定为zip打包的形式
                        $type = WORKS_MANUSCRIPT_TYPE_ZIP;
                    }

                    foreach ($arrFilePath as $kk=>$vv) { //最多支持  封面||内页||封底的情况
                        if ($kk == 1) {
                            $pageType = GOODS_SIZE_TYPE_INNER;
                        } else if($kk == 0) {
                            $pageType = GOODS_SIZE_TYPE_COVER;
                        } else {
                            $pageType = GOODS_SIZE_TYPE_BACK;
                        }
                        //只有一个稿件则标识为整体的
                        if (count($arrFilePath) == 1) {
                            $pageType = ZERO; //0代表不分封面内页
                        }
                        $queueData = [
                            'mch_id'            => $this->standardData['mch_id'],
                            'manuscript_id'     => $v['works_id'],
                            'down_serv_id'      => app(Queue::class)->dispatch(),
                            'order_no'          => $this->orderNo,
                            'order_prod_id'     => $v['order_item_id'],
                            'down_file_type'    => $type,  //稿件类型
                            'down_page_type'    => $pageType, //页面类型 封面 内页 封底
                            'down_url'          => $vv,
                            'down_status'       => 'ready',
                            'created_at'        => time()
                        ];
                        app(AfterCreate::class)->createDownloadQueue($queueData);
                    }

                }
            }
        }
    }

    /**
     * 构建提交生产队列,印品初始状态为prepare,不能
     * 推送到供货商，需要在特定时候将状态更新到ready
     */
    protected function generateProduceQueue()
    {
        $orderId = $this->standardData['order_id'];

        //印品需要合成或下载，不能直接ready,实物可以直接ready
        $arrWorksId = array_unique(array_column($this->standardData['items'], 'works_id'));

        //只有一个works_id并且works_id是0表示这个订单只有实物
        if(count($arrWorksId) == 1 && $arrWorksId[0] == 0) {
            $queueStatus = 'ready'; //可以直接加入到待处理的状态
        } else {
            $queueStatus = 'prepare';
        }
        $queueData = [
            'mch_id'                => $this->standardData['mch_id'],
            'order_id'              => $orderId,
            'produce_queue_type'    => ORDER_PRODUCE_TYPE_AUTO,
            'produce_queue_status'  => $queueStatus,
            'created_at'            => time()
        ];
        app(AfterCreate::class)->createProduceQueue($queueData);
    }

    /**
     * 简易异常抛出
     * @param $code
     * $params $pos 异常位置
     */
    private function easyThrowException($code, $pos) {
        app(\App\Services\Exception::class)->throwException($code,$pos);
    }

    /**
     * 格式化订单标准数据
     * @param $data
     * @return mixed
     */
    abstract public function setOrdersData($data);

    /**
     * 额外需要添加的流程
     * @return mixed
     */
    abstract public function extraProcess();
}