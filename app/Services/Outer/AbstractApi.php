<?php
namespace App\Services\Outer;

use App\Exceptions\CommonException;
use App\Services\Helper;
use GuzzleHttp\Client;

/**
 * 操作外部api的基类
 *
 * 包含对api的各请求和相关签名加密的基本操作
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/12/25
 */
class AbstractApi
{
    protected $domain;  //请求的域名
    protected $signType  = "md5";
    protected $secretKey = ""; //生成签名的key
    protected $isSign    = true;   //是否开启签名验证
    protected $signValue = "sign"; //签名字段
    protected $contentType = "application/x-www-form-urlencoded";
    protected $headerOther = [];  //请求头中额外的字段
    /**
     * 通用获取签名的方法,如果有特殊要求，请在子类中重写
     * @param $params
     * @param string $secretKey
     * @return string
     */
    protected function getSign($params, $secretKey = '')
    {
        return Helper::getSign($params, $secretKey);
    }

    /**
     * @param $url 请求地址
     * @param $params 请求参数
     * @param string $method  请求方法 POST/GET/......
     * @throws CommonException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return mixed
     */
    public function request($url, $params, $method = 'POST')
    {
        if(empty ($url)) {
            throw new CommonException("请求地址必须!",13001);
        }

        $client = new Client();

        $headers = [
            'Content-type'=> $this->contentType //'application/json'
        ];
        $this->headerOther = $this->getOtherHeaderParams($params);

        if(!empty($this->headerOther)) {
            $headers = array_merge($headers, $this->headerOther);
        }

        //是否开启签名验证
        if($this->isSign) {
            $params[$this->signValue] = $this->getSign($params, $this->secretKey);
        }

        //请求参数
        $data = [
            'headers' => $headers,
            'form_params' => $params
        ];
        if ($this->contentType == 'application/json') {
            $data = [
                'headers' => $headers,
                'json' => $params
            ];
        }
        //var_dump($data);exit;
        $response = $client->request($method, $url, $data);

        $result = json_decode($response->getBody()->getContents(),true);

        return $result;
    }

    /**
     * 获取额外的报文头参数
     * @param $params
     * @return array
     */
    protected function getOtherHeaderParams($params)
    {
        return [];
    }
}
