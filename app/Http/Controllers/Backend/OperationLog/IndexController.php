<?php
namespace App\Http\Controllers\Backend\OperationLog;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Backend\OperationLog\IndexRequest;
use Illuminate\Http\Request;
use App\Services\Common\Log\LogInterface;
use App\Services\Helper;
use App\Repositories\OperationLogRepository;

/**
 * 项目说明
 * 详细说明
 * @author:
 * @version: 1.0
 * @date:
 */
class IndexController extends BaseController
{
    protected $viewPath = 'backend.operationlog.index';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $sysId = 'Backend';  //当前所属子系统
    protected $noLog = '__construct,index,table';  //不记录操作日志

    public function __construct(OperationLogRepository $logRepository)
    {


        $this->logDriver =  app(LogInterface::class);

        $this->logDriver->setCollection(config("app.access_log_table"));

        $this->logRepository = $logRepository;
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
            //判断是否有操作人搜索字段
            if (isset($data['operator_name'])&&$data['operator_name']!=""){
                //获取操作人id
               $data['operator_id'] = $this->logRepository->getOperatorId($data['operator_name']);
               unset($data['operator_name']);
            }

            if (empty($data)){
                //提取该子系统所属错误日志
                $data = [
                    'sys' => $this->sysId
                ];
            }



            $listArr = $this->logDriver->getLog($data,$offset,$limit,'add_time');

            $list = $listArr['data'];



            //获取操作人姓名
            $list = $this->logRepository->getOperatorName($list->toArray());

            $htmlContents = $this->renderHtml('backend.operationlog.index._table',['list' =>$list]);

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
           $rowArr = $this->logDriver->getLog($data);
           $row = $rowArr['data'];
            $row = $this->logRepository->getOperatorName($row->toArray());

            $htmlContents = $this->renderHtml('backend.operationlog.index._form', ['row' => $row]);
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
        $this->logDriver->delLog($data);

        return $this->jsonSuccess(['']);
    }

}