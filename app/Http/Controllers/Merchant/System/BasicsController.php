<?php
namespace App\Http\Controllers\Merchant\system;

use App\Http\Controllers\Merchant\BaseController;
use App\Http\Requests\Merchant\system\BasicsRequest;
use App\Repositories\OmsSystemSettingRepository;

/**
 * 项目说明  OMS系统 系统设置-->基础设置
 * 详细说明  OMS系统 系统设置-->基础设置
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/04/08
 */
class BasicsController extends BaseController
{
    protected $viewPath = 'merchant.system.basics';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $mch_id;

    public function __construct(OmsSystemSettingRepository $Repository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->mch_id = empty(session('admin')) == false ? session('admin')['mch_id'] : ' ';
    }

    public function index()
    {
        $info = $this->repositories->getInfo($this->mch_id);
        return view('merchant.system.basics.index',['id'=>$this->mch_id,'row'=>$info]);
    }

   //添加/编辑操作
    public function save(BasicsRequest $request)
    {
        $ret = $this->repositories->save($request->all());
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }

}
