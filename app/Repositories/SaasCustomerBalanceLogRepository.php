<?php
namespace App\Repositories;

use App\Models\DmsAgentInfo;
use App\Models\DmsMerchantAccount;
use App\Models\OmsMerchantAccount;
use App\Models\SaasCustomerBalanceLog;
use App\Models\SaasPayment;
use App\Services\Helper;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * 余额变动仓库
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/23
 */
class SaasCustomerBalanceLogRepository extends BaseRepository
{
    protected $mch_id;
    protected $user_id;

    public function __construct(SaasCustomerBalanceLog $model,DmsMerchantAccount $dmsMerchantAccount,
                                OmsMerchantAccount $omsMerchantAccount,SaasPayment $payment)
    {
        $this->mch_id = isset(session("admin")['mch_id']) ? session("admin")['mch_id'] : PUBLIC_CMS_MCH_ID;
        $this->user_id = isset(session("admin")['agent_info_id']) ? session("admin")['agent_info_id'] : '';

        $this->model = $model;
        $this->accountModel = $dmsMerchantAccount;
        $this->omsAccountModel = $omsMerchantAccount;
        $this->payment = $payment;
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
        $where['mch_id'] = $this->mch_id;
        $where['user_id'] = $this->user_id;
        $where['user_type'] = CHANEL_TERMINAL_AGENT; //分销

        if(isset($where['status'])){
            //按资金类型查询
            $fund_status = [
                'ALL'                          =>          0,                //全部(自定义,非常量)
                'FINANCE_INCOME'               =>          FINANCE_INCOME,   //收入
                'FINANCE_EXPEND'               =>          FINANCE_EXPEND,   //支出
            ];
            if($fund_status[$where['status']] != 0){
                $where['cus_balance_type'] = $fund_status[$where['status']];
            }
            unset($where['status']);
        }

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

        /*金额范围搜索 start*/
        $range_flag = 0;
        if(isset($where['amount_min'])) {
            if(isset($where['amount_max'])) {
                $where[] = ['cus_balance_change','>=',$where['amount_min']];
                $where[] = ['cus_balance_change','<=',$where['amount_max']];
                $range_flag = 1;

                unset($where['amount_min']);
                unset($where['amount_max']);
            } else {
                $where[] = ['cus_balance_change','>=',$where['amount_min']];
                $range_flag = 1;
                unset($where['amount_min']);
            }
        }

        if($range_flag == 0 && isset($where['amount_max'])) {
            $where[] = ['cus_balance_change','<=',$where['amount_max']];
            unset($where['amount_max']);
        }
        /*金额范围搜索 end*/

        if(!empty ($where)) {
            $query =  $query->where($where);
        }

        if(!empty($order)) {
            $query =  $query->orderBy($orderBy[0],$orderBy[1]);
        }

        $list = $query->paginate($limit);

        foreach ($list as $k=>$v){
            //操作人
            if($v['operate_type'] == OPERATE_TYPE_ADMIN){
                $account_info = $this->omsAccountModel->where('oms_adm_id',$v['operate_id'])->select('oms_adm_username')->first();
                $list[$k]['operater_name'] = !empty($account_info['oms_adm_username']) ? $account_info['oms_adm_username'] : $v['operater'];
            }else{
                $account_info = $this->accountModel->where('dms_adm_id',$v['operate_id'])->select('dms_adm_username')->first();
                $list[$k]['operater_name'] = !empty($account_info['dms_adm_username']) ? $account_info['dms_adm_username'] : $v['operater'];
            }

        }
//dd($list->toArray());
        return $list;
    }

    //收入支出统计
    public function Statistics()
    {
        //收入
        $income_total_money = 0;
        $income_info = $this->model->where(['mch_id'=>$this->mch_id,'user_id'=>$this->user_id,'cus_balance_type'=>FINANCE_INCOME])->select('cus_balance_change')->get();
        foreach ($income_info as $k=>$v){
            $income_total_money += $v['cus_balance_change'];
        }

        //支出
        $expenditure_total_money = 0;
        $expenditure_info = $this->model->where(['mch_id'=>$this->mch_id,'user_id'=>$this->user_id,'cus_balance_type'=>FINANCE_EXPEND])->select('cus_balance_change')->get();
        foreach ($expenditure_info as $k=>$v){
            $expenditure_total_money += $v['cus_balance_change'];
        }

        return ['income'=>['money'=>$income_total_money,'count'=>count($income_info)],'expenditure'=>['money'=>$expenditure_total_money,'count'=>count($expenditure_info)]];
    }

    //详情
    public function fundDetail($id)
    {
        $info = $this->getById($id);

        //操作人
        if($info['operate_type'] == OPERATE_TYPE_ADMIN){
            $account_info = $this->omsAccountModel->where('oms_adm_id',$info['operate_id'])->select('oms_adm_username')->first();
            $info['operater_name'] = !empty($account_info['oms_adm_username']) ? $account_info['oms_adm_username'] : $info['operater'];
        }else{
            $account_info = $this->accountModel->where('dms_adm_id',$info['operate_id'])->select('dms_adm_username')->first();
            $info['operater_name'] = !empty($account_info['dms_adm_username']) ? $account_info['dms_adm_username'] : $info['operater'];
        }

        return $info;
    }

    //导出处理
    public function export($param)
    {
        $result = $this->getExportData($param)->toArray();

        if(empty($result)){
            echo '暂无记录';
            die;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        //设置sheet的名字  两种方法
        $spreadsheet->getActiveSheet()->setTitle('资金明细导出');

        //设置自动列宽
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setWidth(10);
        $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(40);

        //设置第一行小标题
        $k = 1;
        $sheet->setCellValue('A'.$k, '业务单号');
        $sheet->setCellValue('B'.$k, '发生时间');
        $sheet->setCellValue('C'.$k, '交易类型');
        $sheet->setCellValue('D'.$k, '资金类型');
        $sheet->setCellValue('E'.$k, '关联支付流水号');
        $sheet->setCellValue('F'.$k, '变动金额');
        $sheet->setCellValue('G'.$k, '余额');
        $sheet->setCellValue('H'.$k, '操作人');
        $sheet->setCellValue('I'.$k, '描述');

        $k = 2;
        $fund_type_arr = config('finance.finance_fund_type');
        $change_type_arr = config('finance.finance_fund_change_type');
        foreach ($result as $key => $value) {
            $sheet->setCellValue('A' . $k, "\t".$value['cus_balance_business_no']);
            $sheet->setCellValue('B' . $k, date('Y-m-d H:i:s',$value['created_at']));
            $sheet->setCellValue('C' . $k, $fund_type_arr[$value['cus_balance_type_detail']]);
            $sheet->setCellValue('D' . $k, $change_type_arr[$value['cus_balance_type']]);
            $sheet->setCellValue('E' . $k, "\t".$value['cus_balance_trade_no']);
            $sheet->setCellValue('F' . $k, $value['cus_balance_change']);
            $sheet->setCellValue('G' . $k, $value['cus_balance']);
            $sheet->setCellValue('H' . $k, $value['operater_name']);
            $sheet->setCellValue('I' . $k, $value['remark']);
            $k++;
        }

        $file_name = '资金明细导出'.date('Y-m-d H:i:s',time());
        $file_name = $file_name . ".xlsx";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$file_name.'"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');

    }

    /**
     * 导出订单数据组装
     * @param $param 创建时间
     */
    public function getExportData($where,$order='created_at desc')
    {
        $where = $this->parseWhere($where);
        $where['mch_id'] = $this->mch_id;
        $where['user_id'] = $this->user_id;
        $where['user_type'] = CHANEL_TERMINAL_AGENT; //分销

        if(isset($where['status'])){
            //按资金类型查询
            $fund_status = [
                'ALL'                          =>          0,                //全部(自定义,非常量)
                'FINANCE_INCOME'               =>          FINANCE_INCOME,   //收入
                'FINANCE_EXPEND'               =>          FINANCE_EXPEND,   //支出
            ];
            if($fund_status[$where['status']] != 0){
                $where['cus_balance_type'] = $fund_status[$where['status']];
            }
            unset($where['status']);
        }

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

        /*金额范围搜索 start*/
        $range_flag = 0;
        if(isset($where['amount_min'])) {
            if(isset($where['amount_max'])) {
                $where[] = ['cus_balance_change','>=',$where['amount_min']];
                $where[] = ['cus_balance_change','<=',$where['amount_max']];
                $range_flag = 1;

                unset($where['amount_min']);
                unset($where['amount_max']);
            } else {
                $where[] = ['cus_balance_change','>=',$where['amount_min']];
                $range_flag = 1;
                unset($where['amount_min']);
            }
        }

        if($range_flag == 0 && isset($where['amount_max'])) {
            $where[] = ['cus_balance_change','<=',$where['amount_max']];
            unset($where['amount_max']);
        }
        /*金额范围搜索 end*/

        if(!empty ($where)) {
            $query =  $query->where($where);
        }

        if(!empty($order)) {
            $query =  $query->orderBy($orderBy[0],$orderBy[1]);
        }

        $list = $query->get();

        foreach ($list as $k=>$v){
            //操作人
            if($v['operate_type'] == OPERATE_TYPE_ADMIN){
                $account_info = $this->omsAccountModel->where('oms_adm_id',$v['operate_id'])->select('oms_adm_username')->first();
                $list[$k]['operater_name'] = !empty($account_info['oms_adm_username']) ? $account_info['oms_adm_username'] : $v['operater'];
            }else{
                $account_info = $this->accountModel->where('dms_adm_id',$v['operate_id'])->select('dms_adm_username')->first();
                $list[$k]['operater_name'] = !empty($account_info['dms_adm_username']) ? $account_info['dms_adm_username'] : $v['operater'];
            }

        }
        return $list;
    }



    /**
     * 新增/修改
     * @param $data
     * @return boolean
     */
    public function save($data)
    {
        if(empty($data['cus_balance_id'])) {
            unset($data['cus_balance_id']);
            $data['created_at'] = time();
            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;
        } else {
            $priKeyValue = $data['cus_balance_id'];
            unset($data['cus_balance_id']);
            $data['updated_at'] = time();
            $ret =$this->model->where('cus_balance_id',$priKeyValue)->update($data);
        }
        //判断是否需要更新缓存
        if (isset($this->isCache)&&$this->isCache === true){
            $table_name = $this->model->getTable();
            $redis = app('redis.connection');
            $data['cus_balance_id'] = $priKeyValue;
            //将数据写入缓存
            $redis->set($table_name.'_'.$priKeyValue , json_encode($data));
        }
        return $ret;

    }

    /**
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getMerchantTableList($where=null, $order=null, $flag=null)
    {
        if(empty($flag)){
            $limit = isset($where['limit']) ? $where['limit']:config('common.page_limit');  //这个10取配置里的
        }else{
            $limit = '';
        }

        $where = $this->parseWhere($where);
        $where['mch_id'] = $this->mch_id;
        $where['user_type'] = CHANEL_TERMINAL_AGENT; //分销

        if(isset($where['status'])){
            //按资金类型查询
            $fund_status = [
                'ALL'                          =>          0,                //全部(自定义,非常量)
                'FINANCE_INCOME'               =>          FINANCE_INCOME,   //收入
                'FINANCE_EXPEND'               =>          FINANCE_EXPEND,   //支出
            ];
            if($fund_status[$where['status']] != 0){
                $where['cus_balance_type'] = $fund_status[$where['status']];
            }
            unset($where['status']);
        }

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

        /*金额范围搜索 start*/
        $range_flag = 0;
        if(isset($where['amount_min'])) {
            if(isset($where['amount_max'])) {
                $where[] = ['cus_balance_change','>=',$where['amount_min']];
                $where[] = ['cus_balance_change','<=',$where['amount_max']];
                $range_flag = 1;

                unset($where['amount_min']);
                unset($where['amount_max']);
            } else {
                $where[] = ['cus_balance_change','>=',$where['amount_min']];
                $range_flag = 1;
                unset($where['amount_min']);
            }
        }

        if($range_flag == 0 && isset($where['amount_max'])) {
            $where[] = ['cus_balance_change','<=',$where['amount_max']];
            unset($where['amount_max']);
        }
        /*金额范围搜索 end*/

        if(!empty ($where)) {
            $query =  $query->where($where);
        }

        if(!empty($order)) {
            $query =  $query->orderBy($orderBy[0],$orderBy[1]);
        }

        $list = $query->paginate($limit);

        foreach ($list as $k=>$v){
            //操作人
            if($v['operate_type'] == OPERATE_TYPE_ADMIN){
                $account_info = $this->omsAccountModel->where('oms_adm_id',$v['operate_id'])->select('oms_adm_username')->first();
                $list[$k]['operater_name'] = !empty($account_info['oms_adm_username']) ? $account_info['oms_adm_username'] : $v['operater'];
            }else{
                $account_info = $this->accountModel->where('dms_adm_id',$v['operate_id'])->select('dms_adm_username')->first();
                $list[$k]['operater_name'] = !empty($account_info['dms_adm_username']) ? $account_info['dms_adm_username'] : $v['operater'];
            }

            //支付方式
            $payInfo = $this->payment->where(['pay_id'=>$v['pay_id']])->select('pay_name')->get()->toArray();
            if(empty($payInfo)){
                $list[$k]['pay_name'] = '后台操作';
            }else{
                $list[$k]['pay_name'] = $payInfo[0]['pay_name'];
            }

            //分销商
            $agentInfo = app(DmsAgentInfo::class);
            $agent_info = $agentInfo->where(['agent_info_id'=>$v['user_id'],'mch_id'=>$v['mch_id']])->select('agent_name')->first();
            $list[$k]['agent_name'] = $agent_info['agent_name'];
        }

        return $list;
    }

    //详情
    public function fundAgentDetail($id)
    {
        $info = $this->getById($id);

        //操作人
        if($info['operate_type'] == OPERATE_TYPE_ADMIN){
            $account_info = $this->omsAccountModel->where('oms_adm_id',$info['operate_id'])->select('oms_adm_username')->first();
            $info['operater_name'] = !empty($account_info['oms_adm_username']) ? $account_info['oms_adm_username'] : $info['operater'];
        }else{
            $account_info = $this->accountModel->where('dms_adm_id',$info['operate_id'])->select('dms_adm_username')->first();
            $info['operater_name'] = !empty($account_info['dms_adm_username']) ? $account_info['dms_adm_username'] : $info['operater'];
        }

        //支付方式
        $payInfo = $this->payment->where(['pay_id'=>$info['pay_id']])->select('pay_name')->get()->toArray();
        if(empty($payInfo)){
            $info['pay_name'] = '后台操作';
        }else{
            $info['pay_name'] = $payInfo[0]['pay_name'];
        }

        $agentInfo = app(DmsAgentInfo::class);
        $agent_info = $agentInfo->where(['agent_info_id'=>$info['user_id'],'mch_id'=>$info['mch_id']])->select('agent_name')->first();
        $info['agent_name'] = $agent_info['agent_name'];

        return $info;
    }

    //获取最近30天分销资金的详细状态信息
    public function getBalanceStatus($user_id)
    {
        $data = [];
        //获取最近30天的时间
        $thirty = strtotime(date("Y-m-d",strtotime("-1 month"))." 00:00:00");
        $today = strtotime(date("Y-m-d",strtotime("today"))." 23:59:59");

        $recharge_money = 0;
        $refund_money = 0;
        $give_money = 0;
        $trade_money = 0;
        $frozen_money = 0;
        //收入--充值
        $recharge = $this->model
                    ->where(['user_id'=>$user_id,'user_type'=>CHANEL_TERMINAL_AGENT,
                        'cus_balance_type'=>FINANCE_INCOME,
                        'cus_balance_type_detail'=>FINANCE_CHANGE_TYPE_RECHARGE])
                    ->whereBetween("created_at",[$thirty,$today])
                    ->orderBy('cus_balance_id','desc')
                    ->get()
                    ->toArray();

        foreach ($recharge as $reck=>$recv){
            $recharge_money += $recv['cus_balance_change'];
        }
        $data['income']['recharge'] = $recharge;
        //收入--退款
        $refund = $this->model
                    ->where(['user_id'=>$user_id,'user_type'=>CHANEL_TERMINAL_AGENT,
                        'cus_balance_type'=>FINANCE_INCOME,
                        'cus_balance_type_detail'=>FINANCE_CHANGE_TYPE_REFUND])
                    ->whereBetween("created_at",[$thirty,$today])
                    ->orderBy('cus_balance_id','desc')
                    ->get()
                    ->toArray();

        foreach ($refund as $refk=>$refv){
            $refund_money += $refv['cus_balance_change'];
        }
        $data['income']['refund'] = $refund;

        //收入--其他
        $give = $this->model
                ->where(['user_id'=>$user_id,'user_type'=>CHANEL_TERMINAL_AGENT,
                    'cus_balance_type'=>FINANCE_INCOME,
                    'cus_balance_type_detail'=>FINANCE_CHANGE_TYPE_GIVE])
                ->whereBetween("created_at",[$thirty,$today])
                ->orderBy('cus_balance_id','desc')
                ->get()
                ->toArray();
        foreach ($give as $gik=>$giv){
            $give_money += $giv['cus_balance_change'];
        }
        $data['income']['give'] = $give;

        //支出--交易
        $trade = $this->model
                    ->where(['user_id'=>$user_id,'user_type'=>CHANEL_TERMINAL_AGENT,
                        'cus_balance_type'=>FINANCE_EXPEND,
                        'cus_balance_type_detail'=>FINANCE_CHANGE_TYPE_TRADE])
                    ->whereBetween("created_at",[$thirty,$today])
                    ->orderBy('cus_balance_id','desc')
                    ->get()
                    ->toArray();

        foreach ($trade as $trk=>$trv){
            $trade_money += $trv['cus_balance_change'];
        }
        $data['expenditure']['trade'] = $trade;

        //支出--冻结
        $frozen = $this->model
                    ->where(['user_id'=>$user_id,'user_type'=>CHANEL_TERMINAL_AGENT,
                        'cus_balance_type'=>FINANCE_EXPEND,
                        'cus_balance_type_detail'=>FINANCE_CHANGE_TYPE_FROZEN])
                    ->whereBetween("created_at",[$thirty,$today])
                    ->orderBy('cus_balance_id','desc')
                    ->limit(1)
                    ->get()
                    ->toArray();
        foreach ($frozen as $frk=>$frv){
            $frozen_money += $frv['cus_balance_frozen_change'];
        }
        $data['expenditure']['frozen'] = $frozen;

        $data['recharge_money'] = number_format($recharge_money,2);
        $data['refund_money'] = number_format($refund_money,2);
        $data['give_money'] = number_format($give_money,2);
        $data['trade_money'] = number_format($trade_money,2);
        $data['frozen_money'] = number_format($frozen_money,2);

        return $data;
    }


    public function getChartInfo()
    {
        for($i=31;$i>=0;$i--){
            $time = date('m/d', strtotime('-'.$i.' days'));
            $days[] = $time;
            $start = strtotime($time . " 00:00:00");
            $end = strtotime($time . " 23:59:59");

            //充值
            $recharges = $this->model
                ->where(['user_id'=>$this->user_id,'user_type'=>CHANEL_TERMINAL_AGENT,
                    'cus_balance_type'=>FINANCE_INCOME,
                    'cus_balance_type_detail'=>FINANCE_CHANGE_TYPE_RECHARGE])
                ->whereBetween("created_at",[$start,$end])
                ->orderBy('cus_balance_id','desc')
                ->sum(DB::raw('cus_balance_change'));
            //交易
            $trades = $this->model
                ->where(['user_id'=>$this->user_id,'user_type'=>CHANEL_TERMINAL_AGENT,
                    'cus_balance_type'=>FINANCE_EXPEND,
                    'cus_balance_type_detail'=>FINANCE_CHANGE_TYPE_TRADE])
                ->whereBetween("created_at",[$start,$end])
                ->orderBy('cus_balance_id','desc')
                ->sum(DB::raw('cus_balance_change'));
            //退款
            $refunds = $this->model
                ->where(['user_id'=>$this->user_id,'user_type'=>CHANEL_TERMINAL_AGENT,
                    'cus_balance_type'=>FINANCE_INCOME,
                    'cus_balance_type_detail'=>FINANCE_CHANGE_TYPE_REFUND])
                ->whereBetween("created_at",[$start,$end])
                ->orderBy('cus_balance_id','desc')
                ->sum(DB::raw('cus_balance_change'));

            $recharge[] = $recharges;
            $trade[] = $trades;
            $refund[] = $refunds;
        }

        $data['days'] = $days;
        $data['recharge'] = $recharge;
        $data['trade'] = $trade;
        $data['refund'] = $refund;
        return $data;

    }



}