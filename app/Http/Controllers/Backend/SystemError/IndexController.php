<?php
namespace App\Http\Controllers\Backend\SystemError;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Merchant\SystemError\IndexRequest;
use Illuminate\Http\Request;
use App\Services\Common\Log\LogInterface;
use App\Services\Helper;

/**
 * 项目说明
 * 详细说明
 * @author:
 * @version: 1.0
 * @date:
 */
class IndexController extends BaseController
{
    protected $viewPath = 'backend.systemerror.index';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $sysId = 'api';  //当前所属子系统
    public function __construct()
    {
        parent::__construct();
    }

    public function table(Request $request)
    {

        try {
            $data = $request->all();

            $limit = intval($data['limit']??config('common.page_limit'));
            $page = $data['page']??1;
            $offset = intval(($page-1)*$limit);

            unset($data['limit']);
            unset($data['page']);
            //去除搜索字段里的空值
            $data = app(Helper::class)->removeNull($data);

            if (empty($data)){
                //提取该子系统所属错误日志
                $data = [
                    'sys' => $this->sysId
                ];
            }
            if (isset($data['code'])){
                $data['code'] = intval($data['code']);
            }


            $listArr = app(LogInterface::class)->getLog($data,$offset,$limit);
            $list = $listArr['data'];

            $htmlContents = $this->renderHtml('backend.systemerror.index._table',['list' =>$list->toArray()]);


            $total = $listArr['count'];


            return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);

        } catch (CommonException $e) {
            //统一收集错误再做处理
            var_dump($e->getMessage());
        }




    }

    //查看詳情操作
    public function detail(Request $request)
    {
        try {
            $data = [
                '_id' => $request->route('id')
            ];
            $rowArr = app(LogInterface::class)->getLog($data);
            $row = $rowArr['data'];

            $htmlContents = $this->renderHtml('backend.systemerror.index._form', ['row' => $row]);
            return $this->jsonSuccess(['html' => $htmlContents]);

        } catch (CommonException $e) {
            //统一收集错误再做处理
            var_dump($e->getMessage());
        }

        return $this->jsonSuccess(['']);
    }


    //刪除操作
    public function delete(Request $request)
    {
        $data = [
            '_id' => $request->route('id')
        ];
        app(LogInterface::class)->delLog($data);

        return $this->jsonSuccess(['']);
    }

}