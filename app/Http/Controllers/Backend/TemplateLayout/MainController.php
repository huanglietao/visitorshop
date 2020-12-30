<?php
namespace App\Http\Controllers\Backend\TemplateLayout;

use App\Http\Controllers\Backend\BaseController;
use App\Http\Requests\Backend\TemplateLayout\MainRequest;
use App\Repositories\SaasTemplateLayoutTypeRepository;
use App\Repositories\SaasTemplatesLayoutRepository;
use App\Services\Helper;
use App\Repositories\SaasProductsSizeRepository;
use App\Services\Template\Main;
use Illuminate\Http\Request;
use App\Repositories\SaasCategoryRepository;
use App\Exceptions\CommonException;

/**
 * 项目说明
 *  布局定义了单个模板的所有数据，可用于替换模板中的子页以及配合封面自由生成模板
 * @author: david
 * @version: 1.0
 * @date: 2020/5/6
 */
class MainController extends BaseController
{
    protected $viewPath = 'backend.templatelayout.main';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(SaasTemplatesLayoutRepository $Repository,
                                SaasProductsSizeRepository $SizeRepository,
                                SaasTemplateLayoutTypeRepository $layoutTypeRepository,
                                SaasCategoryRepository $CategoryRepository)

    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->sizeRepositories = $SizeRepository;
        $this->layoutTypeRepositories = $layoutTypeRepository;
        $this->cateRepositories = $CategoryRepository;

        //获取布局版式类型
        $this->layoutType = $this->layoutTypeRepositories->getlayoutTypes();
    }

    // 列表
    public function index()
    {
        $goodsSpec = $this->sizeRepositories->getGoodsSpecList('0','table');
        $specList = Helper::getChooseSelectData($goodsSpec);
        $specStyle = Helper::getChooseSelectData(config('goods.size_type'));
        $checkStatus = Helper::getChooseSelectData(config('goods.check_status'));
        $layoutType = Helper::getChooseSelectData($this->layoutType);

        return view('backend.templatelayout.main.index',['specStyle'=>$specStyle,'specList'=>$specList,'checkStatus'=>$checkStatus,'layoutType'=>$layoutType]);
    }

    //ajax 获取数据
    protected function table(Request $request)
    {
        $inputs = $request->all();
        $list = $this->repositories->getTableList($inputs,'temp_layout_id desc');
        $goodsSpec = $this->sizeRepositories->getGoodsSpecList('0','table');

        $htmlContents = $this->renderHtml('',[
            'list'          => $list,
            'layoutType'    => $this->layoutType,
            'sizeType'      => config('goods.size_type'),
            'specLink'      => $goodsSpec,
            'checkStatus'  => config('goods.check_status')
            ]);

        $pagesInfo = $list->toArray();

        $total = $pagesInfo['total'];

        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
    }

    //添加/编辑操作
    protected function form(Request $request)
    {
        try {
            if($request->ajax())
            {
                $row = $this->repositories->getByIdFromCache($request->input('id'));

                //获取商品类目分类并转换数据结构输出
                $goodsCategory = $this->cateRepositories->getGoodsThirdCate();
                $goodsCateType = Helper::ListToKV('cate_id','cate_name',$goodsCategory);
                //获取规格数据
                $goodsSpec = $this->sizeRepositories->getGoodsSpecList('0','table');

                if($request->input('id')){
                    $sizeInfo = $this->sizeRepositories->getSizeCombInfo($row['specifications_id']);
                    //根据需要获取内页的详情规格参数
                    foreach ($sizeInfo['detail_list'] as $k =>$v){
                        if($v['size_type'] == GOODS_SIZE_TYPE_INNER){
                            $specInfo = $v;
                        }
                    }
                }else{
                    $specInfo = [];
                }
                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form,[
                    'row'           => $row,
                    'goodsCateType' => $goodsCateType,
                    'layoutType'    => $this->layoutType,
                    'sizeType'      => config('goods.size_type'),
                    'specLink'      => $goodsSpec,
                    'specInfo'      =>$specInfo,
                ]);
                return $this->jsonSuccess(['html' => $htmlContents]);

            }else{
                return view("admin.tips");
            }

        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }
    }

   //添加/编辑操作
    public function save(MainRequest $request)
    {
        $data = $request->all();
        if(empty($data['temp_layout_sort'])){
            $data['temp_layout_sort'] = ZERO;
        }
        if(empty($data['layout_spec_style'])){
            $data['layout_spec_style'] = ZERO;
        }
        $ret = $this->repositories->save($data);
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('无任何修改无需提交');
        }
    }


    //获取规格数据
    public function getGoodsSpecLink(Request $request)
    {
        $post = $request->all();
        $post['size_cate_id'] = $post['id'];
        unset($post['id']);

        $sizeList = $this->sizeRepositories->getSizeInfoByMid('0',$post);
        //选择商品分类返回对应的规格数据
        $sizeLink = [];
        foreach ($sizeList as $k =>$v){
            $sizeLink[$v['size_id']] = $v['size_name'];
        }

        return $this->jsonSuccess($sizeLink);
    }

    // 获取规格详情 同时获取规格标签
    public function getSpecdetail(Request $request)
    {
        $specId = $request->all();
        //先获取一条规格对应的数据包含规格详细
        $sizeInfo = $this->sizeRepositories->getSizeCombInfo($specId['id']);
        //根据需要获取内页的详情规格参数
        $sizeDetail = [];
        foreach ($sizeInfo['detail_list'] as $k =>$v){
            if($v['size_type'] == GOODS_SIZE_TYPE_INNER){
                $sizeDetail = $v;
            }
        }

       //根据规格id获取规格标签
        $sizeList = $this->sizeRepositories->getProductSize($specId['id']);

        $sizeConfig = config('goods.size_type');
        $sizeStyle = [];
        foreach ($sizeList as $k =>$v){
            if(empty($sizeList['size_type'])){
                $sizeStyle['size_name'] = '无';
                $sizeStyle['size_type'] = ZERO;
            }else{
                $sizeStyle['size_name'] = $sizeConfig[$sizeList['size_type']];
                $sizeStyle['size_type'] = $sizeList['size_type'];
            }
        }
        /*if(!empty($sizeList['size_type'])){
            foreach ($sizeList as $k =>$v){
                $sizeStyle[$sizeList['size_type']] = $sizeConfig[$sizeList['size_type']];
            }
        }*/

        return $this->jsonSuccess(['sdetail'=>$sizeDetail,'size_style'=>$sizeStyle]);
    }

    //改变审核状态
    public function checkstatus(Request $request)
    {
        $post = $request->all();
        $ret = $this->repositories->changeCheckStatus($post);

        return $this->jsonSuccess($ret);
    }

    // 克隆模板布局
    public function copy(Request $request)
    {
        $post = $request->all();

        $logic = new Main();
        try{
            $result = $logic->TempLayoutCopy($post['id']);
            if($result != 'true'){
                Helper::EasyThrowException('50012',__FILE__.__LINE__);
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }
        return $this->jsonSuccess(['ret'=>'success']);
    }






}