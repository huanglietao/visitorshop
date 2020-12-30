<?php
namespace App\Http\Controllers\Merchant\User;

use App\Http\Controllers\Merchant\BaseController;
use App\Http\Requests\Merchant\User\MoneyRequest;
use App\Repositories\SaasUserMoneyRepository;
use App\Repositories\SaasUserRepository;
use Illuminate\Http\Request;

/**
 * 项目说明
 * 详细说明
 * @author:
 * @version: 1.0
 * @date:
 */
class MoneyController extends BaseController
{
    protected $viewPath = 'merchant.user.money';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(SaasUserMoneyRepository $Repository,Request $request,SaasUserRepository $userRepository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->userRepository = $userRepository;
        $this->user_id = $request->get('user_id');
        $this->merchantID = empty(session('admin')) == false ? session('admin')['mch_id'] : ' ';
    }


    /**
     * 功能首页结构view
     * @return mixed
     */
    public function index()
    {
        return view('merchant.user.money.index',['user_id'=>$this->user_id]);
    }

    /**
     * ajax获取列表项
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function table(Request $request)
    {
        $inputs = $request->all();

        $inputs['user_id'] = $this->user_id;
        $inputs['mch_id'] = $this->merchantID;

        $list = $this->repositories->getTableList($inputs,"user_money_id desc");
        $username = $this->userRepository->getTableList(['user_id'=>$this->user_id])->toArray();
        $username = $username['data'][0]['user_name'];

        $htmlContents = $this->renderHtml('',['list' =>$list,'username'=>$username]);

        $pagesInfo = $list->toArray();

        $total = $pagesInfo['total'];

        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
    }
    /**
     * 通用表单展示
     * @param Request $request
     * @return mixed
     */
    public function form(Request $request)
    {
        try {
            if($request->ajax())
            {
                $row = $this->repositories->getById($request->input('id'));
                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

    }

   //添加/编辑操作
    public function save(MoneyRequest $request)
    {
        $ret = $this->repositories->save($request->all());
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }

}
