<?php
namespace App\Http\Controllers\Merchant\Agent;

use App\Exceptions\CommonException;
use App\Http\Controllers\Merchant\BaseController;
use App\Http\Requests\Merchant\Agent\ApplyRequest;
use App\Repositories\DmsAgentApplyRepository;
use App\Repositories\DmsMerchantAccountRepository;
use App\Repositories\SaasCustomerLevelRepository;
use App\Services\Helper;
use Illuminate\Http\Request;
use App\Repositories\DmsAgentInfoRepository;

/**
 * 项目说明 OMS系统 分销管理--商家申请表
 * 详细说明 OMS系统 分销管理--商家申请表，实现列表，添加，编辑，删除及组件结合
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/04/15
 */
class ApplyController extends BaseController
{
    protected $viewPath = 'merchant.agent.apply';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $merchantID = '';
    protected $GradeRepository = "";
    protected $InfoRepository = "";

    public function __construct(DmsAgentApplyRepository $Repository, SaasCustomerLevelRepository $GradeRepository,
                                DmsAgentInfoRepository $InfoRepository,DmsMerchantAccountRepository $merchantAccountRepository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->GradeRepository = $GradeRepository;
        $this->InfoRepository = $InfoRepository;
        $this->merchantAccountRepository = $merchantAccountRepository;
        $this->merchantID = empty(session('admin')) == false ? session('admin')['mch_id'] : ' ';
        $this->shop_type = config('agent.shop_type');
        $this->agentInfo = $this->InfoRepository->getList(['mch_id'=>$this->merchantID])->toArray();
    }


    /**
     * 功能首页结构view
     * @return mixed
     */
    public function index()
    {
        $shop_type = Helper::getChooseSelectData($this->shop_type);
        return view('merchant.agent.apply.index',['shop_type'=>$shop_type]);
    }

    /**
     * ajax获取列表项
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function table(Request $request)
    {
        try{
            $inputs = $request->all();
            $inputs['mch_id']=$this->merchantID;
            $list = $this->repositories->getTableList($inputs,"agent_apply_id desc");

            $result = $list->toArray();
            //得到所属等级
            foreach ($result['data'] as $k=>$v){
                if($result['data'][$k]['cust_lv_id']==0){
                    $result['data'][$k]['cust_lv_name'] = "";
                }else{
                    $result['data'][$k]['cust_lv_name'] = $this->GradeRepository->getGrade($this->merchantID,CHANEL_TERMINAL_AGENT,$v['cust_lv_id']);
                }
            }

            $htmlContents = $this->renderHtml('',['list' =>$result['data'],'shop_type'=>$this->shop_type]);
            $pagesInfo = $list->toArray();
            $total = $pagesInfo['total'];
            return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
        }catch (CommonException $e){
            $this->jsonFailed($e->getMessage());
        }

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
                //获取该商家下的所有分销商
                $info = Helper::ListToKV('agent_info_id','agent_name',$this->agentInfo);
                $info = [0=>'请选择']+$info;

                $grade = $this->GradeRepository->getGrade($this->merchantID,CHANEL_TERMINAL_AGENT);
                $grade = Helper::getChooseSelectData($grade);
                $shop_type = Helper::getChooseSelectData($this->shop_type);
                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row,'grade'=>$grade,'shop_type'=>$shop_type,'info'=>$info]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

    }


   //添加/编辑操作
    public function save(ApplyRequest $request)
    {
        try{
            $parmas = $request->all();
            unset($parmas['_token']);
            $user_info_data = $parmas;
            \DB::beginTransaction();
            if($parmas['review_status']==2){
                $user_info_data['agent_info_id'] = "";
                $user_info_data['agent_balance'] = 0;
                $user_info_data['agent_info_desc']=$parmas['agent_apply_desc'];
                unset($user_info_data['agent_apply_id']);
                unset($user_info_data['review_status']);
                unset($user_info_data['review_failed_msg']);
                unset($user_info_data['agent_apply_desc']);
                //商家信息表
                $ret_id = $this->InfoRepository->save($user_info_data);
                $parmas['agent_info_id'] = $ret_id;
                //更新分销账号表
                $account_ret = $this->merchantAccountRepository->updateInfo($parmas['agent_apply_id'],$ret_id);
            }
            //更新商家申请表
            $ret = $this->repositories->save($parmas);

            if ($ret) {
                \DB::commit();
                return $this->jsonSuccess([]);
            } else {
                return $this->jsonFailed('');
            }
        }catch (CommonException $e){
            \DB::rollBack();
            $this->jsonFailed($e->getMessage());
        }
    }

}
