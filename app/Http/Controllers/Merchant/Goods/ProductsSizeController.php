<?php
namespace App\Http\Controllers\Merchant\Goods;

use App\Http\Controllers\Merchant\BaseController;
use App\Http\Requests\Backend\Goods\ProductsSizeRequest;
use App\Repositories\SaasProductsSizeRepository;
use App\Repositories\SaasCategoryRepository;
use Illuminate\Http\Request;

/**
 * 项目说明
 * 详细说明
 * @author:
 * @version: 1.0
 * @date:
 */
class ProductsSizeController extends BaseController
{
    protected $viewPath = 'merchant.goods.productssize';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(SaasProductsSizeRepository $Repository,SaasCategoryRepository $categoryRepository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->categoryRepository =$categoryRepository;
    }

    /**
     * 功能首页结构view
     * @return mixed
     */
    protected function index()
    {

        //获取三级类目下的所有分类
        $categoryList = $this->categoryRepository->getList(['mch_id' => PUBLIC_CMS_MCH_ID,'cate_level'=>CATEGORY_NO_THREE,'cate_uid'=>'goods']);

        return view('backend.goods.productssize.index',['categoryList' => $categoryList]);
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
                //获取三级类目下的所有分类
                $categoryList = $this->categoryRepository->getList(['mch_id' => PUBLIC_CMS_MCH_ID,'cate_level'=>CATEGORY_NO_THREE,'cate_uid'=>'goods']);
                //获取规格标签
                $sizeTypeList = config('goods.size_type');
                //获取单双页数组
                $isTurn = config('goods.is_turn');
                $sizeInfoArr = [];
                if ($request->input('id'))
                {
                    //获取该规格的子页类型
                    $sizeAllInfo = $this->repositories->getPageTypeAndInfo($request->input('id'));
                    $pageType = $sizeAllInfo['pageType'];
                    $sizeInfoArr = $sizeAllInfo['allSizeTypeInfo'];


                }else{
                    //获取子页类型
                    $pageType = config("goods.page_type");
                }



                $row = $this->repositories->getByIdFromCache($request->input('id'));

                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row,'categoryList' => $categoryList,'sizeTypeList' =>$sizeTypeList,'isTurn'=>$isTurn,'pageType' =>$pageType,'sizeInfoArr'=>$sizeInfoArr]);

                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }
    }

   //添加/编辑操作
    public function save(ProductsSizeRequest $request)
    {

        $data = $request->all();
        //插入mid
        $data['mch_id'] = PUBLIC_CMS_MCH_ID;

        $ret = $this->repositories->save($data);

        if ($ret['code']) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed($ret['msg']);
        }
    }

}