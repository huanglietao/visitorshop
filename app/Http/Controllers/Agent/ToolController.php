<?php
namespace App\Http\Controllers\Agent;

use App\Exceptions\CommonException;
use App\Http\Controllers\Agent\BaseController;
use App\Repositories\SaasOrdersRepository;
use App\Repositories\SaasOrderSyncQueueRepository;
use App\Services\Helper;
use App\Services\Orders\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;


/**
 * 客服小工具
 * @author: cjx
 * @version: 1.0
 * @date: 2020-07-22
 */
class ToolController extends BaseController
{
    protected $modules = 'sys';             //当前控制器所属模块
    protected $sysId = 'agent';             //当前控制器所属模块
    protected $merchantID = "";
    protected $noNeedRight = ['*'];


    public function __construct(SaasOrderSyncQueueRepository $orderSyncQueueRepository,SaasOrdersRepository $ordersRepository)
    {
        parent::__construct();
        $this->orderSyncQueueRepository = $orderSyncQueueRepository;
        $this->ordersRepository = $ordersRepository;
    }

    public function index()
    {
        return view('agent.tool');
    }

    //撤销生产状态
    public function revokeProduct(Request $request)
    {
        try{
            $order_no = $request->post('order_no');
            $info = $this->ordersRepository->getOrderInfo('',$order_no);
            if(empty($info)){
                //该订单记录不存在
                Helper::EasyThrowException(70030,__FILE__.__LINE__);
            }

            //记录订单操作日志
            $platform = config('common.sys_abbreviation')['agent'];
            $ord_log_data = [
                'ord_id'        =>      $info['order_id'],
                'operater'      =>      '客服操作',
                'platform'      =>      $platform,
                'action'        =>      '撤销生产状态',
                'note'          =>      $platform.'【客服小工具】操作订单号【'.$info['order_no'].'】撤销生产状态',
            ];
            $this->ordersRepository->recordOrderLog($ord_log_data);

            $res = app(Status::class)->updateToWaitProduce($info['order_id']);

            if($res){
                return $this->jsonSuccess('撤销成功');
            }
        }catch (\Exception $e){
            return $this->jsonFailed($e->getMessage());
        }
    }

    //新增可二次同步标识
    public function synchronization(Request $request)
    {
        try{
            $tb_order_no = $request->post('tb_order_no');
            $is_order_no = $this->orderSyncQueueRepository->getRow(['outer_order_no'=>$tb_order_no]);

            if(empty($is_order_no)){
                //订单号不存在
                Helper::EasyThrowException(70101,__FILE__.__LINE__);
            }

            $is_set = Redis::get("tb".$tb_order_no);
            if(!empty($is_set)){
                return $this->jsonFailed('该订单号已标识过二次同步');
            }else{
                //redis同步标识保存30天
                Redis::setex( 'tb'.$tb_order_no , 2592000 , 1);
                return $this->jsonSuccess('操作成功');
            }

        }catch (\Exception $e){
            return $this->jsonFailed($e->getMessage());
        }
    }

}