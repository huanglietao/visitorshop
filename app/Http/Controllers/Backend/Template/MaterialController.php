<?php
namespace App\Http\Controllers\Backend\Template;

use App\Services\Helper;
use App\Services\Template\Material;
use App\Exceptions\CommonException;
use App\Http\Controllers\Backend\BaseController;
use App\Http\Requests\Backend\Template\MaterialRequest;
use App\Repositories\SaasMaterialRepository;
use Illuminate\Http\Request;
use App\Repositories\SaasCategoryRepository;

/**
 * 项目说明
 * 详细说明
 * @author:
 * @version: 1.0
 * @date:
 */
class MaterialController extends BaseController
{
    protected $viewPath = 'backend.template.material';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $materList = [];       //当前定义分类数组
    protected $tpurl = '';           //定义图片上传域名属性

    public function __construct(SaasMaterialRepository $Repository,SaasCategoryRepository $CateRepository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->cateRepositories = $CateRepository;
        //获取分类数组标识并获取素材和背景两个分类
        $this->materList = ['material'=>'素材分类','background'=>'背景分类'];
        //获取分类数组第一个下标作为默认分类
        if (session('mater_type')&&!isset($inputs['material_type'])) {
            $this->firstType = session('mater_type');
        }else{
            $this->firstType = key($this->materList);
        }
        $this->tpurl = config('template.material')['upload']['tp_url'];

    }

    // 列表
    public function index()
    {
        //获取素材标识类型
        $materialCate = config('goods.material_flag');
        unset($materialCate['templet']);
        $materMerge = Helper::getChooseSelectData($materialCate);

        $materCate = $this->cateRepositories->getTypeArr('material');
        $materCate = Helper::getChooseSelectData($materCate);
        $backCate = $this->cateRepositories->getTypeArr('background');
        $CateMergeArr = $materCate+$backCate;

        return view('backend.template.material.index',['cateList'=>$this->materList,'materType'=>$this->firstType,'materCateType'=>$materMerge,'materCate'=>$CateMergeArr]);
    }

    // 列表数据加载
    protected function table(Request $request)
    {
        try{
            $inputs = $request->all();
            if(isset($inputs['status'])){
                $inputs['material_type'] = $inputs['status'];
                unset($inputs['status']);
            }
            //获取素材类型标识
            if (session('mater_type')&&!isset($inputs['material_type'])){
                //情况1：页面刷新时
                $inputs['material_type'] = session('mater_type');
            }else{
                $inputs['material_type'] = $inputs['material_type']??$this->firstType;
            }
            session(['mater_type' => $inputs['material_type']]); //数据写入session

            $inputs['mch_id'] = ZERO; //加入默认大后台0
            $list = $this->repositories->getTableList($inputs,'material_id desc')->toArray();
            $total = $list['total'];
            //根据搜索条件不同处理数据返回
            if(!empty($list['data'])){
                $materialLogic = new Material();
                $list = $materialLogic->getMakeTableList($list['data']);
            }else{
                $list = $list['data'];
            }

            //根据不同标识获取数据
            $materCateList = $this->cateRepositories->getMaterialCateList($inputs['material_type'],'all');
            //根据选择的类型为背景的话需要插入所属规格标签
            $specStyle = config('goods.size_type');

            $htmlContents = $this->renderHtml('',['list' =>$list,'materialCateList'=>$materCateList,'specStyle'=>$specStyle,'tpurl'=>$this->tpurl,'materFlag'=>config('goods.material_flag')]);

            return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
        }catch (CommonException $e){
            var_dump($e->getMessage());
        }

    }

    // 表单
    public function form(Request $request)
    {
        try {
            if($request->ajax())
            {
                $row = $this->repositories->getById($request->input('id'));
                $materType = session('mater_type');
                //获取该类目下的一级分类
                $cateList = $this->cateRepositories->getMaterialCateList($materType);
                //装饰或画框时的二级分类
                $parentCate = ''; $childCateList = [];
                if($request->input('id')){
                    $childCateList = $this->cateRepositories->getMaterialCateList($materType,ZERO,$row['material_cateid']);
                    $cateInfo = $this->cateRepositories->getById($row['material_cateid']);
                    $parentCate = $cateInfo['cate_parent_id']; //为编辑时一级素材分类的值
                }

                //根据选择的类型为背景的话需要插入所属规格标签
                $specStyle = config('goods.size_type');

                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form,
                    [   'row'          => $row,
                        'matType'      => $materType,
                        'cateList'     =>  $cateList,
                        'parentCate'   => $parentCate,
                        'childCateList'=> $childCateList,
                        'specStyle'    => $specStyle,
                        'uniqid'       => uniqid(),
                        'apiurl'       => 'http://'.config('app.api_url')
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
    public function save(MaterialRequest $request)
    {
        $data = $request->all();

        if(empty($data['id']) && !isset($data['attachment_id'])){
            return $this->jsonFailed('请选择图片上传');
        }
        if(isset($data['material_parent_cateid'])){
            unset($data['material_parent_cateid']);
        }
        if(isset($data['_token'])){
            unset($data['_token']);
        }
        if(empty($data['material_sort'])){
            $data['material_sort'] = ZERO;
        }
        $data['material_type'] = session('mater_type');

        $ret = $this->repositories->save($data);
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }

    //ajax 请求获取下级分类
    public function getMaterialCate(Request $request)
    {
        //获取素材类型的二级分类
        $materCateLists = $this->cateRepositories->getChangeMaterialList($request->all());
        return $this->jsonSuccess(['list' => $materCateLists]);
    }

    //ajax 根据分类标识获取素材分类下的二级分类数据
    public function getMaterCateFlag(Request $request)
    {
        $post = $request->all();
        $materCateLists = $this->cateRepositories->getFlagMaterCate($post['flag']);

        return $this->jsonSuccess($materCateLists);
    }







}