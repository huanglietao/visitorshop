<?php
namespace App\Services\Orders;

use App\Services\Helper;
use Illuminate\Support\Facades\DB;

/**
 * 物流信息跟踪
 * @author: cjx
 * @version: 1.0
 * @date: 2020/07/27
 */

class LogisticsInfo
{

    protected $no; //物流单号
    protected $company;  //物流公司 ：圆通、中通、顺丰等
    protected $code;  //物流编号 如 YTO,STO....

    protected $EBusinessID = ['1395777'];  //商户id
    protected $AppKey = ['bc1109cc-212f-4667-a8c8-d982f51ef236'];
    protected $requestUrl = 'http://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx';

    public function __construct()
    {

    }

    /**
     * 检查订单状态
     * @param $code 物流编号 $logisticCode 运单号
     */
    public function search($code, $logisticCode)
    {

        //是否已记录物流轨迹
       $mongo = DB::connection('mongodb')->collection('logistic');
       $res = $mongo->where('LogisticCode',$logisticCode)->first();

       if(empty($res)){
           $requestData= "{'OrderCode':'','ShipperCode':'".$code."','LogisticCode':'".$logisticCode."'}";

           foreach ($this->EBusinessID as $k=>$v){
               $data = array(
                   'EBusinessID' => $v,
                   'RequestType' => '1002',
                   'RequestData' => urlencode($requestData) ,
                   'DataType' => '2',
               );

               $data['DataSign'] = $this->encrypt($requestData, $this->AppKey[$k]);
               $return = $this->sendPost($this->requestUrl, $data);
               $return = json_decode($return , true);

               if($return['State'] == 3 && $return['Success'] == true){
                   //缓存物流信息
                   $mongo->insert($return);
                   break;
               }else if($return['State'] == 0 && $return['Success'] == true){
                   //暂无轨迹信息
                    break;
               }else if($return['State'] == 4 && $return['Success'] == true){
                   //问题件
                   break;
               }else if($return['Success'] == false){
                   //出错返回空值
                   return $return['Traces'];
               }
           }

           if($return['Success'] == true){
               return $return['Traces'];
           }else{
               return [];
           }

       }else{
           return $res['Traces'];
       }
    }



    /**
     *  post提交数据
     * @param  string $url 请求Url
     * @param  array $datas 提交的数据
     * @return url响应返回的html
     */
    public function sendPost($url, $datas) {
        $temps = array();
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);
        }
        $post_data = implode('&', $temps);
        $url_info = parse_url($url);
        if(empty($url_info['port']))
        {
            $url_info['port']=80;
        }
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader.= "Host:" . $url_info['host'] . "\r\n";
        $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader.= "Connection:close\r\n\r\n";
        $httpheader.= $post_data;
        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $httpheader);
        $gets = "";
        $headerFlag = true;
        while (!feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }
        while (!feof($fd)) {
            $gets.= fread($fd, 128);
        }
        fclose($fd);

        return $gets;
    }

    /**
     * 电商Sign签名生成
     * @param data 内容
     * @param appkey Appkey
     * @return DataSign签名
     */
    public  function encrypt($data, $appkey) {
        return urlencode(base64_encode(md5($data.$appkey)));
    }

}