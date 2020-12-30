<?php
namespace App\Http\Controllers\Agent\Auth;

use App\Http\Controllers\BaseController;
use App\Repositories\AgentRepository;
use Illuminate\Http\Request;
/**
 * 权限列表
 *

 */
class DailyController extends BaseController
{
    protected $agentRepository;
    public function __construct(AgentRepository $agentRepository)
    {
        $this->agentRepository = $agentRepository;
    }

    public function index()
    {
        $setting = $this->getSetting();
        $pageLimit = $setting['default_pages_limit'];
        return view('agent.auth.daily.index',compact('pageLimit'));
    }

    //ajax方式获取列表
    public function table(Request $request)
    {
        $htmlContents = $this->renderHtml('agent.auth.daily._table');

        return response()->json(['status' => 200, 'html' => $htmlContents,'total' => 56]);
    }

    public function detail(Request $request)
    {
        $htmlContents = $this->renderHtml('agent.auth.daily._form');
        return response()->json(['status' => 200, 'html' => $htmlContents]);
    }
}