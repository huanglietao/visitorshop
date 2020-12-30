<?php
namespace App\Services;

use App\Models\DmsAgentInfo;
use App\Models\SaasDiyAssistant;
use App\Models\SaasSyncOrderConf;
use App\Repositories\SaasProductsSkuRepository;
use App\Services\Common\Mongo;
use App\Services\Outer\TbApi;
use Illuminate\Support\Facades\Redis;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Encryption\Encrypter;

/**
 * 功能简介
 *
 * 功能详细说明
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/8/8
 *  .D1
 */
class Helper
{
    /**
     * 能用的随机数生成
     * @param string $type 类型 alpha/alnum/numeric/nozero/unique/md5/encrypt/sha1
     * @param int $len 长度
     * @return string
     */
    public static function build($type = 'alnum', $len = 8)
    {
        switch ($type)
        {
            case 'alpha':
            case 'alnum':
            case 'numeric':

            case 'nozero':
                switch ($type)
                {
                    case 'alpha':
                        $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'alnum':
                        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'numeric':
                        $pool = '0123456789';
                        break;
                    case 'nozero':
                        $pool = '123456789';
                        break;
                }
                return substr(str_shuffle(str_repeat($pool, ceil($len / strlen($pool)))), 0, $len);
            case 'unique':
            case 'md5':
                return md5(uniqid(mt_rand()));
            case 'encrypt':
            case 'sha1':
                return sha1(uniqid(mt_rand(), TRUE));
        }
    }

    /**
     * 生成签名
     * @param $params  生成签名的参数
     * @param string $secretKey 密钥
     * @return string
     */
    public static function getSign($params, $secretKey='')
    {
        $str = '';//待签名字符串
        //先将参数以其参数名的字典序升序进行排序
        ksort($params);
        //遍历排序后的参数数组中的每一个key/value对
        foreach($params as $k => $v){
            if ($v == '' || 'sign' == $k) {
                continue;
            }
            //为key/value对生成一个key=value格式的字符串，并拼接到待签名字符串后面
            $str .= "$k=$v";
        }
        //将签名密钥拼接到签名字符串最后面
        $str .= $secretKey;
        //通过md5算法为签名字符串生成一个md5签名，该签名就是我们要追加的sign参数值
        return md5($str);
    }

    /**
     * json正确返回
     * @param $params
     * @return string
     */
    public static function returnJsonSuccess($params)
    {
        $return['success'] = 'true';
        $return['result'] = $params;

        return $return;
    }

    /**
     * json错误返回
     * @param $params
     * @return string
     */
    public static function returnJsonFail($params)
    {
        $return['success'] = 'false';
        $return['err_code'] = isset($params['err_code']) ? $params['err_code']: '00001';
        $return['err_msg'] = isset($params['err_msg']) ? $params['err_msg']: __("common.sys_error");

        return $return;
    }

    /**
     * 获取客户端ip
     *
     * @return string
     */
    public static function getClientIp()
    {
        $ip= '';
        if (isset($_SERVER)){

            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){

                $ip= $_SERVER["HTTP_X_FORWARDED_FOR"];

            } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {

                $ip= $_SERVER["HTTP_CLIENT_IP"];

            } else {

                $ip= $_SERVER["REMOTE_ADDR"];

            }

        } else {

            if (getenv("HTTP_X_FORWARDED_FOR")){

                $ip= getenv("HTTP_X_FORWARDED_FOR");

            } else if (getenv("HTTP_CLIENT_IP")) {

                $ip= getenv("HTTP_CLIENT_IP");

            } else {

                $ip= getenv("REMOTE_ADDR");

            }

        }
        return $ip;
    }

    //去除数组中的空值
    public function removeNull($data)
    {
        foreach ($data as $k=>$v){
            if ($v == ""){
                unset($data[$k]);
            }
        }
        return $data;
    }

    /**
     * 返回组数中加多个请选择元素
     * @param $data
     * @return array
     */
    public static function getChooseSelectData($data=[])
    {
        $data= [''=>'请选择']+$data;
        return $data;
    }

    /**
     *  日期区间转换成时间戳数组（开始时间戳，结束时间戳）
     * @param $data
     * @return array
     */
    public static function getTimeRangedata($data)
    {
        $array = explode(" - ",$data);
        $end_date = $array[1];
        $start_date = $array[0];
        $dataTime['start'] = strtotime($start_date);
        $dataTime['end'] = strtotime($end_date);
        return $dataTime;
    }

    /**
     * 把数据结果集转成select/radio可用的key=>value形式
     *============================================================
     * exp:
     * $list = [
     *    ['id'=>1, 'name'=>'照片书', 'other1'=>'ot' , 'f2'=>'f2'],
     *    ['id'=>2, 'name'=>'台历', 'other1'=>'ot' , 'f2'=>'f2']
     * ];
     * $ret = Helper::ListToKV('id' , 'name' ,$list)
     * 则会输出
     *  [1=>'照片书', 2=> '台历']
     * 能够直接用于表单的select/radio等控件
     *===========================================================
     *
     * @param $key 指定字段作为键
     * @param $value 指定字段作为值
     * @param $list  要转化的集合
     * @return array
     */
    public static function ListToKV($key , $value ,$list)
    {
        $return = [];
        foreach ($list as $k=>$v) {
            if(!isset($v[$key]) || !isset($v[$value])) {
                return false;
            }

            $return[$v[$key]] = $v[$value];
        }

        return $return;
    }

    /**
     * 抛出简单异常,如果需要复杂处理，用内部的 app(\App\Services\Exception::class)->throwException（）这个方法
     * @param $code
     * @param $pos
     * @param null $lang_key_val
     */
    public static function EasyThrowException($code, $pos, $lang_key_val=null)
    {
        app(\App\Services\Exception::class)->throwException($code,$pos, $lang_key_val);
    }


    /**
     * 方便api操作的异常处理
     * @param $code 代码
     * @param $pos 位置
     * @param array $inputData 额外数据 一般记录请求的数据
     * @param null $lang_key_val
     * @param bool $is_notice
     */
    public static function apiThrowException($code, $pos, $inputData = [], $lang_key_val=null, $is_notice = false)
    {
        $notice_who = "dev";
        app(\App\Services\Exception::class)->throwException($code,$pos, $lang_key_val,$is_notice,$notice_who,$inputData);
    }

    /**
     * 唯一编号生成器
     * @param string $code
     * @return mixed
     */
    public static function generateNo($code = '')
    {
        $orderNo = $code.date('ymdHis').rand(100,999);
        if (Redis::sismember("order_no", $orderNo)) {
            self::generateNo($code);
        } else {
            Redis::sadd("order_no", $orderNo);
        }

        return  $orderNo;
    }

    /**
     * 将图片流转存为图片文件
     * @param $path
     * @param $stream
     * @return mixed
     */
    public static function saveImageStream($path, $stream)
    {
        if (!file_exists($path)) {
            if(!mkdir($path, 0777, true)){
                return false;
            }
        } else if (!is_writeable($path)) {
            return false;
        }

        if(!empty($stream)){
            $file_name = uniqid().mt_rand(10000,99999).'.jpg';

            file_put_contents($path.'/'.$file_name,$stream);

            return $file_name;
        }

        return false;
    }

    /**
     * 通过cookie获取用户登录信息
     * @param $laravelCookie 
     */
    public static function getUserInfoByCookie($laravelCookie)
    {
        //解密
        //var_dump(base64_decode(config("app.key")));exit;
        $CookiesEncrypt = new Encrypter(base64_decode(substr(config("app.key"), 7)), config('app.cipher'));
        $value = $CookiesEncrypt->decrypt($laravelCookie,false);
    }

    /**
     * 简单加密
     * @param $str 待加密串
     * @return string
     */
    public static function easyEncrypt($str)
    {
        return mt_rand(10, 99).$str.mt_rand(10, 99);
    }

    /**
     * 简单解密
     * @param $str 待解密串
     * @return mixed
     */
    public static function easyDecrypt($str)
    {
        $p = substr($str,2,-2);
        return $p;
    }

    /**
     * 获取同步订单信息
     * @param $order_no
     * @param $agent_id
     * @return mixed
     */

    public function getSyncOrderIndo($order_no,$agent_id)
    {
        /*$data = [
            'order_no' => '1011032513941101325',
            'agent_id' => '1'
        ];*/
        $tbConfig = $this->getTbConfig($agent_id);
        if (empty($tbConfig)){
            $res['success'] = 'false';
            $res['result'] = [];
            $res['msg'] = "找不到淘宝配置信息";
            return $res;
        }


        $data = [
            'order_no' => $order_no,
            'agent_id' => $agent_id
        ];
        $api = app(TbApi::class);

        $res = $api->request($tbConfig['sdk_cnf_domain'].'/tb/order/info',$data,'POST');



        //将订单存进diy助手表当订单缓存数据
        if ($res['success'] == 'true' && isset($res['result']['trade'])) {
            
            $diyAssistantModel = app(SaasDiyAssistant::class);
            //查看本地表是否有该订单信息
            $orderDiyInfo = $diyAssistantModel->where(['order_no' => $order_no])->get()->toArray();
            $prodAllInfo = [];
            if (empty($orderDiyInfo))
            {
                $dmsAgentInfo = app(DmsAgentInfo::class);
                //获取所属商户id
                $mch_id = $dmsAgentInfo->where(['agent_info_id' => $agent_id])->value('mch_id');
                //获取商品信息
                //成功获取订单
                //获取商品信息
                $orderArr = $res['result']['trade']['orders']['order'];

                $sku_id = [];
                $last_key = 0;//淘宝订单的最后一个key
                $doubleArr = [];//是否含有多商品合并的淘宝商品（如带包装的商品）
                //判断是否有多商品同个货品
                foreach ($orderArr as $k=>$v)
                {
                    $last_key = $k;
                    $orderArr[$k]['isdouble'] = 0;
                    if (isset($v['outer_sku_id']))
                    {
                        if (array_key_exists($v['outer_sku_id'],$sku_id)){
                            //有同个货品,只存一个，数量往上叠加，其他unset
                            $sku_id[$v['outer_sku_id']] += $v['num'];
                            unset($orderArr[$k]);
                        }else{
                            $sku_id[$v['outer_sku_id']]=$v['num'];
                        }

                        //判断是否为合并商品
                        $doubleStr = strstr($v['outer_sku_id'],DOUBLE_SN);
                        if ($doubleStr){
                            //有合并商品
                            if (isset($orderArr[$k])){
                                $doubleArr[] = $k;
                            }

                        }

                    }

                }
                if (!empty($doubleArr)) {
                    //含有多商品合并的商品
                    foreach ($doubleArr as $k => $v) {
                        $skuArr = explode('_', $orderArr[$k]['outer_sku_id']);
                        $orderArr[$k]['isdouble'] = 1;
                        //将分出来的货号分别新增到订单数组存进缓存表
                        foreach ($skuArr as $kk => $vv) {
                            ++$last_key;
                            $orderArr[$last_key] = $orderArr[$k];
                            $orderArr[$last_key]['double_sku_sn'] = $vv;
                        }
                        unset($orderArr[$v]);
                    }
                }
                $skuRepository = app(SaasProductsSkuRepository::class);
                //判断是否为特殊订单（全是链接的订单）
                $is_special = 1;//默认是特殊订单
                foreach ($orderArr as $k => $v) {
                    $insertDiyData = [];
                    //获取当前商品类型（实物不操作同步队列）
                    if (isset($v['outer_sku_id']))
                    {
                        if ($v['isdouble']){
                            //为合并商品的淘宝商品
                            $prod_type_res = $skuRepository->getGoodstype($v['double_sku_sn'], $mch_id);
                        }else{
                            $prod_type_res = $skuRepository->getGoodstype($v['outer_sku_id'], $mch_id);
                        }

                        if ($prod_type_res['code'] == 1) {
                            $insertDiyData['prod_cate_flag'] = $prod_type_res['goods_type'];
                            //含有货号的商品，则该订单不为特殊订单
                            $is_special = 0;
                        } else {
                            $insertDiyData['prod_cate_flag'] = null;
                        }
                        //获取货品信息
                        if ($v['isdouble']){
                            //为合并商品的淘宝商品
                            $skuInfo = $skuRepository->getProdSkuId($v['double_sku_sn'], $mch_id);
                        }else{
                            $skuInfo = $skuRepository->getProdSkuId($v['outer_sku_id'], $mch_id);
                        }

                        if (!empty($skuInfo)) {
                            $insertDiyData['prod_id'] = $skuInfo[0]['prod_id'];
                            $insertDiyData['sku_id'] = $skuInfo[0]['prod_sku_id'];
                        } else {
                            $insertDiyData['prod_id'] = null;
                            $insertDiyData['sku_id'] = null;
                        }
                    }else{
                        $insertDiyData['prod_cate_flag'] = null;
                        $insertDiyData['prod_id'] = null;
                        $insertDiyData['sku_id'] = null;
                    }

                    //组织数据插入diy助手表
                    $insertDiyData['order_no'] = $order_no;
                    $insertDiyData['agent_id'] = $agent_id;


                    if (isset($v['outer_sku_id'])){
                        $insertDiyData['prod_num'] = $sku_id[$v['outer_sku_id']]??$v['num'];
                    }else{
                        $insertDiyData['prod_num'] = $v['num'];
                    }
                    $insertDiyData['sku_sn'] = $v['outer_sku_id']??"";
                    $insertDiyData['order_info'] = json_encode($res,JSON_UNESCAPED_UNICODE);
                    $insertDiyData['order_prod_name'] = $v['title'];
                    $insertDiyData['order_prod_attr'] = $v['sku_properties_name']??"";
                    $insertDiyData['order_prod_photo'] = $v['pic_path']??"";
                    $insertDiyData['created_at'] = time();
                    //插入diy助手表做缓存
                    $prodAllInfo[] = $insertDiyData;

                    $diyAssistantModel->insert($insertDiyData);
                }
                //更新订单特殊标识字段
                $diyAssistantModel->where('order_no',$order_no)->update(['is_special' => $is_special]);


            }
        }



        return $res;
    }

    /**
     * @param $url  传入的url
     * @param $domain 域名
     * @return string
     */
    public static function getRealUrl($url, $domain)
    {
       if (empty($url)) {
           $realUrl = '';
        } else {
            if (strpos($url, 'http://') !== false || strpos($url, 'https://') !== false ) {
                $realUrl = $url;
            } else {
                $realUrl = $domain."/".$url;
            }
        }
        return $realUrl;
    }
    /**
     * 获取指定菜鸟打印接口的配置信息
     * @param $agent_id  用户id
     * @return array
     */
    public function getCaiNiaoConfig($agent_id)
    {
        $syncConf = app(SaasSyncOrderConf::class);
        $conArr = $syncConf->where(['agent_id' => $agent_id,'sdk_cnf_is_cn'=>1])->first();
        if (empty($conArr)){
            //获取默认的配置
            $conArr = $syncConf->where(['agent_id' => CAINIAO_DEFAULT_USER_ID,'sdk_cnf_is_cn'=>1])->first();
        }
        $conArr = $conArr->toArray();
        return $conArr;

    }

    /**
     * 获取指定用户淘宝接口的配置信息
     * @param $agent_id  用户id
     * @return array
     */
    public function getTbConfig($agent_id)
    {
        $syncConf = app(SaasSyncOrderConf::class);
        $conArr = $syncConf->where(['agent_id' => $agent_id])->first();
        if (!empty($conArr)){
            $conArr = $conArr->toArray();
        }else{
            $conArr = [];
        }
        return $conArr;
    }

    /**
     * 获取外部订单图片信息
     * @param $order_no
     * @param $agent_id  用户id
     * @return array
     */
    public function getTbImage($order_no,$agent_id)
    {
        $tbConfig = $this->getTbConfig($agent_id);
        if (empty($tbConfig)){
            $res['success'] = 'false';
            $res['result'] = [];
            $res['msg'] = "找不到淘宝配置信息";
            return $res;
        }


        $data = [
            'order_no' => $order_no,
            'agent_id' => $agent_id
        ];
        $api = app(TbApi::class);

        $res = $api->request($tbConfig['sdk_cnf_domain'].'/tb/order/get-order-pic',$data,'POST');

        return $res;
    }

    //获取各个类型的时间戳数组
    //$type:near_week 最近7天 near_half_month 最近15天 near_month 最近一个月 last_month 上月 this_month 当月 last_year 去年 this_year 今年
    public function getAllTimestamp($type = 'near_week')
    {
        switch ($type)
        {
            case 'near_week':
                //取最近7天的时间戳
                $startTime = strtotime(date('Y-m-d',strtotime('-6 days'))); // 开始时间
                $endTime = strtotime(date('Y-m-d',strtotime('+1 days'))); //结束时间
                $timeArr = $this->getDayTime($startTime,$endTime);
                break;
            case 'near_half_month':
                //取最近15天的时间戳
                $startTime = strtotime(date('Y-m-d',strtotime('-15 days'))); // 开始时间
                $endTime = strtotime(date('Y-m-d',strtotime('+1 days'))); //结束时间
                $timeArr = $this->getDayTime($startTime,$endTime);
                break;
            case 'near_month':
                //取最近一个月每天的时间戳
                $startTime = strtotime(date('Y-m-d',strtotime('-1 month'))); // 开始时间
                $endTime = strtotime(date('Y-m-d',strtotime('+1 days'))); //结束时间
                $timeArr = $this->getDayTime($startTime,$endTime);
                break;
            case 'last_month':
                //取上个月每天的时间戳
                $this_month = date('m',time());
                $year = date('Y',time());
                if ($this_month == 1){
                    //一月的上一个月为12月
                    $this_month = 12;
                    $year = $year-1;
                }else{
                    $this_month -= 1;
                }
                $day = $year.'-'.$this_month;
                $startTime = strtotime($day); // 开始时间
                $endTime = strtotime("$day +1 month"); //结束时间
                $timeArr = $this->getDayTime($startTime,$endTime);
                break;
            case 'this_month':
                //取这个月每天的时间戳
                $this_month = date('m',time());
                $year = date('Y',time());
                $day = $year.'-'.$this_month;
                $startTime = strtotime($day); // 开始时间
                $endTime = strtotime("$day +1 month"); //结束时间
                $timeArr = $this->getDayTime($startTime,$endTime);
                break;
            case 'last_year':
                //取上年每个月的时间戳
                $year = date('Y',time());
                $year -= 1;
                $day = $year.'-01-01';
                $startTime = strtotime($day); // 开始时间
                $endTime = strtotime("$day +1 year"); //结束时间
                $timeArr = $this->getMonthTime($startTime,$endTime);
                break;
            case 'this_year':
                //取今年每个月的时间戳
                $year = date('Y',time());
                $day = $year.'-01-01';
                $startTime = strtotime($day); // 开始时间
                $endTime = strtotime("$day +1 year"); //结束时间
                $timeArr = $this->getMonthTime($startTime,$endTime);
                break;
            default:
                $timeArr = [];
                break;
        }
       return [
           'start_time' => $startTime??0,
           'end_time'   => $endTime??0,
           'time_arr' => $timeArr,
       ];



    }
    //返回每天开始与结束的时间戳
    public function getDayTime($startTime,$endTime)
    {
        $thisTime = $startTime;
        $timeArr = [];
        $i = 0;
        $addTime = 24*60*60-1;
        while ($thisTime < $endTime)
        {
            $month = date('m',$thisTime);
            $day = date('d',$thisTime);
            //组织每天的开始结束数组
            $timeArr[$i]['time_str'] = ltrim($month,'0').".".ltrim($day,'0');
            $timeArr[$i]['start_time'] = $thisTime;
            $timeArr[$i]['end_time'] = $thisTime + $addTime;
            //下一天的开始时间
            $thisTime = $thisTime+$addTime+1;
            ++$i;
        }
        return $timeArr;

    }
    //返回每月开始与结束的时间戳
    public function getMonthTime($startTime,$endTime)
    {
        $thisTime = $startTime;
        $timeArr = [];
        $i = 0;
        while ($thisTime < $endTime)
        {
            $year = date('Y',$thisTime);
            $month = date('m',$thisTime);
            //组织每天的开始结束数组
            $timeArr[$i]['time_str'] = $year.".".ltrim($month,'0');
            $timeArr[$i]['start_time'] = $thisTime;
            $dateTime = date('Y-m-d',$thisTime);
            $timeArr[$i]['end_time'] = strtotime("$dateTime +1 month")-1;
            //下一天的开始时间
            $thisTime = strtotime("$dateTime +1 month");
            ++$i;
        }
        return $timeArr;
    }

}