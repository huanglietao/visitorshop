<?php
namespace App\Http\Controllers;

use App\Exceptions\CommonException;
use App\Jobs\AccessLog;
use App\Models\SaasSystemSetting;
use App\Models\SassProductsMedia;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 控制器基类
 *
 * 实现控制器通用的一些处理方法
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/7/30
 */

class BaseController extends Controller
{
    protected $tableTpl = '_table';                                 //通用列表名称
    protected $form  =  '_form';                                    //通用form表单名称
    protected $tipSuccessTpl = 'component.tipsSuccess';
    protected $tipWarnTpl = 'component.tipsWarn';
    protected $viewPath  = '' ;                                     //当前控制器的视图目录
    protected $modules = 'sys';                                     //当前功能所属模块
    protected $sysId = 'backend';                                   //当前子系统 默认为大后台
    protected $repositories = '';                                   //数据仓库
    protected $noLog = '';                                          //不记录操作日志，默认为空
    protected $auth = '';                                           //权限控制
    protected $auth_group = '';                                     //用户组数据表名
    protected $auth_rule = '';                                      //权限规则表
    protected $auth_user = '';                                      //用户信息表
    protected $system = '';                                         //系统标识
    protected $noNeedRight = [];                                    //无需检查权限
    protected $userInfo;                                            //账号信息
    protected $systemInfo;                                          //系统基础配置



    public function __construct()
    {

        $setting = $this->getSetting();
        //view()->share('pageLimit', $setting['default_pages_limit']);
        \Config::set('pageLimit',$setting['default_pages_limit']);

        //获取操作人id
        if (\Request::session()->has('admin')){
            $userinfo = \Request::session()->get('admin');
                $operator_id = $userinfo['admin_id'];
        }else{
            $operator_id = null;
        }
        //获取不进行记录操作日志功能的方法串
        $noLogArr = explode(',',$this->noLog);

        //获取当前的方法
        $nowFunction = $this->getControllerAndFunction();

        //判断当前方法是否要记入操作日志
        if (!in_array($nowFunction['method'],$noLogArr)){

            //记录所有请求的_GET与_POST数据作为日志(异步,走队列)
            if(config("app.access_log_enable")) {
                //记日志放入队列
                $data = [
                    'sys'         => $this->sysId,  //所属系统
                    'modules'     => $this->modules, //所属模块
                    'router'      => \Request::getRequestUri(), //当前路由
                    'data'        => ['get'=>$_GET, 'post'=>$_POST],
                    'operator_id' => $operator_id,
                    'add_time'    => time()
                ];
                AccessLog::dispatch($data)->onQueue('logs');
             }

        }

        $url = Input::url();
        //ERP系统跳过权限控制
        if(strpos($url,env('ERP_URL')) === false){

            //获取当前请求url
            $currentUrl = \Request::getRequestUri();

            $commonTips = ['/tips_success','/tips_warn'];

            if(!in_array($currentUrl,$commonTips)){
                //实例化权限控制类
                $this->auth = new AuthController($this->auth_group,$this->auth_rule,$this->auth_user,$this->system);

                //检查路由访问权限
//                if($currentUrl != '/'){
//                    if(! $this->auth->mathUrl($currentUrl,$this->noNeedRight)){
//                        echo "无访问权限";
//                        die;
//                    }
//                }
            }
        }
        $this->userInfo = session('admin');
        $this->systemInfo = SaasSystemSetting::orderBy('setting_id')->first();


    }

    /**
     * 直接把视图转化为html返回
     * @param $tpl 模板名称
     * @param array $data 传入视图的变量
     * @return string html字符串
     */
    protected function renderHtml($tpl,$data = [])
    {
        if (empty($tpl)) {
            $tpl = $this->viewPath.'.'.$this->tableTpl;
        }
        return View::make($tpl,$data)->render();
    }
    /**
     * 获取系统配置
     */
    protected function getSetting()
    {
        //从系统配置里面取，现在我先写死
        return [
            'default_pages_limit' => config("common.page_limit")
        ];
    }
    /**
     * 成功提示
     * @param $tpl 模板名称
     * @param array $data 传入视图的变量
     * @return string html字符串
     */
    public function tipsSuccess(Request $request)
    {
        $tpl = $this->tipSuccessTpl;
        $post = $request->post();
        $text = $post['text']?$post['text']:null;
        $interval = $post['interval']?$post['interval']:1;
        $title = $post['title']?$post['title']:null;


        $data = [
            "html" => $text,
            "interval" => $interval,
            "title"  => $title,
        ];

        $content = $this->renderHtml($tpl,['data' =>$data]);
        return response()->json(['status' => 200, 'html' => $content]);


    }
    /**
     * 删除记录提示
     * @param $tpl 模板名称
     * @param array $data 传入视图的变量
     * @return string html字符串
     */
    public function tipsWarn(Request $request)
    {
        $tpl = $this->tipWarnTpl;
        $post = $request->post();
        $text = $post['text']?$post['text']:null;
        $url = $post['url']?$post['url']:null;
        $title = $post['title']?$post['title']:null;
        $recover = $post['recover']?$post['recover']:"0";
        $isCallback = $post['is_callback']?$post['is_callback']:"0";
        $isComfirm = $post['is_comfirm']?$post['is_comfirm']:"1";

        $data = [
            "html" => $text,
            "url"  => $url,
            "title"  => $title,
            "recover"  => $recover,
            "is_callback"  => $isCallback,
            "is_comfirm"  => $isComfirm,
        ];

        $content = $this->renderHtml($tpl,['data' =>$data]);
        return response()->json(['status' => 200, 'html' => $content]);


    }
    /**
     * 功能首页结构view
     * @return mixed
     */
    protected function index()
    {
        return $this->view('index');
    }

    /**
     * ajax获取列表项
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function table(Request $request)
    {

        $inputs = $request->all();
        $list = $this->repositories->getTableList($inputs);
        $htmlContents = $this->renderHtml('',['list' =>$list]);

        $pagesInfo = $list->toArray();

        $total = $pagesInfo['total'];

        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
    }
    /**
     * 通用表单展示
     * @param Request $request
     * @return mixed
     */
    protected function form(Request $request)
    {
        try {
            if($request->ajax())
            {
                $row = $this->repositories->getById($request->input('id'));
                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {

            return $this->jsonFailed($e->getMessage());
        }

    }

    /**
     * 删除记录(软删除)
     * @param Request $request
     * @return bool
     */
    protected function delete(Request $request)
    {
        $ret = $this->repositories->delete($request->id);
        if($ret) {
            return $this->jsonSuccess(['']);
        } else {
            return $this->jsonFailed("");
        }
    }

    /**
     * 返回成功的json
     * @param $data
     * @return array
     */
    protected function jsonSuccess($data)
    {
        return response()->json(['status' => 200, 'success' => 'true', 'data' => $data]);
    }

    /**
     * 返回失败的json
     * @param $msg
     * @return array
     */
    protected function jsonFailed($msg)
    {
        return response()->json(['status' => 404, 'success' => 'false', 'message' =>$msg]);
    }


    /**
     *
     * @param $path view的具体文件
     * @return mixed
     */
    protected function view($path)
    {
        return \view($this->viewPath.'.'.$path);
    }

    /**
     * 通用抛出异常
     * @param int $code 错误代码，映射配置文件config/exception.php中的数组索引
     * @param string $pos  错误位置
     * @param array $lang_key_val  替换异常语言包里的参数,数组，与语言包里key对应.
     * @param bool $is_notice  是否发送通知
     * @param string $notice_who  通知的对象
     * @param null $ext_info    附带的额外日志信息以及
     * @throws CommonException 抛出公用异常
     */
    protected function throwControllerException($code, $pos, $lang_key_val= null, $is_notice = false, $notice_who = "all", $ext_info = null)
    {
        //将错误行数存到配置中(单生命周期有效)
        \Config::set("exception_pos", $pos);
        //获取错误码对应的错误信息
        $exceptionMessage = $this->getExceptionMessageByCode($code, $lang_key_val);

        //抛出异常
        throw new CommonException($exceptionMessage, $code, $is_notice, $notice_who, $ext_info);

    }

    /**
     * 通过错误代码获取错误信息,
     * @param $code 错误代码
     * @param $lang_key_val 替换异常语言包里的参数,数组，与语言包里key对应.
     * @return string  $strComposeException  信息1||信息2
     */
    private function getExceptionMessageByCode($code, $lang_key_val)
    {
        //通过code获取获取错误信息
        $exceptionConfig = config("exception")[$code];
        //将错识误信息以'||'连接传到exception
        $allException =__($exceptionConfig);
        $strComposeException = ''; //把异常信息用||连接起来

        if(is_array($allException)) {
            //先把异常信息循环看是不是替换(循环替换)
            if(!empty($lang_key_val)) {

                $tempExceptionArr = [];
                $allException = array_values($allException);

                foreach ($allException as $k=>$v) {
                    if(count($lang_key_val) == 1) {
                        $tempExceptionArr[$k] = __($v,['key' => $lang_key_val[0]]);
                    } else {
                        $tempExceptionArr[$k] = __($v,['key' => $lang_key_val[$k]]);
                    }

                }
                $strComposeException = implode('||', $tempExceptionArr);

            } else {
                $strComposeException = implode('||', $allException);
            }

        } else {
            $strComposeException = $allException;
        }

        return $strComposeException;
    }

    /**
     * @return array
     * 获取控制器和方法名
     */
    function getControllerAndFunction()
    {
        $action = \Route::current()->getActionName();
        list($class, $method) = explode('@', $action);
        $class = substr(strrchr($class,'\\'),1);
        return ['controller' => $class, 'method' => $method];
    }


    /**
     * 获得面包屑导航
     * @param int $id
     * @return string
     */
    public function getBreadCrumb($id= '',$path = '')
    {
        $ruleList =  DB::table($this->system.'_auth_rule')->get()->toArray();
        $ruleLists = json_decode(json_encode($ruleList), true); //先编码成json字符串，再解码成数组

        $ruleIds = [];
        foreach ($ruleLists as $k=>$v)
        {
            $ruleIds[$v[$this->system.'_auth_rule_id']][$this->system.'_auth_rule_title'] = $v[$this->system.'_auth_rule_title'];
            $ruleIds[$v[$this->system.'_auth_rule_id']][$this->system.'_auth_rule_pid'] = $v[$this->system.'_auth_rule_pid'];
        }

        $bread = '';
        while (isset($ruleIds[$id])){
            $bread.= $ruleIds[$id][$this->system.'_auth_rule_title'].'/';
            $id= $ruleIds[$id][$this->system.'_auth_rule_pid'];
        }
        //去除最后'/'并且把正确的排序内容父级在前面
        $breadcrumb = substr($bread,0,-1);
        $crumbArr = explode('/',$breadcrumb);
        krsort($crumbArr);
        $breadcrumb = implode('/',$crumbArr);

        return $breadcrumb;
    }



}
