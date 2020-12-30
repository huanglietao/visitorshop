<?php
namespace App\Services\Outer\Erp;

use App\Models\ErpVipCustomer;
use App\Repositories\ErpFinanceDocRepository;
use App\Repositories\ErpVipCustomerRepository;

/**
 * 控制台逻辑处理类
 * @author: cjx
 * @version: 1.0
 * @date: 2019/12/25
 */

class Dashboard
{
    protected $erpFinanceDocRepository;
    protected $erpVipCustomerRepository;
    protected $partnerCcode;


    public function __construct(ErpFinanceDocRepository $erpFinanceDocRepository,ErpVipCustomerRepository $erpVipCustomerRepository)
    {
        $this->erpFinanceDocRepository = $erpFinanceDocRepository;
        $this->erpVipCustomerRepository = $erpVipCustomerRepository;
        $this->partnerCcode = empty(session('capital')) == false ? session('capital')['data']['partner_code'] : ' ';

    }

    /**
     * 获取最近充值记录(按创建时间降序)
     * @param string $partnerCode 客户编号 $num 获取条数
     * @return array
     */
    public function getRechargeList($num)
    {
        $partnerCode = $this->partnerCcode;
        $data = $this->erpFinanceDocRepository->getRechargeData($partnerCode,$num);

        return $data;
    }

    /**
     * 获取客户最近登录信息
     * @param string $partnerCode 客户编号
     * @return array
     */
    public function getLoginInfo()
    {
        $partnerCode = $this->partnerCcode;
        $data = $this->erpVipCustomerRepository->getUserLoginInfo($partnerCode);

        return $data;
    }

    /**
     * 请求客户信息查询接口 http://60.30.76.54:806/ec/account/do_search
     * @param string $requestUrl 请求地址 $postData 请求数据
     * @return array
     */
    public function requestApi()
    {
        $requestApi = new Api();
        $postData['partner_code'] = $this->partnerCcode;
        $requestUrl = config("erp.interface_url").config("erp.search");
        $res = $requestApi->request($requestUrl,$postData);

        return $res;
    }

    /**
     * 返回问候语
     * @return array
     */
    public function getGreet()
    {
        //获取星期
        $weekArray = array("日","一","二","三","四","五","六");
        $data['week'] = date("Y-m-d")."星期".$weekArray[date("w")];

        //问好
        $hours = date("H");
        $data['greet'] = '您好!';
        if($hours < 11) {
            $data['greet'] = '早上好!';
        } else if ($hours < 13) {
            $data['greet'] = '中午好!';
        } else if ($hours < 19 ){
            $data['greet'] = '下午好!';
        } else {
            $data['greet'] = '晚上好';
        }

        return $data;
    }
}