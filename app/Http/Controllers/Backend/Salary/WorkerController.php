<?php
namespace App\Http\Controllers\Backend\Salary;

use App\Http\Controllers\Backend\BaseController;
use App\Repositories\SaasSalaryWorkerRepository;
use Illuminate\Http\Request;

/**
 * 职工管理->职工列表
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020-06-28
 */
class WorkerController extends BaseController
{
    protected $viewPath = 'backend.salary.worker';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块

    public function __construct(SaasSalaryWorkerRepository $repository)
    {
        parent::__construct();
        $this->repositories  = $repository;
        $this->positionList = $this->repositories->getPositionList();
    }


    public function index()
    {
        return view('backend.salary.worker.index');
    }

    /**
     * ajax获取列表项
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function table(Request $request)
    {

        $inputs = $request->all();
        $list = $this->repositories->getTableList($inputs);
        $htmlContents = $this->renderHtml('',['list' =>$list]);

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
                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row,'positions'=>$this->positionList]);

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

        if($param['salary_worker_position'] == 0){
            return $this->jsonFailed('请选择职位');
        }

        $ret = $this->repositories->save($param);
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }


}