<?php
namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Backend\BaseController;
use App\Http\Requests\Backend\Auth\CmsAuthGroupRequest;
use App\Repositories\CmsAuthGroupRepository;
use Illuminate\Http\Request;


/**
 * cms角色组管理功能
 * 详细说明：不同的角色组对应不同的权限,角色有上下级层级关系
 * @author: David
 * @version: 1.0
 * @date:2020/4/8
 */
class CmsAuthGroupController extends BaseController
{
    protected $viewPath = 'backend.auth.cmsauthgroup';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(CmsAuthGroupRepository $Repository)
    {
        parent::__construct();
        $this->repositories = $Repository;
    }


    //表单加载
    public function form(Request $request)
    {
        try {
            if($request->ajax())
            {
                $rule= $this->repositories->getCmsruleList($request->input('id'));

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
    public function save(CmsAuthGroupRequest $request)
    {
        $post = $request->all();

        try{
            if(isset($post['checkedall'])||isset($post['expandall'])){
                unset($post['checkedall']);
                unset($post['expandall']);
            }
            $ret = $this->repositories->save($post);
            if ($ret) {
                return $this->jsonSuccess([]);
            } else {
                return $this->jsonFailed('没有更新修改无需提交');
            }
        }catch (CommonException $re){
            return $this->jsonFailed($re->getMessage());
        }

    }



}