<?php
namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Agent\BaseController;
use App\Exceptions\CommonException;
use App\Jobs\Notice;
use App\Models\SaasIsmsSmsLog;
use App\Repositories\DmsAgentInfoRepository;
use App\Repositories\DmsMerchantAccountRepository;
use App\Repositories\SaasCustomerLevelRepository;
use App\Services\Helper;
use Illuminate\Http\Request;

/**
 * 系统设置
 *
 * 实现商户基本信息修改和密码管理
 * @author: liujh
 * @version: 1.0
 * @date: 2020/8/6
 */
class SystemController extends BaseController
{
    public function __construct(DmsAgentInfoRepository $dmsAgentInfoRepository,SaasCustomerLevelRepository $customerLevelRepository,
                                DmsMerchantAccountRepository $dmsMerchantAccountRepository,Helper $helper)
    {
        parent::__construct();
        $this->merchantID = empty(session('admin')) == false ? session('admin')['mch_id'] : $this->agent_mid;
        $this->agentId = empty(session('admin')) == false ? session('admin')['agent_info_id'] : '';
        $this->adminId = empty(session('admin')) == false ? session('admin')['dms_adm_id'] : '';

        $this->dmsAgentInfoRepository = $dmsAgentInfoRepository;
        $this->customerLevelRepository = $customerLevelRepository;
        $this->dmsMerchantAccountRepository = $dmsMerchantAccountRepository;
        $this->helper = $helper;
        $this->agentInfo = $dmsAgentInfoRepository->getById($this->agentId)->toArray();
        $this->isMain = ZERO;
    }

    public function index()
    {
        return view('agent.system.index');
    }

    //基本信息页面
    public function basicInfo(Request $request)
    {
        //登录账号是否是主账号
        $admin = $this->dmsMerchantAccountRepository->getById($this->adminId)->toArray();
        if($admin['is_main']){
            $this->isMain = ONE;
        }

        //获取该商家的主账号
        $mainAccount = $this->dmsMerchantAccountRepository->getList(['mch_id'=>$this->merchantID,'agent_info_id'=>$this->agentId,'is_main'=>ONE])->toArray();
        if(empty($mainAccount)){
            $mainAccount[0] = [
                'dms_adm_username'=>'',
                'dms_adm_status' =>0
            ];
        }
        //商家信息
        $agentInfo = $this->agentInfo;
        $agentInfo['cust_lv_name'] = "";
        //等级
        $leName = $this->customerLevelRepository->getList(['cust_lv_type'=>CHANEL_TERMINAL_AGENT,'cust_lv_id'=>$agentInfo['cust_lv_id']])->toArray();
        if(!empty($leName)){
            $agentInfo['cust_lv_name'] = $leName[0]['cust_lv_name'];
        }

        $agentType = config("agent.shop_type");
        $helper = app(Helper::class);
        $agentType = $helper->getChooseSelectData($agentType);

        $htmlContents = $this->renderHtml("agent.system._basicinfo",['account'=>$mainAccount[0],'info'=>$agentInfo,'type'=>$agentType,'isMain'=>$this->isMain]);
        return response()->json(['status' => 200, 'html' => $htmlContents]);
    }

    //密码管理页面
    public function pwdManagement(Request $request)
    {
        if($request->ajax())
        {
            //登录账号是否是主账号
            $admin = $this->dmsMerchantAccountRepository->getById($this->adminId)->toArray();
            if($admin['is_main']){
                $this->isMain = ONE;
            }
            $htmlContents = $this->renderHtml("agent.system._pwdmanagement",['isMain'=>$this->isMain,'info'=>$this->agentInfo]);
            return response()->json(['status' => 200, 'html' => $htmlContents]);
        }else{
            return view("agent.system._pwdmanagement");
        }
    }

    //基本信息修改
    public function baseSave(Request $request)
    {
        $params = $request->all();
        unset($params['_token']);
        $params['agent_info_id'] = $this->agentId;
        try{
            \DB::beginTransaction();
            $ret = $this->dmsAgentInfoRepository->save($params);
            if($ret){
                \DB::commit();
                return $this->jsonSuccess([]);
            }
        }catch (CommonException $e){
            \DB::rollBack();
            return $this->jsonFailed($e->getMessage());
        }
    }

    //密码管理修改
    public function pwdSave(Request $request)
    {
        $params = $request->all();
        $info = $this->agentInfo;
        //如果什么信息都没有填写
        if(empty($params['old_pwd']) && empty($params['new_pwd']) && $params['payword']==$info['is_open_pay']){
            return $this->jsonFailed("请填写要修改的信息");
        }
        $flag = true;
        try{
            \DB::beginTransaction();
            if(!empty($params['old_pwd']) && !empty($params['new_pwd'])){
                $accountInfo = $this->dmsMerchantAccountRepository->getById($this->adminId)->toArray();
                $md5Pwd = $this->dmsMerchantAccountRepository->setPassword($params['old_pwd'],$accountInfo['dms_adm_salt']);
                if($md5Pwd!=$accountInfo['dms_adm_password']){
                    return $this->jsonFailed("旧登录密码错误，请重新输入");
                }else{
                    $data = [
                        'dms_adm_id'=>$this->adminId,
                        'dms_adm_password'=>$params['new_pwd']
                    ];
                    $ret = $this->dmsMerchantAccountRepository->save($data);
                    if(!$ret){
                        $flag = false;
                    }
                }
            }

            //如果存在旧支付密码，判断输入的是否正确
            if(isset($params['payword'])){
                if(!empty($params['payword'])){
                    $payword = $this->dmsAgentInfoRepository->setPassword($params['old_pay_pwd'],$info['payword_salt']);
                    if($payword!=$info['payword']){
                        return $this->jsonFailed("旧支付密码错误，请重新输入");
                    }
                }

                //如果选择的支付状态与存在的不同
                if($params['payword']!=$info['is_open_pay']){
                    if(empty($params['payword'])){
                        $info_data = [
                            'agent_info_id'=>$this->agentId,
                            'is_open_pay'=>ZERO
                        ];
                    }
                    else if($params['payword']==2){
                        $salt = $this->helper->build();
                        $payword = $this->dmsAgentInfoRepository->setPassword($params['new_pay_pwd'],$salt);

                        $info_data = [
                            'agent_info_id'=>$this->agentId,
                            'payword'=>$payword,
                            'payword_salt'=>$salt,
                            'is_open_pay'=>ONE
                        ];

                    }
                    $iRet = $this->dmsAgentInfoRepository->save($info_data);
                    if(!$iRet){
                        $flag = false;
                    }
                }
            }

            if($flag){
                \DB::commit();
                return $this->jsonSuccess([]);
            }
        }catch (CommonException $e){
            \DB::rollBack();
            return $this->jsonFailed($e->getMessage());
        }

    }


    //获取验证码
    public function getCode(Request $request)
    {
        $mobile = $request->post('mobile');
        $smsLogModel = app(SaasIsmsSmsLog::class);
        $sms_log = $smsLogModel->where(['sms_mobile'=>$mobile])->orderBy('created_at','desc')->first();
        $last_time = isset($sms_log['created_at']) ? $sms_log['created_at'] : 0;

        if(time() - $last_time < 60){
            //60秒内不可重复发送
            return $this->jsonFailed(['msg'=>'请在1分钟后重新点击发送','code'=>$sms_log['sms_code'],'time'=>$last_time]);
        }

        //生成验证码 用于发送验证码
        $code = mt_rand(100000,999999);

        //所需传入的数据
        $messageData = [
            'type' => 'sms',
            'options' => [
                'query' => [
                    'RegionId' => "cn-hangzhou",
                    'PhoneNumbers' => $mobile,
                    'SignName' => "爱美印",
                    'TemplateCode' => "SMS_164825679",
                    'TemplateParam' => "{'code':".$code."}",
                ]
            ]
        ];

        //放入消息通知队列
        $ret = Notice::dispatch($messageData)->onQueue('q1');

        $time = time();
        //记录数据库
        $ins_data = [
            'sms_mobile' => $mobile,
            'sms_code' => $code,
            'created_at' => $time,
        ];
        $smsLogModel->create($ins_data);
        return $this->jsonSuccess(['code'=>$code,'time'=>$time]);

    }

}