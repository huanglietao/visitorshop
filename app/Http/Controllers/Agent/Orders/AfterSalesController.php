<?php
namespace App\Http\Controllers\Agent\Orders;

use App\Http\Controllers\Agent\BaseController;
use App\Repositories\SaasOrderServiceReasonRepository;
use App\Repositories\SaasOrdersRepository;
use App\Repositories\SaasServiceRepository;
use Illuminate\Http\Request;


/**
 * 分销订单售后列表
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020/04/15
 */

class AfterSalesController extends BaseController
{
    protected $viewPath = 'agent.orders.aftersales';  //当前控制器所的view所在的目录
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
        $job_status_list = config('order.service_status');
        return view("agent.orders.aftersales.index",['pageLimit'=>$pageLimit,'jobStatusList'=>$job_status_list]);
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
    public function form(Request $request)
    {
        try {
            if($request->ajax())
            {
                $row = $this->repositories->getById($request->input('id'));

                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row,'reasonList'=>$this->reasonList,'parentList'=>$this->reasonParentList]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("agent.orders.aftersales._form");
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
                $ret = $this->repositories->save($data,config('common.sys_abbreviation')['agent'],session("admin")['dms_adm_username'],session("admin")['dms_adm_id']);

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


    //售后撤回
    public function withdraw(Request $request)
    {
        $job_id = $request->id;
        try {
            try{
                if($request->ajax())
                {
                    \DB::beginTransaction();

                    $res = $this->repositories->withdraw($job_id);

                    if($res){
                        \DB::commit();
                        return $this->jsonSuccess('');
                    }
                }else{
                    return view("agent.orders.aftersales._form");
                }
            }catch (\Exception $exception){
                \DB::rollBack();
                if(!empty($exception->getMessage())){
                    return $this->jsonFailed($exception->getMessage());
                }else{
                    app(\App\Services\Exception::class)->throwException('70045',__FILE__.__LINE__);
                }
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

    }

    public function orderGoods()
    {
        $htmlContents = $this->renderHtml('agent.orders.aftersales._order_goods');
        return $this->jsonSuccess(['html' => $htmlContents]);
    }

    /**
     * 删除记录(软删除)
     * @param Request $request
     * @return bool
     */
    protected function delete(Request $request)
    {
        $ret = $this->repositories->deleteJob($request->id,session("admin")['dms_adm_username'],config('common.sys_abbreviation')['agent'],session("admin")["dms_adm_id"]);
        if($ret) {
            return $this->jsonSuccess(['']);
        } else {
            return $this->jsonFailed("");
        }
    }

}