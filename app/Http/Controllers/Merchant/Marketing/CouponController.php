<?php
namespace App\Http\Controllers\Merchant\Marketing;

use App\Exceptions\CommonException;
use App\Http\Controllers\Merchant\BaseController;
use App\Http\Requests\Merchant\Marketing\CouponRequest;
use App\Repositories\OmsCouponRepository;
use App\Repositories\SaasCategoryRepository;
use App\Services\Helper;
use Illuminate\Http\Request;

/**
 * 项目说明 OMS系统 营销管理--优惠券列表
 * 详细说明 OMS系统 营销管理--优惠券列表，实现列表，添加，编辑，删除及组件结合
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/04/13
 */
class CouponController extends BaseController
{
    protected $viewPath = 'merchant.marketing.coupon';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $merchantID = '';

    public function __construct(OmsCouponRepository $Repository ,SaasCategoryRepository $categoryRepository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->category_list = $categoryRepository->getTypeArr('goods');
        $this->sales_chanel = $Repository->getSalesChanel();
        $this->merchantID = empty(session('admin')) == false ? session('admin')['mch_id'] : ' ';
    }

    /**
     * 功能首页结构view
     * @return mixed
     */
    protected function index()
    {
        $sales_chanel = Helper::getChooseSelectData($this->sales_chanel);
        return view('merchant.marketing.coupon.index',['sales_chanel'=>$sales_chanel]);
    }


    /**
     * ajax获取列表项
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function table(Request $request)
    {
        $inputs = $request->all();
        $inputs['mch_id']=$this->merchantID;
        $list = $this->repositories->getTableList($inputs,"cou_id desc");

        $result = $list->toArray();
        //得到所属子系统名称
        foreach ($result['data'] as $k=>$v){
            $result['data'][$k]['sales_chanel_name'] = $this->repositories->getSalesChanel($v["sales_chanel_id"]);
        }

        $htmlContents = $this->renderHtml('',['list' =>$result['data']]);

        $pagesInfo = $list->toArray();

        $total = $pagesInfo['total'];

        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
    }



    /**
     * 通用表单展示
     * @param Request $request
     * @return mixed
     */
    public function form(Request $request)
    {
        try {
            if($request->ajax())
            {
                $row = $this->repositories->getById($request->input('id'));
                $sales_chanel = $this->sales_chanel;
                $goods_or_category_list = [];
                if(!empty($row)){
                    $result = $row->toArray();
                    //当使用范围为指定商品时，根据商品id找到商品名称
                    if($result['cou_use_limits']==2){
                        $goods_or_category_list = $this->repositories->getGoodsCategory($result['cou_use_limits'],$result['goods_id'],$this->merchantID);
                    }
                    //当使用范围为指定分类时，根据商品分类id找到商品分类名称
                    if($result['cou_use_limits']==3){
                        $goods_or_category_list = $this->repositories->getGoodsCategory($result['cou_use_limits'],$result['goods_category_id'],$this->merchantID);
                    }
                }
                //获取商品
                $goods_list = $this->repositories->getGoods($this->merchantID);

                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row,'sales_chanel'=>$sales_chanel,'category_list'=>$this->category_list,'goods_list'=>$goods_list,'goods_or_category_list'=>$goods_or_category_list]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

    }


   //添加/编辑操作
    public function save(CouponRequest $request)
    {
        try{
            \DB::beginTransaction();
            $params = $request->all();
            $data=[
                'cou_id' => $params['cou_id'],
                'mch_id' => $this->merchantID,
                'sales_chanel_id' => $params['sales_chanel_id'],
                'cou_use_limits' => $params['cou_use_limits'],
                'cou_name' => $params['cou_name'],
                'cou_desc' => $params['cou_desc'],
                'cou_type' => $params['cou_type'],
                'cou_distribution_method' => $params['cou_distribution_method'],
                'cou_denomination' => $params['cou_denomination'],
                'cou_use_rule' => $params['cou_use_rule'],
                'cou_min_consumption' => $params['cou_min_consumption']??0,
                'cou_nums' => $params['cou_nums'],
                'cou_score' => $params['cou_score'],
                'goods_id'=>$params['goods_id'],
                'goods_category_id'=>$params['goods_category_id'],
            ];

            //优惠券生效时间和失效时间
            $cou_time = Helper::getTimeRangedata($params['cou_time']);
            $data['cou_start_time'] = $cou_time['start'];
            $data['cou_end_time'] = $cou_time['end'];

            $ret = $this->repositories->save($data);

            //如果类型为优惠码时，生成优惠码存到优惠码表
            if($params['cou_type'] == 2) {
                $ret = $this->repositories->saveCouponNum($params,$ret);
            }

            if ($ret) {
                \DB::commit();
                return $this->jsonSuccess([]);
            } else {
                return $this->jsonFailed('');
            }
        }catch (CommonException $e) {
            \DB::rollBack();
            return $this->jsonFailed($e->getMessage());
        }

    }

}
