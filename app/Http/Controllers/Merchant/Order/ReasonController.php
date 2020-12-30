<?php
namespace App\Http\Controllers\Merchant\Order;

use App\Http\Controllers\Merchant\BaseController;
use App\Repositories\SaasCategoryRepository;
use App\Repositories\SaasOrderServiceReasonRepository;
use Illuminate\Http\Request;

/**
 * 售后原因文案
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020-07-01
 */
class ReasonController extends BaseController
{
    protected $viewPath = 'merchant.order.reason';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块

    public function __construct(SaasOrderServiceReasonRepository $repository)
    {
        parent::__construct();
        $this->repositories  = $repository;
        $this->typeList = $this->repositories->getType();
    }


    public function index()
    {
        return view('merchant.order.reason.index');
    }

    /**
     * ajax获取列表项
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function table(Request $request)
    {

        $inputs = $request->all();
        $list = $this->repositories->getTableList($inputs)->toArray();
        $data = app(SaasCategoryRepository::class)->getTreeList($list,'reason_pid','service_reason_id','reason');
        $htmlContents = $this->renderHtml('',['list' =>$data]);

        return $this->jsonSuccess(['html' => $htmlContents]);
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
                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row,'typeList'=>$this->typeList]);

                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

    }

    //添加、编辑操作
    public function save(Request $request)
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


}