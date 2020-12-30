<?php
namespace App\Http\Controllers\Agent\Test;

use App\Http\Controllers\Agent\BaseController;
use App\Http\Requests\Agent\Test\mediaRequest;
use App\Repositories\SassProductsMediaRepository;

/**
 * 项目说明
 * 详细说明
 * @author:
 * @version: 1.0
 * @date:
 */
class mediaController extends BaseController
{
    protected $viewPath = 'agent.test.media';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(SassProductsMediaRepository $Repository)
    {
        parent::__construct();
        $this->repositories = $Repository;
    }

   //添加/编辑操作
    public function save(mediaRequest $request)
    {
        $ret = $this->repositories->save($request->all());
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }

}