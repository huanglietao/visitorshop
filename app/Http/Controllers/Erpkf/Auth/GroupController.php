<?php
namespace App\Http\Controllers\Erpkf\Auth;

use App\Http\Controllers\Erpkf\BaseController;
use App\Http\Requests\Erpkf\Auth\GroupRequest;
use App\Repositories\ErpKfAuthGroupRepository;
use Illuminate\Http\Request;
/**
 * 项目说明
 * 详细说明
 * @author: David
 * @version: 1.0
 * @date: 2020/01/15
 */
class GroupController extends BaseController
{
    protected $viewPath = 'erpkf.auth.group';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(ErpKfAuthGroupRepository $Repository)
    {
        parent::__construct();
        $this->repositories = $Repository;
    }

   //添加/编辑操作
    public function save(GroupRequest $request)
    {
        //dump($request->all());die;
        $ret = $this->repositories->save($request->all());
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }

    protected function form(Request $request)
    {
        try {
            if($request->ajax())
            {
                $row = $this->repositories->getById($request->input('id'));
                $data =json_encode(['id'=>2,'parent'=>2,'text'=>'测试']);
                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row,'data'=>$data]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

    }
    public function tree(Request $request)
    {
        $data =['id'=>2,'parent'=>2,'text'=>'测试'];
        return $data;
        dump(121);die;
    }

}