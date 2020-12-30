<?php
namespace App\Services\Orders;

use App\Models\SaasCategory;
use App\Models\SaasOrderErpPushQueue;
use App\Models\SaasProducts;
use App\Repositories\SaasCompoundQueueRepository;
use App\Repositories\SaasDownloadQueueRepository;
use App\Repositories\SaasNewSpDownloadQueueRepository;
use App\Repositories\SaasNewSuppliersOrderRepository;
use App\Repositories\SaasOrderProduceQueueRepository;
use App\Repositories\SaasOrderProductsRepository;
use App\Repositories\SaasOrderPushQueueRepository;
use App\Repositories\SaasOrdersRepository;
use App\Repositories\SaasProductsRepository;
use App\Repositories\SaasProductsSkuRepository;
use App\Repositories\SaasSpDownloadQueueRepository;
use App\Repositories\SaasSuppliersOrderProductRepository;
use App\Repositories\SaasSuppliersOrderRepository;
use App\Repositories\ScmBatchPrintRepository;
use App\Services\Factory;
use App\Services\Helper;

/**
 * 订单提交生产逻辑
 * @author: cjx
 * @version: 1.0
 * @date: 2020/04/07
 */

class Production
{
    protected $orderRepository;
    protected $ordProdQueueRepository;
    protected $compoundQueueRepository;
    protected $downloadQueueRepository;
    protected $supOrderRepository;
    protected $ordPushQueueRepository;
    protected $supOrdProdRepository;
    protected $productRepository;
    protected $skuRepository;
    protected $spDownloadQueueRepository;

    public function __construct(SaasOrdersRepository $order,SaasOrderProduceQueueRepository $orderProduceQueueRepository,
                                SaasCompoundQueueRepository $compoundQueueRepository,SaasDownloadQueueRepository $downloadQueueRepository,
                                SaasOrderProductsRepository $orderProductsRepository,SaasSuppliersOrderRepository $suppliersOrderRepository,
                                SaasOrderPushQueueRepository $orderPushQueueRepository,SaasSuppliersOrderProductRepository $orderProductRepository,
                                SaasProductsRepository $productsRepository,SaasProductsSkuRepository $productsSkuRepository,
                                SaasSpDownloadQueueRepository $spDownloadQueueRepository,SaasOrderErpPushQueue $erpPushQueue,SaasCategory $category,
                                SaasProducts $products,ScmBatchPrintRepository $batchPrintRepository,SaasNewSuppliersOrderRepository $newSuppliersOrderRepository,
                                SaasNewSpDownloadQueueRepository $newSpDownloadQueueRepository)
    {
        $this->orderRepository = $order;
        $this->ordProdQueueRepository = $orderProduceQueueRepository;
        $this->compoundQueueRepository = $compoundQueueRepository;
        $this->downloadQueueRepository = $downloadQueueRepository;
        $this->ordProdRepository = $orderProductsRepository;
        $this->supOrderRepository = $suppliersOrderRepository;
        $this->ordPushQueueRepository = $orderPushQueueRepository;
        $this->supOrdProdRepository = $orderProductRepository;
        $this->productRepository = $productsRepository;
        $this->skuRepository = $productsSkuRepository;
        $this->spDownloadQueueRepository = $spDownloadQueueRepository;
        $this->batchPrintRepository = $batchPrintRepository;
        $this->newSpOrdRepository = $newSuppliersOrderRepository;
        $this->newSpDownloadQueueRepository = $newSpDownloadQueueRepository;

        $this->erpPushQueueModel = $erpPushQueue;
        $this->categoryModel = $category;
        $this->productModel = $products;

    }

    /**
     * 检查订单状态
     * @param null $order_id 订单id
     */
    public function checkOrder($order_id)
    {
        $order_info = $this->orderRepository->getById($order_id);

        if(empty($order_info)) {
            //订单记录不存在
            Helper::EasyThrowException(70030,__FILE__.__LINE__);
        }

        //生产队列信息
        $produce_queue_info = $this->ordProdQueueRepository->getRow(['order_id'=>$order_id]);

        if($produce_queue_info['produce_queue_type'] == ORDER_PRODUCE_TYPE_AUTO){
            //自动提交生产的队列，需检查订单状态
            if($order_info['order_status'] != ORDER_STATUS_WAIT_PRODUCE || $order_info['order_prod_status'] != ORDER_NO_PRODUCE || $order_info['order_comf_status'] != ORDER_CONFIRMED || $order_info['order_pay_status'] != ORDER_PAYED){
                //非已支付，已确认，未生产状态下，不允许提交生产
                //订单状态未满足提交生产条件
                Helper::EasyThrowException(70070,__FILE__.__LINE__);
            }
        }else{
            //手动提交情况
            if(!in_array($order_info['order_status'],[ORDER_STATUS_WAIT_PRODUCE,ORDER_STATUS_WAIT_DELIVERY])){
                //订单总状态非待生产、已付款或者待发货、已生产下，不允许提交生产
                //订单状态未满足提交生产条件
                Helper::EasyThrowException(70070,__FILE__.__LINE__);
            }
        }

        //检查作品合成状态
        $compound_where = [
            ['order_no','=',$order_info['order_no']],
            ['comp_queue_status','!=','finish'],
        ];
        $download_where = [
            ['order_no','=',$order_info['order_no']],
            ['down_status','!=','finish'],
        ];
        $compound_info = $this->compoundQueueRepository->getRow($compound_where);
        $download_info = $this->downloadQueueRepository->getRow($download_where);
        if(!empty($compound_info) || !empty($download_info)){
            //作品尚未合成,请稍后再试
            Helper::EasyThrowException(70072,__FILE__.__LINE__);
        }
    }

    /**
     * 提交生产处理
     * @param null $order_id 订单id
     */
    public function submit($order_id)
    {
        //订单信息
        $order_info = $this->orderRepository->getById($order_id);

        //检查订单状态
        $this->checkOrder($order_id);

        //提交处理(现只插入旧表数据)
        $this->analysisInfo($order_info);

        //添加到Erp推送队列
        $this->addErpQueue($order_info);

        //改变订单状态(已确认，已支付，生产中，总状态:待发货、已生产)
        app(Status::class)->updateToProducing($order_id);

        return true;
    }

    /**
     * 解析订单详情信息
     * @param string $order_info
     */
    public function analysisInfo($order_info)
    {
        //订单详情信息
        $ord_prod_info = $this->ordProdRepository->getList(['ord_id'=>$order_info['order_id']],'created_at');

        //供货商订单详情表
        foreach ($ord_prod_info as $k=>$v){
            $exist_sp_order_prod = $this->supOrdProdRepository->getList(['ord_prod_id'=>$v['ord_prod_id']],'created_at')->toArray();

            //防止重复提交
            if(!empty($exist_sp_order_prod)){
                continue;
            }

            //添加到供货商下载队列
            $this->addSupplierDownloadQueue($v,true);

            //作品合成信息
            $compound_info = $this->compoundQueueRepository->getRow(['order_prod_id'=>$v['ord_prod_id']]);

            //商品信息
            $product_info = $this->productRepository->getById($v['prod_id']);

            $sp_order_product_data = [
                'ord_prod_id'   =>  $v['ord_prod_id'],
                'ord_id'        =>  $v['ord_id'],
                'prj_id'        =>  $v['prj_id'],
                'sku_id'        =>  $v['sku_id'],
                'prod_id'       =>  $v['prod_id'],
                'cate_id'       =>  $product_info['prod_cate_uid'],
                'sp_file_url'   =>  $compound_info['comp_queue_file_info'],
                'sp_nums'       =>  $v['prod_num'],
                'supplier_id'   =>  $v['sp_id'],
                'pages'         =>  $v['prod_pages'],
                'prod_price'    =>  $v['prod_cost'],
                'prj_type'      =>  $v['prj_type'],
                'created_at'    =>  time(),
            ];
            $this->supOrdProdRepository->insert($sp_order_product_data);

        }

        //供货商订单表
        foreach ($ord_prod_info as $kk=>$vv){
            //同一个供货商同一订单不能重复提交生产
            $exist_sp_order = $this->supOrderRepository->getRow(['ord_id'=>$vv['ord_id'],'supplier_id'=>$vv['sp_id']]);

            if(!empty($exist_sp_order)){
                //供货商订单表已有该条记录,则不添加订单，只更新商品价格
                $this->supOrderRepository->update(['ord_id'=>$vv['ord_id'],'supplier_id'=>$vv['sp_id']],['sp_goods_amount'=>($exist_sp_order['sp_goods_amount'] + $vv['prod_cost'])]);
                continue;
            }

            //提交到推送工厂队列
//            $push_queue_id = $this->submitPushQueue($order_info,$vv['sp_id']);

            //配送金额，暂定为0
            $distribution_amount = $this->getDistributionnAmount();

            $sp_order_data = [
                'mch_id'                =>  $vv['mch_id'],
                'ord_id'                =>  $vv['ord_id'],
                'order_no'              =>  $order_info['order_no'],
                'supplier_id'           =>  $vv['sp_id'],
                'sp_goods_amount'       =>  $vv['prod_cost'], //商品金额
                'sp_order_amount'       =>  $vv['prod_cost'] + $distribution_amount, //订单金额
                'sp_freight_amount'     =>  $distribution_amount, //配送金额
                'express_id'            =>  $vv['delivery_id'], //配送方式ID
//                'sp_push_queue_id'      =>  $push_queue_id, //订单推送工厂队列ID
                'sp_ord_rcv_user'       =>  $order_info['order_rcv_user'],
                'sp_ord_rcv_phone'      =>  $order_info['order_rcv_phone'],
                'sp_ord_rcv_country'    =>  $order_info['order_rcv_country'],
                'sp_ord_rcv_province'   =>  $order_info['order_rcv_province'],
                'sp_ord_rcv_city'       =>  $order_info['order_rcv_city'],
                'sp_ord_rcv_area'       =>  $order_info['order_rcv_area'],
                'sp_ord_rcv_address'    =>  $order_info['order_rcv_address'],
                'sp_ord_rcv_zipcode'    =>  $order_info['order_rcv_zipcode'],
                'transaction_time'      =>  strtotime($order_info['created_at']),
                'created_at'            =>  time(),

            ];
            $sp_ord_id = $this->supOrderRepository->insertGetId($sp_order_data);

            //关联供货商订单详情sp_ord_id
            $this->supOrdProdRepository->update(['ord_id'=>$vv['ord_id'],'supplier_id'=>$vv['sp_id']],['sp_ord_id'=>$sp_ord_id]);
        }

//        $erp_queue = $this->erpPushQueueModel->where('order_id',$order_info['order_id'])->first();
//        if(empty($erp_queue)){
//            //插入saas_order_erp_push_queue表
//            $erp_push_data = [
//                'mch_id'                =>  $order_info['mch_id'],
//                'order_id'              =>  $order_info['order_id'],
//                'order_push_status'     =>  'ready',
//                'created_at'            =>  time(),
//            ];
//            $this->erpPushQueueModel->insert($erp_push_data);
//        }
//
//        //若是实物订单(订单仅有一实物商品)则插入供货商批量打单表
//        $this->addBatchPrint($order_info);
//
//        //改变订单状态(已确认，已支付，生产中，总状态:待发货、已生产)
//        app(Status::class)->updateToProducing($order_info['order_id']);
    }

    /**
     *  更新生产队列状态
     * @param string $order_id  订单ID $queue_type 提交方式(手动、自动)
     */
    public function updateProduceQueue($order_id,$queue_type,$queue_status)
    {
        $queueData = [
            'produce_queue_type'    => $queue_type,
            'produce_queue_status'  => $queue_status,
            'start_time'            => time(),
            'end_time'              => time(),
            'updated_at'            => time()
        ];
        $this->ordProdQueueRepository->update(['order_id'=>$order_id],$queueData);
    }

    /**
     *  提交到推送工厂队列
     * @param string $order_info 订单信息
     */
    public function submitPushQueue($order_info,$sp_id)
    {
        $push_queue_data = [
            'mch_id'         =>  $order_info['mch_id'],
            'supplier_id'    =>  $sp_id,
            'order_id'       =>  $order_info['order_id'],
            'created_at'     =>  time(),
        ];
        return $this->ordPushQueueRepository->insertGetId($push_queue_data);
    }

    /**
     *  配送金额
     */
    public function getDistributionnAmount()
    {
        return 0;
    }

    /**
     *  添加到供货商下载队列
     * @param string $ord_prod_info 订单详情信息 $generate null则插入sp_download新表
     */
    public function addSupplierDownloadQueue($ord_prod_info,$generate=null)
    {
        //订单信息
        $order_info = $this->orderRepository->getById($ord_prod_info['ord_id']);

        //货品信息
        $sku_info = $this->skuRepository->getById($ord_prod_info['sku_id']);

        //解析文件地址
        $urls_arr = [];
        if($ord_prod_info['prj_type'] == WORKS_FILE_TYPE_DIY){
            //diy作品
            $compound_queue_info = $this->compoundQueueRepository->getRow(['order_prod_id'=>$ord_prod_info['ord_prod_id']]);
            $urls_arr = $this->getDownUrl(json_decode($compound_queue_info['comp_queue_file_info'],true));
        }else if($ord_prod_info['prj_type'] == WORKS_FILE_TYPE_UPLOAD){
            //稿件
            $download_queue_info = $this->downloadQueueRepository->getList(['mch_id'=>$ord_prod_info['mch_id'],'order_prod_id'=>$ord_prod_info['ord_prod_id']]);
            $ser_ids = [];
            foreach ($download_queue_info as $k=>$v) {
                if(empty($v['down_local_path']) || empty($v['down_local_file_name'])){
                    $urls_arr[] = $v['down_url'];
                }else{
                    $urls_arr[] = $v['down_local_path'].'/'.$v['down_local_file_name'];
                }
                $ser_ids[] = $v['down_serv_id'];
            }
        }else if($ord_prod_info['prj_type'] === WORKS_FILE_TYPE_EMPTY){
            //实物,生成txt文件
            $factoryService = app(Factory::class);

            //获取erp名称
            $erp_name = $factoryService->getErpName($order_info['user_id'],$order_info['mch_id']);

            //组装文件名称
            $info_data = [
                'erp_name'      =>  $erp_name,
                'order_no'      =>  $order_info['order_no'],
                'factory_code'  =>  $sku_info['prod_supplier_sn'],
                'project_sn'    =>  $ord_prod_info['ord_prj_item_no'],
                'quantity'      =>  $ord_prod_info['prod_num'],
                'page_count'    =>  $ord_prod_info['prod_pages'],
            ];
            $get_name = $factoryService->generateFileName($ord_prod_info['prod_id'],$info_data,false,true);

            //实物文件名称
            $entity_name = $get_name.config('order.entity_flag');

            //实物文件路径
            $entity_dir = env('WORKS_ENTITY_DIR').'/'.date('Y-m-d');
            $entity_path = env('WORKS_FILE').$entity_dir;

            // 创建目录
            @mkdir(iconv('utf-8', 'gbk', $entity_path), 0777, true);
            file_put_contents($entity_path.'/'.$entity_name,'shiwu');

            $urls_arr[] = $entity_path.$entity_name;
            $compound_queue_info['comp_queue_serv_id'] = 1;

        }

        $cover_flag = config('order.cover_flag');

        //循环插入下载地址
        foreach ($urls_arr as $key=>$val){

            //判断是否为封面
            if(strstr($val,$cover_flag )) {
                //封面
                $type = GOODS_SIZE_TYPE_COVER;
            } else {
                //内页
                $type = GOODS_SIZE_TYPE_INNER;
            }
            $file_name = substr($val, strripos($val, '/') + 1);
            $path = dirname($val);

            if(strstr($val,config('order.entity_flag'))){
                //实物类文件路径、名称处理
                $path = isset($entity_dir) ? $entity_dir : '';
                $file_name = isset($entity_name) ? env('WORKS_FILE_URL').'/'.$path.'/'.$entity_name : '';
            }

            $sp_download_queue_data = [
                'mch_id'         =>  $ord_prod_info['mch_id'],
                'sp_id'          =>  $ord_prod_info['sp_id'],
                'ord_prod_id'    =>  $ord_prod_info['ord_prod_id'],
                'ord_id'         =>  $ord_prod_info['ord_id'],
                'prod_id'        =>  $ord_prod_info['prod_id'],
                'sku_id'         =>  $ord_prod_info['sku_id'],
                'work_id'        =>  $ord_prod_info['prj_id'],
                'service_id'     =>  empty($compound_queue_info['comp_queue_serv_id']) ? $ser_ids[$k] : $compound_queue_info['comp_queue_serv_id'],
                'order_no'       =>  $order_info['order_no'],
                'material_no'    =>  $sku_info['prod_supplier_sn'],
                'project_sn'     =>  $ord_prod_info['ord_prj_item_no'],
                'filename'       =>  $file_name,
                'filetype'       =>  $type,
                'path'           =>  $path,
                'created_at'     =>  time(),
            ];

            if(empty($generate)){
                //插入新sp_download表
                $this->newSpDownloadQueueRepository->insert($sp_download_queue_data);
            }else{
                //插入旧sp_download表
                $this->spDownloadQueueRepository->insert($sp_download_queue_data);
            }
        }
    }

    /**
     * 通过合成队列里存的文件url解析出下载的地址
     * @param $queue_path 合成队列中的文件路径信息
     * @return array $arr_path;
     */
    public function getDownUrl($queue_path)
    {
        $root_dir = config('order.online_create_root');
        $root_dir_d = config('order.online_create_root_d');

        $arr_path = [];
        foreach ($queue_path as $k=>$v){
            if(empty($v)) {
                continue;
            } else {
                $new_path = str_replace($root_dir, '', $v);
                $new_path = str_replace($root_dir_d, '', $new_path);
                $linux_path = preg_replace('/\\\\{1,}/', '/', $new_path);
                $arr_path[] = $linux_path;
            }
        }
        return $arr_path;
    }

    /**
     *  实物订单(订单仅有一实物商品)添加到供货商批量打单表
     * @param string $order_info 订单信息
     */
    public function addBatchPrint($order_info)
    {
        $ord_prod_info = $this->ordProdRepository->getList(['ord_id'=>$order_info['order_id']])->toArray();
        if(count($ord_prod_info) == 1){
            //订单仅有一商品

            //获取实物货品id
            $res = $this->batchPrintRepository->getSupplierSnList();
            $entity_product_ids = [];
            foreach ($res as $k=>$v){
                $entity_product_ids = array_merge($entity_product_ids,$v['sku_ids']);
            }

            if(in_array($ord_prod_info[0]['sku_id'],$entity_product_ids)){
                //实物商品
                $print_data = [
                    'order_no'              =>  $order_info['order_no'],
                    'order_id'              =>  $order_info['order_id'],
                    'mch_id'                =>  $order_info['mch_id'],
                    'user_id'               =>  $order_info['user_id'],
                    'order_delivery_id'     =>  $order_info['order_delivery_id'],
                    'sku_id'                =>  $ord_prod_info[0]['sku_id'],
                    'print_rcv_user'        =>  $order_info['order_rcv_user'],
                    'print_rcv_phone'       =>  $order_info['order_rcv_phone'],
                    'print_rcv_province'    =>  $order_info['order_rcv_province'],
                    'print_rcv_city'        =>  $order_info['order_rcv_city'],
                    'print_rcv_area'        =>  $order_info['order_rcv_area'],
                    'print_rcv_address'     =>  $order_info['order_rcv_address'],
                    'print_rcv_zipcode'     =>  $order_info['order_rcv_zipcode'],
                    'order_exp_fee'         =>  $order_info['order_exp_fee'],
                    'buy_num'               =>  $ord_prod_info[0]['prod_num'],
                    'order_create_time'     =>  strtotime($order_info['created_at']),
                ];

                $this->batchPrintRepository->insert($print_data);
            }
        }
    }

    /**
     *  添加到Erp推送队列
     * @param string $order_info 订单信息
     */
    public function addErpQueue($order_info)
    {
        $erp_queue = $this->erpPushQueueModel->where('order_id',$order_info['order_id'])->first();
        if(empty($erp_queue)){
            //插入saas_order_erp_push_queue表
            $erp_push_data = [
                'mch_id'                =>  $order_info['mch_id'],
                'order_id'              =>  $order_info['order_id'],
                'order_push_status'     =>  'ready',
                'created_at'            =>  time(),
            ];
            return $this->erpPushQueueModel->insertGetId($erp_push_data);
        }
    }

    /**
     *  Erp订单生产处理
     * @param $order_no 订单号 $item_no 订单项目号 $sp_id 供货商id $erp_order_no erp订单号 $agent_id 分销id
     */
    public function startProcessing($order_no,$item_no,$sp_id,$erp_order_no=null,$agent_id)
    {
        //订单信息
        $order_info = $this->orderRepository->getOrderInfo('',$order_no);

        //订单详情信息
        if(count(explode('-',$item_no)) == 2){
            $item_no = $item_no.'-1';
        }
        $ord_prod_info = $this->ordProdRepository->getRow(['ord_prj_item_no'=>$item_no]);

        if(empty($ord_prod_info) || empty($order_info)){
            //该订单记录不存在
            Helper::EasyThrowException(70030,__FILE__.__LINE__);
        }

        //Erp队列ID
        $queue_id = $this->erpPushQueueModel->where('order_id',$order_info['order_id'])->value('order_erp_push_id');
        if (empty($queue_id)){
            //推送工厂队列不存在
            Helper::EasyThrowException(70093,__FILE__.__LINE__);
        }

        $ord_prod_info['sp_id'] = !empty($sp_id) ? $sp_id : $ord_prod_info['sp_id'];

        //配送金额，暂定为0
        $distribution_amount = $this->getDistributionnAmount();

        //作品合成信息
        $compound_info = $this->compoundQueueRepository->getRow(['order_prod_id'=>$ord_prod_info['ord_prod_id']]);

        //组装新供货商订单表数据
        $sp_ord_data = [
            'mch_id'                    =>  $order_info['mch_id'],
            'agent_id'                  =>  $agent_id,
            'sp_id'                     =>  $ord_prod_info['sp_id'],
            'ord_id'                    =>  $order_info['order_id'],
            'order_no'                  =>  $order_info['order_no'],
            'erp_order_no'              =>  !empty($erp_order_no) ? $erp_order_no : '',
            'ord_prj_no'                =>  $item_no,
            'serial_number'             =>  $order_info['erp_order_serial_no'],
            'prj_type'                  =>  $ord_prod_info['prj_type'],
            'prj_id'                    =>  $ord_prod_info['prj_id'],
            'prod_id'                   =>  $ord_prod_info['prod_id'],
            'sku_id'                    =>  $ord_prod_info['sku_id'],
            'new_sp_file_url'           =>  $compound_info['comp_queue_file_info'],
            'sp_num'                    =>  $ord_prod_info['prod_num'],
            'prod_price'                =>  $ord_prod_info['prod_cost'],
            'new_sp_push_queue_id'      =>  $queue_id,
            'new_sp_order_amount'       =>  $ord_prod_info['prod_cost'] + $distribution_amount,
            'new_sp_pages'              =>  $ord_prod_info['prod_pages'],
            'new_sp_ord_rcv_user'       =>  $order_info['order_rcv_user'],
            'new_sp_ord_rcv_phone'      =>  $order_info['order_rcv_phone'],
            'new_sp_ord_rcv_country'    =>  $order_info['order_rcv_country'],
            'new_sp_ord_rcv_province'   =>  $order_info['order_rcv_province'],
            'new_sp_ord_rcv_city'       =>  $order_info['order_rcv_city'],
            'new_sp_ord_rcv_area'       =>  $order_info['order_rcv_area'],
            'new_sp_ord_rcv_address'    =>  $order_info['order_rcv_address'],
            'new_sp_ord_rcv_zipcode'    =>  $order_info['order_rcv_zipcode'],
            'transaction_time'          =>  strtotime($order_info['created_at']),
            'created_at'                =>  time(),
        ];

        //插入供货商订单数据
        $this->newSpOrdRepository->insert($sp_ord_data);

        //添加到供货商下载队列
        $this->addSupplierDownloadQueue($ord_prod_info);

        //若是实物订单(订单仅有一实物商品)则插入供货商批量打单表
        $this->addBatchPrint($order_info);

    }

}