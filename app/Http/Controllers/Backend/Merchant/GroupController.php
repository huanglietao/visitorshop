<?php
namespace App\Http\Controllers\Backend\Merchant;

use App\Http\Controllers\Backend\BaseController;
use App\Http\Requests\Backend\Merchant\OmsAuthGroupRequest;
use App\Repositories\OmsAuthGroupRepository;
use Illuminate\Http\Request;


/**
 * 商户管理->商户角色
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020/04/10
 */
class GroupController extends BaseController
{
    protected $viewPath = 'backend.merchant.group';  //当前控制器所的view所在的目录
    protected $modules = 'sys';                     //当前控制器所属模块
    protected $noNeedRight = [];                    //无需检查权限


    public function __construct(OmsAuthGroupRepository $omsAuthGroupRepository)
    {
        parent::__construct();
        $this->repositories = $omsAuthGroupRepository;

    }

    //表单加载
    public function form(Request $request)
    {
        try {
            if($request->ajax())
            {
                $rule= $this->repositories->getOmsruleList($request->input('id'));

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
    public function save(OmsAuthGroupRequest $request)
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
                return $this->jsonFailed('');
            }
        }catch (CommonException $re){
            return $this->jsonFailed($re->getMessage());
        }

    }

}