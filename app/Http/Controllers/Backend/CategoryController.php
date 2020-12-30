<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Backend\BaseController;
use App\Http\Requests\Backend\CategoryRequest;
use App\Repositories\SaasCategoryRepository;
use App\Services\Tree;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Exceptions\CommonException;


/**
 * 分类管理
 * 管理数据配置后台的各个分类
 * @author:hlt
 * @version: 1.0
 * @date:2020/3/25
 */
class CategoryController extends BaseController
{
    protected $viewPath = 'backend.category';  //当前控制器所的view所在的目录
    protected $modules = 'category';        //当前控制器所属模块
    protected $sysId = 'backend';        //当前控制器所属模块

    public function __construct(SaasCategoryRepository $Repository )
    {
        parent::__construct();
        $this->repositories = $Repository;
        //获取分类数组标识
        $this->cateList = config('common.backend_category');
        //获取分类数组第一个下标作为默认分类

        if (session('category_type')) {
            $this->firstType = session('category_type');
        }else{
            $this->firstType = key($this->cateList);
        }


    }

    public function index()
    {
        return view('backend.category.index',['cateList'=>$this->cateList,'firstType'=>$this->firstType]);
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
            //加入默认的查询条件
            $inputs['cate_status'] = $inputs['cate_status']??1;
            //获取分类标识
            if (session('category_type')&&!isset($inputs['cate_uid'])){
                //情况1：页面刷新时
                $inputs['cate_uid'] = session('category_type');
            }else{
                $inputs['cate_uid'] = $inputs['cate_uid']??$this->firstType;
            }

            session(['category_type' => $inputs['cate_uid']]);

            $list = $this->repositories->getTableList($inputs)->toArray();
            //取出父级分类名称
            $list['data'] = $this->repositories->getParentList($list['data']);
            //无限级分类
            $categoryList = $this->repositories->getTreeList($list['data']);
            $htmlContents = $this->renderHtml('',['list' =>$categoryList,'cate_type' => $inputs['cate_uid'] ]);
            $pagesInfo = $list;
            $total = $pagesInfo['total'];
            return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);

        } catch (CommonException $e) {
            //统一收集错误再做处理
            var_dump($e->getMessage());
        }

    }

    public function form(Request $request)
    {
        try {

            if($request->ajax())
            {
                $category_type = session('category_type');
                //获取该类目下的所有分类
                $categoryList = $this->repositories->getTypeArr($category_type);

                $row = $this->repositories->getByIdFromCache($request->input('id'));

                $cateType = session('category_type');

                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row,'categoryList' => $categoryList,'cateType' => $cateType ]);

                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }
    }

    //添加/编辑操作
    public function save(CategoryRequest $request)
    {

        //获取当前类目
        $data = $request->all();
        $data['cate_uid'] = session('category_type');
        //获取父辈id
        $data['cate_all_parent'] = $this->repositories->getParentId($data['cate_parent_id'],$data['cate_uid']);

        //获取添加的类目等级
        $data['cate_level'] = $this->repositories->getLevel($data['cate_all_parent']);

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