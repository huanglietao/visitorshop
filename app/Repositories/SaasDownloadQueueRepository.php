<?php
namespace App\Repositories;

use App\Models\DmsAgentInfo;
use App\Models\SaasDownloadQueue;
use App\Models\SaasManuscript;
use App\Models\SaasOrderProduceQueue;
use App\Models\SaasOrderProducts;
use App\Models\SaasOrders;
use App\Models\SaasOuterOrderCreateLog;
use App\Models\SaasProducts;
use App\Models\SaasProductsSku;
use App\Models\SaasSpDownloadQueue;
use App\Models\SaasSuppliersOrderProduct;
use App\Services\Factory;
use GuzzleHttp\Client;

/**
 * 下载队列仓库
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/23
 */

class SaasDownloadQueueRepository extends BaseRepository
{
    public function __construct(SaasDownloadQueue $model,SaasProducts $products,SaasProductsSku $productsSku,SaasOrders $orders,
                                DmsAgentInfo $agentInfo,SaasOrderProducts $orderProducts,SaasSpDownloadQueue $spDownloadQueue,
                                SaasSuppliersOrderProduct $suppliersOrderProduct,SaasOuterOrderCreateLog $outerOrderCreateLog,
                                SaasManuscript $manuscript,SaasOrderProduceQueue $orderProduceQueue)
    {
        $this->model = $model;
        $this->productModel = $products;
        $this->skuModel = $productsSku;
        $this->orderModel = $orders;
        $this->agentInfoModel = $agentInfo;
        $this->orderProductModel = $orderProducts;
        $this->spDownloadModel = $spDownloadQueue;
        $this->spOrdProductModel = $suppliersOrderProduct;
        $this->outerOrderModel = $outerOrderCreateLog;
        $this->manascriptModel = $manuscript;
        $this->ordProductQueueModel = $orderProduceQueue;
    }

    /**
     * 新增/修改
     * @param $data
     * @return boolean
     */
    public function save($data)
    {
        if(empty($data['download_id'])) {
            unset($data['download_id']);
            $data['created_at'] = time();
            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;
        } else {
            $priKeyValue = $data['download_id'];
            unset($data['download_id']);
            $data['updated_at'] = time();
            $ret =$this->model->where('download_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
        if (isset($this->isCache)&&$this->isCache === true){
            $table_name = $this->model->getTable();
            $redis = app('redis.connection');
            $data['download_id'] = $priKeyValue;
            //将数据写入缓存
            $redis->set($table_name.'_'.$priKeyValue , json_encode($data));
        }
        return $ret;

    }

    /**
     * 获取下载队列
     * @param $mch_id $size $service_id
     * @return array
     */
    public function getDownloadList($mch_id, $size, $service_id)
    {
        $size = !empty($size) ? $size : 1;

        if(!empty($mch_id)){
            $where['mch_id'] = $mch_id;
        }
        if(!empty($service_id)){
            $where['down_serv_id'] = $service_id;
        }
        $where['down_status'] = 'ready';

        $res = $this->model->with('item')->where($where)->where('down_times','<',3)->orderBy('created_at')->take($size)->get();

        $arr[] = '';
        foreach ($res as $k=>$v){
            //订单信息
            $order_info = $this->orderModel->where('order_no',$v['order_no'])->select('user_id')->first();
            $res[$k]['user_id'] = $order_info['user_id'];

            //商品信息
            $product_info = $this->productModel->where('prod_id',$v['item']['prod_id'])->select('prod_name', 'prod_cate_uid')->first();
            $res[$k]['prod_name'] = $product_info['prod_name'];

            if (in_array($product_info['prod_cate_uid'],config('goods.add_one_cate'))) {
                $v['item']['prod_pages'] = $v['item']['prod_pages']+1;
            }

            //货品信息
            $sku_info = $this->skuModel->where('prod_sku_id',$v['item']['sku_id'])->select('prod_sku_sn','prod_supplier_sn')->first();
            $res[$k]['prod_sku_sn'] = $sku_info['prod_sku_sn'];
            $res[$k]['prod_process_code'] = $sku_info['prod_supplier_sn'];

            $res[$k]['pdf_path'] = $this->getPdfPath($v);
            $res[$k]['pdf_name'] = $this->getPdfName($v);

        }
        return $res;
    }

    /**
     * 更新队列状态
     * @param $qids $status
     * @return
     */
    public function updateQueue($qids, $status)
    {
        $data['down_status'] = $status;
        $arr_qid = explode(',',$qids);

        if($status == 'progress'){
            $data['start_time'] = time();
        }

        if($status == 'error'){
            $this->model->whereIn('download_id',$arr_qid)->increment('down_times');
        }


        $this->model->whereIn('download_id',$arr_qid)->update($data);

        if($status == 'finish'){
            foreach ($arr_qid as $k=>$v){
                $queue_info = $this->model->where('download_id',$v)->select('order_no','order_prod_id','manuscript_id')->first();
                $order_ids = $this->model->where('order_no',$queue_info['order_no'])->whereIn('down_status',['prepare','ready','progress','error'])->get()->toArray();

                if(empty($order_ids)){
                    //全部下载完成
                    //更新订单详情作品处理状态
                    $this->orderProductModel->where('ord_prod_id',$queue_info['order_prod_id'])->update(['pro_handel_type'=>WORKS_HANDEL_TYPE_PROCESSED]);

                    //N8订单回写为已下载状态
                    $this->writeBackN8($queue_info['order_no']);

                    //更新订单生产队列，开始自动提交生产
                    $order_info = $this->orderModel->where('order_no',$queue_info['order_no'])->select('order_id')->first();
                    $this->ordProductQueueModel->where(['order_id'=>$order_info['order_id'],'produce_queue_status'=>'prepare'])->update(['produce_queue_status'=>'ready']);

                }else{
                    //部分下载完成
                    $ord_prod_ids = $this->model->where('order_prod_id',$queue_info['order_prod_id'])->whereIn('down_status',['prepare','ready','progress','error'])->get()->toArray();
                    if(empty($ord_prod_ids)){
                        //更新当前队列对应的订单详情
                        $this->orderProductModel->where('ord_prod_id',$queue_info['order_prod_id'])->update(['pro_handel_type'=>WORKS_HANDEL_TYPE_PROCESSED]);
                    }
                }

                //更新稿件表下载状态
                $this->manascriptModel->where('script_id',$queue_info['manuscript_id'])->update(['manu_down_status'=>DOWNLOADED]);
            }
        }
        return true;
    }

    /**
     * N8订单下载状态回写
     * @param $order_no
     * @return
     */
    public function writeBackN8($order_no)
    {
        $catchInfo = $this->outerOrderModel->where('order_no',$order_no)->select('out_order_id','outer_order_callback_url','outer_order_item_id','outer_order_create_status','outer_order_status')->first();
        if(!empty($catchInfo)){
            $post_url = $catchInfo['order_callback_url'];

            $zt_sp_id = config('order.zt_n8_sp_id');
            $zt_secret = config('order.zt_n8_secret');

            $data['supplier_id'] =$sign_data['supplier_id'] = $zt_sp_id;
            $data['status'] =$sign_data['status'] = 11;   //已下载
            $data['order_item_ids'] =$sign_data['order_item_ids'] = $catchInfo['outer_order_item_id'];
            $data['timestamp'] =$sign_data['timestamp'] = time()*1000;

            $data['sign'] = $this->getSignature_c($sign_data,$zt_secret);
            $http = new Client();
            $res = $http->post($post_url,json_encode($data));
            if($res['code'] == 0) {
                //更新队列状态为11
                if($catchInfo['order_status'] == 10) {
                    $this->outerOrderModel->where('order_no',$order_no)->update(['order_status' => 11]);
                }
            }else {
                $this->outerOrderModel->where('order_no',$order_no)->update(['c_callback_error_msg' => '更新下载状态失败,错误信息为：'.$res['msg']]);
            }
        }
    }

    /**
     * @param $params
     * @param $secret
     * @return string
     */
    function getSignature_c($params, $secret){

        $str = '';//待签名字符串
        //先将参数以其参数名的字典序升序进行排序
        ksort($params);
        //遍历排序后的参数数组中的每一个key/value对
        foreach($params as $k => $v){
            if ($v == '' || 'sign' == $k) {
                continue;
            }
            //为key/value对生成一个key=value格式的字符串，并拼接到待签名字符串后面
            $str .= "$k=$v"."&";
        }
        //将签名密钥拼接到签名字符串最后面
        $str .= "supplier_secret=".$secret;
        //var_dump($str);exit;
        //通过md5算法为签名字符串生成一个md5签名，该签名就是我们要追加的sign参数值
        return strtoupper(md5($str));
    }

    /**
     * 获取pdf文件所在目录
     * @param $info
     * @return staring $pdf_path
     */
    public function getPdfPath($info)
    {
        $pdf_path = $info['mch_id'].'/'.date('Y-m-d', $info['created_at']).'/'.str_replace('/','-',$info['prod_name']).'/'.$info['order_no'].'/'.$info['item']['ord_prj_item_no'];
        $this->model->where('download_id',$info['download_id'])->update(['down_local_path'=>$pdf_path]);

        return $pdf_path;
    }

    /**
     * 获取pdf文件名称
     * @param $info
     * @return staring $pdfname
     */
    public function getPdfName($info, $iconv=false)
    {
        $is_c = strpos($info['down_url'], '-cover.pdf') || strpos($info['down_url'], '_cover.pdf')|| $info['down_page_type'] == GOODS_SIZE_TYPE_COVER;
        $hz = pathinfo($info['down_url'],PATHINFO_EXTENSION );
        $partType = $is_c ? '_cover.'.$hz : '.'.$hz;
//        $tmp = explode('-', $info['item']['ord_prj_item_no']);
//        $project_sn = str_replace('-', '_', $info['item']['ord_prj_item_no']);
//        $goods_name = str_replace('/','-',$info['prod_supplier_sn']);
//        $pages = empty($pages)?$info['item']['prod_num']:$pages;
//
//        if($hz != 'zip')
//        {
//            $pages = $info['item']['prod_num'];
//        }
//
//        $erp_name = $this->agentInfoModel->where('agent_info_id',$info['user_id'])->select('erp_name')->first();
//        $tt = !empty($erp_name) ? $erp_name['erp_name'] :'广州电商';
//
//        $pdfname = $tt.'-'.$goods_name.'-['.$info['order_no'].']{'.$project_sn.'_'.$info['item']['prod_num'].'}-'.$tmp[1].'X'.$pages.'w'.$partType ;

        //货品信息
        $sku_info = $this->skuModel->where('prod_sku_id',$info['item']['sku_id'])->select('prod_supplier_sn')->first();

        //获取erp名称
        $erp_name = app(Factory::class)->getErpName($info['user_id'],$info['mch_id']);

        //组装文件名称
        $info_data = [
            'erp_name'      =>  $erp_name,
            'order_no'      =>  $info['order_no'],
            'factory_code'  =>  $sku_info['prod_supplier_sn'],
            'project_sn'    =>  $info['item']['ord_prj_item_no'],
            'quantity'      =>  $info['item']['prod_num'],
            'page_count'    =>  $info['item']['prod_pages'],
        ];
        $get_name = app(Factory::class)->generateFileName($info['item']['prod_id'],$info_data,$is_c,false);

        $pdfname = $get_name.'.'.$hz;

        if(isset($info['download_id'])){
            $this->model->where('download_id',$info['download_id'])->update(['down_local_file_name'=>$pdfname]);
        }
        return $pdfname;


    }
}