<?php
namespace App\Http\Controllers\Backend\Queue;

use App\Exceptions\CommonException;
use App\Http\Controllers\Backend\BaseController;
use App\Repositories\SaasDeliveryQueueRepository;
use App\Services\Helper;
use Illuminate\Http\Request;

/**
 * 项目说明
 * 同步队列功能
 * @author: david
 * @version: 1.0
 * @date: 2020/7/3
 */
class DeliveryQueueController extends BaseController
{
    protected $viewPath = 'backend.queue.deliveryqueue';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(SaasDeliveryQueueRepository $Repository)
    {
        parent::__construct();
        $this->repositories     = $Repository;

        //获取统计同步状态
        $this->queueStatus = $this->repositories->getQueueStatus();
        //获取状态数据
        $this->queueStatusList = config('common.syncStatus');
    }

    //列表
    public function index()
    {
        $queueStatusList = Helper::getChooseSelectData($this->queueStatusList);
        return view('backend.queue.deliveryqueue.index',['queueStatus'=>$this->queueStatus,'queueStatusList'=>$queueStatusList]);
    }

    //ajax数据
    protected function table(Request $request)
    {
        try {

            $inputs = $request->all();
            $list = $this->repositories->getTableList($inputs,'created_at desc');

            $htmlContents = $this->renderHtml('',
                ['list' =>$list,'queueStatusList'=>$this->queueStatusList]);
            //转数组取总数量
            $pagesInfo = $list->toArray();
            $total = $pagesInfo['total'];

            return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
        } catch (CommonException $e) {
            //统一收集错误再做处理
            return $this->jsonFailed($e->getMessage());
        }

    }

    //表单
    protected function form(Request $request)
    {
        try {
            if($request->ajax())
            {
                $row = $this->repositories->getById($request->input('id'));

                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row,'queueStatusList'=>$this->queueStatusList]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

    }

    //保存
    public function save(Request $request)
    {
        $post = $request->all();

        $ret = $this->repositories->save($post);
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }

    //改变状态
    public function changQueueStatus(Request $request)
    {
        $post = $request->all();
        $ret = $this->repositories->updateQueueStatus($post);
        if($ret){
            return $this->jsonSuccess([]);
        }else{
            return $this->jsonFailed('操作失败');
        }

    }




}