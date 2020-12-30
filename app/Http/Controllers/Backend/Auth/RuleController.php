<?php
namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Backend\BaseController;
use App\Http\Requests\Backend\Auth\RuleRequest;
use App\Repositories\CmsAuthRuleRepository;
use App\Repositories\SaasCategoryRepository;
use App\Services\Helper;
use Illuminate\Http\Request;
use App\Exceptions\CommonException;

/**
 * 菜单管理
 * 菜单操作管理功能，每个功能的路由和提示信息都可以在这里定义管理
 * @author: david
 * @version: 1.0
 * @date:2020/6/19
 */
class RuleController extends BaseController
{
    protected $viewPath = 'backend.auth.rule';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块

    public function __construct(CmsAuthRuleRepository $Repository,SaasCategoryRepository $cateRepository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->cateRepo = $cateRepository;
    }

    //列表加载
    protected function table(Request $request)
    {

        try {
            $inputs = $request->all();

            $list = $this->repositories->getTableList($inputs,'cms_auth_rule_weigh desc')->toArray();
            //无限级分类
            $categoryList = $this->cateRepo->getTreeList($list['data'],'cms_auth_rule_pid','cms_auth_rule_id','cms_auth_rule_title');
            $htmlContents = $this->renderHtml('',['list' =>$categoryList,'yn'=>config('goods.y_n')]);
            $pagesInfo = $list;
            $total = $pagesInfo['total'];
            return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);

        } catch (CommonException $e) {
            //统一收集错误再做处理
            var_dump($e->getMessage());
        }

    }

    //表单
    protected function form(Request $request)
    {
        try {
            if($request->ajax())
            {
                $row = $this->repositories->getById($request->input('id'));

                $list = $this->repositories->getList([])->toArray();
                //无限级分类
                $cateList = $this->cateRepo->getTreeList($list,'cms_auth_rule_pid','cms_auth_rule_id','cms_auth_rule_title');
                $cateList = Helper::ListToKV('cms_auth_rule_id','cms_auth_rule_title',$cateList);

                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row,'cateList'=>$cateList]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

    }

   //添加/编辑操作
    public function save(RuleRequest $request)
    {
        $post = $request->all();
        if(empty($post['cms_auth_rule_weigh'])){
            $post['cms_auth_rule_weigh'] = ZERO;
        }
        $ret = $this->repositories->save($post);

        if (isset($ret['code'])&&$ret['code']==0){
            return $this->jsonFailed($ret['msg']);
        }
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }

    //改变是否为菜单
    public function updateField(Request $request)
    {
        $post = $request->all();
        $ret = $this->repositories->changeUpdateField($post);

        return $this->jsonSuccess(['ret' => $ret['flag']]);;
    }






}