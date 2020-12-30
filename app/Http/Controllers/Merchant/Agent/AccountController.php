<?php
namespace App\Http\Controllers\Merchant\Agent;

use App\Exceptions\CommonException;
use App\Http\Controllers\Merchant\BaseController;
use App\Http\Requests\Merchant\Agent\AccountRequest;
use App\Repositories\DmsMerchantAccountRepository;
use App\Repositories\DmsAgentApplyRepository;
use App\Repositories\OmsMerchantAccountRepository;
use Illuminate\Http\Request;

/**
 * 项目说明 OMS系统 分销管理--商家账号
 * 详细说明 OMS系统 分销管理--商家账号，实现列表，添加，编辑，删除及组件结合
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/04/16
 */
class AccountController extends BaseController
{
    protected $viewPath = 'merchant.agent.account';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $groupList;
    protected $groupTreeList;
    protected $merchantID = '';

    public function __construct(DmsMerchantAccountRepository $Repository, DmsAgentApplyRepository $InfoRepository,OmsMerchantAccountRepository $merchantAccountRepository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->Inforepositories = $InfoRepository;
        $this->merchantRepositories = $merchantAccountRepository;
        $this->groupList = $this->repositories->getGroupList();
        $this->groupTreeList = $this->repositories->getMerchantGroup();
        $this->merchantID = empty(session('admin')) == false ? session('admin')['mch_id'] : ' ';
    }


    /**
     * ajax获取列表项
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function table(Request $request)
    {
        try{
            $inputs = $request->all();
            $inputs['mch_id'] = $this->merchantID;
            $list = $this->repositories->getTableList($inputs,"dms_adm_id desc");
            $data = $list->toArray();
            foreach ($data['data'] as $k =>$v){
                if(!empty($data['data'][$k]['dms_adm_logintime'])){
                    $data['data'][$k]['dms_adm_logintime'] = (int)$v['dms_adm_logintime'];
                }
                if(!empty($data['data'][$k]['created_at'])){
                    $data['data'][$k]['created_at'] = (int)$v['created_at'];
                }
                if(!empty($data['data'][$k]['updated_at'])){
                    $data['data'][$k]['updated_at'] = (int)$v['updated_at'];
                }
            }

            //所属商家
            $merchant = $this->merchantRepositories->getRow(['is_main'=>PUBLIC_YES,'mch_id'=>$this->merchantID],['oms_adm_username'])->toArray();

            $htmlContents = $this->renderHtml('',['list' =>$data['data'],'groupList' =>$this->groupList,'oms_adm_username'=>$merchant['oms_adm_username']]);
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
    protected function form(Request $request)
    {
        try {
            if($request->ajax())
            {
                $agent_apply_id = "";
                $row = $this->repositories->getById($request->input('id'));
                if($request->get('agent_info_id')){
                    $agent_apply_id=$request->get('agent_info_id');
                }else{
                    $agent_apply_id= $row['agent_info_id'];
                }
                $message = "";
                $infoList = $this->repositories->getAllMerchantInfo($agent_apply_id);

                if(empty($infoList)){
                    $message = "请先审核该账号的商户申请";
                }else{
                    $infoList = $infoList[0];
                }

                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row,'infoList' => $infoList,'groupList' => $this->groupTreeList,'agent_apply_id'=>$agent_apply_id,'message'=>$message]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

    }

   //添加/编辑操作
    public function save(AccountRequest $request)
    {
        try{
            $params=$request->all();
            unset($params['_token']);

            $params['mch_id'] = $this->merchantID;
            \DB::beginTransaction();
            if(isset($params['agent_info_id'])){
                $params['is_main'] = 1;
                $ret = $this->Inforepositories->updateIsCreate($params['agent_info_id']);
            }

            $ret = $this->repositories->save($params);
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
