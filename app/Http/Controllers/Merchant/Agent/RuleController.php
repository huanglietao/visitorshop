<?php
namespace App\Http\Controllers\Merchant\Agent;

use App\Http\Controllers\Merchant\BaseController;
use App\Http\Requests\Merchant\Agent\RuleRequest;
use App\Repositories\DmsAuthRuleRepository;
use App\Repositories\SaasCategoryRepository;
use Illuminate\Http\Request;
use App\Exceptions\CommonException;
use App\Services\Helper;

/**
 * 项目说明 OMS系统 分销管理--菜单管理
 * 详细说明 实现列表，添加，编辑，删除及组件结合
 * @author: david
 * @version: 1.0
 * @date:  2020/07/10
 */
class RuleController extends BaseController
{
    protected $viewPath = 'merchant.agent.rule';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $merchantID = '';

    public function __construct(DmsAuthRuleRepository $Repository,SaasCategoryRepository $cateRepository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->cateRepo = $cateRepository;

        $this->merchantID = empty(session('admin')) == false ? session('admin')['mch_id'] : ' ';
    }

    /**
     * ajax获取列表项
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function table(Request $request)
    {

        try {
            $inputs = $request->all();

            $list = $this->repositories->getTableList($inputs,'dms_auth_rule_weigh desc')->toArray();
            //无限级分类
            $categoryList = $this->cateRepo->getTreeList($list['data'],'dms_auth_rule_pid','dms_auth_rule_id','dms_auth_rule_title');
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
                $cateList = $this->cateRepo->getTreeList($list,'dms_auth_rule_pid','dms_auth_rule_id','dms_auth_rule_title');
                $cateList = Helper::ListToKV('dms_auth_rule_id','dms_auth_rule_title',$cateList);

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
        if(empty($post['dms_auth_rule_weigh'])){
            $post['dms_auth_rule_weigh'] = ZERO;
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
