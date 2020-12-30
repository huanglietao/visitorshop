<?php
namespace App\Http\Controllers\Backend\System;

use App\Http\Controllers\Backend\BaseController;
use App\Http\Requests\Backend\System\SmtpRequest;
use App\Repositories\SaasSmtpRepository;

/**
 * 项目说明  CMS系统 邮件设置
 * 详细说明  CMS系统 邮件设置，实现列表，添加，编辑，删除及组件结合
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/03/03
 */
class SmtpController extends BaseController
{
    protected $viewPath = 'backend.system.smtp';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(SaasSmtpRepository $Repository)
    {
        parent::__construct();
        $this->repositories = $Repository;
    }


   //添加/编辑操作
    public function save(SmtpRequest $request)
    {
        $ret = $this->repositories->save($request->all());
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }

}