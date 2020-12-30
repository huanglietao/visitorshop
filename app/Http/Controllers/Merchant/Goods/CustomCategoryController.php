<?php
namespace App\Http\Controllers\Merchant\Goods;

use App\Exceptions\CommonException;
use App\Http\Controllers\Merchant\BaseController;
use App\Http\Requests\Merchant\Goods\CustomCategoryRequest;
use App\Repositories\SaasCustomCategoryRepository;
use App\Services\Helper;
use Illuminate\Http\Request;

/**
 * 商品自定义分类
 * 商品自定义分类
 * @author:
 * @version: 1.0
 * @date:
 */
class CustomCategoryController extends BaseController
{
    protected $viewPath = 'merchant.goods.customcategory';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(SaasCustomCategoryRepository $Repository)
    {
        parent::__construct();

        $this->repositories = $Repository;
    }

    /**
     * 功能首页结构view
     * @return mixed
     */
    protected function index()
    {
        return view('merchant.goods.customcategory.index');
    }
    /**
     * ajax获取列表项
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function table(Request $request)
    {
        try {
            $inputs = $request->all();
            $mchId = isset(session('admin')['mch_id']) ? session('admin')['mch_id'] : '';
            if (empty($mchId)){
                Helper::EasyThrowException('20031',__FILE__.__LINE__);
            }
            $inputs['mch_id'] = $mchId;
            $list = $this->repositories->getTableList($inputs)->toArray();
            //取出父级分类名称
            $list['data'] = $this->repositories->getParentList($list['data']);

            //无限级分类
            $categoryList = $this->repositories->getTreeList($list['data']);
            $htmlContents = $this->renderHtml('',['list' =>$categoryList]);
            $pagesInfo = $list;
            $total = $pagesInfo['total'];
            return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);

        } catch (CommonException $e) {
            //统一收集错误再做处理
            return $this->jsonFailed($e->getMessage());
        }

    }
    public function form(Request $request)
    {
        try {

            if($request->ajax())
            {
                $mchId = isset(session('admin')['mch_id']) ? session('admin')['mch_id'] : '';
                //获取该类目下的所有分类
                $categoryList = $this->repositories->getAllList($mchId);

                $row = $this->repositories->getByIdFromCache($request->input('id'));
                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row,'categoryList' => $categoryList]);

                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }
    }

    //添加/编辑操作
    public function save(CustomCategoryRequest $request)
    {
        $mchId = isset(session('admin')['mch_id']) ? session('admin')['mch_id'] : '';
        //获取当前类目
        $data = $request->all();
        //获取父辈id //这里暂时只作一级分类
        /*$data['cate_all_parent'] = $this->repositories->getParentId($data['cate_parent_id'],$mchId);*/

        //获取添加的类目等级
        /*$data['cate_level'] = $this->repositories->getLevel($data['cate_all_parent']);*/

        //加入商家id
        $data['mch_id'] = $mchId;

        //加入创建时间和更新时间
        $data['created_at'] = time();

        $ret = $this->repositories->save($data);


        if (isset($ret['code'])&&$ret['code']==0){
            return $this->jsonFailed($ret['msg']);
        }
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }

}