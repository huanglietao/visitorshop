<?php
namespace App\Http\Controllers\Backend\System;

use App\Http\Controllers\Backend\BaseController;
use App\Http\Requests\Backend\System\PaymentRequest;
use App\Repositories\SaasPaymentRepository;
use Illuminate\Http\Request;
use App\Exceptions\CommonException;

/**
 * 项目说明  CMS系统 支付设置
 * 详细说明  CMS系统 支付设置，实现列表，添加，编辑，删除及组件结合
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/03/02
 */
class PaymentController extends BaseController
{
    protected $viewPath = 'backend.system.payment';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(SaasPaymentRepository $Repository)
    {
        parent::__construct();
        $this->repositories = $Repository;
    }

    /**
     * ajax获取列表项
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function table(Request $request)
    {
        $inputs = $request->all();
        $inputs['mch_id']=0;
        $list = $this->repositories->getTableList($inputs,'pay_id desc');
        $htmlContents = $this->renderHtml('',['list' =>$list]);

        $pagesInfo = $list->toArray();

        $total = $pagesInfo['total'];

        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
    }


   //添加/编辑操作
    public function save(PaymentRequest $request)
    {
        $params = $request->all();
        $data = [
            'pay_id'=>$params['pay_id'],
            'pay_name'=>$params['pay_name'],
            'pay_class_name'=>$params['pay_class_name'],
            'pay_desc'=>$params['pay_desc'],
            'pay_logo'=>$params['pay_logo'],
            'pay_status'=>$params['pay_status']
        ];

        //支付宝配置添加，支付宝配置参数整合
        if($params['pay_class_name']=="alipay"){
            $params['pay_config_param'] = ['partner'=>$params['pid'],'seller_id'=>$params['seller_id'],'key'=>$params['key']];
            $params['pay_config_param']= json_encode($params['pay_config_param'],JSON_UNESCAPED_UNICODE); //把数组转成json格式保存

            $data['pay_config_param']=$params['pay_config_param'];
        }
        //微信配置添加，微信配置参数整合
        elseif($params['pay_class_name']=="wxpay"){
            $params['pay_config_param'] = ['appid'=>$params['appid'],'mchid'=>$params['mchid'],'wekey'=>$params['wekey'],'appsecret'=>$params['appsecret'],'sslcert_path'=>$params['sslcert_path'],'sslkey_path'=>$params['sslkey_path']];
            $params['pay_config_param']= json_encode($params['pay_config_param'],JSON_UNESCAPED_UNICODE);

            $data['pay_config_param']=$params['pay_config_param'];
        }

        //存入数据库
        $ret = $this->repositories->save($data);
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }

    //添加，编辑页面数据请求
    public function form(Request $request)
    {
        try {
            if($request->ajax())
            {
                $row = $this->repositories->getById($request->input('id'));

                if($row['pay_id']){
                    if($row['pay_class_name'] == 'alipay'){
                        $aliconfig = json_decode($row['pay_config_param'], true);//支付宝参数配置
                        $row['pid'] = $aliconfig['partner'];
                        $row['seller_id'] = $aliconfig['seller_id'];
                        $row['key'] = $aliconfig['key'];
                    }else{
                        $wechconfig = json_decode($row['pay_config_param'], true);//微信参数配置
                        $row['appid'] = $wechconfig['appid'];
                        $row['mchid'] = $wechconfig['mchid'];
                        $row['wekey'] = $wechconfig['wekey'];
                        $row['appsecret'] = $wechconfig['appsecret'];
                        $row['sslcert_path'] = $wechconfig['sslcert_path'];
                        $sslcert_path_arr = explode('/',$wechconfig['sslcert_path']);
                        $row['sslcert_path_name'] = $sslcert_path_arr[count($sslcert_path_arr)-1];
                        $row['sslkey_path'] = $wechconfig['sslkey_path'];
                        $sslkey_path_arr = explode('/',$wechconfig['sslkey_path']);
                        $row['sslkey_path_name'] = $sslkey_path_arr[count($sslkey_path_arr)-1];
                    }
                }

                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }
    }

}