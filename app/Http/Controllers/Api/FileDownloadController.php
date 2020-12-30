<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\CommonException;
use App\Repositories\SaasDownloadQueueRepository;
use App\Services\Helper;
use Illuminate\Http\Request;

/**
 * pdf文件下载接口
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020/05/21
 */
class FileDownloadController extends BaseController
{

    /**
     * 获取待下载的队列
     * @param Request $request
     * @param $mch_id 商户id，size 条数，service_id 合成服务器标识
     * @return \Illuminate\Http\JsonResponse
     */
    public function getQueueList(Request $request,SaasDownloadQueueRepository $downloadQueueRepository)
    {
        try{
            try {
                \DB::beginTransaction();
                $mch_id = $request->input('mch_id');
                $size = $request->input('size');
                $service_id = $request->input('service_id');

                $list = $downloadQueueRepository->getDownloadList($mch_id, $size, $service_id);
                if($list){
                    \DB::commit();
                    return $this->success([$list]);
                }
            } catch (\Exception $e) {
                \DB::rollBack();
                if(!empty($e->getMessage())){
                    return $this->error($e->getCode(),$e->getMessage());
                }else{
                    //文件下载出错
                    app(\App\Services\Exception::class)->throwException('13001',__FILE__.__LINE__);
                }
            }
        }catch (CommonException $exception){
            return $this->error($exception->getCode(),$exception->getMessage());
        }
    }

    /**
     * 更新文件下载队列状态
     * @param Request $request
     * @param $qid 队列id，$status 状态
     * @return \Illuminate\Http\JsonResponse
     */
    public function UpdateQueues(Request $request,SaasDownloadQueueRepository $downloadQueueRepository)
    {

        try{
            try {
                $qids = $request->input('qids');
                $status = $request->input('status');

                if(empty($qids) || empty($status)){
                    Helper::apiThrowException("10022", __FILE__.__LINE__);
                }

                \DB::beginTransaction();
                $res = $downloadQueueRepository->updateQueue($qids, $status);
                if($res){
                    \DB::commit();
                    return $this->success([]);
                }

            } catch (\Exception $e) {
                \DB::rollBack();
                if(!empty($e->getMessage())){
                    return $this->error($e->getCode(),$e->getMessage());
                }else{
                    //文件状态更新出错
                    app(\App\Services\Exception::class)->throwException('13002',__FILE__.__LINE__);
                }
            }
        }catch (CommonException $exception){
            return $this->error($exception->getCode(),$exception->getMessage());
        }
    }
}
