<?php
namespace App\Http\Controllers\Backend\Delivery;

use App\Http\Controllers\Backend\BaseController;
use App\Http\Requests\Backend\Delivery\ExpressRequest;
use App\Repositories\SaasExpressRepository;

/**
 * 项目说明  CMS系统 物流设置-物流公司
 * 详细说明  CMS系统 物流设置-物流公司，实现快递列表，添加，编辑，删除及组件结合
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/03/16
 */
class ExpressController extends BaseController
{
    protected $viewPath = 'backend.delivery.express';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(SaasExpressRepository $Repository)
    {
        parent::__construct();
        $this->repositories = $Repository;
    }

   //添加/编辑操作
    public function save(ExpressRequest $request)
    {
        $data= $request->all();

        if($data['express_id']){
            unset($data['_token']);
        }
        $ret = $this->repositories->save($data);
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }

}
