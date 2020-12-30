<?php
namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Merchant\BaseController;
use App\Models\OmsNews;
use App\Models\OmsSystemSetting;
use App\Repositories\SaasArticleRepository;
use Illuminate\Http\Request;

/**
 * 商户系统首面
 *
 * 功能详细说明
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/10/12
 */
class IndexController extends BaseController
{
    public function __construct(OmsSystemSetting $omsSystemSetting,SaasArticleRepository $articleRepository)
    {
        parent::__construct();
        $this->omsSystemModel = $omsSystemSetting;
        $this->artReposities = $articleRepository;
        //获取商户id
        $this->mid = empty(session('admin')) == false ? session('admin')['mch_id'] : ' ';

    }

    public function index()
    {
        //判断有没有平台菜单
        $is_flag = $this->getMchRuleType();
        $oms_rule_flag = session('oms_rule_flag');
        $paltList = [];
        if ($is_flag){
            $paltList = config('common.oms_rule_flag');
            if (!empty($oms_rule_flag)){
                //切换菜单模式
                $where['oms_auth_rule_flag'] = $oms_rule_flag;
                $menuList = $this->auth->getSidebar('dashboard',$where);
            }else{
                //第一次进平台菜单模式，默认标识存进session
                session(['oms_rule_flag'=>DEFAULT_RULE_FLAG]);
                $where['oms_auth_rule_flag'] = DEFAULT_RULE_FLAG;
                $menuList = $this->auth->getSidebar('dashboard',$where);
            }
        }else{
            $menuList = $this->auth->getSidebar();
        }
        $oms_rule_flag = session('oms_rule_flag');


        $oms_system_info = $this->omsSystemModel->where('mch_id',$this->userInfo['mch_id'])->first();
        //获取商户对应的未读消息
        $artList = $this->getMchNews($this->mid);
        return view('merchant.index',['menuList'=>$menuList,'userInfo'=>$this->userInfo,'systemInfo'=>$this->systemInfo,'omsSystemInfo'=>$oms_system_info,'artList'=>$artList,'paltList' => $paltList,'current_flag' => $oms_rule_flag]);
    }

    //.david 获取大后台未读的公告通知消息数据
    public function getMchNews($mid)
    {
        $newsList = OmsNews::where(['mch_id'=>$mid])->get()->toArray();
        $newsids=[];
        foreach ($newsList as $k=>$v){
            $newsids[] = $v['articles_id'];
        }

        $list = $this->artReposities->getUnReadArticle($newsids,ZERO);
        $num = count($list);
        return $num;
    }

    //获取商家是否开启了平台菜单模式
    public function getMchRuleType()
    {
        return config('common.oms_platform_menu');
    }
    //菜单模式切换
    public function changeRuleFLag(Request $request)
    {
        $post = $request->post();
        //将标识存进session,
        session(['oms_rule_flag'=>$post['flag']]);
        return $this->jsonSuccess([]) ;
    }
}