<?php
namespace App\Http\Controllers\Backend\System;

use App\Http\Controllers\Backend\BaseController;
use App\Repositories\SaasBasicsRepository;
use Illuminate\Http\Request;

/**
 * 系统设置->基础设置
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020-03-05
 */
class BasicsController extends BaseController
{
    protected $viewPath = 'backend.system.basics';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $basicsRepository;
    protected $adminId;


    public function __construct(SaasBasicsRepository $basicsRepository)
    {
        parent::__construct();
        $this->basicsRepository = $basicsRepository;
        $this->adminId = empty(session('admin')) == false ? session('admin')['admin_id'] : ' ';
    }


    public function index()
    {
        $info = $this->basicsRepository->getInfo($this->adminId);
        return view('backend.system.basics.index',['id'=>$this->adminId,'data'=>$info]);
    }

    /**
     * 保存系统基础信息
     * @param array $data 基础信息
     * @return array
     */
    public function save(Request $request)
    {
        $param = $request->post();
        $res = $this->basicsRepository->save($param);

        if($res){
            return $this->jsonSuccess([]);
        }else{
            return $this->jsonFailed([]);
        }

    }

}