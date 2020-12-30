<?php
namespace App\Http\Controllers\Backend\Templatecenter;

use App\Http\Controllers\Backend\BaseController;
use App\Http\Requests\Backend\Templatecenter\InnerRequest;
use App\Repositories\SaasInnerTemplatesRepository;
use App\Repositories\SaasProductsSizeRepository;
use App\Repositories\SaasSizeInfoRepository;
use Illuminate\Http\Request;
use App\Repositories\SaasCategoryRepository;
use App\Services\Helper;
use App\Services\Template\Main;
use App\Services\Template\Attachment;


/**
 * 项目说明
 * 内页模板控制器：内页模板库主要是通过背景素材等创建模板内页，主模板可直接使用且可以多次使用
 * @author: david
 * @version: 1.0
 * @date: 2020/4/23
 */
class InnerController extends BaseController
{
    protected $viewPath = 'backend.templatecenter.inner';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(SaasInnerTemplatesRepository $Repository,
                                SaasCategoryRepository $CategoryRepository,
                                SaasProductsSizeRepository $SizeRepository,
                                SaasSizeInfoRepository $SizeInfoRepository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->cateRepositories = $CategoryRepository;
        $this->sizeRepositories = $SizeRepository;
        $this->sizeInfoRepositories = $SizeInfoRepository;
        //获取模板主题分类数据
        $ThemeList = $this->cateRepositories->getTList(GOODS_MAIN_CATEGORY_TEMPLATE);
        $this->tempThemeList = Helper::ListToKV('cate_id','cate_name',$ThemeList);
    }

    // 列表
    public function index()
    {
        $goodsSpec = $this->sizeRepositories->getGoodsSpecList('0','table');
        $specList = Helper::getChooseSelectData($goodsSpec);
        $tempThemeList = Helper::getChooseSelectData($this->tempThemeList);
        $checkStatus = Helper::getChooseSelectData(config('goods.check_status'));
        return view('backend.templatecenter.inner.index',['tempThemeList'=>$tempThemeList,'specList'=>$specList,'checkStatus'=>$checkStatus]);
    }

    //数据加载
    protected function table(Request $request)
    {
        $inputs = $request->all();
        //获取规格
        $specList = $this->sizeRepositories->getGoodsSpecList('0','table');

        $list = $this->repositories->getTableList($inputs,'created_at desc')->toArray();
        $total = $list['total'];

        //根据搜索条件不同处理数据返回
        if(!empty($list['data'])){
            $list = $this->getMakeInnertTemp($list['data']);
        }else{
            $list = $list['data'];
        }
        $domain = 'http://'.config('app.manage_url').'/';
        $htmlContents = $this->renderHtml('',['list' =>$list,
            'specList'=>$specList,
            'tempThemeList'=>$this->tempThemeList,
            'checkStatus'=>config('goods.check_status'),
            'url'=>$domain
        ]);

        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
    }

    //添加/编辑操作
    protected function form(Request $request)
    {
        try {
            $row = $this->repositories->getByIdFromCache($request->input('id'));

            //获取商品类目分类并转换数据结构输出
            $goodsCategory = $this->cateRepositories->getGoodsThirdCate();
            $goodsCateType = Helper::ListToKV('cate_id','cate_name',$goodsCategory);
            $goodsFlag = Helper::ListToKV('cate_id','cate_flag',$goodsCategory); //匹配商品标识

            //根据不同操作显示规格数据
            $specList = $this->sizeRepositories->getGoodsSpecList();

            $defaultGoodsType = $goodsCategory[0]['cate_id']; //默认第一个商品分类做下标
            $default_spec=  array_keys($specList[$defaultGoodsType]); //默认第一个商品分类的规格参数

            $attachLogic = new Attachment();
            if(empty($request->input('id'))){ //添加
                $specLink = $specList[$defaultGoodsType];
                $cateFlag = $goodsCategory[0]['cate_flag'];
                //获取背景像素尺寸
                $backSize = $attachLogic->getBackPx(array_keys($specLink)[0]);
                if(!empty($backSize[GOODS_SIZE_TYPE_INNER])){
                    $sizeInfo = $backSize[GOODS_SIZE_TYPE_INNER];
                    $widthPx = ceil($sizeInfo['size_info_dpi']*round($sizeInfo['size_design_w']/25.4,3));
                    $heightPx = ceil($sizeInfo['size_info_dpi']*round($sizeInfo['size_design_h']/25.4,3));
                    $backPx = ['width'=>$widthPx,'height' => $heightPx];
                }else{
                    $backPx = ['width'=>0,'height' => 0];
                }
            }else{ //编辑
                $specLink = $specList[$row['goods_type_id']];
                //获取商品分类标识
                $goodsCate = $this->cateRepositories->getCategoryFlag($row['goods_type_id']);
                $cateFlag = $goodsCate->cate_flag;
                //获取背景像素尺寸
                $backSize = $attachLogic->getBackPx($row['specifications_id']);
                if(!empty($backSize[GOODS_SIZE_TYPE_INNER])){
                    $sizeInfo = $backSize[GOODS_SIZE_TYPE_INNER];
                    $widthPx = ceil($sizeInfo['size_info_dpi']*round($sizeInfo['size_design_w']/25.4,3));
                    $heightPx = ceil($sizeInfo['size_info_dpi']*round($sizeInfo['size_design_h']/25.4,3));
                    $backPx = ['width'=>$widthPx,'height' => $heightPx];
                }else{
                    $backPx = ['width'=>0,'height' => 0];
                }

            }

            //当选择台挂历时的年份
            $logicMain = new Main();
            $yearList =$logicMain->getCalendarYear('2018');


            $ids= $row['inner_temp_id'];
            //组装素材数据
            if(!empty($ids)) {
                $attaList = $attachLogic->getAttachmentByTempId($ids,TEMPLATE_PAGE_INNER);

                $arrBack = [];
                $arrDecorate = [];
                $arrFrame = [];
                if(!empty($row)) {

                    foreach ($attaList as $k =>$v) {
                        switch ($v['temp_attach_material_type']) {
                            case MATERIAL_TYPE_BACKGROUND :
                                $arrBack[] = $v['temp_attach_id'].','.$v['temp_attach_material_type'].'/'.'sml/'.$v['temp_attach_path'];
                                break;
                            case MATERIAL_TYPE_DECORATE :
                                $arrDecorate[] =  $v['temp_attach_id'].','.$v['temp_attach_material_type'].'/'.'sml/'.$v['temp_attach_path'];
                                break;
                            case MATERIAL_TYPE_FRAME:
                                $arrFrame[] =  $v['temp_attach_id'].','.$v['temp_attach_material_type'].'/'.'sml/'.$v['temp_attach_path'];
                                $arrFrame[] = $attachLogic->getFrameOther($v['temp_attach_id'].','.$v['temp_attach_material_type'].'/'.'sml/'.$v['temp_attach_path']);
                                break;
                            default : $arrBack[] =  $v['temp_attach_id'].','.$v['temp_attach_material_type'].'/'.'sml/'.$v['temp_attach_path']; break;
                        }
                    }
                }

                $row['background'] = implode(',', $arrBack);
                $row['decorate'] =  implode(',', $arrDecorate);
                $row['frame'] =  implode(',', $arrFrame);
            }

            return view($this->viewPath.'.'.$this->form,[
                'row'           => $row,
                'tempThemeList' => $this->tempThemeList,
                'goodsCateType' => $goodsCateType,
                'specLink'      => $specLink,
                'default_spec'  => $default_spec,
                'tgl'           => GOODS_DIY_CATEGORY_CALENDAR,
                'yearList'      => $yearList,
                'cateFlag'      => $cateFlag,
                'goodsFlag'     => $goodsFlag,
                'uniqid'           =>uniqid(),
                'apiurl'           =>'http://'.config('app.api_url'),
                'backPx'           => $backPx,
                //'sizeType'     => config('goods.size_type'),
            ]);
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

    }

   //保存
    public function save(InnerRequest $request)
    {
        $params = $request->all();
        if(isset($params['_token'])){
            unset($params['_token']);
        }
        if(empty($params['inner_temp_sort'])){
            $params['inner_temp_sort'] = ZERO;
        }
        $ret = $this->repositories->save($params);
        if ($ret['code']) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed($ret['msg']);
        }
    }

    //处理列表数据
    public function getMakeInnertTemp($data)
    {
        foreach ($data as $k=>$v){
            $data[$k]['size_design_w'] = $v['size_info'][0]['size_design_w'];
            $data[$k]['size_design_h'] = $v['size_info'][0]['size_design_h'];
            $data[$k]['size_location_top'] = $v['size_info'][0]['size_location_top'];
            $data[$k]['size_location_left'] = $v['size_info'][0]['size_location_left'];
            $data[$k]['size_location_bottom'] = $v['size_info'][0]['size_location_bottom'];
            $data[$k]['size_location_right'] = $v['size_info'][0]['size_location_right'];
        }
        return $data;
    }

    //改变审核状态
    public function checkstatus(Request $request)
    {
        $post = $request->all();
        $ret = $this->repositories->changeCheckStatus($post);

        return response()->json(['status' => 200, 'ret' => $ret]);;
    }









}