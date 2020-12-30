<?php
namespace App\Http\Controllers\Erpkf\Auth;

use App\Http\Controllers\Erpkf\BaseController;
use App\Http\Requests\Erpkf\Auth\KfUsersRequest;
use App\Repositories\ErpKfUsersRepository;
use Illuminate\Http\Request;

/**
 * 项目说明
 * 详细说明
 * @author:
 * @version: 1.0
 * @date:
 */
class KfUsersController extends BaseController
{
    protected $viewPath = 'erpkf.auth.kfusers';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(ErpKfUsersRepository $Repository)
    {
        parent::__construct();
        $this->repositories = $Repository;
    }

    //列表
    public function index()
    {
        $setting = $this->getSetting();
        $pageLimit = $setting['default_pages_limit'];

        return view('erpkf.auth.kfusers.index',compact('pageLimit','domain'));
    }

    //ajax 获取数据
    protected function table(Request $request)
    {
        $domain = 'http://'.config('app.erpkf_url').'/';
        $inputs = $request->all();

        $list = $this->repositories->getTableList();
        $groupList = $this->repositories->getGroupList();

        $htmlContents = $this->renderHtml('',['list' =>$list,'url'=>$domain,'groupList'=>$groupList]);

        $pagesInfo = $list->toArray();

        $total = $pagesInfo['total'];

        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
    }

   //添加/编辑操作
    public function save(KfUsersRequest $request)
    {
        $post= $request->all();
        if($post['id']){
            unset($post['_token']);
        }
        //dump($post);die;
        $ret = $this->repositories->save($post);
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }

}