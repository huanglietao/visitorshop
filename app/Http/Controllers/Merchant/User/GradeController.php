<?php
namespace App\Http\Controllers\Merchant\User;

use App\Http\Controllers\Merchant\BaseController;
use App\Http\Requests\Merchant\User\GradeRequest;
use App\Repositories\SaasCustomerLevelRepository;
use Illuminate\Http\Request;

/**
 * 项目说明 OMS系统 会员管理--会员组别
 * 详细说明 OMS系统 会员管理--会员组别，实现列表，添加，编辑，删除及组件结合
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/04/29
 */
class GradeController extends BaseController
{
    protected $viewPath = 'merchant.user.grade';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $merchantID = '';

    public function __construct(SaasCustomerLevelRepository $Repository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->merchantID = empty(session('admin')) == false ? session('admin')['mch_id'] : ' ';
    }


    /**
     * ajax获取列表项
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function table(Request $request)
    {

        $inputs = $request->all();
        $inputs['cust_lv_type']=CHANEL_TERMINAL_USER;
        $inputs['mch_id']=$this->merchantID;
        $list = $this->repositories->getTableList($inputs,"cust_lv_id desc");
        $htmlContents = $this->renderHtml('',['list' =>$list]);

        $pagesInfo = $list->toArray();

        $total = $pagesInfo['total'];

        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
    }

   //添加/编辑操作
    public function save(GradeRequest $request)
    {

        $params = $request->all();
//        var_dump($params);exit;
        $data = [
            'cust_lv_id'=>$params['cust_lv_id'],
            'mch_id'=>$this->merchantID,
            'cust_lv_type'=>CHANEL_TERMINAL_USER,
            'cust_lv_name'=>$params['cust_lv_name'],
            'cust_lv_discount'=>$params['cust_lv_discount'],
            'cust_lv_score'=>$params['cust_lv_score'],
            'cust_lv_desc'=>$params['cust_lv_desc'],
            'sort'=>$params['sort']?$params['sort']:0,
        ];

        $ret = $this->repositories->save($data);
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }

}
