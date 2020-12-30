<?php
namespace App\Repositories;

use App\Models\OmsMerchantAccount;
use App\Models\SaasExchange;
use App\Models\SaasOrderServiceReason;
use App\Models\SaasSalesChanel;
use App\Services\Helper;

/**
 * 换货单仓库
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020/4/26
 */
class SaasExchangeRepository extends BaseRepository
{
    public function __construct(SaasExchange $model, OmsMerchantAccount $merchantAccount,SaasServiceRepository $serviceRepository,SaasSalesChanel $chanel,
                                SaasOrderServiceReason $orderServiceReason)
    {
        $this->mch_id = session("admin")['mch_id'];

        $this->model = $model;
        $this->mchAccountModel = $merchantAccount;
        $this->chanelModel = $chanel;
        $this->ordReasonModel = $orderServiceReason;

        $this->serviceRepository = $serviceRepository;
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
        $where['mch_id'] =$this->mch_id;

        //order 必须以 'id desc'这种方式传入.
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

        foreach ($list as $k=>$v){
            //操作人

            $account_info = $this->mchAccountModel->where('oms_adm_id',$v['admin_id'])->select('oms_adm_username')->first();
            $list[$k]['operater'] = $account_info['oms_adm_username'];

            //换货原因
            $list[$k]['job_reason_text'] = $this->ordReasonModel->where(['service_reason_id'=>$v['bart_reason']])->value('reason');
        }

        return $list;
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

    //换货单详情
    public function exchangeDetail($ord_bart_id)
    {
        $barter_info = $this->getById($ord_bart_id);
        $data = $this->serviceRepository->serviceInfo($barter_info['job_id']);

        //换货单号
        $data['exchange_order_no'] = $barter_info['exchange_order_no'];

        //订单来源
        $chanel_info = $this->chanelModel->where('cha_id',$data['orderInfo']['cha_id'])->select("cha_name")->first();
        $data['orderInfo']['cha_name'] = $chanel_info['cha_name'];

        return $data;
    }
}