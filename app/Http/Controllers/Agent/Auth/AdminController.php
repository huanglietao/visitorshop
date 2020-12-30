<?php
namespace App\Http\Controllers\Agent\Auth;

use App\Http\Controllers\Agent\BaseController;
use App\Repositories\AgentRepository;
use Illuminate\Http\Request;

/**
 * 权限管理->管理员列表
 * DMS管理员列表
 * @author: cjx
 * @version: 1.0
 * @date: 2020-04-26
 */
class AdminController extends BaseController
{
    protected $viewPath = 'agent.auth.admin';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块

    public function __construct(AgentRepository $agentRepository)
    {
        $this->repositories = $agentRepository;
        $this->agtGroupList = $this->repositories->getGroupList(session("admin")['agent_info_id']);
    }

    public function index()
    {
        $setting = $this->getSetting();
        $pageLimit = $setting['default_pages_limit'];
        return view('agent.auth.admin.index',['pageLimit'=>$pageLimit,'agtGroupList' =>$this->agtGroupList]);
    }

    //ajax方式获取列表
    public function table(Request $request)
    {

        $inputs = $request->all();
        $list = $this->repositories->getTableList($inputs,'dms_adm_id asc');

        $htmlContents = $this->renderHtml('',['list' =>$list,'groupList' =>$this->repositories->getGroupList(session("admin")['agent_info_id'],'all')]);

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
                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row,'agtGroupList'=>$this->agtGroupList]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }
    }

    //添加、编辑操作
    public function save(Request $request)
    {
        $param = $request->all();
        unset($param['_token']);

        if($param['dms_adm_password'] != $param['confirm_password']){
            return $this->jsonFailed('登录密码和确认密码不一致');
        }

        $ret = $this->repositories->save($param);
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }
}