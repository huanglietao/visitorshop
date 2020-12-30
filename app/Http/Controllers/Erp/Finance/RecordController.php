<?php
namespace App\Http\Controllers\Erp\Finance;

use App\Http\Controllers\Erp\BaseController;
use App\Http\Requests\Erp\Finance\RecordRequest;
use App\Repositories\ErpFinanceDocRepository;
use App\Services\Outer\Erp\Finance;
use Illuminate\Http\Request;

/**
 * ERP系统充值记录
 *
 * 功能详细说明
 * @author: cjx
 * @version: 1.0
 * @date: 2019/10/12
 */

class RecordController extends BaseController
{
    protected $viewPath = 'erp.finance.record';  //当前控制器所的view所在的目录
    protected $modules = 'sys';                 //当前控制器所属模块

    public function __construct(ErpFinanceDocRepository $Repository)
    {
        parent::__construct();
        $this->repositories = $Repository;
    }

    /**
     * 表格数据渲染
     */
    public function table(Request $request)
    {
        $list = app(Finance::class)->getTableData($request);

        $htmlContents = $this->renderHtml('',['list' =>$list]);
        $pagesInfo = $list->toArray();
        $total = $pagesInfo['total'];

        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
    }
}