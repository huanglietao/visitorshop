<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/22 0022
 * Time: 14:39
 */

namespace App\Services\Common\CallWayBillPrinter;

use App\Services\Common\CallWayBillPrinter\RlsInfoDto;
use App\Services\Common\CallWayBillPrinter\CargoInfoDto;
use App\Services\Common\CallWayBillPrinter\WaybillDto;



class BillPrinter
{
    /**
     * *******2联150 丰密运单*************
     */
    /**
     * 调用打印机 不弹出窗口 适用于批量打印【二联单】
     */
    protected $url7 = "http://localhost:4455/sf/waybill/print?type=V2.0.FM_poster_100mm150mm&output=noAlertPrint";
    /**
     * 调用打印机 弹出窗口 可选择份数 适用于单张打印【二联单】
     */
    protected $url8 = "http://localhost:4040/sf/waybill/print?type=V2.0.FM_poster_100mm150mm&output=print";

    /**
     * 直接输出图片的BASE64编码字符串 可以使用html标签直接转换成图片【二联单】
     */
    protected $url9 = "http://localhost:4040/sf/waybill/print?type=V2.0.FM_poster_100mm150mm&output=image";

    /**
     * *******3联210 丰密运单*************
     */
    /**
     * 调用打印机 不弹出窗口 适用于批量打印【三联单】
     */
    protected $url10 = "http://localhost:4040/sf/waybill/print?type=V3.0.FM_poster_100mm210mm&output=noAlertPrint";
    /**
     * 调用打印机 弹出窗口 可选择份数 适用于单张打印【三联单】
     */
    protected $url11 = "http://localhost:4040/sf/waybill/print?type=V3.0.FM_poster_100mm210mm&output=print";

    /**
     * 直接输出图片的BASE64编码字符串 可以使用html标签直接转换成图片【三联单】
     */
    protected $url12 = "http://localhost:4040/sf/waybill/print?type=V3.0.FM_poster_100mm210mm&output=image";

    /**
     * 获取顺丰打单的配置数组
     */
    protected $config = [
        'clientCode'   => 'ZRYYSKJ',//对应丰桥平台获取的clientCode
        'checkWord'    => 'lDRr77Kh5XnDqQMlCe6zBmqesVjNA8rc',
        'monthAccount' => '0225966490',
        'url'          => 'http://bsp-oisp.sf-express.com/bsp-oisp/sfexpressService' //下单接口
    ];

    public function __construct($post_data,$config = [])
    {
        $this->post_data = $post_data;
        if (!empty($config)){
            $this->config = $config;
        }
    }

    //顺丰下单
    public function place_order()
    {
        $order_data = $this->transArray($this->post_data);

        $data = $this->Order($order_data);
        return $data;
    }


    //返回封装数据
    public function re_data()
    {
        //1.根据业务需求确定请求地址,并确定是否替换版本
        $reqURL = $this->handle_url($this->url7,false);//true 不需要  false 需要

        //2.组装参数  丰密参数 true设置 false 不设置
        $post_json_data = $this->assembly_param(true,$this->post_data);
        return ['reqURL' => $reqURL,'post_json_data' => $post_json_data];
    }

    public function run_post()
    {
        //1.根据业务需求确定请求地址,并确定是否替换版本
        $reqURL = $this->handle_url($this->url7,false);//true 不需要  false 需要

        //2.组装参数  丰密参数 true设置 false 不设置
        $post_json_data = $this->assembly_param(true,$this->post_data);

        $face_info = json_decode($post_json_data,true);

        //3.发送请求
        $result = $this->send_post($reqURL, $post_json_data);

        //如果url是打印图片 则保存图片到本地
        if(strpos($reqURL, "image")){
            //4.处理结果数据
            $imageData = $this->handle_data($result);
            //5图片保存到本地
            $this->save_image($imageData);
        }
        $result = json_decode($result,true);
        $result['mailNo'] = $face_info[0]['mailNo']; //快递单号

        return $result;
    }

    /**
     *保存图片到本地
     * @param unknown $imageData
     */
    function save_image($imageData){
        $showtime=date("YmdHis",time()+8*3600);
        //判断是否包含多张图片
        if(strpos($imageData, "\",\"")){
            $var=explode("\",\"",$str);
            $i=0;
            foreach ($var as $value){
                $i++;
                $imgName = "D:\\qiaoWaybill-".$showtime."-".$i.".jpg";
                generate_image($imageData, $imgName);
            }
        }else{
            $imgName = "D:\\qiaoWaybill-".$showtime.".jpg";
            generate_image($imageData, $imgName);
        }
    }

    /**
     * 处理url
     * @param unknown $reqURL
     * @param unknown $notTopLogo true 不需要  false 需要
     * @return mixed
     */
    function handle_url($reqURL,$notTopLogo){

        if ( $notTopLogo && strpos($reqURL, "V2.0"))
        {
            $reqURL = str_replace("V2.0", "V2.1",$reqURL);;
        }

        if ($notTopLogo && strpos($reqURL,"V3.0"))
        {
            $reqURL = str_replace("V3.0", "V3.1",$reqURL);
        }

        return $reqURL;
    }

    /**
     * 组装参数
     * @param unknown $fengmi
     * @return string
     */
    function assembly_param($fengmi,$data=[]){

        $waybillDto = new WaybillDto();

        //这个必填
        $waybillDto->appId = $this->config['clientCode']; //对应丰桥平台获取的clientCode
        $waybillDto->appKey = $this->config['checkWord']; //对应丰桥平台获取的checkWord

        $waybillDto->mailNo = $data['mailNo'];
        /*$waybillDto->mailNo = "SF7551234567890";*/
        //$waybillDto->mailNo="SF7551234567890,SF2000601520988,SF2000601520997";//子母单方式


        //签回单号  签单返回服务POD 会打印两份快单 其中第二份作为返寄的单==如有签回单业务需要传此字段值
        //$waybillDto->returnTrackingNo="SF1060081717189";

        //收件人信息
        $waybillDto->consignerProvince = $data['province']??"";
        $waybillDto->consignerCity = $data['city']??"";
        $waybillDto->consignerCounty = $data['area']??"";
        $waybillDto->consignerAddress = $data['address']??""; //详细地址建议最多30个字  字段过长影响打印效果
        $waybillDto->consignerCompany = "";
        $waybillDto->consignerMobile = $data['mobile']??"";
        $waybillDto->consignerName = $data['consignee']??"";
        $waybillDto->consignerShipperCode = "";
        $waybillDto->consignerTel = "";


        //寄件人信息
        $waybillDto->deliverProvince = $data['sender_province'];
        $waybillDto->deliverCity = $data['sender_city'];
        $waybillDto->deliverCounty = $data['sender_area'];
        $waybillDto->deliverCompany = "";
        $waybillDto->deliverAddress = $data['sender_address']; //详细地址建议最多30个字  字段过长影响打印效果
        $waybillDto->deliverName = $data['sender_person']??"";
        $waybillDto->deliverMobile = $data['sender_phone']??"";
        $waybillDto->deliverShipperCode = "";
        $waybillDto->deliverTel = "";


        //$waybillDto->destCode = "755"; //目的地代码 参考顺丰地区编号
        //$waybillDto->zipCode = "571"; //原寄地代码 参考顺丰地区编号



        //1 ：标准快递   2.顺丰特惠   3： 电商特惠   5：顺丰次晨  6：顺丰即日  7.电商速配   15：生鲜速配
        $waybillDto->expressType = 1;

        ///addedService
        //   COD代收货款价值 单位元   此项和月结卡号绑定的增值服务相关
        //$waybillDto->codValue = "999.9";
        //$waybillDto->codMonthAccount = ""; //代收货款卡号 -如有代收货款专用卡号必传

        //$waybillDto->insureValue = "501"; //声明保价价值  单位元

        $waybillDto->monthAccount =  $this->config['monthAccount']; //月结卡号
        $waybillDto->orderNo = "";
        $waybillDto->payMethod = 1; // 1寄方付 2收方付 3第三方月结支付

        $waybillDto->childRemark = "";//子单号备注
        $waybillDto->mainRemark = $data['extra_data']??"";//主运单备注
        $waybillDto->returnTrackingRemark = "";//签回单备注
        //$waybillDto->custLogo = "";
        //$waybillDto->logo = "";
        //$waybillDto->insureFee = "";
        //$waybillDto->payArea = "";
        //加密项
        $waybillDto->encryptCustName = false;//加密寄件人及收件人名称
        $waybillDto->encryptMobile = false;//加密寄件人及收件人联系手机



        /*$cargo = new CargoInfoDto();
        $cargo->cargo = "苹果7S";
        $cargo->cargoCount = 2;
        $cargo->cargoUnit = "件";
        $cargo->sku = "00015645";
        $cargo->remark = "手机贵重物品 小心轻放";

        $cargo2 = new CargoInfoDto();
        $cargo2->cargo = "苹果macbook pro";
        $cargo2->cargoCount = 10;
        $cargo2->cargoUnit = "件";
        $cargo2->sku = "00015646";
        $cargo2->remark = "笔记本贵重物品 小心轻放";

        $cargoInfoList = array($cargo,$cargo2);


        $waybillDto->cargoInfoDtoList = $cargoInfoList;*/
        //$waybillDto->rlsInfoDtoList = $rlsInfoDtoList;


        if ($fengmi)
        {
            $rlsMain = new RlsInfoDto();
            $rlsMain->abFlag = "A";
            $rlsMain->codingMapping = "F33";
            $rlsMain->codingMappingOut = "1A";
            $rlsMain->destRouteLabel = "755WE-571A3";
            $rlsMain->destTeamCode = "";
            $rlsMain->printIcon = "00000000";
            $rlsMain->proCode = "T4";
            /*$rlsMain->qrcode = "MMM={'k1':'755WE','k2':'021WT','k3':'','k4':'T4','k5':'SF7551234567890','k6':''}";*/
            $rlsMain->qrcode = "MMM={'k1':'755WE','k2':'021WT','k3':'','k4':'T4','k5':'".$data['mailNo']."','k6':''}";
            $rlsMain->sourceTransferCode = "021WTF";
            /*$rlsMain->waybillNo = "SF7551234567890";*/
            $rlsMain->waybillNo = $data['mailNo'];
            $rlsMain->xbFlag = "XB";

            $rlsInfoDtoList=array($rlsMain);

            if (null != ($waybillDto->returnTrackingNo))
            {
                $rlsBack = new RlsInfoDto();
                $rlsBack->waybillNo = $waybillDto->returnTrackingNo;
                $rlsBack->destRouteLabel = "021WTF";
                $rlsBack->printIcon = "00000000";
                $rlsBack->proCode = "T4";
                $rlsBack->abFlag = "A";
                $rlsBack->xbFlag = "XB";
                $rlsBack->codingMapping = "1A";
                $rlsBack->codingMappingOut = "F33";
                $rlsBack->destTeamCode = "";
                $rlsBack->sourceTransferCode = "755WE-571A3";
                //对应下订单设置路由标签返回字段twoDimensionCode 该参
                /*$rlsBack->qrcode = "MMM={'k1':'21WT','k2':'755WE','k3':'','k4':'T4','k5':'SF1060081717189','k6':''}";*/
                $rlsBack->qrcode = "MMM={'k1':'21WT','k2':'755WE','k3':'','k4':'T4','k5':'".$data['mailNo']."','k6':''}";

                array_push($rlsInfoDtoList,$rlsBack);
            }

            $waybillDto->rlsInfoDtoList = $rlsInfoDtoList;

        }

        $waybillDtoList = array($waybillDto);
        $post_json_data = json_encode($waybillDtoList,JSON_UNESCAPED_UNICODE);
        return  $post_json_data;
    }


    /**
     * 发送post请求
     *
     * @param string $url
     *            请求地址
     * @param array $post_data
     *            post键值对数据
     * @return string
     */
    function send_post($reqURL, $post_data)
    {

        /* echo "url:" .$reqURL;
         echo "\n";
         echo "参数:" .$post_data;*/
        //curl验证成功
        $ch = curl_init($reqURL);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($post_data)
        ));


        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            print curl_error($ch);
        }
        curl_close($ch);

        if(strpos($reqURL, "image")=== false){
            /*echo "\n";
            echo "返回:".$result;*/
        }
        return $result;
    }

    /**
     * 处理数据
     * @param unknown $result
     */
    function handle_data($result){

        $startIndex = strpos($result,"[")+1;
        $substrLen = strrpos($result,"]") - $startIndex;
        $imageData = substr($result,$startIndex,$substrLen);

        /**
         * 如果以 \ 开头 ,截取
         */
        if(strpos($imageData,"\\")===0){
            $imageData = substr($imageData,1);
        }

        /**
         * 如果以 \ 结尾 ,截取
         */
        if(substr_compare($imageData, "\\", -strlen("\\")) === 0){
            $imageData = substr($imageData,0,(strlen($imageData)-1));
        }

        //换行符替换为空
        str_replace("\\n", "",$imageData);
        return $imageData;
    }

    /**
     *
     * @param unknown $imgStr 图片文件内容
     * @param unknown $imgName 图片地址+名称
     * @return boolean
     */
    function generate_image($imgStr, $imgName){
        if ($imgStr == null){
            return false;
        }

        $r = file_put_contents($imgName, base64_decode($imgStr));

        echo "\n";
        if (!$r) {
            echo $imgName." 图片生成失败\n";
        }else{
            echo $imgName." 图片生成成功\n";
        }
    }

    /**
     * 转换下单数组
     * @param unknown $result
     */
    public function transArray($data)
    {
        $order_data = [];
        //收件人信息
        $order_data['d_province'] = $data['province']??"";
        $order_data['d_city'] = $data['city']??"";
        $order_data['d_county'] = $data['area']??"";
        $order_data['d_address'] = $data['address']??""; //详细地址建议最多30个字  字段过长影响打印效果
        $order_data['d_company'] = "";
        $order_data['d_tel'] = $data['mobile']??"";
        $order_data['d_contact'] = $data['consignee']??"";

        //付款方式
        $order_data['pay_method'] = 1;
        //快递产品类别
        $order_data['express_type'] = 1;
        $order_data['orderid'] = $data['order_id'];


        //寄件人信息
        $order_data['j_province'] = $data['sender_province'];
        $order_data['j_city'] = $data['sender_city'];
        $order_data['j_county'] = $data['sender_area'];
        $order_data['j_address'] = $data['sender_address'];
        $order_data['j_company']  = "";
        $order_data['d_province'] = $data['sender_address']; //详细地址建议最多30个字  字段过长影响打印效果
        $order_data['j_contact'] = $data['sender_person']??"";
        $order_data['j_tel'] = $data['sender_phone']??"";

        return $order_data;
    }




    /**
     * 下单接口
     * @param unknown $result
     */
    /* public function get_order()
     {


         $file = './1_order.txt';
         $checkword = $this->config['checkWord'];
         $xmlContent = file_get_contents($file);

         $verifyCode = base64_encode(md5(($xmlContent.$checkword),true));
         $post_data = array(
             'xml' => $xmlContent,
             'verifyCode' => $verifyCode
         );
         $resultCont = $this->send_post($this->config['url'],json_encode($post_data,JSON_UNESCAPED_UNICODE));
         var_dump($resultCont);
         die;
     }*/

    /**
     * 顺丰BSP下订单接口（含筛选）
     * 下订单接口根据客户需要，可提供以下三个功能：
     * 1) 客户系统向顺丰下发订单。
     * 2) 为订单分配运单号。
     * 3) 筛单（可选，具体商务沟通中双方约定，由顺丰内部为客户配置）。
     * 此接口也用于路由推送注册。客户的顺丰运单号不是通过此下订单接口获取，但却需要获取BSP的路由推送时，
     * 需要通过此接口对相应的顺丰运单进行注册以使用BSP的路由推送 接口。
     *
     * @param string $post['j_company'] //寄件方公司名称
     * @param string $post['j_contact'] //寄件方联系人
     * @param string $post['j_tel']     //寄件方联系电话
     * @param string $post['j_address'] //寄件地址
     * @param string $post['j_province']//寄件方省份   (选填)
     * @param string $post['j_city']    //寄件方城市   (选填)
     * @param string $post['j_county']  //寄件方县区   (选填)
     * @param string $post['orderid']   //客户订单号
     * @param string $post['d_company'] //到件方公司名称(选填)
     * @param string $post['d_contact'] //收件方联系人
     * @param string $post['d_tel']     //收件方联系电话
     * @param string $post['d_address'] //收件方详细地址，如果不传输 d_province/d_city 字段，此详细地址 需包含省市信息，以提高地址识别的 成功率，示例：“广东省深圳市福田 区新洲十一街万基商务大厦 10楼”。
     * @param string $post['d_province']//收件方省份
     * @param string $post['d_city']    //收件方城市
     * @param string $post['d_county']  //收件方县区
     * @param string $post['pay_method']//付款方式：1:寄方付2:收方付3:第三方付
     * @param string $post['express_type']//快件产品类别
     * @param string $post['is_docall'] //是否要求通过是否手持终端通知顺丰收派员收件：1：要求 其它为不要求
     * @param string $post['d_county']  //收件方县区
     * @param array $params             //可选参数的数组
     * @param array $cargoes            //货物名称数组【name:商品名称count:商品数量】
     * @param array $cargoes['name']    //商品
     * @param array $cargoes['count']   //数量
     * @param array $addedServices      //增值服务
     * @param string $post['express_type']//*1顺丰标快；2顺丰特惠；3电商特惠；5顺丰次晨；6顺丰即日；7电商速配；
     * @param string $post['pay_method']  //*付款方式  1寄方付； 2收方付；3第三方付
     * @return string
     */
    public function Order($params = array(), $cargoes = array(), $addedServices = array())
    {

        $order_params = $this->paramsToString($params);


        $cargoes_str  = count($cargoes) > 0 ? $this->paramsToString($cargoes, 'Cargo') : '';
        $addedServices_str = count($addedServices) > 0 ? $this->paramsToString($addedServices, 'AddedService') : '';
        $xml_string   = "<Order$order_params>$cargoes_str$addedServices_str</Order>";
        $data         = $this->postXmlBodyWithVerify($xml_string,'OrderService');
        return  $this->OrderResponse($data,'OrderResponse');
    }

    /**
     * 转换属性为XML字符串
     * @param array $params
     * @param string $xml_Name
     * @return string
     */
    protected function paramsToString($params = [], $xml_Name = '')
    {
        $string = '';
        $return_string = '';
        if ($xml_Name && is_array($params)) {
            foreach ($params as $key => $value) {
                if ( is_array($value)){
                    $string = $this->paramsToString($value);
                }else{
                    $string .= " $key=\"$value\"";
                }
                $return_string .= "<$xml_Name$string></$xml_Name>";
            }
        } elseif (!$xml_Name && is_array($params)) {
            foreach ($params as $k => $v) {
                $string .= " $k=\"$v\"";
            }
            $return_string = $string;
        }

        return $return_string;
    }
    /**
     * 顺丰BSP接口主程序 已经已经集成验证
     * @param $xml
     * @return bool|mixed
     */
    public function postXmlBodyWithVerify($xml,$server){
        $xml       = $this->buildXml($xml,$server);
        $verifyCode= $this->sign($xml, $this->config['checkWord']);
        $post_data = "xml=$xml&verifyCode=$verifyCode";
        $response  = $this->postXmlCurl($post_data,$this->getPostUrl());
        return $response;
    }

    /**
     * 拼接XML字符串
     * @param $bodyData
     * @return string
     */
    public function buildXml($bodyData,$server){
        $xml = '<Request service="'.$server.'" lang="zh-CN">' .
            '<Head>'.$this->config['clientCode'].'</Head>' .
            '<Body>' . $bodyData . '</Body>' .
            '</Request>';
        return $xml;
    }


    /**
     * 计算验证码
     * data 是拼接完整的报文XML
     * check_word 是顺丰给的接入码
     * @param string $data
     * @param string $check_word
     * @return string
     */
    public static function sign($data, $check_word) {
        $string = trim($data).trim($check_word);
        $md5    = md5(mb_convert_encoding($string, 'UTF-8', mb_detect_encoding($string)), true);
        $sign   = base64_encode($md5);
        return $sign;
    }


    /**
     * 作用：以post方式提交xml到对应的接口url
     * @param $data
     * @param $url
     * @param int $second
     * @return bool|mixed
     */
    public function postXmlCurl($data,$url,$second=60)
    {
        try{
            header("Content-type: text/html; charset=utf-8");
            $ch = curl_init();//初始化curl
            curl_setopt($ch, CURLOPT_URL,$url);//抓取指定网页
            curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
            curl_setopt($ch, CURLOPT_TIMEOUT, $second);//超时设置
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
            curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $data = curl_exec($ch);//运行curl
            curl_close($ch);
            return $data;
        }catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 转换顺丰返回XML
     * @param $data
     * @param $name
     * @return array
     */
    public function getResponse($data, $name) {
        $ret = array();
        $xml = @simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
        if ($xml){
            $ret['head'] = (string)$xml->Head;
            if ($xml->Head == 'OK'){
                $ret = array_merge($ret , $this->getData($xml, $name));
            }
            if ($xml->Head == 'ERR'){
                $ret = array_merge($ret , $this->getErrorMessage($xml));
            }
        }
        return $ret;
    }

    /**
     * 获取xml字段
     * @param $xml
     * @param $name
     * @return array
     */
    public function getData($xml, $name) {
        $ret = array();
        if (isset($xml->Body->$name)){
            foreach ($xml->Body->$name as $v) {
                foreach ($v->attributes() as $key => $val) {
                    $ret[$key] = (string)$val;
                }
            }
        }
        return $ret;
    }

    /**
     * 获取错误信息
     * @param $xml
     * @return array
     */
    public function getErrorMessage($xml) {
        $ret = array();
        $ret['message'] = (string)$xml->ERROR;
        if (isset($xml->ERROR[0])) {
            foreach ($xml->ERROR[0]->attributes() as $key => $val) {
                $ret[$key] = (string)$val;
            }
        }
        return $ret;
    }


    /**
     * 返回结果
     * @param $data
     * @return array
     */
    private function  OrderResponse($data,$type = '') {
        return $this->getResponse($data,$type);
    }

    /**
     * 获取POSTURL地址
     * @return string
     */
    protected function getPostUrl(){
        return $this->config['url'];
        /*  if($this->config['ssl']){
              return   $this->config['server_ssl'].$this->config['uri'];
          } else {
              return   $this->config['server'].$this->config['uri'];
          }*/
    }




}