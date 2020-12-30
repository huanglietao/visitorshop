<?php
namespace App\Http\Controllers\Merchant\User;

use App\Http\Controllers\Merchant\BaseController;
use App\Http\Requests\Merchant\User\UserRequest;
use App\Repositories\SaasCustomerLevelRepository;
use App\Repositories\SaasUserRepository;
use App\Services\Helper;
use Illuminate\Http\Request;

/**
 * 项目说明 OMS系统 会员管理--会员列表
 * 详细说明 OMS系统 会员管理--会员列表，实现列表，添加，编辑，删除及组件结合
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/04/29
 */
class UserController extends BaseController
{
    protected $viewPath = 'merchant.user.user';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(SaasUserRepository $Repository,SaasCustomerLevelRepository $customerLevelRepository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->customerLevelRepository = $customerLevelRepository;
        $this->merchantID = empty(session('admin')) == false ? session('admin')['mch_id'] : ' ';
        $this->gradeList = $this->customerLevelRepository->getGrade($this->merchantID,CHANEL_TERMINAL_USER);
    }


    /**
     * 功能首页结构view
     * @return mixed
     */
    public function index()
    {
        $gradeList = Helper::getChooseSelectData($this->gradeList);
        return view('merchant.user.user.index',['gradeList'=>$gradeList]);
    }


    /**
     * ajax获取列表项
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function table(Request $request)
    {
        $inputs = $request->all();
        $inputs['mch_id'] = $this->merchantID;
        $list = $this->repositories->getTableList($inputs,"user_id desc");
        $htmlContents = $this->renderHtml('',['list' =>$list,'gradeList'=>$this->gradeList]);

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
                $gradeList = Helper::getChooseSelectData($this->gradeList);

                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row,'gradeList'=>$gradeList]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

    }


   //添加/编辑操作
    public function save(UserRequest $request)
    {
        $params = $request->all();
        unset($params['_token']);
        $params['mch_id'] = $this->merchantID;
//        var_dump($params);exit;

        $ret = $this->repositories->save($params);
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }

}
