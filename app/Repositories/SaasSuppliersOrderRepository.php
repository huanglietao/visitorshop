<?php
namespace App\Repositories;

use App\Models\DmsAgentInfo;
use App\Models\SaasCompoundService;
use App\Models\SaasOrders;
use App\Models\SaasProducts;
use App\Models\SaasProductsMedia;
use App\Models\SaasProductsSku;
use App\Models\SaasSalesChanel;
use App\Models\SaasSpDownloadQueue;
use App\Models\SaasSuppliersOrderProduct;
use App\Models\SaasSuppliersOrders;
use App\Services\Helper;

/**
 * 供货商订单仓库
 * @author: cjx
 * @version: 1.0
 * @date: 2020/05/07
 */

class SaasSuppliersOrderRepository extends BaseRepository
{
    protected $mch_id;
    protected $sp_id;

    public function __construct(SaasSuppliersOrders $suppliersOrders,SaasProducts $products,
                                SaasProductsMediaRepository $productsMedia,SaasProductsRelationAttrRepository $relationAttrRepository,
                                SaasProductsSku $sku,DmsAgentInfo $agentInfo,SaasSalesChanel $chanel,SaasOrders $orders,SaasAreasRepository $areasRepository,
                                SaasSpDownloadQueue $spDownloadQueue,SaasCompoundService $compoundService,SaasSuppliersOrderProduct $suppliersOrderProduct,
                                SaasOrdersRepository $ordersRepository)
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

        $this->mediaRepository = $productsMedia;
        $this->prodRelationAttrRepository = $relationAttrRepository;
        $this->areaRepository = $areasRepository;
        $this->orderRepository = $ordersRepository;
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
        $where['supplier_id'] = $this->sp_id;
        $where['sp_examine'] = SUPPLIER_ORDER_EXAMINE_REVIEWED; //取已审核

        if(isset($where['status'])){
            //按订单状态查询(待生产,生产中,已发货)
            $order_status = [
                'ALL'                                 =>          '100',             //全部(自定义,非常量)
                'ORDER_NO_PRODUCE'                    =>          ORDER_NO_PRODUCE,  //待生产
                'ORDER_PRODUCING'                     =>          ORDER_PRODUCING,   //生产中
                'ORDER_SHIPPED'                       =>          ORDER_SHIPPED,     //已发货
            ];

            if($order_status[$where['status']] != '100'){
                if($order_status[$where['status']] == ORDER_NO_PRODUCE || $order_status[$where['status']] == ORDER_PRODUCING){
                    $where['sp_produce_status'] = $order_status[$where['status']];
                }else{
                    $where['sp_delivery_status'] = $order_status[$where['status']];
                }
            }
            unset($where['status']);
        }

        //order 必须以 'id desc'这种方式传入
        $orderBy = [];
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }
        $query = $this->model->with(['item']);

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
            $arr[$k]['total'] = count($v->item); //item数量
            $nums = 0; //总件数
            foreach ($v->item as $key=>$val){
                $nums += $val->sp_nums;

                //商品信息
                $prod_info = $this->prodMoel->where('prod_id',$val->prod_id)->select("prod_name")->first();
                $arr[$k]['item'][$key]['prod_name'] = $prod_info->prod_name;
                $arr[$k]['item'][$key]['prod_main_thumb'] = $this->mediaRepository->getProductPhoto($val->prod_id)[0]['prod_md_path'];
                $arr[$k]['item'][$key]['attr_str'] = $this->prodRelationAttrRepository->getProductAttr($val->sku_id);

                //货品信息
                $sku_info = $this->skuModel->where('prod_sku_id',$val->sku_id)->select('prod_sku_price')->first();
                $arr[$k]['item'][$key]['prod_sku_price'] = $sku_info['prod_sku_price'];

                //供货商下载队列信息
                $sp_download_info = $this->spDownloadModel->where(['ord_id'=>$v->ord_id,'sp_id'=>$v->supplier_id,'ord_prod_id'=>$val->ord_prod_id])->select('sp_down_queue_id','service_id','sp_id','ord_id','filename','path','filetype','is_down','ord_prod_id')->get()->toArray();
                if($val->prj_type == WORKS_FILE_TYPE_DIY || $val->prj_type == WORKS_FILE_TYPE_UPLOAD){
                    //diy作品、稿件下载
                    foreach ($sp_download_info as $kk=>$vv){
                        $public_id = $this->compoundServiceModel->where('comp_serv_id',$vv['service_id'])->select('public_ip')->first();
                        $sp_download_info[$kk]['url'] = 'http://'.$public_id['public_ip'].'/'.$vv['path'].'/'.$vv['filename'];

                        //下载状态
                        if($vv['is_down'] == SUPPLIER_QUEUE_STATUS_NOT_DOWNLOAD){
                            $arr[$k]['item'][$key]['is_download'] = '未下载';
                        }else{
                            $arr[$k]['item'][$key]['is_download'] = '已下载';
                        }
                    }
                    $arr[$k]['item'][$key]['download'] = $sp_download_info;

                }else if($val->prj_type == WORKS_FILE_TYPE_EMPTY){
                    //实物
                    foreach ($sp_download_info as $kk=>$vv){
                        $sp_download_info[$kk]['url'] = $vv['filename'];

                        //下载状态
                        if($vv['is_down'] == SUPPLIER_QUEUE_STATUS_NOT_DOWNLOAD){
                            $arr[$k]['item'][$key]['is_download'] = '未下载';
                        }else{
                            $arr[$k]['item'][$key]['is_download'] = '已下载';
                        }
                    }
                    $arr[$k]['item'][$key]['download'] = $sp_download_info;
                }
            }
            $arr[$k]['nums'] = $nums;

            //主订单表信息
            $order_info = $this->orderModel->where('order_id',$v['ord_id'])->select('user_id','cha_id','order_status')->first();
            $arr[$k]['order_status'] = $order_info['order_status'];

            //店铺名称
            $agentInfo = $this->agentInfoModel->where('agent_info_id',$order_info['user_id'])->select("agent_name")->first();
            $arr[$k]['agent_name'] = !empty($agentInfo) ? $agentInfo->agent_name : '';

            //渠道
            $chanel = $this->chanelModel->where('cha_id',$order_info['cha_id'])->select("cha_name")->first();
            $arr[$k]['cha_name'] = !empty($chanel) ? $chanel->cha_name : '';

            //省市区转换
            $province = $this->areaRepository->getAreaIdList($v['sp_ord_rcv_province'])->toArray();
            $city = $this->areaRepository->getAreaIdList($v['sp_ord_rcv_city'])->toArray();
            $area = $this->areaRepository->getAreaIdList($v['sp_ord_rcv_area'])->toArray();

            $arr[$k]['province_name'] = !empty($province) ? $province['area_name'] : '';
            $arr[$k]['city_name'] = !empty($city) ? $city['area_name'] : '';
            $arr[$k]['area_name'] = !empty($area) ? $area['area_name'] : '';

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
        $where['supplier_id'] = $this->sp_id;
        $where['sp_examine'] = SUPPLIER_ORDER_EXAMINE_REVIEWED; //取已审核

        $all = count($this->model->where($where)->get());                                                               //全部
        $wait_produce = count($this->model->where('sp_produce_status',ORDER_NO_PRODUCE)->where($where)->get());         //待生产
        $producing = count($this->model->where('sp_produce_status',ORDER_PRODUCING)->where($where)->get());             //生产中
        $shipping = count($this->model->where('sp_delivery_status',ORDER_SHIPPED)->where($where)->get());               //已发货

        return [$all,$wait_produce,$producing,$shipping];
    }

    /**
     *  封面内页下载
     * @return array
     */
    public function download($param)
    {
        //更新队列下载状态
        $this->spDownloadModel->where('sp_down_queue_id',$param['id'])->update(['is_down'=>SUPPLIER_QUEUE_STATUS_DOWNLOADED]);

        $where = [
            ['sp_id','=',$param['sid']],
            ['ord_prod_id','=',$param['item']],
            ['ord_id','=',$param['oid']],
            ['is_down','!=',SUPPLIER_QUEUE_STATUS_DOWNLOADED],
        ];
        $res = $this->spDownloadModel->where($where)->get();

        if(count($res) == 0){
            //更新供货商订单详情下载状态字段
            $this->spOrderProdModel->where(['ord_id'=>$param['oid'],'supplier_id'=>$param['sid'],'ord_prod_id'=>$param['item']])->update(['sp_download_status'=>SUPPLIER_QUEUE_STATUS_DOWNLOADED]);
        }

        //开始下载
        $this->orderRepository->startDownload($param['url']);

        return true;
    }

    /**
     *  发货处理
     * @return array
     */
    public function delivery($param)
    {
        $sp_order_info = $this->getById($param['sp_ord_id']);
        $order_info = $this->orderRepository->getOrderInfo($sp_order_info['ord_id']);

        if(empty($order_info)){
            //该订单记录不存在
            Helper::EasyThrowException(70030,__FILE__.__LINE__);
        }

        if($sp_order_info['sp_order_status'] != ORDER_STATUS_WAIT_DELIVERY && $sp_order_info['sp_order_status'] != ORDER_STATUS_WAIT_PRODUCE){
            //订单未满足发货条件(生产流程暂时无法判断，跳过生产环节)
            Helper::EasyThrowException(70034,__FILE__.__LINE__);
        }

        if($sp_order_info['sp_examine'] != SUPPLIER_ORDER_EXAMINE_REVIEWED){
            //订单未审核完成
            Helper::EasyThrowException(70083,__FILE__.__LINE__);
        }

        //发货数据
        $data = [
            'delivery_code'            =>  $param['delivery_code'],
            'order_delivery_id'        =>  $param['delivery_id'],
            'order_exp_fee'            =>  $order_info['order_exp_fee'],
            'order_remark_admin'       =>  '',
        ];

        //发货流程处理
        $this->orderRepository->delivery($sp_order_info['ord_id'],$data,session("admin")['scm_adm_username'],'SCM',$param['sp_ord_id']);

        return true;
    }

}