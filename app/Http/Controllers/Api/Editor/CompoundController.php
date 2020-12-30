<?php
namespace App\Http\Controllers\Api\Editor;

use App\Exceptions\CommonException;
use App\Repositories\SaasCompoundQueueRepository;
use App\Repositories\SaasOrderProduceQueueRepository;
use App\Repositories\SaasOrderProductsRepository;
use App\Repositories\SaasOrdersRepository;
use App\Repositories\SaasProductsRepository;
use App\Repositories\SaasProductsSkuRepository;
use App\Repositories\SaasProjectsOrderTempRepository;
use App\Repositories\SaasProjectsRepository;
use App\Services\Common\Mongo;
use App\Services\Factory;
use App\Services\Goods\Info;
use App\Services\Helper;
use App\Services\Queue;
use Illuminate\Http\Request;

/**
 * 合成器相关接口
 *
 * 合成列表，合成状态更新，合成配置保存
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/5/12
 */

class CompoundController extends BaseController
{
    /**
     * 获取合成记录
     * @param Request $request
     * @param Queue $queue
     * @param SaasProjectsRepository $repoWorks
     * @param SaasProductsRepository $repoGoods
     * @param SaasProjectsOrderTempRepository $repoPrjTemp
     * @param SaasProductsSkuRepository $repoSku
     * @param SaasOrderProductsRepository $repoOrderItem
     * @return \Illuminate\Http\JsonResponse
     */
    public function GetCompoundQueue(Request $request,Queue $queue, SaasProjectsRepository $repoWorks,
         SaasProductsRepository $repoGoods,SaasProjectsOrderTempRepository $repoPrjTemp,
         SaasProductsSkuRepository $repoSku, SaasOrderProductsRepository $repoOrderItem)
    {
        try {
            $params = $request->all();
            $list = $queue->getCompoundQueue($params);
            if (empty($list)) {
                return $this->success([]);
            }
            //查询作品数据
            $worksInfo = $repoWorks->getRow(['prj_id' => $list['works_id']]);

            //查询商品信息
            $goodsInfo = $repoGoods->getRow(['prod_id' => $worksInfo['prod_id']],['prod_name']);
            //查询商品图片
            $photoInfo= app(Info::class)->getGoodsPhotosList( $worksInfo['prod_id']);
            $goodsInfo['main_thumb'] = $photoInfo['cover'];
            //查询货品信息
            $skuInfo = $repoSku->getRow(['prod_sku_id' => $worksInfo['sku_id']], ['prod_sku_sn', 'prod_process_code', 'prod_supplier_sn']);

            //作品临时信息
            $prjTempInfo = $repoPrjTemp->getRow(['prj_id' => $list['works_id']], ['user_id', 'user_type','ord_quantity']);

            //订单详情
            $itemInfo = $repoOrderItem->getRow(['ord_prod_id' => $list['order_prod_id']], ['prod_num']);

            if ($prjTempInfo['user_type'] == CHANEL_TERMINAL_AGENT) {
                $agentId = $prjTempInfo['user_id'];
            } else {
                $agentId = 0;
            }
            if(empty($goodsInfo)) {
                Helper::apiThrowException("40014",__FILE__.__LINE__);
            }
            if(empty($worksInfo)) {
                Helper::apiThrowException("60001",__FILE__.__LINE__);
            }

            $return = [];
            $return['qid'] = $list['comp_queue_id'];
            $return['project_sn'] = $list['project_sn'];
            $return['order_id'] = $list['order_no'];
            $return['work_id'] = $list['works_id'];
            $return['work_createtime'] = $worksInfo['created_at'];
            $return['goods_name'] = $goodsInfo['prod_name'];
            $return['goods_id'] = $worksInfo['prod_id'];
            $return['product_id'] = $worksInfo['sku_id'];
            $return['sp_id'] = $worksInfo['mch_id'];
            $return['agent_id'] = $agentId;
            $return['work_name'] = $worksInfo['prj_name'];
            $return['queue_createtime'] = $list['created_at'];
            $return['queue_confirmtime'] = $list['created_at'];
            $return['goods_thumb_url'] = config('common.static_url').'/'.$goodsInfo['main_thumb'];
            $return['work_flag'] = 2;
            $return['quantity'] = $itemInfo['prod_num'];
            $return['factory_code'] = $skuInfo['prod_sku_sn'];
            $return['factory_pro_name'] = $skuInfo['prod_supplier_sn'];
            $return['erp_sp_name'] = app(Factory::class)->getErpName($agentId,$worksInfo['mch_id']);
            //商业印刷返回商业印刷的作品id 2020.08.12 by david
            if(isset($worksInfo['coml_works_id']) && !empty($worksInfo['coml_works_id'])){
                $return['coml_works_id'] = $worksInfo['coml_works_id'];
            }
            return $this->success([$return]);

        } catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }

    }

    /**
     * 获取合成列表
     * @param Request $request
     * @param Queue $queue
     * @param SaasProjectsRepository $repoWorks
     * @param SaasProductsRepository $repoGoods
     * @param SaasProjectsOrderTempRepository $repoPrjTemp
     * @param SaasProductsSkuRepository $repoSku
     * @param SaasOrderProductsRepository $repoOrderItem
     * @return array
     */
    public function getCompoundQueueList(Request $request,Queue $queue, SaasProjectsRepository $repoWorks,
         SaasProductsRepository $repoGoods,SaasProjectsOrderTempRepository $repoPrjTemp,
         SaasProductsSkuRepository $repoSku, SaasOrderProductsRepository $repoOrderItem)
    {
        try {
            $params = $request->all();
            $list = $queue->getCompoundQueue($params);

            $return = [];
            foreach ($list['list'] as $k => $v) {

                //查询作品数据
                $worksInfo = $repoWorks->getRow(['prj_id' => $v['works_id']]);

                //查询商品信息
                $goodsInfo = $repoGoods->getRow(['prod_id' => $worksInfo['prod_id']], ['prod_name']);
                //查询商品图片
                $photoInfo = app(Info::class)->getGoodsPhotosList($worksInfo['prod_id']);
                $goodsInfo['main_thumb'] = $photoInfo['cover'];
                //查询货品信息
                $skuInfo = $repoSku->getRow(['prod_sku_id' => $worksInfo['sku_id']], ['prod_sku_sn', 'prod_process_code','prod_supplier_sn']);

                //作品临时信息
                $prjTempInfo = $repoPrjTemp->getRow(['prj_id' => $v['works_id']], ['user_id','user_type', 'ord_quantity']);

                //订单详情
                $itemInfo = $repoOrderItem->getRow(['ord_prod_id' => $v['order_prod_id']], ['prod_num']);

                if ($prjTempInfo['user_type'] == CHANEL_TERMINAL_AGENT) {
                    $agentId = $prjTempInfo['user_id'];
                } else {
                    $agentId = ZERO;
                }
                if (empty($goodsInfo ['prod_name'])) {
                    Helper::apiThrowException("40014", __FILE__ . __LINE__);
                }
                if (empty($worksInfo)) {
                    Helper::apiThrowException("60001", __FILE__ . __LINE__);
                }
                $return['list'][$k]['qid'] = $v['comp_queue_id'];
                $return['list'][$k]['project_sn'] = $v['project_sn'];
                $return['list'][$k]['order_id'] = $v['order_no'];
                $return['list'][$k]['work_id'] = $v['works_id'];
                $return['list'][$k]['work_createtime'] = $worksInfo['created_at'];
                $return['list'][$k]['goods_name'] = $goodsInfo['prod_name'];
                $return['list'][$k]['goods_id'] = $worksInfo['prod_id'];
                $return['list'][$k]['product_id'] = $worksInfo['sku_id'];
                $return['list'][$k]['sp_id'] = $worksInfo['mch_id'];
                $return['list'][$k]['agent_id'] = $agentId;
                $return['list'][$k]['work_name'] = $worksInfo['prj_name'];
                $return['list'][$k]['queue_createtime'] = $v['created_at'];
                $return['list'][$k]['queue_confirmtime'] = $v['created_at'];
                $return['list'][$k]['goods_thumb_url'] = config('common.static_url') . '/' . $goodsInfo['main_thumb'];
                $return['list'][$k]['work_flag'] = 2;
                $return['list'][$k]['quantity'] = $itemInfo['prod_num'];
                $return['list'][$k]['factory_code'] = $skuInfo['prod_sku_sn'];
                $return['list'][$k]['factory_pro_name'] = $skuInfo['prod_supplier_sn'];
                $return['list'][$k]['erp_sp_name'] = app(Factory::class)->getErpName($agentId,$worksInfo['mch_id']);
                //商业印刷返回商业印刷的作品id
                if(isset($worksInfo['coml_works_id']) && !empty($worksInfo['coml_works_id'])){
                    $return['list'][$k]['coml_works_id'] = $worksInfo['coml_works_id'];
                }

            }
            $return['remain_count'] = $list['count'];
            return $this->success([$return]);
        }catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    /**
     * 更新合成状态
     * @param Request $request
     * @param SaasCompoundQueueRepository $repoQueue
     * @param SaasOrdersRepository $order
     * @param SaasOrderProduceQueueRepository $produceQueue
     * @param SaasOrderProductsRepository $orderProducts
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCompoundQueue(Request $request, SaasCompoundQueueRepository $repoQueue,
        SaasOrdersRepository $order, SaasOrderProduceQueueRepository $produceQueue, SaasOrderProductsRepository $orderProducts)
    {
        try {
            $qid =      $request->input('qid');
            $state =   $request->input('state');
            $filePath = $request->input('save_paths');
            $errMsg   = $request->input('error_msg');
            if (empty($qid) || empty($state)) {
                Helper::apiThrowException("10022", __FILE__ . __LINE__);
            }
            $time = time();
            switch ($state){
                case 'ready' :
                    $status = 'ready';
                    break;
                case 'progress' :
                    $status = 'progress';
                    $info['start_time'] = $time;
                    break;
                case 'finish' :
                    $status = 'finish';
                    $info['end_time'] = $time;
                    break;
                case 'error':
                    $status = 'error';
                    break;
                default :  $status = 'error';
                    break;
            }
            $info['error_msg'] = $errMsg;
            $info['timeline'] = $time;
            $info['comp_queue_status'] = $status;
            if($status == 'finish') {
                $info['comp_queue_file_info'] = $filePath;
            }

            $ret = $repoQueue->update(['comp_queue_id' => $qid], $info);
            if($ret && $status == 'finish') {

                //订单是否全部合成
                $queueInfo = $repoQueue->getRow(['comp_queue_id' => $qid]);
                $orderNo = $queueInfo['order_no'];

                $orderProducts->update(['ord_prj_item_no' => $queueInfo['project_sn']], ['pro_handel_type' => WORKS_HANDEL_TYPE_PROCESSED]);

                $noCreate = ['prepare','ready','progress', 'error'];
                $list = $repoQueue->getRows(['order_no'=>$orderNo, 'comp_queue_status' => $noCreate], 'comp_queue_id')->toArray();
                //全部合成完成
                if (empty($list)) {
                    $orderInfo = $order->getRow(['order_no' => $orderNo]);
                    $produceQueue->update(['order_id'=> $orderInfo['order_id']], ['produce_queue_status' => 'ready']);
                }
            }

            return $this->success([[]]);

        }catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }

    }

    /**
     * 保存合成配置信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveCompoundSettings(Request $request)
    {
        try {
            $settingInfo = json_decode($request->setting_infos, true);
            if (empty($settingInfo)) {
                Helper::apiThrowException("10022",__FILE__.__LINE__);
            }

            $mongo = new Mongo();
            foreach ($settingInfo as $k => $v) {
                //是否存在记录
                $info = $mongo->select('compound_setting', ['goods_id' => $v['goods_id']]);

                if (!empty($info)) {  //更新记录
                    $mongo->update('compound_setting', ['info' => $v['info']], ['goods_id' => $v['goods_id']]);
                } else {  //添加记录
                    $mongo->insert('compound_setting', $v);
                }
            }
            return $this->success([[]]);
        }catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    /**
     * 获取合成配置
     * @param Request $request
     * @param SaasProductsRepository $repoGoods
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompoundSettings(Request $request, SaasProductsRepository $repoGoods)
    {
        try {
            $goodsId = $request->input('goods_id');
            $mchId = $request->input('sp_id');

            if (empty($goodsId) || empty($mchId)) {
                Helper::apiThrowException("10022", __FILE__ . __LINE__);
            }

            $mchId = $mchId == -1 ? 0 : $mchId;

            $where = [];
            if($goodsId== -1) {
                if (!empty($mchId))
                    $where = ['mch_id' => $mchId];
            } else {
                $where = ['mch_id' => $mchId, 'prod_id' => $goodsId];
            }
            $list = $repoGoods->getRows($where, 'prod_id')->toArray();


            $ids = array_column($list,'prod_id');

            $return = [];
            //获取商品配置
            $ids = array_values($ids);
            $i = 0;
            foreach ($ids as $k =>$id) {

                $mongo = new Mongo();
                $info = $mongo->select('compound_setting', ['goods_id' => strval($id)]);

                if(isset($info[0]['info'])) {
                    unset($info[0]['_id']);
                    $return['setting_infos'][$i] = $info[0];
                    $i++;
                }

            }

            return $this->success([$return]);

        }catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    /**
     *
     * @param Request $request
     * @return array
     */
    public function syncCmsSetting(Request $request)
    {
        $goodsId = $request->input('goods_id');
        if (empty($goodsId)) {
            Helper::apiThrowException("10022", __FILE__ . __LINE__);
        }
        $return = app(Info::class)->syncCmsCompoundSettingToOms($goodsId);
        $data = [
            'result' => $return
        ];
        return $this->success([$data]);
    }
}