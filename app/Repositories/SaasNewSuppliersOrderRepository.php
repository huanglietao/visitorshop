<?php
namespace App\Repositories;

use App\Models\DmsAgentInfo;
use App\Models\OmsMerchantInfo;
use App\Models\SaasCompoundService;
use App\Models\SaasNewSpDownloadQueue;
use App\Models\SaasNewSuppliersOrders;
use App\Models\SaasOrders;
use App\Models\SaasProducts;
use App\Models\SaasProductsMedia;
use App\Models\SaasProductsPrint;
use App\Models\SaasProductsSku;
use App\Models\SaasSalesChanel;
use App\Models\SaasSpDownloadQueue;
use App\Models\SaasSuppliersOrderProduct;
use App\Services\Helper;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


/**
 * 供货商订单仓库
 * @author: cjx
 * @version: 1.0
 * @date: 2020/06/22
 */

class SaasNewSuppliersOrderRepository extends BaseRepository
{
    protected $mch_id;
    protected $sp_id;

    public function __construct(SaasNewSuppliersOrders $suppliersOrders,SaasProducts $products,
                                SaasProductsMediaRepository $productsMedia,SaasProductsRelationAttrRepository $relationAttrRepository,
                                SaasProductsSku $sku,DmsAgentInfo $agentInfo,SaasSalesChanel $chanel,SaasOrders $orders,SaasAreasRepository $areasRepository,
                                SaasSpDownloadQueue $spDownloadQueue,SaasCompoundService $compoundService,SaasSuppliersOrderProduct $suppliersOrderProduct,
                                SaasOrdersRepository $ordersRepository,SaasNewSpDownloadQueue $newSpDownloadQueue,SaasSizeInfoRepository $sizeInfoRepo,OmsMerchantInfoRepository $merInfoRepo)
    {

        $this->mch_id = isset(session("admin")['mch_id']) ? session("admin")['mch_id'] : PUBLIC_CMS_MCH_ID;
        $this->sp_id = isset(session("admin")['sp_id']) ? session("admin")['sp_id'] : '';
        $this->model = $suppliersOrders;
        $this->prodMoel = $products;
        $this->skuModel = $sku;
        $this->agentInfoModel = $agentInfo;
        $this->chanelModel = $chanel;
        $this->orderModel = $orders;
        $this->spDownloadModel = $spDownloadQueue;
        $this->compoundServiceModel = $compoundService;
        $this->spOrderProdModel = $suppliersOrderProduct;
        $this->newSpDownloadModel = $newSpDownloadQueue;

        $this->mediaRepository = $productsMedia;
        $this->prodRelationAttrRepository = $relationAttrRepository;
        $this->areaRepository = $areasRepository;
        $this->orderRepository = $ordersRepository;
        $this->sizeInfo = $sizeInfoRepo;
        $this->merInfoRepository = $merInfoRepo;
    }


    /**
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getTableList($where=null, $order='created_at desc')
    {
        $limit = isset($where['limit']) ? $where['limit']:config('common.page_limit');  //这个10取配置里的
        $where = $this->parseWhere($where);
        //$where['mch_id'] = $this->mch_id;
        $where['sp_id'] = $this->sp_id;
        $where['new_sp_examine'] = SUPPLIER_ORDER_EXAMINE_REVIEWED; //取已审核

        if(isset($where['status'])){
            //按订单状态查询(待生产,生产中,已发货)
            $order_status = [
                'ALL'                                 =>          '100',                        //全部(自定义,非常量)
                'SP_ORDER_STATUS_PRODUCE'             =>          SP_ORDER_STATUS_PRODUCE,      //待生产
                'SP_ORDER_STATUS_PRODUCING'           =>          SP_ORDER_STATUS_PRODUCING,    //生产中
                'SP_ORDER_STATUS_DELIVERY'            =>          SP_ORDER_STATUS_DELIVERY,     //已送货
                'SP_ORDER_STATUS_SEND'                =>          SP_ORDER_STATUS_SEND,         //已发货
            ];

            if($order_status[$where['status']] != '100'){
                if($order_status[$where['status']] == ORDER_NO_PRODUCE || $order_status[$where['status']] == ORDER_PRODUCING){
                    $where['sp_order_status'] = $order_status[$where['status']];
                }else{
                    $where['sp_order_status'] = $order_status[$where['status']];
                }
            }
            unset($where['status']);
        }


        if(isset($where['ord_prj_no'])){
            //订单项目号查询处理
            $where['ord_prj_no'] = str_replace('_','-',substr($where['ord_prj_no'],0,strripos($where['ord_prj_no'],'_')));
        }

        //order 必须以 'id desc'这种方式传入
        $orderBy = [];
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }


        $query = $this->model;

        //查询时间
        if(isset($where['created_at'])){
            $created_at = $where['created_at'];
            $time_list = Helper::getTimeRangedata($created_at);
            $query = $query->whereBetween("created_at",[$time_list['start'],$time_list['end']]);
            unset($where['created_at']);
        }

        if(!empty ($where)) {
            $query =  $query->where($where);
        }

        if(!empty($order)) {
            $query =  $query->orderBy($orderBy[0],$orderBy[1]);
        }

        $list = $query->paginate($limit);

        $arr[] = '';
        foreach ($list as $k=>$v){
            $arr[$k] = $v->toArray();
            //商品信息
            $prod_info = $this->prodMoel->where('prod_id',$v->prod_id)->select("prod_name")->first();
            $arr[$k]['item']['prod_name'] = isset($prod_info->prod_name) ? $prod_info->prod_name : '';
            $arr[$k]['item']['prod_main_thumb'] = $this->mediaRepository->getProductPhoto($v->prod_id)[0]['prod_md_path'];
            $arr[$k]['item']['attr_str'] = $this->prodRelationAttrRepository->getProductAttr($v->sku_id);
            $arr[$k]['item']['is_download'] = '';

            //供货商下载队列信息
            if(empty($v->ord_id)){
                $sp_download_info = $this->newSpDownloadModel->where(['project_sn'=>$v->ord_prj_no,'sp_id'=>$v->sp_id])->select('new_sp_down_queue_id','service_id','sp_id','ord_id','filename','path','filetype','is_down','ord_prod_id')->get()->toArray();
            }else{
                $sp_download_info = $this->newSpDownloadModel->where(['ord_id'=>$v->ord_id,'sp_id'=>$v->sp_id])->select('new_sp_down_queue_id','service_id','sp_id','ord_id','filename','path','filetype','is_down','ord_prod_id')->get()->toArray();
            }
            if($v->prj_type == WORKS_FILE_TYPE_DIY || $v->prj_type == WORKS_FILE_TYPE_UPLOAD){
                //diy作品、稿件下载
                foreach ($sp_download_info as $kk=>$vv){
                    $public_id = $this->compoundServiceModel->where('comp_serv_id',$vv['service_id'])->select('public_ip')->first();
                    $url = $vv['path'].'/'.$vv['filename'];
                    $sp_download_info[$kk]['url'] = Helper::getRealUrl($url,'http://'.$public_id['public_ip']);

                    //下载状态
                    if($vv['is_down'] == SUPPLIER_QUEUE_STATUS_NOT_DOWNLOAD){
                        $arr[$k]['item']['is_download'] = '未下载';
                    }else{
                        $arr[$k]['item']['is_download'] = '已下载';
                    }
                }
                $arr[$k]['item']['download'] = $sp_download_info;

            }else if($v->prj_type == WORKS_FILE_TYPE_EMPTY){
                //实物
                foreach ($sp_download_info as $kk=>$vv){
                    $sp_download_info[$kk]['url'] = $vv['filename'];

                    //下载状态
                    if($vv['is_down'] == SUPPLIER_QUEUE_STATUS_NOT_DOWNLOAD){
                        $arr[$k]['item']['is_download'] = '未下载';
                    }else{
                        $arr[$k]['item']['is_download'] = '已下载';
                    }
                }
                $arr[$k]['item']['download'] = $sp_download_info;
            }

            //购买数量
            $arr[$k]['nums'] = $v['sp_num'];

            //主订单表信息
            $order_info = $this->orderModel->where('order_id',$v['ord_id'])->select('user_id','cha_id','order_status')->first();

            //店铺名称
            $agentInfo = $this->agentInfoModel->where('agent_info_id',$order_info['user_id'])->select("agent_name")->first();
            $arr[$k]['agent_name'] = !empty($agentInfo) ? $agentInfo->agent_name : '';

            //渠道
            $chanel = $this->chanelModel->where('cha_id',$order_info['cha_id'])->select("cha_name")->first();
            $arr[$k]['cha_name'] = !empty($chanel) ? $chanel->cha_name : '';

            //省市区转换
            $province = $this->areaRepository->getAreaIdList($v['new_sp_ord_rcv_province']);
            $city = $this->areaRepository->getAreaIdList($v['new_sp_ord_rcv_city']);
            $area = $this->areaRepository->getAreaIdList($v['new_sp_ord_rcv_area']);

            $arr[$k]['province_name'] = !empty($province) ? $province['area_name'] : '';
            $arr[$k]['city_name'] = !empty($city) ? $city['area_name'] : '';
            $arr[$k]['area_name'] = !empty($area) ? $area['area_name'] : '';

            //订单项目号转换
            $arr[$k]['originally_ord_prj_no_'] = $arr[$k]['ord_prj_no'];
            $str = $arr[$k]['ord_prj_no'].'_'.$v['sp_num'];
            $arr[$k]['ord_prj_no'] = str_replace('-','_',$str);

        }
        $arr = $arr[0] == '' ? [] : $arr;
        $list = $list->toArray();
        $list['data'] = $arr;
//dd($list);
        return $list;
    }


    /**
     * 新增/修改
     * @param $data
     * @return boolean
     */
    public function save($data)
    {
        if(empty($data['sp_ord_id'])){
            unset($data['sp_ord_id']);
            $ret = $this->model->create($data);
        } else {
            $priKeyValue = $data['sp_ord_id'];
            unset($data['sp_ord_id']);

            $data['updated_at'] = time();
            $ret =$this->model->where('sp_ord_id',$priKeyValue)->update($data);
        }
        return $ret;
    }

    /**
     * 删除(软删除)
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $model = $this->model->find($id);
        $model->delete();

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }

    /**
     *  各状态订单数量统计
     * @return array
     */
    public function orderStatusCount()
    {
        //$where['mch_id'] = $this->mch_id;
        $where['sp_id'] = $this->sp_id;
        $where['new_sp_examine'] = SUPPLIER_ORDER_EXAMINE_REVIEWED; //取已审核

        $all = count($this->model->where($where)->get());                                                                    //全部
        $wait_produce = count($this->model->where('sp_order_status',SP_ORDER_STATUS_PRODUCE)->where($where)->get());         //待生产
        $producing = count($this->model->where('sp_order_status',SP_ORDER_STATUS_PRODUCING)->where($where)->get());          //生产中
        $shipping = count($this->model->where('sp_order_status',SP_ORDER_STATUS_DELIVERY)->where($where)->get());            //已送货
        $send = count($this->model->where('sp_order_status',SP_ORDER_STATUS_SEND)->where($where)->get());                    //已发货

        return [$all,$wait_produce,$producing,$shipping,$send];
    }

    /**
     *  封面内页下载
     * @return array
     */
    public function download($param)
    {

        //更新队列下载状态
        $this->newSpDownloadModel->where('new_sp_down_queue_id',$param['id'])->update(['is_down'=>SUPPLIER_QUEUE_STATUS_DOWNLOADED]);
        $where = [
            ['project_sn','=',$param['item_no']],
            ['is_down','!=',SUPPLIER_QUEUE_STATUS_DOWNLOADED],
        ];
        $res = $this->newSpDownloadModel->where($where)->get();

        if(count($res) == 0){
            //更新供货商订单下载状态字段
            $this->model->where(['ord_prj_no'=>$param['item_no']])->update(['new_sp_download_status'=>SUPPLIER_QUEUE_STATUS_DOWNLOADED]);
        }

        //开始下载
        $this->orderRepository->startDownload($param['url']);

        return true;
    }

    /**
     *  获取发货列表数据对账数据
     * @return array
     */
    public function getSendTableList($where=null, $order=null,$setlimit =false)
    {
        $limit = isset($where['limit']) ? $where['limit']:config('common.page_limit');  //这个10取配置里的
        if($setlimit == 'true'){
            $limit = 9999;
        }
        $where = $this->parseWhere($where);

        $goodswhere = [];$skuwhere = [];
        if(!empty($where['prod_name'])){
            $goodswhere['prod_name'] = $where['prod_name'];
            unset($where['prod_name']);
        }
        if(!empty($where['prod_supplier_sn'])){
            $skuwhere['prod_supplier_sn'] = $where['prod_supplier_sn'];
            unset($where['prod_supplier_sn']);
        }

        //order 必须以 'id desc'这种方式传入.
        $orderBy = [];
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }

        $query = $this->model->WhereHas(
            'prosku',function($query) use ($skuwhere) {
            if (!empty($skuwhere)) {
                $query->where($skuwhere);
            }
        })->WhereHas(
               'product',function($query) use ($goodswhere) {
               if (!empty($goodswhere)) {
                   $query->where('prod_name','like','%'.$goodswhere['prod_name'].'%');
               }
           })
            ->with(['prosku','product']);
         //dd($where);
        //查询交易时间
        if(isset($where['send_time'])){
            $compound_time = $where['send_time'];
            $time_list = Helper::getTimeRangedata($compound_time);
            $query = $query->where("created_at",">=",$time_list['start'])->where('created_at','<=',$time_list['end']);
            unset($where['send_time']);
        }

        if(!empty ($where)) {
            $query =  $query->where($where);
        }

        if(!empty($order)) {
            $query =  $query->orderBy($orderBy[0],$orderBy[1]);
        }

        $list = $query->paginate($limit);
        //转数组再组装数据
        $listArr = $list->toArray();

        if($listArr){
            //查询规格详情数据
            $sizeInfoList = $this->sizeInfo->getList(['size_type'=>GOODS_SIZE_TYPE_INNER])->toArray();
            $sizeInfoArr = array_column($sizeInfoList,'size_is_2faced','size_id');
            $isTrun = config('goods.is_turn');

            //组装客户简称
            $agentInfo = $this->agentInfoModel->whereNotNull('agent_code')->get()->toArray();
            $agentInfoArr = array_column($agentInfo,'erp_name','agent_info_id');
            //商户客户简称
            $merInfo = $this->merInfoRepository->getList([])->toArray();
            $merInfoArr = array_column($merInfo,'erp_name','mch_id');

            foreach ($listArr['data'] as $k=>$v){
                //组装单双面
                $listArr['data'][$k]['prod_print'] = SaasProductsPrint::where(['prod_id'=>$v['prod_id']])->first()->toArray();
                $listArr['data'][$k]['is_trun'] = $isTrun[$sizeInfoArr[$listArr['data'][$k]['prod_print']['prod_size_id']]];
                //组装客户简称
                if(isset($agentInfoArr[$v['agent_id']])){
                    $listArr['data'][$k]['agent_name'] = $agentInfoArr[$v['agent_id']];
                }else{
                    $listArr['data'][$k]['agent_name'] = $merInfoArr[$v['mch_id']];
                }
                //拼接流水号
                $listArr['data'][$k]['ord_prj_no'] = str_replace('-','_',$v['ord_prj_no']).'_'.$v['sp_num'];
                //组织冲印类订购数量，区分新老系统
                $singleArr = config('goods.add_one_cate');//冲印类id
                $listArr['data'][$k]['cate_id'] = $this->prodMoel->where('prod_id',$v['prod_id'])->select('prod_cate_uid')->first();
                if(in_array($listArr['data'][$k]['cate_id']['prod_cate_uid'],$singleArr) && !empty($v['prj_id'])){
                    $listArr['data'][$k]['sp_num']=$v['sp_num']*($v['new_sp_pages']+1);
                }
                /*$listArr['data'][$k]['agent_code'] = substr($v['order_no'],0,3);
                if(array_key_exists($listArr['data'][$k]['agent_code'],$agentInfoArr)){
                    if(isset($agentInfoArr[$listArr['data'][$k]['agent_code']])){
                        $listArr['data'][$k]['agent_name'] = $agentInfoArr[$listArr['data'][$k]['agent_code']];
                    }else{
                        $listArr['data'][$k]['agent_name'] = $merInfoArr[$listArr['data'][$k]['agent_code']];
                    }

                }else{
                    $listArr['data'][$k]['agent_code'] = substr($v['order_no'],0,2);
                    if(isset($agentInfoArr[$listArr['data'][$k]['agent_code']])){
                        $listArr['data'][$k]['agent_name'] = $agentInfoArr[$listArr['data'][$k]['agent_code']];
                    }else{
                        $listArr['data'][$k]['agent_name'] = $merInfoArr[$listArr['data'][$k]['agent_code']];
                    }

                }*/
            }

        }

        return $listArr;
    }

    /**
     * 导出处理
     * @param $param 创建时间
     */
    public function export($param)
    {

        //获取数据
        $list = $this->getSendTableList($param,null,true);
        $result = $list['data'];

        if(empty($result)){
            echo '暂无记录';
            die;
        }

        $data = [];$orderStatus = config('order.supplier_order_status');
        foreach ($result as $k=>$v){
            $data[$k]['order_no'] = $v['erp_order_no'];
            $data[$k]['serial_number'] = $v['ord_prj_no'];
            $data[$k]['product_sn'] = $v['prosku'][0]['prod_supplier_sn'];
            $data[$k]['num'] = $v['sp_num'];
            //$data[$k]['amount'] = $v['new_sp_order_amount'];
            $data[$k]['status'] = $orderStatus[$v['sp_order_status']];
            $data[$k]['time'] = date('Y-m-d H:i:s',$v['created_at']);
            $data[$k]['is_trun'] = $v['is_trun'];
            $data[$k]['agent_name'] = $v['agent_name'];
        }

        $spreadsheet= new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        //设置sheet的名字  两种方法
        $spreadsheet->getActiveSheet()->setTitle('生产对账报表导出');

        //设置自动列宽
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(40);
        $sheet->getColumnDimension('E')->setAutoSize(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(40);

        //设置第一行小标题
        $k = 1;
        $sheet->setCellValue('A'.$k, '订单编号');
        $sheet->setCellValue('B'.$k, '订单流水号');
        $sheet->setCellValue('C'.$k, '客户简称');
        $sheet->setCellValue('D'.$k, '产品名称');
        $sheet->setCellValue('E'.$k, '订购人数');
        $sheet->setCellValue('F'.$k, '订购数量');
        //$sheet->setCellValue('G'.$k, '金额');
        $sheet->setCellValue('G'.$k, '单双面');
        $sheet->setCellValue('H'.$k, '订单状态');
        $sheet->setCellValue('I'.$k, '订单日期');

        $k = 2;
        foreach ($data as $key => $value) {
            $sheet->setCellValue('A' . $k, "\t".$value['order_no']);
            $sheet->setCellValue('B' . $k, "\t".$value['serial_number']);
            $sheet->setCellValue('C' . $k, $value['agent_name']);
            $sheet->setCellValue('D' . $k, $value['product_sn']);
            $sheet->setCellValue('E' . $k, 1);
            $sheet->setCellValue('F' . $k, $value['num']);
            //$sheet->setCellValue('G' . $k, $value['amount']);
            $sheet->setCellValue('G' . $k, $value['is_trun']);
            $sheet->setCellValue('H' . $k, $value['status']);
            $sheet->setCellValue('I' . $k, $value['time']);
            $k++;
        }

        $file_name = '生产对账报表'.date('Y-m-d H:i:s',time());
        $file_name = $file_name . ".xlsx";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$file_name.'"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');

    }

    /**
     * 旧系统数据生成供货商订单
     * param $data 数据格式参考$arr
     */
    public function createSpOrder($data)
    {
//        $data = array (
//            'order_no' => '2020080700431',
//            'items' => '{"serial_number": "200807134706580984_3_1_1", "file_url": "{\\"combine\\": false, \\"cover\\": \\"http://47.92.90.159/fc_ftp-image//20200807/12\\\\u5bf8\\\\u7cbe\\\\u88c5\\\\u4e66\\\\u5347\\\\u7ea7\\\\u6b3e/200807134706580984-3-1/\\\\u5e7f\\\\u5dde\\\\u7535\\\\u5546-A4HHP\\\\u5185120G32\\\\u76f8-[200807134706580984]{200807134706580984_3_1_1}-3X1w_cover.pdf\\", \\"inner\\": \\"http://47.92.90.159/fc_ftp-image///20200807/12\\\\u5bf8\\\\u7cbe\\\\u88c5\\\\u4e66\\\\u5347\\\\u7ea7\\\\u6b3e/200807134706580984-3-1/\\\\u5e7f\\\\u5dde\\\\u7535\\\\u5546-A4HHP\\\\u5185120G32\\\\u76f8-[200807134706580984]{200807134706580984_3_1_1}-3X1w.pdf\\"}", "goods_number": 1.0, "goods_name": "A4HHP\\u5185120G32\\u76f8"}',
//            'sign' => '9C984C7D443D224035453CFC07675ED4',
//            'supplier_code' => '印艺阁印刷',
//            'timestamp' => '1596783409',
//            'partner_short_name' => '广州电商',
//            'agent_id'=> 1,
//            'mch_id'=> 1,
//            'sp_id'=> 13,
//            'sku_id'=> 757,
//            'goods_id'=> 334,
//        );

        $res = json_decode($data['items'],true);
        $file_arr = json_decode($res['file_url'],true);
        $order_no = substr($res['serial_number'],0,stripos($res['serial_number'],'_'));
        $ord_prj_no = str_replace('_','-',substr($res['serial_number'],0,strripos($res['serial_number'],'_')));

        //流水号重复则不插入
        $is_exist = $this->model->where(['ord_prj_no'=>$ord_prj_no])->first();

        if(empty($is_exist)){
            //供货商订单数据
            $sp_order_data = [
                'mch_id'            =>  $data['mch_id'],
                'agent_id'          =>  $data['agent_id'],
                'sp_id'             =>  $data['sp_id'],
                'order_no'          =>  $order_no,
                'erp_order_no'      =>  $data['order_no'],
                'ord_prj_no'        =>  $ord_prj_no,
//            'serial_number'     =>  $res['serial_number'],
                'prj_type'          =>  WORKS_FILE_TYPE_UPLOAD,
                'prod_id'           =>  $data['goods_id'],
                'sku_id'            =>  $data['sku_id'],
                'sp_num'            =>  $res['goods_number'],
                'created_at'        =>  time(),
            ];

            $this->insert($sp_order_data);

            //供货商下载队列数据
            $cover_flag = config('order.cover_flag');
            foreach ($file_arr as $k=>$v){
                if($v){
                    //判断是否为封面
                    if(strstr($v,$cover_flag )) {
                        //封面
                        $type = GOODS_SIZE_TYPE_COVER;
                    } else {
                        //内页
                        $type = GOODS_SIZE_TYPE_INNER;
                    }

                    $file_name = substr($v, strripos($v, '/') + 1);
                    $path = dirname($v);

                    $sp_download_data = [
                        'ord_prod_id'       =>  PUBLIC_NO,
                        'ord_id'            =>  PUBLIC_NO,
                        'mch_id'            =>  $data['mch_id'],
                        'sp_id'             =>  $data['sp_id'],
                        'order_no'          =>  $order_no,
                        'prod_id'           =>  $data['goods_id'],
                        'sku_id'            =>  $data['sku_id'],
//                    'serial_number'     =>  $res['serial_number'],
                        'project_sn'        =>  $ord_prj_no,
                        'filetype'          =>  $type,
                        'path'              =>  $path,
                        'filename'          =>  $file_name,
                        'created_at'        =>  time(),
                    ];
                    $this->newSpDownloadModel->create($sp_download_data);
                }
            }
        }
    }

}