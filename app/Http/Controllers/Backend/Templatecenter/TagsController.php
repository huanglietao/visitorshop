<?php
namespace App\Http\Controllers\Backend\Templatecenter;

use App\Http\Controllers\Backend\BaseController;
use App\Http\Requests\Backend\Templatecenter\TagsRequest;
use App\Repositories\SaasTemplateTagsRepository;

/**
 * 项目说明
 * 模板标签：主模板可以关联多个模板标签，方便搜索该模板相对应到哪些标签中
 * @author: david
 * @version: 1.0
 * @date:
 */
class TagsController extends BaseController
{
    protected $viewPath = 'backend.templatecenter.tags';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(SaasTemplateTagsRepository $Repository)
    {
        parent::__construct();
        $this->repositories = $Repository;
    }

   //添加/编辑操作
    public function save(tagsRequest $request)
    {
        $ret = $this->repositories->save($request->all());
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }

}