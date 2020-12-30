<?php
namespace App\Http\Controllers\Merchant\Auth;

use App\Http\Controllers\Merchant\BaseController;
use App\Http\Requests\Backend\Merchant\OmsAuthGroupRequest;
use App\Repositories\OmsAuthGroupRepository;
use Illuminate\Http\Request;


/**
 * 权限管理->角色管理
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020/04/14
 */
class GroupController extends BaseController
{
    protected $viewPath = 'merchant.auth.group';  //当前控制器所的view所在的目录
    protected $modules = 'sys';                     //当前控制器所属模块
    protected $noNeedRight = [];                    //无需检查权限


    public function __construct(OmsAuthGroupRepository $omsAuthGroupRepository)
    {
        parent::__construct();
        $this->repositories = $omsAuthGroupRepository;

    }

    /**
     * ajax获取列表项
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function table(Request $request)
    {

        $inputs = $request->all();
        $list = $this->repositories->getTableList($inputs,'',session("admin")['oms_adm_group_id']);
        $htmlContents = $this->renderHtml('',['list' =>$list]);

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

            $ret = $this->repositories->save($post,session("admin")['oms_adm_group_id']);
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