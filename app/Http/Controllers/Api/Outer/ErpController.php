<?php
namespace App\Http\Controllers\Api\Outer;

use App\Exceptions\CommonException;
use App\Http\Controllers\Api\BaseController;
use App\Models\DmsAgentInfo;
use App\Models\SaasAreas;
use App\Models\SaasDelivery;
use App\Models\SaasDownloadQueue;
use App\Models\SaasExpress;
use App\Models\SaasNewSuppliersOrders;
use App\Models\SaasOrderErpPushQueue;
use App\Models\SaasOrderProduceQueue;
use App\Models\SaasOrderProducts;
use App\Models\SaasOrders;
use App\Models\SaasOuterErpOrderCreate;
use App\Models\SaasOuterErpOrderCreateQueue;
use App\Models\SaasProducts;
use App\Models\SaasProductsSku;
use App\Models\SaasSuppliers;
use App\Repositories\AreasRepository;
use App\Repositories\DmsAgentInfoRepository;
use App\Repositories\SaasAreasRepository;
use App\Repositories\SaasDeliveryRepository;
use App\Repositories\SaasExpressRepository;
use App\Repositories\SaasOrdersRepository;
use App\Repositories\SaasProductsRepository;
use App\Repositories\SaasSalesChanelRepository;
use App\Services\Helper;
use App\Services\Orders\OrdersEntity;
use App\Services\Orders\Production;
use App\Services\Orders\Status;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\EventListener\ValidateRequestListener;
use App\Repositories\SaasNewSuppliersOrderRepository;

/**
 * Erp请求创建订单相关接口
 *
 * @author: hlt
 * @version: 1.0
 * @date: 2020/06/18
 */
class ErpController extends BaseController
{
    /**
     * @param Request $request
     * @param OrdersEntity $entity
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCreateOrderInfo(Request $request, OrdersEntity $entity,SaasOrders $orderModel,
                                DmsAgentInfo $agentInfoModel,SaasSuppliers $saasSuppliersModel,SaasProductsSku $skuModel,
                                SaasAreasRepository $saasAreasRepository,SaasAreas $saasAreasModel,SaasExpress $expressModel,
                                SaasDelivery $deliveryModel,AreasRepository $areasRepository,SaasOuterErpOrderCreate $outerOrderCreateModel,
                                SaasOuterErpOrderCreateQueue $queueModel,SaasExpressRepository $expressRepository,SaasDeliveryRepository $deliveryRepository

    )
    {
        try
        {
            try {
                $param = $request->all();
                $data = $param;
                //参数检查
                $this->checkData($data);

                $itemInfo = json_decode($data['items'],true);

                $orderNo = $this->parseErpOrderItemNo($itemInfo['serial_number']);
                //判断该订单号在系统是否存在
                $isOldOrder = $orderModel->where('order_no',$orderNo)->exists();
                //获取所属分销id跟所属商户id
                $agentInfo = $agentInfoModel->where('erp_name',$data['partner_short_name'])->get()->toArray();
                if (empty($agentInfo)){
                    Helper::EasyThrowException(230015,__FILE__.__LINE__);//找不到对应分销,
                }
                $agentId = $agentInfo[0]['agent_info_id'];
                $mchId = $agentInfo[0]['mch_id'];
                //获取供货商id
                $supplierInfo = $saasSuppliersModel->where('sup_short_name',$data['supplier_code'])->get()->toArray();
                if (empty($supplierInfo)){
                    Helper::EasyThrowException(230016,__FILE__.__LINE__);//找不到对应供货商,
                }
                $supplierId = $supplierInfo[0]['sup_id'];
                //获取商品sku_id
                $skuInfo = $skuModel->where('prod_supplier_sn',$itemInfo['goods_name'])->get()->toArray();
                if (empty($skuInfo))
                {
                    Helper::EasyThrowException(230017,__FILE__.__LINE__);//找不到对应sku
                }
                $skuId = $skuInfo[0]['prod_sku_id'];
                $goodId = $skuInfo[0]['prod_id'];
                if (!$isOldOrder)
                {
                    //判断是否为老系统的订单
                    $isExist = \DB::connection('ishop_mysql')->table('is_order')->where(['order_no' => $orderNo])->exists();
                    if ($isExist){
                        \DB::beginTransaction();
                        //组织数据
                        //为老系统订单，走另一套逻辑
                        $data['agent_id'] = $agentId;
                        $data['mch_id']   = $mchId;
                        $data['sp_id']    = $supplierId;
                        $data['sku_id']   = $skuId;
                        $data['goods_id'] = $goodId;
                        app(SaasNewSuppliersOrderRepository::class)->createSpOrder($data);
                        \DB::commit();
                        return ['code' => 1,'msg'=>'ok'];
                    }

                    $isNewOrder = 1;//是否为新订单
                    //新订单
                    if (!isset($data['receiver_info'])){
                        //创建新订单收货地址不能为空
                        Helper::EasyThrowException(230008,__FILE__.__LINE__);//收货信息不能为空
                    }
                    if (empty($data['receiver_info'])){
                        //创建新订单收货地址不能为空
                        Helper::EasyThrowException(230008,__FILE__.__LINE__);//收货信息不能为空
                    }
                    //收货信息验证
                    $receiverInfo = json_decode($data['receiver_info'],true);
                    if (empty($receiverInfo['consignee'])){
                        Helper::EasyThrowException(230009,__FILE__.__LINE__);//收货人信息不能为空
                    }
                    if (empty($receiverInfo['ship_mobile'])){
                        Helper::EasyThrowException(230010,__FILE__.__LINE__);//收货人电话不能为空
                    }
                    if (empty($receiverInfo['ship_addr'])){
                        Helper::EasyThrowException(230011,__FILE__.__LINE__);//详细地址不能为空
                    }

                    if (empty($receiverInfo['province']) && empty($receiverInfo['city']) && empty($receiverInfo['district']))
                    {
                        //解析详细地址
                        $addressArray = $areasRepository->parseDetailAddress($receiverInfo['ship_addr']);
                        if (!$addressArray)
                        {
                            Helper::EasyThrowException(230018,__FILE__.__LINE__);//地址解析出错
                        }
                    }else{
                        //直接取传过来的收货地址
                        $addressArray['p'] = $receiverInfo['province'];
                        $addressArray['c'] = $receiverInfo['city'];
                        $addressArray['a'] = $receiverInfo['district'];
                        $addressArray['d'] = $receiverInfo['ship_addr'];
                    }
                    //获取省市区code
                    $provCode = $saasAreasRepository->provinceNameToCode($addressArray['p']);
                    $cityCode = $saasAreasRepository->cityNameToCode($addressArray['c']);
                    $districtCode = $saasAreasRepository->districtNameToCode($addressArray['a']);
                    $shipAddr = $addressArray['d'];
                    //解析文件信息
                    if (!is_array($itemInfo['file_url'])){
                        //不为数组，转化为数组再验证
                        $fileInfo = json_decode($itemInfo['file_url'],true);
                    }else{
                        $fileInfo = $itemInfo['file_url'];
                    }
                    if (empty($fileInfo['inner']) && empty($fileInfo['combine'])){
                        Helper::EasyThrowException(230019,__FILE__.__LINE__); //内页不能为空
                    }
                    //组织创建订单文件url
                    if (!empty($fileInfo['inner'])) {
                        if (empty($fileInfo['cover'])){
                            //只存在一个内页的情况
                            $fileUrl = $fileInfo['inner'];
                        }else{
                            $fileUrl = $fileInfo['cover'].'||'.$fileInfo['inner'];
                        }
                    }else{
                        if (empty($fileInfo['cover'])){
                            //只存在一个内页的情况
                            $fileUrl = $fileInfo['combine'];
                        }else{
                            $fileUrl = $fileInfo['cover'].'||'.$fileInfo['combine'];
                        }
                    }
                  /*  if (empty($data['delivery_code'])){
                        Helper::EasyThrowException(230012,__FILE__.__LINE__);//快递方式不能为空
                    }*/
                    if (isset($data['delivery_code']) && !empty($data['delivery_code']))
                    {
                        //获取快递id
                        $res = $expressRepository->codeGetDeliveryId($data['delivery_code'],$mchId,$goodId);
                        if ($res['code']!=1){
                            Helper::EasyThrowException(230022,__FILE__.__LINE__);//商品对应快递出错
                        }else{
                            $expressId = $res['delivery_id'];
                        }
                    }else{
                        //根据商品获取对应的默认快递方式
                        $res = $deliveryRepository->getDefaultDeliveryId($goodId);
                        if ($res['code']!=1){
                            $expressId = 0;
                        }else{
                            $expressId = $res['delivery_id'];
                        }
                    }
                    //插入外部订单创建表
                    $outerCreateData = [
                        'outer_order_info' => json_encode($data),
                        'outer_order_no'   => $itemInfo['serial_number'],
                        'erp_order_no'     => $data['order_no']??"",
                        'mch_id'           => $mchId,
                        'agent_id'         => $agentId,
                        'sp_id'            => $supplierId,
                        'goods_id'         => $goodId,
                        'sku_id'           => $skuId,
                        'goods_num'        => $itemInfo['goods_number'],
                        'consignee'        => $receiverInfo['consignee'],
                        'ship_mobile'      => $receiverInfo['ship_mobile'],
                        'province'         => $provCode,
                        'city'             => $cityCode,
                        'district'         => $districtCode,
                        'ship_addr'        => $shipAddr,
                        'ship_zip'         => $receiverInfo['ship_zip']??"",
                        'file_url'         => $fileUrl,
                        'express_id'       => $expressId??"",
                        'created_at'       => time(),
                    ];
                }else{
                    $isNewOrder = 0;
                    //系统已有订单
                    $outerCreateData = [
                        'outer_order_info' => json_encode($data),
                        'outer_order_no'   => $itemInfo['serial_number'],
                        'erp_order_no'     => $data['order_no']??"",
                        'is_new_order'     => $isNewOrder,
                        'mch_id'           => $mchId,
                        'agent_id'         => $agentId,
                        'sp_id'            => $supplierId,
                        'goods_id'         => $goodId,
                        'sku_id'           => $skuId,
                        'created_at'       => time()
                    ];
                }
                \DB::beginTransaction();
                //判断该订单流水号在外协订单创建表是否已经生成
                $oExist = $outerOrderCreateModel->where('outer_order_no',$outerCreateData['outer_order_no'])->exists();
                if (!$oExist){
                    $createId = $outerOrderCreateModel->insertGetId($outerCreateData);

                    if ($createId){
                        //插入生成订单队列
                        $queueData = [
                            'order_create_id' => $createId,
                            'outer_order_no'  => $itemInfo['serial_number'],
                            'is_new_order'    => $isNewOrder,
                            'created_at'      => time(),
                        ];
                        $queueRes = $queueModel->insert($queueData);
                        if (!$queueRes){
                            //插入失败 回滚
                            \DB::rollBack();
                            Helper::EasyThrowException(230021,__FILE__.__LINE__); //插入失败
                        }
                    }else{
                        //插入失败 回滚
                        \DB::rollBack();
                        Helper::EasyThrowException(230021,__FILE__.__LINE__); //插入失败
                    }
                    \DB::commit();
                }else{
                    $outerCreateData['updated_at'] = time();
                    //已存在，只需更新订单创建表
                    $outerOrderCreateModel->where('outer_order_no',$outerCreateData['outer_order_no'])->update($outerCreateData);
                    \DB::commit();
                }
                return ['code' => 1,'msg'=>'ok'];

            } catch (\Exception $exception){
                \DB::rollBack();
                throw new CommonException($exception->getMessage(),$exception->getCode(),false,'all',[__FILE__.__LINE__.$exception->getMessage()]);
            }
        }catch (CommonException $e) {
            if ($e->getCode()!=0){
                file_put_contents('/tmp/outer_erp_order_create.log',$e->getMessage(),FILE_APPEND);
                //验证出错
                return ['code' => 2,'msg'=>$e->getMessage()];
            }else{
                file_put_contents('/tmp/outer_erp_order_create.log',$e->getMessage(),FILE_APPEND);
                //逻辑出错
                return ['code' => 2,'msg'=>API_ERROR];
            }


        }
    }


    //订单必传参数检查
    public function checkData($data)
    {
        file_put_contents('/tmp/outer_erp_order_create.log',var_export($data,true),FILE_APPEND);
        //获取签名
        $sign = $data['sign'];
        if (empty($data['sign'])){
            Helper::EasyThrowException(230001,__FILE__.__LINE__);//签名验证失败
        }
        unset($data['sign']);
        //验证签名
        $trueSign = $this->getSign($data);

        file_put_contents('/tmp/sign.log',$trueSign,FILE_APPEND);
        if ($trueSign != $sign) {
            Helper::EasyThrowException(230001,__FILE__.__LINE__);//签名验证失败
        }
        //验证参数
        if (!isset($data['timestamp'])||empty($data['timestamp'])|| !is_numeric($data['timestamp'])){
            Helper::EasyThrowException(230002,__FILE__.__LINE__);//当前时间戳验证失败
        }
        if (!isset($data['items'])||empty($data['items'])){
            Helper::EasyThrowException(230003,__FILE__.__LINE__);//订单详情信息
        }
        $itemInfo = json_decode($data['items'],true);
        if (!isset($itemInfo['serial_number'])||empty($itemInfo['serial_number'])){
            Helper::EasyThrowException(230004,__FILE__.__LINE__);//订单流水号
        }
        if (!isset($itemInfo['goods_name'])||empty($itemInfo['goods_name'])){
            Helper::EasyThrowException(230005,__FILE__.__LINE__);//工厂码
        }
        if (!isset($itemInfo['file_url']) || empty($itemInfo['file_url'])){
            Helper::EasyThrowException(230007,__FILE__.__LINE__);//印刷文件url不能为空
        }
        if (!is_array($itemInfo['file_url'])){
            //不为数组，转化为数组再验证
            $itemInfo['file_url'] = json_decode($itemInfo['file_url'],true);
        }
        if ((isset($itemInfo['file_url']['cover']) && empty($itemInfo['file_url']['cover'])) && (isset($itemInfo['file_url']['inner']) && empty($itemInfo['file_url']['inner'])) && (isset($itemInfo['file_url']['combine']) && empty($itemInfo['file_url']['combine']))){
            Helper::EasyThrowException(230007,__FILE__.__LINE__);//印刷文件url不能为空
        }

        if (empty($data['partner_short_name'])){
            Helper::EasyThrowException(230013,__FILE__.__LINE__);//客户简称不能为空
        }
        if (empty($data['supplier_code'])){
            Helper::EasyThrowException(230014,__FILE__.__LINE__);//供货商代码不能为空
        }
        return true;
    }

    /**
     * @param $params
     * @param string $secretKey
     * @return string
     */
    protected function getSign($params, $secretKey = API_SIGN_KEY)
    {
        ksort($params);

        $ret_str = '';
        foreach ($params as $k=>$v) {
            $ret_str .= $k.'='.$v."&";
        }
        $ret_str = $ret_str.'key='.$secretKey;
        return strtoupper(md5($ret_str));
    }

    //模拟数据
    public function getDemoData(){
        $data=[
            'timestamp' => time()
        ];
        $file_url = [
            'cover' => 'http://47.92.90.159:1010/6/2020-06-22/测试商品02/409200622142145567/409200622142145567-1-1/商务测试-供应商02-[409200622142145567]{409200622142145567_1_1_1}-1X1w_cover.pdf',
            'inner' => 'http://47.92.90.159:1010/6/2020-06-22/测试商品02/409200622142145567/409200622142145567-1-1/商务测试-供应商02-[409200622142145567]{409200622142145567_1_1_1}-1X1w.pdf',
            'combine' => false,
        ];
        $file_url = json_encode($file_url);
        $item = [
            'serial_number' => "40920062214245567_1_1_1",
            'goods_name'    => "供应商02",
            'goods_number'  => '1',
            'file_url'      => $file_url
        ];
        $json_item = json_encode($item);

        $data['items'] = $json_item;
        $receiver_info = [
            'consignee'   => "张三",
            'ship_mobile' => "13265961649",
            'province'    => "广东省",
            'city'        => "广州市",
            'district'    => "天河区",
            'ship_addr'   => '天盈创意园D1033',
            'ship_zip'    => '000000',
        ];
        $receiver_info_json = json_encode($receiver_info);
        $data['receiver_info'] = $receiver_info_json;
        $data['delivery_code'] = "yto";
        $data['partner_short_name'] = "商务测试";
        $data['supplier_code'] = "米软科技";
        $data['sign'] = $this->getSign($data);
        return $data;
    }


    //解析外协传过来的订单流水号获取订单号
    public function parseErpOrderItemNo($itemNo)
    {
        $em_arr = ['_','-'];
        foreach ($em_arr as $ke=>$ve){
            if (strstr( $itemNo ,$ve ) !== false){
                $oArray = explode($ve,$itemNo);
                //获取订单号
                $orderNo = $oArray[0];
                return $orderNo;
            }
        }
    }
    //解析外协传过来的订单流水号获取订单项目号
    public function parseErpOrderToItemNo($itemNo)
    {
        $em_arr = ['_','-'];
        foreach ($em_arr as $ke=>$ve){
            if (strstr( $itemNo ,$ve ) !== false){
                $oArray = explode($ve,$itemNo);
                if (count($oArray) != 3){
                    array_pop($oArray);
                }
                //获取订单项目号
                $orderItemNo = implode('-',$oArray);
                return $orderItemNo;
            }
        }
    }

    //搬运之前外协传过来的订单号至新供货商订单表
    public function moveOrderNo()
    {
        $createModel = app(SaasOuterErpOrderCreate::class);
        $queueModel = app(SaasOuterErpOrderCreateQueue::class);
        $newOrderModel = app(SaasNewSuppliersOrders::class);
        $createInfo = $createModel->select('outer_order_id','outer_order_info','sp_id')->get()->toArray();
        foreach ($createInfo as $k => $v){
            $item = json_decode($v['outer_order_info'],true);
            if (isset($item['order_no'])){
                //获取该条记录的新订单号
                $new_order_no = $queueModel->where(['order_create_id' => $v['outer_order_id'],'status'=>'finish'])->value('new_order_no');
                //将erp订单号放进新供货商订单表
                $newOrderModel->where(['order_no' => $new_order_no,'sp_id'=>$v['sp_id']])->update(['erp_order_no' => $item['order_no']]);
            }
       }

    }

}