<?php
namespace App\Http\Controllers\Api\Editor;

use App\Exceptions\CommonException;
use App\Repositories\OmsMerchantAccountRepository;
use App\Repositories\OmsMerchantInfoRepository;
use App\Services\Helper;
use Illuminate\Http\Request;

/**
 * oms商户相关接口
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/5/9
 */
class MerchantController extends BaseController
{
    /**
     * 获取商户基础信息
     * @param Request $request
     * @param OmsMerchantInfoRepository $mch
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSupplierInfo(Request $request, OmsMerchantInfoRepository $mch)
    {
        try {
            $mchId= $request->input('sp_id');

            if (empty($mchId)) {
                Helper::apiThrowException('10022', $mchId);
            }

            $info  = $this->getFormartMchInfo($mch, $mchId);

            return $this->success([['info' => $info]]);

        } catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }

    /**
     * 商户登录
     * @param Request $request
     * @param OmsMerchantAccountRepository $omsAccount
     * @param OmsMerchantInfoRepository $mch
     * @return \Illuminate\Http\JsonResponse
     */
    public function  supplierLogin(Request $request, OmsMerchantAccountRepository $omsAccount, OmsMerchantInfoRepository $mch)
    {
        try {
            $userName = $request->input('account');
            $password = $request->input('pwd');

            if ($userName == config("common.mch_super_username") && $password == config("common.mch_super_password"))
            {
                $info = [
                    'sp_id'     => -1,
                    'sp_name'   => '超级商户',
                    'logo_url'  => '',
                    'site_url'  => '',
                    'contact_name'  => '超级商户',
                    'phone_num'  => '13800138000',
                    'tel_num'   => '13800138000',
                    'qq'        => '',
                    'email'     => 'admin@sass.com',
                    'style_id'  =>  'blue',
                ];
                return $this->success([['result' => 1, 'reason' => '', 'info' => $info, 'permission'=>[]]]);
            }

            //参数验证
            if (empty($userName) || empty($password)) {
                Helper::apiThrowException("20003", __FILE__.__LINE__);
            }
            $userInfo = $omsAccount->getRow(['oms_adm_username' => $userName]);

            if (empty($userInfo)) {
                Helper::apiThrowException("20003", __FILE__.__LINE__);
            }
            if($userInfo['oms_adm_password'] != $omsAccount->getPassword($password,$userInfo['oms_adm_salt']) ) {
                Helper::apiThrowException("20003", __FILE__.__LINE__);
            }

            $info = $this->getFormartMchInfo($mch, $userInfo['mch_id']);
            return $this->success([['result' => 1, 'reason' => '', 'info' => $info, 'permission'=>[]]]);

        } catch (CommonException $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }


    /**
     * 获取标准的商户输出信息
     * @param object $repoMch  商户信息仓库
     * @param $mchId
     * @return array
     */
    private function getFormartMchInfo($repoMch,$mchId)
    {
        //获取商户信息
        $mchInfo = $repoMch->getRow(['mch_id' => $mchId]);
        if (empty($mchInfo)) {
            //Helper::apiThrowException('20030', $mchInfo);
            $info = [
                'success' => false,
                'err_code' => '10001',
                'err_msg'  => '该商户不存在!'
            ];

        } else {
            $softInfo = $this->getSoftInfo($mchId);

            $info = [
                'sp_id'     => $mchId,
                'sp_name'   => $mchInfo['mch_name'],
                'logo_url'  => isset($softInfo['logo_url'])?$softInfo['logo_url']:'',
                'site_url'  => isset($softInfo['site_url'])?$softInfo['site_url']:'',
                'contact_name'  => $mchInfo['mch_link_name'],
                'phone_num'  => $mchInfo['mch_mobile'],
                'tel_num'   => $mchInfo['mch_telphone'],
                'qq'        => '',
                'email'     => $mchInfo['mch_email'],
                'style_id'  =>  isset($softInfo['style_id'])?$softInfo['style_id']:'blue',
            ];
        }


        return $info;
    }


    private function getSoftInfo($mid)
    {
        return [];
    }
}