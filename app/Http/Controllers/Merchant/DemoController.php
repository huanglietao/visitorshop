<?php
namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Agent\DemoRequest;
use App\Models\AgentUserAccount;
use App\Repositories\AgentRepository;
use App\Services\Test;
use Illuminate\Http\Request;

/**
 * 开发demo
 *
 * 以分销账号表为例的demo,实现列表、添加、修改、删除及各组件
 * 给合使用
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/8/5
 */
class DemoController extends BaseController
{
    protected $agentRepository;
    protected $viewPath = 'merchant.demo';
    public function __construct(AgentRepository $agentRepository)
    {
        parent::__construct();
        $this->agentRepository = $agentRepository;
    }
    //列表展示页面
    public function index()
    {
        $setting = $this->getSetting();
        $pageLimit = $setting['default_pages_limit'];
        /*
         * 容器使用方法
         * 在App\Providers\AppServiceProvider中的$singletons里
         * 添加相应的单例容器，再通过app(Test::class)可以得到此实例.
         * 这种做法产生的实例为单例的的对象.大部场影下单例场景就够用了，除非特殊的场景
         * 在同一生命周期内对同一个类要实例化多个对象.这种场景可具体分析具体实现
         * app(Test::class)->go();
        */


        return view("merchant.demo.index");
    }

    //ajax方式获取列表
    public function table(Request $request)
    {
        $inputs = $request->all();

        $list = $this->agentRepository->getAccountList($inputs);
        $htmlContents = $this->renderHtml('',['list' =>$list]);

        $pagesInfo = $list->toArray();

        $total = $pagesInfo['total'];

        return response()->json(['status' => 200, 'html' => $htmlContents,'total' => $total]);
    }


    //表单视图
    public function form(Request $request)
    {
        return parent::form($request);
    }

   //添加/编辑操作
    public function save(DemoRequest $request)
    {
        //AgentUserAccount::create($request->all());
        $this->agentRepository->createAccount($request->all());
    }

    //记录删除
    public function delete(Request $request)
    {

    }
}