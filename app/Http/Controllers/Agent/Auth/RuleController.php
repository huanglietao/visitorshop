<?php
namespace App\Http\Controllers\Agent\Auth;

use App\Http\Controllers\Agent\BaseController;
use App\Repositories\DmsAuthGroupRepository;
use Illuminate\Http\Request;

/**
 * 权限管理->角色管理
 * DMS管理员角色组
 * @author: cjx
 * @version: 1.0
 * @date: 2020-04-27
 */
class RuleController extends BaseController
{
    protected $viewPath = 'agent.auth.rule';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块

    public function __construct(DmsAuthGroupRepository $authGroupRepository)
    {
        $this->repositories = $authGroupRepository;
    }

    public function index()
    {
        $setting = $this->getSetting();
        $pageLimit = $setting['default_pages_limit'];
        return view('agent.auth.rule.index',['pageLimit'=>$pageLimit]);

    }

    //ajax方式获取列表
    public function table(Request $request)
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
                $rule= $this->repositories->getDmsruleList($request->input('id'));

                $row = $this->repositories->getById($request->input('id'));

                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row,'data'=>$rule]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }
    }

    //添加/编辑操作
    public function save(Request $request)
    {
        $post = $request->all();

        try{
            if(isset($post['checkedall'])||isset($post['expandall'])){
                unset($post['checkedall']);
                unset($post['expandall']);
            }

            $ret = $this->repositories->save($post,session("admin")['dms_adm_group_id']);
            if ($ret) {
                return $this->jsonSuccess([]);
            } else {
                return $this->jsonFailed('');
            }
        }catch (CommonException $re){
            return $this->jsonFailed($re->getMessage());
        }

    }
}