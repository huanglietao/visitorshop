<?php
namespace App\Http\Controllers\Agent;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Repositories\NewsRepository;


/**
 * 消息管理控制器
 *
 * 实现列表查看功能
 * @author: daiyd
 * @version: 1.0
 * @date: 2019/8/6
 */
class NewsController extends BaseController
{

    protected $newsRepository;
    protected $viewPath = 'agent.news';
    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }
    //列表展示页面
    public function index()
    {
        $setting = $this->getSetting();
        $pageLimit = $setting['default_pages_limit'];
        return view("agent.news.index",compact('pageLimit'));
    }

    //ajax方式获取列表
    public function table(Request $request)
    {
        $inputs = $request->all();

        $list = $this->newsRepository->getNewsList($inputs);

        $htmlContents = $this->renderHtml('',['list' =>$list]);

        $pagesInfo = $list->toArray();

        $total = $pagesInfo['total'];

        return response()->json(['status' => 200, 'html' => $htmlContents,'total' => $total]);

    }

    //列表展示页面
    public function detail(Request $request)
    {
        $setting = $this->getSetting();
        $pageLimit = $setting['default_pages_limit'];
        return view("agent.news.detail",compact('pageLimit'));
        $id = $request->route("id");
        $info = $this->newsRepository->getNewsDetail($id);

        $htmlContents = $this->renderHtml('agent.news.detail',['info' =>$info]);

        return response()->json(['status' => 200, 'html' => $htmlContents]);
    }



}