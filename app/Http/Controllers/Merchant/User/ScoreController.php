<?php
namespace App\Http\Controllers\Merchant\User;

use App\Http\Controllers\Merchant\BaseController;
use App\Http\Requests\Merchant\User\ScoreRequest;
use App\Repositories\SaasUserScoreRuleRepository;
use App\Services\Helper;
use Illuminate\Http\Request;

/**
 * 项目说明 OMS系统 会员管理--积分规则
 * 详细说明 OMS系统 会员管理--金粉规则，实现列表，添加，编辑，删除及组件结合
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/04/29
 */
class ScoreController extends BaseController
{
    protected $viewPath = 'merchant.user.score';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(SaasUserScoreRuleRepository $Repository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->scoreRule = ['1'=>'登录','2'=>'消费','3'=>'签到'];
        $this->merchantID = empty(session('admin')) == false ? session('admin')['mch_id'] : ' ';
    }


    /**
     * 功能首页结构view
     * @return mixed
     */
    public function index()
    {
        $scoreRule = Helper::getChooseSelectData($this->scoreRule);
        return view('merchant.user.score.index',['scoreRule'=>$scoreRule]);
    }

    /**
     * ajax获取列表项
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function table(Request $request)
    {
        $inputs = $request->all();
        $inputs['mch_id']=$this->merchantID;
        $list = $this->repositories->getTableList($inputs,"score_rule_id desc");
        $htmlContents = $this->renderHtml('',['list' =>$list,'scoreRule'=>$this->scoreRule]);

        $pagesInfo = $list->toArray();

        $total = $pagesInfo['total'];

        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
    }
    /**
     * 通用表单展示
     * @param Request $request
     * @return mixed
     */
    public function form(Request $request)
    {
        try {
            if($request->ajax())
            {
                $row = $this->repositories->getById($request->input('id'));

                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row,'scoreRule'=>$this->scoreRule]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

    }



   //添加/编辑操作
    public function save(ScoreRequest $request)
    {
        $params = $request->all();
        $params['mch_id']=$this->merchantID;
        $ret = $this->repositories->save($params);
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }

}
