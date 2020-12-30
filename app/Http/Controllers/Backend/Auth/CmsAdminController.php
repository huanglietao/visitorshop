<?php
namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Backend\BaseController;
use App\Http\Requests\Backend\Auth\CmsAdminRequest;
use App\Repositories\CmsAdminRepository;
use App\Repositories\CmsAuthGroupRepository;
use Illuminate\Http\Request;
use App\Services\Helper;
use Validator;

/**
 * 项目说明
 * 详细说明
 * @author:
 * @version: 1.0
 * @date:
 */
class CmsAdminController extends BaseController
{
    protected $viewPath = 'backend.auth.cmsadmin';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $group = '';

    public function __construct(CmsAdminRepository $Repository,CmsAuthGroupRepository $CmsGroupRepository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->groupRepositories = $CmsGroupRepository;
        // 获取角色组数据
        $this->group = $this->groupRepositories->getCmsGroupList();
        //获取分类数组标识
        $this->groupsList = Helper::getChooseSelectData($this->group);
    }
    //列表
    public function index()
    {
        return view('backend.auth.cmsadmin.index',['groups'=>$this->groupsList]);
    }

    //ajax 获取数据
    protected function table(Request $request)
    {
        $inputs = $request->all();
        $list = $this->repositories->getTableList($inputs);

        $htmlContents = $this->renderHtml('',['list' =>$list,'authGroup'=>$this->group]);

        $pagesInfo = $list->toArray();

        $total = $pagesInfo['total'];

        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
    }
    //表单加载
    public function form(Request $request)
    {
        try {
            if($request->ajax())
            {
                $row = $this->repositories->getByIdFromCache($request->input('id'));

                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row,'groupList'=>$this->group]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }
    }
   //添加/编辑操作
    public function save(CmsAdminRequest $request)
    {
        $ret = $this->repositories->save($request->all());
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('没有更新修改无需提交');
        }
    }

}