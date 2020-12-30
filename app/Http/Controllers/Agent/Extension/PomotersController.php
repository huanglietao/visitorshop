<?php
namespace App\Http\Controllers\Agent\Extension;

use App\Http\Controllers\Agent\BaseController;
use App\Repositories\DmsAgentInfoRepository;
use App\Repositories\DmsMerchantAccountRepository;
use Illuminate\Http\Request;

/**
 * 推广人列表
 * @author: cjx
 * @version: 1.0
 * @date: 2020-08-07
 */
class PomotersController extends BaseController
{
    protected $viewPath = 'agent.extension.pomoters';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块

    public function __construct(DmsAgentInfoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $setting = $this->getSetting();
        $pageLimit = $setting['default_pages_limit'];
        $self_info = $this->repository->getSelfInfo();
        return view('agent.extension.pomoters.index',['pageLimit'=>$pageLimit,'self_info'=>$self_info]);

    }

    //ajax方式获取列表
    public function table(Request $request)
    {
        $inputs = $request->all();
        $list = $this->repository->getPomotersList($inputs);
        $htmlContents = $this->renderHtml('',['list' =>$list['data']]);
        $total = $list['total'];

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