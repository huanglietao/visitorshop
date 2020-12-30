<?php
namespace App\Http\Controllers\Backend\Merchant;

use App\Http\Controllers\Backend\BaseController;
use App\Http\Requests\Backend\Merchant\AccountRequest;
use App\Repositories\OmsMerchantAccountRepository;
use Illuminate\Http\Request;

/**
 * 商户管理->商户账号
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020-04-03
 */
class AccountController extends BaseController
{
    protected $viewPath = 'backend.merchant.account';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块


    public function __construct(OmsMerchantAccountRepository $merchantAccountRepository)
    {
        parent::__construct();
        $this->repositories = $merchantAccountRepository;
        $this->groupList = $this->repositories->getGroupList();
    }

    public function index()
    {
        return view('backend.merchant.account.index');
    }

    /**
     * ajax获取列表项
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function table(Request $request)
    {

        $inputs = $request->all();
        $list = $this->repositories->getTableList($inputs,'',1);

        $htmlContents = $this->renderHtml('',['list' =>$list,'groupList' =>$this->repositories->getGroupList('all')]);

        $pagesInfo = $list->toArray();

        $total = $pagesInfo['total'];

        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
    }

    //添加、编辑操作
    public function save(AccountRequest $request)
    {
        $param = $request->all();
        unset($param['_token']);

        $ret = $this->repositories->save($param,1);
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
                $id = $request->input('id');
                $row = $this->repositories->getById($id);
                $infoList = $this->repositories->getAllMerchantInfo($id);

                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row,'infoList' => $infoList,'groupList' => $this->groupList]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

    }
}