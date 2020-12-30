<?php
namespace App\Http\Controllers\Agent;

use App\Exceptions\CommonException;
use App\Http\Controllers\Agent\BaseController;
use App\Http\Requests\Agent\RegisterRequest;
use App\Jobs\Notice;
use App\Models\DmsAgentInfo;
use App\Models\DmsMerchantAccount;
use App\Models\DmsNews;
use App\Models\SaasIsmsSmsLog;
use App\Models\SaasSystemSetting;
use App\Repositories\AgentRepository;
use App\Repositories\DmsAgentApplyRepository;
use App\Repositories\DmsAgentInfoRepository;
use App\Repositories\DmsMerchantAccountRepository;
use App\Repositories\OmsAgentDeployRepository;
use App\Repositories\SaasAdvertisementRepository;
use App\Repositories\SaasArticleRepository;
use App\Repositories\SaasCartRepository;
use App\Repositories\SaasMainTemplatesRepository;
use App\Repositories\SaasProjectsRepository;
use App\Services\Helper;
use Illuminate\Http\Request;
use App\Repositories\SaasSalesChanelRepository;


/**
 * 功能简介
 *
 * 功能详细说明
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/7/30
 */
class IndexController extends BaseController
{
    protected $viewPath = 'agent.index';    //当前控制器所的view所在的目录
    protected $modules = 'sys';             //当前控制器所属模块
    protected $sysId = 'agent';             //当前控制器所属模块
    protected $merchantID = "";
    protected $noNeedRight = ['*'];
    protected $deployInfo;                  //站点配置信息
	protected $noCookie = 'workSuccess';

    public function __construct(SaasMainTemplatesRepository $mainTemplatesRepository, OmsAgentDeployRepository $agentDeployRepository,
                                SaasProjectsRepository $projectsRepository, SaasAdvertisementRepository $adListRepository,
                                SaasSalesChanelRepository $chanelRepository, SaasArticleRepository $articleRepository,
                                SaasCartRepository $cartRepository,SaasSystemSetting $systemSetting,DmsMerchantAccount $dmsAccount,
                                DmsAgentInfo $dmsAgentInfo,SaasIsmsSmsLog $smsLog,AgentRepository $agent)
    {
        parent::__construct();
        $this->mainTemplatesRepository = $mainTemplatesRepository;
        $this->merchantID = empty(session('admin')) == false ? session('admin')['mch_id'] : $this->agent_mid;
        $this->agentId = empty(session('admin')) == false ? session('admin')['agent_info_id'] : '';

        $this->deployInfo = $agentDeployRepository->getDeployInfo($this->merchantID);
        $this->projectRepository = $projectsRepository;
        $this->adListRepositories = $adListRepository;
        $this->channelRepositories = $chanelRepository;
        $this->articleRepositories = $articleRepository;
        $this->cartRepositories = $cartRepository;
        $this->systemModel = $systemSetting;
        $this->dmsAccount = $dmsAccount;
        $this->dmsAgentInfo = $dmsAgentInfo;
        $this->smsLogModel = $smsLog;
        $this->agentRepostory = $agent;

        $this->channel = $this->channelRepositories->getAgentChannleId();//获取渠道id
        //获取最新发布的文章
        $this->wtArtList = $this->articleRepositories->getRows(['art_sign'=>GOODS_MAIN_CATEGORY_HELP,'is_open'=>ONE,'mch_id' => [0,$this->merchantID]],'created_at','desc',4);

        //获取购物车数量
        $cart= $this->cartRepositories->getList(['user_id'=>$this->agentId,'cha_id'=>$this->channel])->toArray();
        if($cart && !empty([0]['cart_info'])){
            $this->cartNum = count(json_decode($cart[0]['cart_info']));
        }else{
            $this->cartNum = '';
        }

    }

    public function index()
    {
        if(empty(session('admin'))){
            return redirect('/index/home');
        }
        //获取商户对应的未读消息
        $artList =$this->getMchNews($this->agentId,$this->merchantID);

        $menuList = $this->auth->getSidebar();
        $system_info = $this->systemModel->first();
        return view($this->viewPath,['menuList'=>$menuList,'userInfo'=>$this->userInfo,'deployInfo'=>$this->deployInfo,'artList'=>$artList,'cartNum'=>$this->cartNum,'systemInfo'=>$system_info]);
    }

    //官网首页 start
    public function home()
    {
        $template = $this->getTemplate();

        //.david 获取广告数据
        $adSpecilList = $this->adListRepositories->getAdvertiseList(['ad_position'=>ONE,'mch_id'=>$this->merchantID,'ad_flag'=>AD_FLAG_AGENT_BJ])->toArray();


        if(!empty($adSpecilList)){ //特殊专属布局
            $adList = $this->adListRepositories->getMakeAdList($adSpecilList);
        }else{
            $adLists = $this->adListRepositories->getAdvertiseList(['mch_id'=>$this->merchantID,'ad_flag'=>AD_FLAG_AGENT_SY,'channel_id'=>$this->channel])->toArray();
            //商户没有广告就取大后台
            if(empty($adLists)){
                $adLists = $this->adListRepositories->getAdvertiseList(['mch_id'=>ZERO,'ad_flag'=>AD_FLAG_AGENT_SY,'channel_id'=>$this->channel])->toArray();
            }
            //如果有多条只获取最后一条
            $adList[]=array_pop($adLists);
            $adList = $this->adListRepositories->getMakeAdList($adList);
        }

        //获取优势块
        $ysList = $this->adListRepositories->getYsAdlist($this->merchantID,$this->channel);
        if(empty($ysList)){
            $ysList[0]['ad_images'] = [];
        }
        //获取合作块
        $hzList = $this->adListRepositories->getHzAdlist($this->merchantID,$this->channel);
        if(empty($hzList)){
            $hzList[0]['ad_images'] = [];
        }
        //获取专属服务块
        $zsList = $this->adListRepositories->getZsAdlist($this->merchantID,$this->channel);
        if(empty($zsList)){
            $zsList[0]['ad_images'] = [];
        }

        return view($this->viewPath.'.home',['template'=>$template,'deployInfo'=>$this->deployInfo,
            'adList'=>$adList,'zsAd'=>$zsList[0]['ad_images'],
            'ysAd'=>$ysList[0]['ad_images'],'hzAd'=>$hzList[0]['ad_images'],'wtArtList'=>$this->wtArtList]);
    }
    //官网首页 end


    public function getTemplate()
    {
        $template_info=$this->mainTemplatesRepository->getTableList(['mch_id'=>[0,$this->merchantID],'main_temp_check_status'=>TEMPLATE_STATUS_VERIFYED],
            "main_temp_use_times desc",16)->toArray();
        $template = $template_info['data'];
        return $template;
    }


    //注册 start
    public function register()
    {
        //判断端口为手机端还是pc端 by .david
        $is_mobile = $this->isMobile();
        $shop_type = config('agent.shop_type');

        if($is_mobile){
            return view($this->viewPath.'.mregister',['shop_type'=>$shop_type,'deployInfo'=>$this->deployInfo,'wtArtList'=>$this->wtArtList]);
        }else{
            return view($this->viewPath.'.register',['shop_type'=>$shop_type,'deployInfo'=>$this->deployInfo,'wtArtList'=>$this->wtArtList]);
        }
    }

    public function save(DmsAgentApplyRepository $Repository,RegisterRequest $request,DmsMerchantAccountRepository $dmsMerchantAccountRepository)
    {
        $params = $request->all();


        $dmsAccount = app(DmsAgentInfoRepository::class);
        $agentApplay = app(DmsAgentApplyRepository::class);
        //验证手机号
        $isRegister = $agentApplay->checkMobile($params['mobile']);
        if($isRegister){
            return $this->jsonFailed('该手机号已被注册,请输入新的手机号');
        }
        //如果邀请码存在，则验证邀请码是否正确
        if(!empty($params['inviter'])){
            $info = $dmsAccount->checkInviter($params['inviter']);
            if(empty($info)){
                return $this->jsonFailed('该邀请码不存在，请重新输入');
            }else{
                $params['inviter_id'] = $info['agent_info_id'];
            }
        }

        $inviter_code = $dmsAccount->getInviterCode();
        $params['inviter_code'] = $inviter_code;

        //手机验证规则
        $chars = "/^1[3|4|5|7|8][0-9]{9}$/";
        if (!preg_match($chars, $params['mobile'])){
            return $this->jsonFailed("请输入正确的手机号");
        }

        //判断省市区如果有值，是否完整
        if(!empty($params['province'])){
            if(empty($params['district']) || $params['district']=='0' || $params['district']=='-1' || $params['district']=='区'){
                return $this->jsonFailed("请把地区选择完整");
            }
        }

        unset($params['_token']);
        unset($params['inviter']);
        $params['mch_id']=$this->agent_mid;
        \DB::beginTransaction();
        try{
            $accountData = [
                'mch_id' => $this->agent_mid,
                'is_main' => ONE,
                'dms_adm_username' => $params['mobile'],
                'dms_adm_password' => $params['dms_adm_password'],
                'dms_adm_status'   => ZERO,
                'dms_adm_avatar'   => '/images/defaultHead.png',
                'dms_adm_mobile'   => $params['mobile']
            ];
            unset($params['dms_adm_username']);
            unset($params['dms_adm_password']);
            $apply_id = $Repository->save($params);
            if($apply_id){
                $accountData['agent_info_id'] = $apply_id;
            }
            $ret = $dmsMerchantAccountRepository->save($accountData);
            if ($ret) {
                \DB::commit();
                return $this->jsonSuccess([]);
            } else {
                \DB::rollBack();
                return $this->jsonFailed('');
            }
        }catch (CommonException $e){
            \DB::rollBack();
            return $this->jsonFailed($e->getMessage());
        }
    }
    //注册 end

    //作品提交成功页
    public function workSuccess(Request $request)
    {
        $param = $request->all();
        $data = $this->projectRepository->handleWorkSuccess($param);
        $msg = '';
        if(empty($data)){
            $msg = '非法链接，请输入正确的链接';
        }

        return view('agent.index.work_success',['deployInfo'=>$this->deployInfo,'data'=>$data,'wtArtList'=>$this->wtArtList,'msg'=>$msg]);
    }

    //.david 获取大后台未读的公告通知消息数据
    public function getMchNews($agentid,$mid)
    {
        $newsList = DmsNews::where(['agent_id'=>$agentid])->get()->toArray();
        $newsids=[];
        foreach ($newsList as $k=>$v){
            $newsids[] = $v['articles_id'];
        }

        $list = $this->articleRepositories->getUnReadArticle($newsids,$mid);
        $num = count($list);
        return $num;
    }


    //验证手机号码是否已被注册
    public function checkMobile(Request $request)
    {
        $params = $request->all();
        $dmsAccount = app(DmsAgentApplyRepository::class);
        $isRegister = $dmsAccount->checkMobile($params['mobile']);
        if($isRegister){
            return $this->jsonFailed('该手机号已被注册,请输入新的手机号');
        }else{
            return $this->jsonSuccess([]);
        }
    }

    //忘记密码
    public function forget()
    {
        return view('agent.index.forget',['deployInfo'=>$this->deployInfo,'wtArtList'=>$this->wtArtList]);
    }

    //检查账户
    public function checkName(Request $request)
    {
        $name = $request->post('agent_name');
        $is_user = $this->dmsAccount->where(['dms_adm_username'=>$name])->first();
        if(empty($is_user)){
            return $this->jsonFailed('账户不存在');
        }else{
            $info = $this->dmsAgentInfo->where(['agent_info_id'=>$is_user['agent_info_id']])->select('mobile')->first();
            $mobile = str_replace(substr($info['mobile'],3,4),'****',$info['mobile']);
            return $this->jsonSuccess($mobile);
        }
    }

    //获取验证码
    public function getCode(Request $request)
    {
        $name = $request->post('agent_name');
        $user_info = $this->dmsAccount->where(['dms_adm_username'=>$name])->first();
        $mobile = $this->dmsAgentInfo->where(['agent_info_id'=>$user_info['agent_info_id']])->value('mobile');

        $sms_log = $this->smsLogModel->where(['sms_mobile'=>$mobile])->orderBy('created_at','desc')->first();
        $last_time = isset($sms_log['created_at']) ? $sms_log['created_at'] : 0;

        if(time() - $last_time < 60){
            //60秒内不可重复发送
            return $this->jsonFailed('请在1分钟后重新点击发送');
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

        //记录数据库
        $ins_data = [
            'sms_mobile' => $mobile,
            'sms_code' => $code,
            'created_at' => time(),
        ];
        $this->smsLogModel->create($ins_data);

        session(['sms_code' => $code]);
        session(['sms_send_time' => time()]);

        return $this->jsonSuccess('发送成功');

    }

    //校对验证码并修改密码
    public function verification(Request $request)
    {
        $name = $request->post('agent_name');
        $code = $request->post('code');
        $password = $request->post('password');

        $user_info = $this->dmsAccount->where(['dms_adm_username'=>$name])->first();
        $mobile = $this->dmsAgentInfo->where(['agent_info_id'=>$user_info['agent_info_id']])->value('mobile');

        $sms_log = $this->smsLogModel->where(['sms_mobile'=>$mobile])->orderBy('created_at','desc')->first()->toArray();

        $add_time = date($sms_log['created_at']);
        $now_time = date(time());
        $minute = ceil(($now_time - $add_time)%86400/60);

        if($minute >= 10){
            //超过10钟则过期
            return $this->jsonFailed('验证码已过期，请重新发送');
        }

        if($code != $sms_log['sms_code']){
            return $this->jsonFailed('验证码错误');
        }else{
            //修改密码
            $data['dms_adm_salt'] = Helper::build();
            $data['dms_adm_password'] = $this->agentRepostory->setPassword($password,$data['dms_adm_salt']);
            $data['updated_at'] = time();

            $this->dmsAccount->where('dms_adm_id',$user_info['dms_adm_id'])->update($data);

            return $this->jsonSuccess('修改成功');
        }

    }

    //商业印刷作品提交成功页
    public function comlWorkSuccess(Request $request)
    {
        $param = $request->all();
        $param['is_mobile'] = $this->isMobile();

        $data = $this->projectRepository->handleComlWorkSuccess($param);
        if(empty($data)){
            die;
        }
        return view('agent.index.comlwork_success',['deployInfo'=>$this->deployInfo,'data'=>$data,'wtArtList'=>$this->wtArtList]);
    }





}