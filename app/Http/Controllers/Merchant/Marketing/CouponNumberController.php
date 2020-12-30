<?php
namespace App\Http\Controllers\Merchant\Marketing;

use App\Http\Controllers\Merchant\BaseController;
use App\Http\Requests\Merchant\Marketing\CouponNumberRequest;
use App\Repositories\OmsCouponNumberRepository;
use Illuminate\Http\Request;

/**
 * 项目说明 OMS系统 营销管理--优惠券列表--优惠码页面
 * 详细说明 OMS系统 营销管理--优惠券列表--优惠码页面
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/04/14
 */
class CouponNumberController extends BaseController
{
    protected $viewPath = 'merchant.marketing.couponNumber';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $cou_id = "";
    public function __construct(OmsCouponNumberRepository $Repository,Request $request)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->cou_id = $request->get('id');
    }


    public function index()
    {
        return view('merchant.marketing.couponNumber.index',['cou_id'=>$this->cou_id]);
    }

    /**
     * ajax获取列表项
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function table(Request $request)
    {
        $inputs = $request->all();
        $list = $this->repositories->getTableList($inputs,"cou_num_id desc");
        $result = $list->toArray();
        //获取所属优惠券的名称
        foreach ($result['data'] as $k=>$v){
            $result['data'][$k]['cou_name'] = $this->repositories->getCouponName($v['cou_id']);
        }

        $htmlContents = $this->renderHtml('',['list' =>$result['data']]);

        $pagesInfo = $list->toArray();

        $total = $pagesInfo['total'];

        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
    }


   //添加/编辑操作
    public function save(CouponNumberRequest $request)
    {
        $ret = $this->repositories->save($request->all());
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }

}