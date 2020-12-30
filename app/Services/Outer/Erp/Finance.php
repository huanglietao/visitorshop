<?php
namespace App\Services\Outer\Erp;

use App\Models\ErpVipCustomer;
use App\Repositories\ErpFinanceDocRepository;
use App\Repositories\ErpVipCustomerRepository;
use Illuminate\Http\Request;

/**
 * 资金管理逻辑处理类
 * @author: cjx
 * @version: 1.0
 * @date: 2019/12/25
 */

class Finance
{
    protected $erpFinanceDocRepository;
    protected $partnerCode;

    public function __construct(ErpFinanceDocRepository $erpFinanceDocRepository)
    {
        $this->erpFinanceDocRepository = $erpFinanceDocRepository;
        $this->partnerCode = empty(session('capital')) == false ? session('capital')['data']['partner_code'] : ' ';

    }

    /**
     * 获取充值记录数据
     * @return array
     */
    public function getTableData($request)
    {
        $inputs = $request->all();
        $inputs['partner_code'] = $this->partnerCode;
        $list = $this->erpFinanceDocRepository->getTableList($inputs,'createtime desc');

        return $list;
    }
}