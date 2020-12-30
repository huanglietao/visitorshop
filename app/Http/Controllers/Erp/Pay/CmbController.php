<?php
namespace App\Http\Controllers\Erp\Pay;

use App\Http\Controllers\Erp\BaseController;
use App\Services\Outer\Pay\CmbAggregate;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\Psr7\parse_query;
use App\Services\Outer\Erp\Api;

/**
 * 招行支付相关控制器
 *
 * 支付通知方法实殃
 * @author: cjx
 * @version: 1.0
 * @date: 2020/01/02
 */
class CmbController extends BaseController
{
    protected $modules = 'payment';//当前控制器所属模块


    //异步回调的url
    public function notify(CmbAggregate $cmb)
    {

        file_put_contents('/tmp/cmbpay.log',var_export($_POST,true), FILE_APPEND);
        $arrData = $_POST;
        /*$json = 'biz_content=%7B%22merId%22%3A%22308999150570002%22%2C%22openId%22%3A%22o0xFV4wXFttZiHplNCvq9GGm8lrM%22%2C%22orderId%22%3A%2214233022%22%2C%22cmbOrderId%22%3A%22003219052720175970907079%22%2C%22userId%22%3A%22N002864744%22%2C%22txnAmt%22%3A%221%22%2C%22currencyCode%22%3A%22156%22%2C%22payType%22%3A%22WX%22%2C%22txnTime%22%3A%2220190527201844%22%2C%22endDate%22%3A%2220190527%22%2C%22endTime%22%3A%22201843%22%2C%22dscAmt%22%3A%220%22%7D&sign=MCMCKUgAv%2FIUIL3ceQ3gaFhUHPN4LMb8xBG2zOUaurdpciSFmCnJAD3SpnEaCIGTl3XhKsXaK%2FqWVxaZk5x%2BPt5%2F5BKypqLrmyOiqsq45H8%2FSyOYLE9VKPWL02YztJcFVNNNrOmeLOzcWbxqt7RPhgSanyZ9pXxXIBvqA9Uk91G2NN6TxP5gxcaTKMWAidLxhyYLiEO0XDRZZCDTg%2BkW1WNH2imjpGSlfR%2BhwI0lcMzdH7QRDDyB1jEKrNr%2Fcuy12mC2MMX61pFGzh5GSoO2s%2FS1eoPf9vSdPzrrBqqshDss3yBRWlPLa6GnTw9oxJ%2F7uQVizaur960rW7UOcsOzYg%3D%3D&encoding=UTF-8&version=0.0.1&signMethod=01';
        $strDecode = urldecode($json);
        $arrData = parse_query($strDecode);*/


        //获取公钥
        $publicKey = config('common.cmb.cmb_pub_key');

        $res = $cmb->verify($arrData,$publicKey,$arrData['sign']);


        if ($res){
            //验证签名通过  这里直接返回了1
            $this->_doNotify($arrData);

        }else{
            //验证不通过
            echo "fail";
        }

    }

    private function _doNotify($arrData)
    {
        $data = json_decode($arrData['biz_content'],true);


        //商户订单号
        $out_trade_no = $data['orderId'];

        //招行交易号
        $trade_no = $data['cmbOrderId'];

        //招行单位为分，需转换为元
        $total_fee = floatval($data['txnAmt']/100);

        //1、查找支付单号是否存在
        $payment_info=json_decode(DB::table("erp_finance_doc")->where(['recharge_no' => $out_trade_no])->get(),true);

        DB::beginTransaction();
        try
        {
            if ($payment_info) {
                //判断支付单未支付

                if ($payment_info[0]['status'] != '1') {
                    //金额匹配

                    if (floatval($total_fee) != floatval($payment_info[0]['recharge_fee'])) {

                        //Log::write(var_export($data,true),$type = 'log', $force = true);
                        //file_put_contents(PAYLOG_ERROR_PATH . $out_trade_no . '-' . $trade_no . '-.log', $name_date . '金额不匹配(' . $total_fee . '---' . floatval($payment_info->account) . ')' . "\t\n", FILE_APPEND);
                        die('success1');
                    }
                    //更新支付单状态
                    //事务开始
                    $update_result = DB::table("erp_finance_doc") ->where(['recharge_no'=>$out_trade_no])->update(['status' => '1', 'trade_no' => $trade_no,'finishtime' => time()]);
                    if (!$update_result) {

                        //file_put_contents(PAYLOG_ERROR_PATH . $recharge_no . '-' . $trade_no . '-.log', $name_date . 'payment update 失败' . "\t\n", FILE_APPEND);
                        //Log::write($recharge_no . '-' . $trade_no , $name_date . 'RechargeDoc update 失败',$type = 'log', $force = true);
                        throw new CommonException("success2");
                    }


                    //访问商户充值接口 by hlt
                    $curl = new Api();
                    $api_url = config("erp.interface_url").config("erp.login");
                    $post_data = [
                        'partner_code' => $payment_info[0]['partner_code'],
                        'money' => floatval($payment_info[0]['amount']),
                        'real_pay_money'   => floatval($payment_info[0]['recharge_fee']),
                        'service_money'   => floatval($payment_info[0]['handling_fee']),
                        'gather_type' => 3,
                        'gather_bank_code' => '0201',
                        'trade_serial_number' => $trade_no,
                        'trade_order_number' => $out_trade_no,
                        'trade_start_time' => date('Y-m-d H:i:s',$payment_info[0]['createtime']),
                        'trade_done_time' => date('Y-m-d H:i:s',time())
                    ];
                    file_put_contents('/tmp/cmb_auto_entry_account.log',var_export($post_data,true),FILE_APPEND);
                    $res_arr = new Api();
                    $result_arr  = $res_arr->request(config('erp.interface_url').config('erp.recharge'),$post_data);
                    file_put_contents('/tmp/cmb_auto_entry_account.log',var_export($result_arr,true),FILE_APPEND);
                    if($result_arr['code'] == 1){
                        //上账成功
                        DB::table("erp_finance_doc") ->where(['recharge_no'=>$out_trade_no])->update(['capital_change_status' => '1', 'capital_change_msg' => $result_arr['message']]);
                    }else{
                        //上账失败
                        DB::table("erp_finance_doc") ->where(['recharge_no'=>$out_trade_no])->update(['capital_change_status' => '0', 'capital_change_msg' => $result_arr['message']]);
                    }
                    DB::commit();
                    $json_str = $this->returnJson('success');
                    echo $json_str;
                     /*$this->success();*/

                }
            } else {
                //如果支付单号不存在，邮件通知技术员
                /*$this->error();*/
                $json_str = $this->returnJson('fail');
                echo $json_str;
            }

        }
        catch (CommonException $e)
        {
            file_put_contents('/tmp/cmb_auto_entry_account_error.log',var_export($e->getMessage(),true),FILE_APPEND);
            DB::rollBack();
            $json_str = $this->returnJson('fail');
            echo $json_str;
            /*$this->error($e->getMessage());*/
        }

    }

    //接口返回数据组织
    public function returnJson($type)
    {
        $cmb = app(CmbAggregate::class);
        $arr = [];
        if ($type=="success")
        {
            //成功返回
            $arr = [
                "returnCode" => "SUCCESS",
                "encoding"   => "UTF-8",
                "version"    => "0.0.1",
                "signMethod" => "01",
                "respCode"   => "SUCCESS"
            ];

        }else if ($type=="fail"){
            //失败返回
            $arr = [
                "returnCode" => "FAIL",
                "encoding"   => "UTF-8",
                "version"    => "0.0.1",
                "signMethod" => "01",
            ];
        }
        $privateKey = config('common.cmb.mch_pri_key');
        $arr['sign'] = $cmb->getSign($arr,$privateKey);
        $json_str = json_encode($arr);
        return $json_str;
    }
}
