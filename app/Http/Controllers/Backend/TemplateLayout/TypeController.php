<?php
namespace App\Http\Controllers\Backend\TemplateLayout;

use App\Http\Controllers\Backend\BaseController;
use App\Http\Requests\Backend\TemplateLayout\TypeRequest;
use App\Repositories\SaasTemplateLayoutTypeRepository;

/**
 *  布局管理的版式管理
 *  模板布局中可以选择不同的布局版式，基础版或者单图版，通讯录版式
 * @author:dai
 * @version: 1.0
 * @date:2020/4/15
 */
class TypeController extends BaseController
{
    protected $viewPath = 'backend.templatelayout.type';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(SaasTemplateLayoutTypeRepository $Repository)
    {
        parent::__construct();
        $this->repositories = $Repository;
    }

   //添加/编辑操作
    public function save(TypeRequest $request)
    {
        $ret = $this->repositories->save($request->all());
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }

}