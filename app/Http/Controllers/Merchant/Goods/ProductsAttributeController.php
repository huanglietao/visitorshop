<?php
namespace App\Http\Controllers\Merchant\Goods;

use App\Http\Controllers\Merchant\BaseController;
use App\Http\Requests\Merchant\Goods\ProductsAttributeRequest;
use App\Repositories\SaasProductsAttributeRepository;
use App\Repositories\SaasCategoryRepository;
use App\Repositories\SaasAttributeValueRepository;
use Illuminate\Http\Request;

/**
 * 项目说明
 * 详细说明
 * @author:
 * @version: 1.0
 * @date:
 */
class ProductsAttributeController extends BaseController
{
    protected $viewPath = 'merchant.goods.productsattribute';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(SaasProductsAttributeRepository $Repository,SaasCategoryRepository $categoryRepository,SaasAttributeValueRepository $attributeValueRepository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->categoryRepository = $categoryRepository;
        $this->attributeValueRepository = $attributeValueRepository;
    }

    /**
     * 功能首页结构view
     * @return mixed
     */
    protected function index()
    {

        //获取二级类目下的所有分类
        $categoryList = $this->categoryRepository->getList(['mch_id' => PUBLIC_CMS_MCH_ID,'cate_level'=>CATEGORY_NO_TWO,'cate_uid'=>'goods'])->toArray();

        return view('backend.goods.productsattribute.index',['categoryList' => $categoryList]);
    }
    /**
     * ajax获取列表项
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function table(Request $request)
    {

        $inputs = $request->all();

        $list = $this->repositories->getTableList($inputs)->toArray();



        $htmlContents = $this->renderHtml('',['list' =>$list['data']]);


        $total = $list['total'];

        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
    }


    public function form(Request $request)
    {

        try {
            if($request->ajax())
            {
                //获取二级类目下的所有分类
                $categoryList = $this->categoryRepository->getList(['mch_id' => PUBLIC_CMS_MCH_ID,'cate_level'=>CATEGORY_NO_TWO,'cate_uid'=>'goods']);

                //获取属性值数组
                $attrValues = [];
                if ($request->input('id'))
                {
                    $attrValues = $this->attributeValueRepository->getList(['attr_id' => $request->input('id')])->toArray();
                }

                $row = $this->repositories->getByIdFromCache($request->input('id'));

                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row,'categoryList' => $categoryList,'attrValues' =>$attrValues]);

                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }
    }

   //添加/编辑操作
    public function save(ProductsAttributeRequest $request)
    {
        $ret = $this->repositories->save($request->all());

        if ($ret['code']) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed($ret['msg']);
        }
    }
    //删除属性值
    public function deleteAttrValue(Request $request)
    {
        $ret = $this->attributeValueRepository->delete($request->id);
        if($ret) {
            return $this->jsonSuccess(['']);
        } else {
            return $this->jsonFailed("");
        }
    }

}