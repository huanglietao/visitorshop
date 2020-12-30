<?php
namespace App\Http\Controllers\Merchant\Agent\Strategy;

use App\Http\Controllers\Merchant\BaseController;
use App\Http\Requests\Merchant\Agent\Strategy\RechargeRuleRequest;
use App\Repositories\OmsAgentRechargeRuleRepository;

/**
 * 项目说明
 * 详细说明
 * @author:
 * @version: 1.0
 * @date:
 */
class RechargeRuleController extends BaseController
{
    protected $viewPath = 'merchant.agent.strategy.rechargerule';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(OmsAgentRechargeRuleRepository $Repository)
    {
        parent::__construct();
        $this->merchantID = empty(session('admin')) == false ? session('admin')['mch_id'] : ' ';
        $this->repositories = $Repository;
    }

   //添加/编辑操作
    public function save(RechargeRuleRequest $request)
    {
        $params = $request->all();
        if(isset($params['status'])){
            unset($params['status']);
        }
        if($params['recharge_fee']<0){
            return $this->jsonFailed('充值金额必须大于0');
        }
        if($params['present_fee']<0){
            return $this->jsonFailed('奖励金额必须大于等于0');
        }

        unset($params['_token']);
        $params['mch_id'] = $this->merchantID;
        $ret = $this->repositories->save($params);
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }

}