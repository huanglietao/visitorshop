<?php
namespace App\Http\Controllers\Merchant\Order;

use App\Http\Controllers\Merchant\BaseController;
use App\Repositories\SaasOrderTagRepository;
use Illuminate\Http\Request;

/**
 * 商户订单管理->订单标签
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020/06/29
 */
class TagController extends BaseController
{
    protected $viewPath = 'merchant.order.tag';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块

    public function __construct(SaasOrderTagRepository $repository)
    {
        parent::__construct();
        $this->repositories = $repository;
        $this->mch_id = isset(session("admin")['mch_id']) ? session("admin")['mch_id'] : PUBLIC_CMS_MCH_ID;

    }

    //列表展示页面
    public function index()
    {
        return view("merchant.order.tag.index");
    }

    //ajax方式获取列表
    public function table(Request $request)
    {
        $inputs = $request->all();
        $list = $this->repositories->getTableList($inputs)->toArray();

        $htmlContents = $this->renderHtml('',['list' =>$list['data']]);
        $total = $list['total'];

        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
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
                $row = $this->repositories->getById($request->input('id'));

                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

    }

    //添加、编辑操作
    public function save(Request $request)
    {
        $param = $request->all();
        unset($param['_token']);
        $param['mch_id'] = $this->mch_id;


        $ret = $this->repositories->save($param);
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }

}