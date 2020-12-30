<?php
namespace App\Http\Controllers\Backend\Maintenance;

use App\Http\Controllers\Backend\BaseController;
use App\Repositories\SaasExceptionLogRepository;
use App\Services\Helper;
use Illuminate\Http\Request;

/**
 * 项目说明
 * 文章列表功能控制在其所属的渠道平台发布显示
 * @author: david
 * @version: 1.0
 * @date: 2020/8/11
 */
class ExceptionLogController extends BaseController
{
    protected $viewPath = 'backend.maintenance.exceptionlog';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(SaasExceptionLogRepository $Repository)
    {
        parent::__construct();
        $this->repositories = $Repository;
    }

    //列表
    public function index()
    {
        $isSolved = Helper::getChooseSelectData(config('goods.y_n'));
        return view('backend.maintenance.exceptionlog.index',['isSolved'=>$isSolved]);
    }

    //ajax数据
    protected function table(Request $request)
    {
        try {
            $inputs = $request->all();
            $list = $this->repositories->getTableList($inputs,'created_at desc');

            $htmlContents = $this->renderHtml('',['list' =>$list ,'yn'=>config('goods.y_n')]);
            //转数组取总数量
            $pagesInfo = $list->toArray();
            $total = $pagesInfo['total'];

            return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
        } catch (CommonException $e) {
            //统一收集错误再做处理
            return $this->jsonFailed($e->getMessage());
        }

    }

    //改变是否处理
    public function updateField(Request $request)
    {
        $post = $request->all();
        $ret = $this->repositories->changeUpdateField($post);

        return $this->jsonSuccess(['ret' => $ret['flag']]);;
    }



}