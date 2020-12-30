<?php
namespace App\Http\Controllers\Api\Editor;

use App\Exceptions\CommonException;
use App\Repositories\SaasNewSpDownloadQueueRepository;
use App\Repositories\SaasNewSuppliersOrderRepository;
use App\Repositories\SaasOrderProductsRepository;
use App\Repositories\SaasProductsSkuRepository;
use App\Repositories\SaasSpDownloadQueueRepository;
use App\Repositories\SaasSuppliersRepository;
use App\Repositories\ScmAdminRepository;
use App\Services\Factory;
use Illuminate\Http\Request;

/**
 * 下载器相关接口
 *
 * 供货商登录、下载等接口
 * @author: david
 * @version: 1.0
 * @date: 2020/6/8
 */
class SupplierController extends BaseController
{
    /**
     * 供货商登录
     * @param Request $request
     * @param ScmAdminRepository $scm
     * @param SaasSuppliersRepository $supplier
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request,ScmAdminRepository $scm, SaasSuppliersRepository $supplier)
    {
        try {

            $account = $request->input('account');
            $pwd     = $request->input('pwd');


            //获取账户信息
            $info = $scm->getRow(['scm_adm_username'=>$account]);
            if(empty ($info)) {
                return $this->success([['result'=>ZERO, 'reason'=> '', 'info'=> '']]);
            }else{
                $sup_info = $supplier->getById($info['sp_id']);
            }

            //判断账号密码是否可用
            if($info['scm_adm_password'] != md5($pwd.$info['scm_adm_salt'])) {
                return $this->success([['result'=>ZERO, 'reason'=> '账号或密码错误', 'info'=> '']]);
            }

            $sp_info = [
                'sp_id' => $info['mch_id'],
                'pd_id' => $info['sp_id'],
                'pd_name' => $sup_info['sup_name'],
                'logo_url' => $info['scm_adm_avatar']
            ];
            return $this->success([['result'=>ONE, 'reason' => '', 'info' =>$sp_info ]]);
        } catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    /**
     * 获取待下载队列记录
     * @param Request $request
     * @param SaasSuppliersRepository $supplier
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDownloadQueues(Request $request, SaasSuppliersRepository $supplier)
    {
        try {
            $mid  = $request->input('sp_id');
            $sid  = $request->input('pd_id');
            $size = $request->input('size');
            $newSpOrderRepository = app(SaasNewSpDownloadQueueRepository::class);

            $queue_info = $supplier->getDownloadQueues($mid,$sid,$size);

            $return['list'] = [];
            foreach($queue_info['list'] as $k=>$v){
                $return['list'][$k]['qid'] = $v->new_sp_down_queue_id;
                $return['list'][$k]['project_sn'] = $v->project_sn;
                $return['list'][$k]['order_id'] = $v->order_no;
                $return['list'][$k]['work_id'] = $v->work_id;
                $return['list'][$k]['work_createtime'] = 0;
                $return['list'][$k]['queue_createtime'] = $v->created_at;
                $return['list'][$k]['goods_name'] = $v->prod_name;
                $return['list'][$k]['goods_id'] = $v->prod_id;
                $return['list'][$k]['download_url'] = $newSpOrderRepository->getSpFileUrl($v->project_sn,$v->path,$v->filename,$v->service_id);

                $erpName = '长荣云印';
                $skuId= $v->sku_id;
                $skuInfo = app(SaasProductsSkuRepository::class)->getRow(['prod_sku_id' => $skuId], ['prod_supplier_sn']);

                $orderItemInfo = app(SaasOrderProductsRepository::class)->getRow(['ord_prod_id' => $v->ord_prod_id], ['prod_num', 'prod_pages']);

                if (empty($orderItemInfo)) {
                    $spItenInfo = app(SaasNewSuppliersOrderRepository::class)->getRow(['ord_prj_no' => $v->project_sn], ['sp_num', 'new_sp_pages']);
                }

                $info = [
                    'erp_name'  =>$erpName,
                    'order_no'  => $v->order_no,
                    'factory_code' =>$skuInfo['prod_supplier_sn'],
                    'project_sn'   => $v->project_sn,
                    'quantity'     => $orderItemInfo['prod_num'] ??$spItenInfo['sp_num'] ,
                    'page_count'   => $orderItemInfo['prod_pages']??$spItenInfo['new_sp_pages'],

                ];

                if (in_array($v->prod_cate_uid,config('goods.add_one_cate'))) {
                    $info['page_count'] = $info['page_count']+1;
                }

                $path = app(Factory::class)->generateDirName($v->prod_id, $info);

                if ($v->filetype == GOODS_SIZE_TYPE_COVER) {
                    $isCover = 1;
                } else {
                    $isCover = 0;
                }

                $fileName = app(Factory::class)->generateFileName($v->prod_id, $info,$isCover);
                $outputType = app(Factory::class)->getGoodsOutputType($v->prod_id);

                $return['list'][$k]['save_path'] = $path.$fileName.'.'.$outputType;

            }
            return $this->success([['remain_count'=>$queue_info['total'], 'list' => $return['list']]]);

        }catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }

    }


    /**
     *  更新下载队列记录状态
     * @param Request $request
     * @param SaasSuppliersRepository $supplier
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateDownloadQueue(Request $request, SaasNewSpDownloadQueueRepository $spDownQueue)
    {
        try {
            $qid     = $request->input('qid');
            $status  = $request->input('state');
            $msg     = $request->input('error_msg');

            $data =[
                'new_sp_down_queue_id' => $qid,
                'download_status' => $status,
                'err_msg'=>$msg
            ];
             $ret = $spDownQueue->updateQueueStatus($data);

            if($ret['code']==ONE){
                return $this->success([['success'=>'true', "result"=>[]]]);
            }else{
                return $this->error([['error'=>$ret['code'], "result"=>$ret['msg']]]);
            }

        }catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }

    }




}