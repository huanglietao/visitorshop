<?php
namespace App\Http\Controllers\Merchant\Auth;

use App\Http\Controllers\Merchant\BaseController;
use App\Http\Requests\Backend\Merchant\AccountRequest;
use App\Repositories\OmsMerchantAccountRepository;
use Illuminate\Http\Request;

/**
 * 权限管理->管理员列表
 * OMS管理员列表
 * @author: cjx
 * @version: 1.0
 * @date: 2020-04-09
 */
class AdminController extends BaseController
{
    protected $viewPath = 'merchant.auth.admin';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块


    public function __construct(OmsMerchantAccountRepository $merchantAccountRepository)
    {
        parent::__construct();
        $this->repositories = $merchantAccountRepository;
        $this->groupList = $this->repositories->getGroupList();
        $this->groupTreeList = $this->repositories->getMerchantGroup(session('admin')['oms_adm_group_id']);
    }

    public function index()
    {
        return view('merchant.auth.admin.index');
    }

    /**
     * ajax获取列表项
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function table(Request $request)
    {

        $inputs = $request->all();
        $list = $this->repositories->getTableList($inputs,'',0);
        $arr_list = array_column($this->groupTreeList,'oms_group_name','oms_group_id');

        $htmlContents = $this->renderHtml('',['list' =>$list,'groupList' =>$arr_list]);

        $pagesInfo = $list->toArray();

        $total = $pagesInfo['total'];

        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
    }

    //添加、编辑操作
    public function save(AccountRequest $request)
    {
        $param = $request->all();
        unset($param['_token']);

        $ret = $this->repositories->save($param);
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
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
                $infoList = $this->repositories->getAllMerchantInfo();
                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row,'infoList' => $infoList,'groupList' => $this->groupTreeList]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

    }
}