<?php
namespace App\Http\Controllers\Backend;

/**
 * sass数据配置平台入口
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/10/12
 */
class IndexController extends BaseController
{
    protected $noNeedRight = ['index'];  //无需检查权限

    public function __construct()
    {
        $redis = app('redis.connection');
        $data['cms_adm_id'] = "test";

        //将数据写入缓存
        $res = $redis->set('test' , json_encode($data));

        parent::__construct();
    }

    public function index()
    {
        $menuList = $this->auth->getSidebar();


        return view('backend.index',['menuList'=>$menuList,'userInfo'=>$this->userInfo,'systemInfo'=>$this->systemInfo]);
    }

}
