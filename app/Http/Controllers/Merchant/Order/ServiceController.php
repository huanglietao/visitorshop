<?php
namespace App\Http\Controllers\Merchant\Order;

use App\Exceptions\CommonException;
use App\Http\Controllers\Merchant\BaseController;
use App\Repositories\SaasOrderServiceReasonRepository;
use App\Repositories\SaasOrdersRepository;
use App\Repositories\SaasServiceRepository;
use App\Services\Helper;
use Illuminate\Http\Request;
use App\Services\Exception;


/**
 * 商户订单售后列表
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020/04/15
 */

class ServiceController extends BaseController
{

    protected $viewPath = 'merchant.order.service';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块

    public function __construct(SaasServiceRepository $repository,SaasOrdersRepository $ordersRepository)
    {
        parent::__construct();
        $this->repositories = $repository;
        $this->ordRepositories = $ordersRepository;
        $this->reasonList = $this->repositories->getServiceReason();
        $this->reasonParentList = app(SaasOrderServiceReasonRepository::class)->getType();
    }

    //售后订单列表展示页面
    public function index()
    {
        $setting = $this->getSetting();
        $pageLimit = $setting['default_pages_limit'];
        return view("merchant.order.service.index",['pageLimit'=>$pageLimit]);
    }

    //ajax方式获取列表
    public function table(Request $request)
    {
        $inputs = $request->all();
        $list = $this->repositories->getTableList($inputs)->toArray();
        $htmlContents = $this->renderHtml('',['list' =>$list['data']]);
        $total = $list['total'];

        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
    }

    /**
     * 通用表单展示
     * @param Request $request
     * @return mixed
     */
    protected function form(Request $request)
    {
        try {
            if($request->ajax())
            {
                $row = $this->repositories->getById($request->input('id'));

                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row,'reasonList'=>$this->reasonList,'parentList'=>$this->reasonParentList]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("merchant.order.service._form");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

    }

    //添加、编辑操作
    public function save(Request $request)
    {
        try{
            try{
                \DB::beginTransaction();

                $param = $request->all();
                $param['mch_id'] = session("admin")['mch_id'];
                unset($param['_token']);

                $data = $this->repositories->getSaveData($param);
                $ret = $this->repositories->save($data,config('common.sys_abbreviation')['merchant'],session("admin")['oms_adm_username'],session("admin")['oms_adm_id']);

                if ($ret) {
                    \DB::commit();
                    return $this->jsonSuccess('');
                }
            }catch (\Exception $exception){
                \DB::rollBack();
                if(!empty($exception->getMessage())){
                    return $this->jsonFailed($exception->getMessage());
                }else{
                    app(\App\Services\Exception::class)->throwException('70041',__FILE__.__LINE__);
                }
            }
        }catch (CommonException $e){
            return $this->jsonFailed($e->getMessage());
        }

    }

    //售后详情
    public function detail(Request $request)
    {
        $id = $request->id;
        try {
            $data = $this->repositories->serviceInfo($id);

            return view("merchant.order.service.detail",['data'=>$data,'reasonList'=>$this->reasonList]);
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }
    }

    //售后处理
    public function handle(Request $request)
    {
        $id = $request->id;
        try {
            if($request->post())
            {
                $param = $request->all();
                $param['job_id'] = $id;
                unset($param['_token']);

                $res = $this->repositories->serviceHandle($param);

                if($res){
                    return $this->jsonSuccess('操作成功');
                }
            }else{
                $data = $this->repositories->serviceInfo($id);
                return view("merchant.order.service.handle",['data'=>$data,'reasonList'=>$this->reasonList,'parentList'=>$this->reasonParentList]);
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

    }

    //换货单
    public function exchange(Request $request)
    {
        $job_id = $request->id;
        try {
            try{
                if($request->post())
                {
                    \DB::beginTransaction();

                    $param = $request->all();

                    if(!isset($param['exchange_item']) || $param['exchange_item'][0] == ''){
                        return $this->jsonFailed('请选择换货商品');
                    }

                    unset($param['_token']);
                    $param['job_id'] = $job_id;

                    $res = $this->repositories->exchangeOrder($param);
                    if($res){
                        \DB::commit();
                        return $this->jsonSuccess('换货单创建成功',201);
                    }
                }else{
                    $data = $this->repositories->serviceInfo($job_id);
                    $htmlContents = $this->renderHtml('merchant.order.service.exchange',['data'=>$data]);
                    return $this->jsonSuccess(['html' => $htmlContents]);
                }
            }catch (\Exception $exception){
                \DB::rollBack();
                if(!empty($exception->getMessage())){
                    return $this->jsonFailed($exception->getMessage());
                }else{
                    app(\App\Services\Exception::class)->throwException('70038',__FILE__.__LINE__,'','','',['job_id'=>$job_id]);
                }
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

    }


    //获取订单金额、状态判断
    public function getAmount(Request $request)
    {
        $order_no = $request->post("order_no");

        $data = $this->ordRepositories->getOrderInfo('',$order_no);
        if(!empty($data)){

            //检查订单状态
            if($data['order_status'] == ORDER_STATUS_WAIT_RECEIVE || $data['order_status'] == ORDER_STATUS_FINISH){
                //待收货、已发货或者交易完成、已收货状态才可售后
                $res['order_real_total'] = $data['order_real_total'];
                $res['order_exp_fee'] = $data['order_exp_fee'];
                return $this->jsonSuccess($res);
            }else{
                return $this->jsonFailed(__('exception.order_operate_job_fail'));
            }
        }else{
            return $this->jsonFailed(__('exception.order_record_exist'));
        }

    }

    //审核归档
    public function review(Request $request)
    {
        $job_id = $request->post('job_id');
        try {
            try{
                \DB::beginTransaction();

                $param = $request->all();
                $param['job_id'] = $job_id;
                $ret = $this->repositories->examine($param,config('common.sys_abbreviation')['merchant'],session("admin")['oms_adm_username']);

                if ($ret) {
                    \DB::commit();
                    return $this->jsonSuccess('审核归档成功');
                }
            }catch (\Exception $exception){
                \DB::rollBack();
                if(!empty($exception->getMessage())){
                    return $this->jsonFailed($exception->getMessage());
                }else{
                    app(\App\Services\Exception::class)->throwException('70090',__FILE__.__LINE__,'','','',['job_id'=>$job_id]);
                }
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }
    }

    //售后工单导出
    public function jobExport(Request $request)
    {
        try{
            $param = $request->data;
            $this->repositories->export(json_decode($param,true));

        }catch (CommonException $e){
            return $this->jsonFailed($e->getMessage());
        }
    }

    /**
     * 返回成功的json
     * @param $data
     * @return array
     */
    protected function jsonSuccess($data,$status=200)
    {
        return response()->json(['status' =>$status , 'success' => 'true', 'data' => $data]);
    }

    /**
     * 删除记录(软删除)
     * @param Request $request
     * @return bool
     */
    protected function delete(Request $request)
    {
        $ret = $this->repositories->deleteJob($request->id,session("admin")["oms_adm_username"],config('common.sys_abbreviation')['merchant'],session("admin")["oms_adm_id"]);
        if($ret) {
            return $this->jsonSuccess(['']);
        } else {
            return $this->jsonFailed("");
        }
    }
}