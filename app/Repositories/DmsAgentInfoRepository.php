<?php
namespace App\Repositories;
use App\Models\DmsAgentInfo;
use App\Models\DmsMerchantAccount;
use App\Models\OmsMerchantInfo;
use App\Models\SaasCustomerBalanceLog;
use App\Models\SaasOrders;
use App\Services\Helper;
use Illuminate\Support\Facades\DB;

/**
 * 仓库模板
 * 仓库模板
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/04/15
 */
class DmsAgentInfoRepository extends BaseRepository
{
    protected $isCache = false; //是否使用缓存

    public function __construct(DmsAgentInfo $model,SaasCustomerBalanceLog $balanceLogModel,OmsMerchantInfo $merchantInfo,DmsMerchantAccount $merchantAccount,
                                SaasOrders $orders)
    {
        $this->agent_id = isset(session("admin")['agent_info_id']) ? session("admin")['agent_info_id'] : '';
        $this->model =$model;
        $this->balanceLogModel =$balanceLogModel;
        $this->mchInfoModel =$merchantInfo;
        $this->dmsAccountModel =$merchantAccount;
        $this->orders =$orders;
    }

    /**
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getTableList($where=null, $order=null)
    {
        $limit = isset($where['limit']) ? $where['limit']:config('common.page_limit');  //这个10取配置里的
        $where = $this->parseWhere($where);

        //order 必须以 'id desc'这种方式传入.
        $orderBy = [];
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }

        //查询账号
        $where_info = [];
        if(isset($where['mch_name'])){
            $a_id = $this->dmsAccountModel->where(['dms_adm_username'=>$where['mch_name']])->value('agent_info_id');
            $where_info['agent_info_id'] = !empty($a_id) ? $a_id : PUBLIC_NO;
            unset($where['mch_name']);
        }

        $query = $this->model->orWhereHas(
            'account',function($query) use ($where_info) {
            if (!empty($where_info)) {
                return $query->where($where_info);
            }
        })->with(['account']);;


        //店铺名称
        if(isset($where['agent_name']) && !empty($where['agent_name'])){
            $query = $query->where('agent_name', 'like', '%'.$where['agent_name'].'%');
            unset($where['agent_name']);
        }
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

        return $list;
    }


    /**
     * 新增/修改
     * @param $data
     * @return boolean
     */
    public function save($data)
    {
        if(empty($data['agent_info_id'])) {
            unset($data['agent_info_id']);
            $data['created_at'] = time();
            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;
        } else {
            $priKeyValue = $data['agent_info_id'];
            unset($data['agent_info_id']);
            $data['updated_at'] = time();
            $ret =$this->model->where('agent_info_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
         if (isset($this->isCache)&&$this->isCache === true){
             $table_name = $this->model->getTable();
             $redis = app('redis.connection');
             $data['agent_info_id'] = $priKeyValue;
             //将数据写入缓存
             $redis->set($table_name.'_'.$priKeyValue , json_encode($data));
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

        //删除缓存数据
        if (isset($this->isCache)&&$this->isCache === true){
            $table_name = $this->model->getTable();
            $redis = app('redis.connection');
            $data['agent_info_id'] = $id;
            $redis->del($table_name.'_'.$id);
        }

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }

    /**
     * 获取分销商等级id
     * @param $dms_adm_id
     * @return $cust_lv_id
     */
    public function getCustLvId($agent_info_id)
    {
        $agent_info = $this->model->where('agent_info_id',$agent_info_id)->select('cust_lv_id')->get();
        $cust_lv_id = json_decode($agent_info,true)[0]['cust_lv_id'];
        return $cust_lv_id;
    }



    /**
     * 存放数据到资金变动表
     * @param
     * @return
     */
    public function balanceLogSave($data)
    {
        $data['created_at'] = time();
        $ret = $this->balanceLogModel->insertGetId($data);
        return $ret;
    }

    /**
     * 通过分销id取合作编号
     * @param $mch_id $agent_id
     * @return array
     */
    public function getCodeById($agent_id)
    {
        $agent_info = $this->model->where('agent_info_id',$agent_id)->select('mch_id','agent_code')->first();
        if(empty($agent_info['agent_code'])){
            $mch_info = $this->mchInfoModel->where('mch_id',$agent_info['mch_id'])->select('mch_code')->first();
            return $mch_info['mch_code'];
        }else{
            return $agent_info['agent_code'];
        }
    }

    /**
     * 通过合作编号取商户mch_id和分销商agent_id
     * @param $code
     * @return array
     */
    public function getMchIdAndAgentIdByCode($code)
    {
        $data = $this->getRow(['agent_code'=>$code],['agent_info_id','mch_id']);
        return $data;
    }

    /**
     * 根据mch_id获取分销商
     * Date: 2020/6/17
     * Time: 15:52
     * @param $mch_id
     * @return array
     */
    public function getAgentList($mch_id,$is_choose_data=null)
    {
        $List = [];
        $agentInfo = $this->model->where(['mch_id'=>$mch_id])->select('agent_info_id','agent_name')->get()->toArray();
        $helper = app(Helper::class);
        $agentList = $helper->ListToKV('agent_info_id','agent_name',$agentInfo);
        if(empty($is_choose_data)){
            $List = $helper->getChooseSelectData($agentList);
        }else{
            return $agentList;
        }
        return $List;
    }

    //获取分销大客户的订单量
    public function getAgentSaleOrder()
    {
        $agentInfoModel = app(DmsAgentInfo::class);
        $orderModel = app(SaasOrders::class);
        $saleChannleRepository = app(SaasSalesChanelRepository::class);
        $agentType = config('agent.key_customers');
        //获取大客户店铺类型的分销商
        $agentInfo = $agentInfoModel->whereIn('agent_type',$agentType)->select('agent_info_id','agent_name')->get()->toArray();
        //获取分销渠道id
        $agentChanleId = $saleChannleRepository->getAgentChannleId();

       //获取各个分销商的订单销量
        $saleOrder = [];
        foreach ($agentInfo as $k => $v){
            //获取该分销商的订单销量
            $saleCount = $orderModel->where(['user_id'=>$v['agent_info_id'],'cha_id' => $agentChanleId])->whereNotIn('order_status',[ORDER_STATUS_CANCEL])->count();
            $saleOrder[$v['agent_info_id']]['sale_num'] = $saleCount;
            $saleOrder[$v['agent_info_id']]['agent_name'] = $v['agent_name'];
        }
        $saleOrder = $this->arraySort($saleOrder,'sale_num','desc');
        $saleOrder = array_values($saleOrder);
        return $saleOrder;
    }

    //查看邀请码是否存在
    public function checkInviter($inviter)
    {
        $isExist = $this->model->where(['inviter_code'=>$inviter])->first();
        return $isExist;
    }

    //DMS注册时邀请码生成
    public function getInviterCode()
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code =  substr(str_shuffle(str_repeat($pool, ceil(8 / strlen($pool)))), 0, 5);
        $isExist = $this->model->where(['inviter_code'=>$code])->count();
        if($isExist){
            $this->getInviterCode();
        }
        return $code;
    }

    /**
     * author：cjx
     * date：2020-08-07
     * 推广列表
     */
    public function getPomotersList($where=null, $order=null)
    {
        $limit = isset($where['limit']) ? $where['limit']:config('common.page_limit');  //这个10取配置里的
        $where = $this->parseWhere($where);
        $where['inviter_id'] = $this->agent_id;

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
            $query =  $query->where($where)->select('agent_name','agent_info_id','mch_id','inviter_code','created_at');
        }
        if(!empty($order)) {
            $query =  $query->orderBy($orderBy[0],$orderBy[1]);
        }

        $list = $query->paginate($limit)->toArray();

        foreach ($list['data'] as $k=>$v){
            //订单数
            $list['data'][$k]['order'] = $this->orders->where(['mch_id'=>$v['mch_id'],'user_id'=>$v['agent_info_id']])->count();
            //总金额
            $list['data'][$k]['amount'] = $this->orders->where(['mch_id'=>$v['mch_id'],'user_id'=>$v['agent_info_id']])->sum('order_real_total');
        }
        return $list;
    }

    /**
     * author：cjx
     * date：2020-08-07
     * 获取邀请码、订单总数、订单总金额
     */
    public function getSelfInfo($agent_id=null)
    {
        $agent_id = !empty($agent_id) ? $agent_id : $this->agent_id;
        $data['code'] = $this->model->where(['agent_info_id'=>$agent_id])->value('inviter_code');

        //邀请人
        $list = $this->model->where(['inviter_id'=>$this->agent_id])->select('agent_info_id','mch_id')->get()->toArray();
        $list_mid = array_unique(array_column($list,'mch_id'));
        $list_aid = array_unique(array_column($list,'agent_info_id'));

        //订单总数
        $data['order'] = $this->orders->whereIn('mch_id',$list_mid)->whereIn('user_id',$list_aid)->count();

        //订单总金额
        $data['amount'] = $this->orders->whereIn('mch_id',$list_mid)->whereIn('user_id',$list_aid)->sum('order_real_total');

        return $data;

    }

    /**
     * 获取分销商为被邀请的id还有邀请人的名字
     * Author: LJH
     * Date: 2020/8/11
     * Time: 16:28
     * @param $mch_id
     * @return array
     */
    public function getInfoIds($mch_id)
    {
        $result = [];
        $result['ids'] = [];
        $result['name'] = [];
        $ids = $this->model->where(['mch_id'=>$mch_id])->whereNotIn('inviter_id',[ZERO])->select('agent_info_id','inviter_id')->get()->toArray();
        foreach ($ids as $k=>$v){
            $agent_name = $this->model->where(['agent_info_id'=>$v['inviter_id']])->value('agent_name');
            $result['ids'][] = $v['agent_info_id'];
            $result['name'][$v['agent_info_id']] = $agent_name;
        }
        return $result;
    }




}
