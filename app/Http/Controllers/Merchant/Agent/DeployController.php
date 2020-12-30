<?php
namespace App\Http\Controllers\Merchant\Agent;

use App\Http\Controllers\Merchant\BaseController;
use App\Repositories\OmsAgentDeployRepository;
use App\Services\Helper;
use Illuminate\Http\Request;

/**
 * 站点配置
 * @author: cjx
 * @version: 1.0
 * @date:  2020/05/11
 */
class DeployController extends BaseController
{
    protected $viewPath = 'merchant.agent.deploy';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $mchId;

    public function __construct(OmsAgentDeployRepository $Repository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->mchId = session('admin')['mch_id'];
    }

    /**
     * 功能首页结构view
     * @return mixed
     */
    protected function index()
    {
        $data = $this->repositories->getDeployInfo($this->mchId);
        $url = env('AGENT_URL').'?mid='.Helper::easyEncrypt($this->mchId);

        return view('merchant.agent.deploy.index',['data'=>$data,'url'=>$url]);
    }

    /**
     * 保存系统基础信息
     * @param array $data 基础信息
     * @return array
     */
    public function save(Request $request)
    {
        $param = $request->post();
        $param['mch_id'] = $this->mchId;
        $res = $this->repositories->save($param);

        if($res){
            return $this->jsonSuccess('操作成功');
        }else{
            return $this->jsonFailed([]);
        }

    }

}
