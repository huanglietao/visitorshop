<?php
namespace App\Http\Controllers\Backend\Templatecenter;

use App\Http\Controllers\Backend\BaseController;
use App\Http\Requests\Backend\Templatecenter\MainRequest;
use App\Repositories\SaasCategoryRepository;
use App\Repositories\SaasCoverTemplatesRepository;
use App\Repositories\SaasInnerTemplatesRepository;
use App\Repositories\SaasMainTemplatesRepository;
use App\Repositories\SaasSizeInfoRepository;
use App\Repositories\SaasTemplateTagsRepository;
use App\Services\Helper;
use App\Services\Template\Attachment;
use App\Services\Template\Main;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SaasSizeInfo;
use App\Repositories\SaasProductsSizeRepository;
use App\Exceptions\CommonException;

/**
 * 项目说明
 * 详细说明
 * @author: dai
 * @version: 1.0
 * @date: 2020/4/15
 */
class MainController extends BaseController
{
    protected $viewPath = 'backend.templatecenter.main';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $tpurl = '';    //当前定义图片接口域名

    public function __construct(SaasMainTemplatesRepository $Repository,
                                SaasCategoryRepository $CategoryRepository,
                                SaasSizeInfoRepository $sizeInRepository,
                                SaasProductsSizeRepository $SizeRepository,
                              SaasInnerTemplatesRepository $innerRepository,
                                SaasCoverTemplatesRepository $coverRepository,
                                SaasTemplateTagsRepository $tagsRepository)

    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->cateRepositories = $CategoryRepository;
        $this->sizeRepositories = $SizeRepository;
        $this->sizeInfoRepositories = $sizeInRepository;
        $this->coverRepositories = $coverRepository;
        $this->innerRepositories = $innerRepository;
        $this->tagsRepositories = $tagsRepository;

        //获取模板主题分类数据
        $ThemeList = $this->cateRepositories->getTList(GOODS_MAIN_CATEGORY_TEMPLATE);
        $this->tempThemeList = Helper::ListToKV('cate_id','cate_name',$ThemeList);
        $this->tpurl = config('template.material')['upload']['tp_url'];

    }

    // 列表
    public function index()
    {
        $goodsSpec = $this->sizeRepositories->getGoodsSpecList('0','table');
        $specList = Helper::getChooseSelectData($goodsSpec);
        $tempThemeList = Helper::getChooseSelectData($this->tempThemeList);
        $checkStatus = Helper::getChooseSelectData(config('goods.check_status'));
        return view('backend.templatecenter.main.index',['tempThemeList'=>$tempThemeList,'specList'=>$specList,'checkStatus'=>$checkStatus]);
    }
    protected function table(Request $request)
    {
        $inputs = $request->all();
        $inputs['mch_id'] = [ZERO]; //加入默认大后台0
        $list = $this->repositories->getTableList($inputs,'created_at desc')->toArray();

        $listData = $list['data'];
        $specList = $this->sizeRepositories->getGoodsSpecList('0','table');
        foreach ($listData as $k=>$v){
            $listData[$k]['page_count'] = count($v['main_pages']);
        }
        $domain = 'http://'.config('app.manage_url').'/';

        $htmlContents = $this->renderHtml('',['list' =>$listData,
            'tempThemeList'=>$this->tempThemeList,
            'specList'=>$specList,
            'checkStatus'=>config('goods.check_status'),
            'yn'=>config('goods.y_n'),
            'url'=>$domain
        ]);

        $total = $list['total'];
        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
    }

    protected function form(Request $request)
    {
        try {
            $row = $this->repositories->getById($request->input('id'));

            //获取商品类目分类并转换数据结构输出
            $goodsCategory = $this->cateRepositories->getGoodsThirdCate();
            $defaultGoodsType = $goodsCategory[0]['cate_id']; //默认第一个
            $goodsCateType = Helper::ListToKV('cate_id','cate_name',$goodsCategory);
            $goodsFlag = Helper::ListToKV('cate_id','cate_flag',$goodsCategory);
            //获取模板标签
            $tempTagsList = $this->tagsRepositories->getTemptagsList();
            $tagsList = Helper::ListToKV('temp_tags_id','temp_tages_name',$tempTagsList);

            $attachLogic = new Attachment();
            //根据不同操作显示规格数据
            $specList = $this->sizeRepositories->getGoodsSpecList();
            if(empty($request->input('id'))){ //添加
                $specLink = $specList[$defaultGoodsType];
                $cateFlag = $goodsCategory[0]['cate_flag'];
                $tempTag = [];
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
                $cover = $this->coverRepositories->getCoverTemp($row['cover_temp_id']);
                $inner = $this->innerRepositories->getInnerTemp($row['inner_temp_id']);
                $row['cover'] = $cover['cover_temp_name'];
                $row['inner'] = $inner['inner_temp_name'];
                $tempTag = explode(',',$row['temp_tag']);
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

            $default_spec=  array_keys($specList[$defaultGoodsType]);

            $logicMain = new Main();
            $yearList =$logicMain->getCalendarYear('2018');

            $ids= $row['main_temp_id'];
            //组装素材数据
            if(!empty($ids)) {
                $attaList = $attachLogic->getAttachmentByTempId($ids,TEMPLATE_PAGE_MAIN);
                //$attaMstyle=  $attachLogic->getAttachmentMstyleByTempId($ids, Constants::TEMPLATE_PAGE_MAIN,Constants::MATERIAL_TYPE_MSTYLE);

                $arrBack = [];
                $arrDecorate = [];
                $arrFrame = [];
                $arrmstyle = [];
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
                            case MATERIAL_TYPE_MSTYLE :
                                $arrmstyle[] =  $v['temp_attach_id'].','.$v['temp_attach_material_type'].'/'.'big/'.$v['temp_attach_path'];
                                break;
                            default : $arrBack[] =  $v['temp_attach_id'].','.$v['temp_attach_material_type'].'/'.'sml/'.$v['temp_attach_path']; break;
                        }
                    }


                }
                $row->main_background = implode(',', $arrBack);
                $row->main_decorate =  implode(',', $arrDecorate);
                $row->main_frame =  implode(',', $arrFrame);
                $row->main_templet =  implode(',', $arrmstyle);
            }

            return view($this->viewPath.'.'.$this->form,[
                'row'           => $row,
                'tempThemeList' => $this->tempThemeList,
                'goodsCateType' => $goodsCateType,
                'specLink'      => $specLink,
                'default_spec'  => $default_spec,
                'tgl'           => GOODS_DIY_CATEGORY_CALENDAR,
                'cateFlag'      => $cateFlag,
                'yearList'      => $yearList,
                'goodsFlag'     => $goodsFlag,
                'defaultGoodsType' => $defaultGoodsType,
                'uniqid'           =>uniqid(),
                'apiurl'           => 'http://'.config('app.api_url'),
                'tagsList'         => $tagsList,
                'tempTag'          => $tempTag,
                'backPx'           =>$backPx,
                //'apiurl'           =>config('common.plapi_url'),
            ]);
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

    }

    // 根据商品分类id获取规格对应数据
    public function getGoodsSpecLink(Request $request)
    {
        $post = $request->all();
        if($post['tempf']=='face'){
            $arrLink = $this->sizeRepositories->getGoodsSpecList('0','','face');
        }else{
            $arrLink = $this->sizeRepositories->getGoodsSpecList();
        }

        return response()->json(['status' => 200, 'list' => $arrLink]);;
    }

     // 获取规格详情
    public function specdetail(Request $request)
    {
        $spec_id = $request->all();
        $info = $this->sizeInfoRepositories->getSizeInfoDetail($spec_id['id'],GOODS_SIZE_TYPE_INNER);

        if(empty($info)) {
            $htmlContents = $this->renderHtml($this->viewPath.'.specdetail',['row'=>'','rowtip'=>'规格参数未设置!']);
            return $this->jsonSuccess(['html' => $htmlContents]);
        }

        $htmlContents = $this->renderHtml($this->viewPath.'.specdetail',['row'=>$info,'isTurn'=>config('goods.is_turn'),'yn'=>config('goods.y_n'),'isCross'=>config('goods.is_cross')]);
        return $this->jsonSuccess(['html' => $htmlContents]);

    }

    //添加/编辑操作
    public function save(MainRequest $request)
    {
        $params = $request->all();
        if(isset($params['_token'])){
            unset($params['_token']);
        }
        if(empty($params['main_temp_sort'])){
            $params['main_temp_sort'] = ZERO;
        }
        $ret = $this->repositories->save($params);
        if ($ret['code']) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed($ret['msg']);
        }
    }

    //改变前端显示或vip模板
    public function updateField(Request $request)
    {
        $post = $request->all();
        $ret = $this->repositories->changeUpdateField($post);

        return response()->json(['status' => 200, 'ret' => $ret['flag']]);;
    }

    //改变审核状态
    public function checkstatus(Request $request)
    {
        $post = $request->all();
        $ret = $this->repositories->changeCheckStatus($post);

        return response()->json(['status' => 200, 'ret' => $ret]);
    }

     //封面/内页设置
    public function setting(Request $request)
    {
        //获取分类
        $data = $request->all();

        if(empty($data['type']) || empty($data['goods_type']) || empty($data['spec'])){
            // 验证失败 输出错误信息
            return $this->jsonFailed("404 No Found");
        }
        //获取规格数据
        $goodsSpec = $this->sizeRepositories->getGoodsSpecList('0','table');
        $proSize = $this->sizeRepositories->getProductSize($data['spec']);
        if($data['type']==ONE){ //封面传规格
            $spec = $data['spec'];
        }else{ //内页传规格标签
            $spec = $proSize['size_type'];
        }
        //获取封面或内页数据
        $setData = [
            'type' => $data['type'],
            'goods_type'  => $data['goods_type'],
            'page' => isset($data['page']) ? $data['page'] : 1,
            'spec'  => $spec,
        ];
        $list = $this->getSettingData($setData);
        //分页
        $page = !empty($data['page']) ? $data['page'] : 1;

        $htmlContents = $this->renderHtml($this->viewPath.'.setting',['list'=>$list,
            'tempCate'   => $this->tempThemeList,
            'page_type'  => $data['type'],
            'goodsType'  => $data['goods_type'],
            'sizeType'   => config('goods.size_type'),
            'goodsSpec'  => $goodsSpec,
            'specId'     => $data['spec'],
            'size'       => $proSize['size_type'],
            'page'       => $page,
            ]);

        return $this->jsonSuccess(['html' => $htmlContents]);
    }

    /**
     * 获取配置数据
     * @param $data
     * @param int $is_ajax 是否搜索请求
     */
    private function getSettingData($data, $is_ajax = 0)
    {
        $where = [];
        //数据参数转换
        $where['goods_type_id'] = $data['goods_type'];
        $where['page'] = $data['page'];

        //判断封面还是内页配置
        if($data['type'] == ONE){
            $where['specifications_id'] = $data['spec'];
            if(isset($data['cate_id']) && !empty($data['cate_id'])){
                $where['cover_temp_theme_id'] = $data['cate_id'];
            }
            if(isset($data['search_value']) && !empty($data['search_value'])){
                $where['cover_temp_name'] = $data['search_value'];
            }
            //过滤条件
            $where['cover_temp_check_status'] =  TEMPLATE_STATUS_VERIFYED;

            $list = $this->coverRepositories->getTableList($where,'',12)->toArray();
        }else{

            if(isset($data['cate_id']) && !empty($data['cate_id'])){
                $where['inner_temp_theme_id'] = $data['cate_id'];
            }
            if(isset($data['spec'])){
                $where['inner_spec_style'] = $data['spec'];
            }
            if(isset($data['inner_spec_style']) && !empty($data['inner_spec_style'])){
                $where['inner_spec_style'] = $data['inner_spec_style'];
            }
            if(isset($data['search_value']) && !empty($data['search_value'])){
                $where['inner_temp_name'] = $data['search_value'];
            }
            //过滤条件
            $where['inner_temp_check_status'] =  TEMPLATE_STATUS_VERIFYED;

            $list = $this->innerRepositories->getTableList($where,'',12)->toArray();
        }

        //前端请求操作是返回json数据
        if(!empty($is_ajax)) {
            echo json_encode([
                'success' => 'true',
                'data' => [
                    'type' =>$data['type'],
                    'list' => $list['data'],
                    'total' => $list['total'],
                    'limit' =>12,
                    'page'=>!empty($data['page']) ? $data['page'] : 1,
                    'pagesize'=>ceil($list['total']/12)]
            ]);
        }

        $lists = ['list'=> $list['data'],'total' => $list['total'],'limit'=> 12];
        return $lists;
    }

    // setting操作触发请求ajax
    public function tempdata(Request $request)
    {
        $post = $request->all();

        $setData = [
            'page' => !empty($post['page']) ? $post['page'] : 1,
            'spec'  => isset($post['spec']) ?$post['spec']: null,
            'inner_spec_style'  => isset($post['inner_type']) ?$post['inner_type']:'',
            'goods_type'  => $post['goods_type'],
            'type' => $post['type'], //内页or封面
            'cate_id' => $post['cate_id'],
           // 'search_type' => $post['search_type'],
            'search_value' => $post['search_value'],
        ];

        $this->getSettingData($setData ,1);

    }

    // 克隆模板
    public function copy(Request $request)
    {
        $post = $request->all();
        $logic = new Main();
        try{
            \DB::beginTransaction();
            $ret = $logic->TemplateCopy($post['id']);
            if($ret == 'true'){
                \DB::commit();
            }
        } catch (CommonException $e) {
            \DB::rollBack();
            return $this->jsonFailed($e->getMessage());
        }

        return $this->jsonSuccess(['ret'=>'success']);
    }

     // 获取背景图片像素大小
    public function getBackPx(Request $request)
    {
        $post = $request->all();
        $logic = new Attachment();
        $backPx = $logic->getBackPx($post['sizeId']);

        if(!empty($backPx[GOODS_SIZE_TYPE_INNER])){
            $sizeInfo = $backPx[GOODS_SIZE_TYPE_INNER];
            $widthPx = ceil($sizeInfo['size_info_dpi']*round($sizeInfo['size_design_w']/25.4,3));
            $heightPx = ceil($sizeInfo['size_info_dpi']*round($sizeInfo['size_design_h']/25.4,3));
            return $this->jsonSuccess(['width' => $widthPx, 'height' => $heightPx]);
        }else{
            return $this->jsonSuccess(['width' => 0, 'height' => 0]);
        }

    }











}