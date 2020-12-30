<?php
namespace App\Http\Controllers\Backend\Templatecenter;

use App\Http\Controllers\Backend\BaseController;
use App\Repositories\SaasInnerTemplatesPagesRepository;
use App\Repositories\SaasInnerTemplatesRepository;
use App\Repositories\SaasCategoryRepository;
use App\Services\Template\Main;
use Illuminate\Http\Request;
use App\Repositories\SaasSizeInfoRepository;
use App\Http\Requests\Backend\Templatecenter\InnerChildRequest;

/**
 * 项目说明
 * 内页模板子页控制器
 * @author: dai
 * @version: 1.0
 * @date: 2020/4/25
 */
class InnerChildController extends BaseController
{
    protected $viewPath = 'backend.templatecenter.innerchild';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(SaasInnerTemplatesPagesRepository $Repository,
                                SaasInnerTemplatesRepository $InnerRepository,
                                 SaasCategoryRepository $CateRepository,
                                SaasSizeInfoRepository $sizeInRepository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->innerRepositories = $InnerRepository;
        $this->cateRepositories = $CateRepository;
         $this->sizeInfoRepositories = $sizeInRepository;
    }

    // 列表
    public function childindex(Request $request)
    {
        $post = $request->all('id');
        session(['tid' => $post['id']]);

        return view('backend.templatecenter.innerchild.childindex');
    }
    protected function table(Request $request)
    {
        $inputs = $request->all();

        $inputs['inner_page_tid'] = session('tid');

        $list = $this->repositories->getTableList($inputs)->toArray();
        $total = $list['total'];

        //根据搜索条件不同处理数据返回
        if(!empty($list['data'])){
            $list = $this->getMakeInnertPagesTemp($list['data']);
        }else{
            $list = $list['data'];
        }

        $htmlContents = $this->renderHtml('',['list' =>$list,'pageType'=>config('goods.page_type'),'isCross'=>config('goods.is_cross')]);
        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
    }

    protected function form(Request $request)
    {
        try {
            if($request->ajax())
            {
                $row = $this->repositories->getByIdFromCache($request->input('id'));

                $tid = session('tid');
                $inner = $this->innerRepositories->getInnerTemp($tid);

                $specInfo = $this->sizeInfoRepositories->getSizeInfoDetail($inner->specifications_id,GOODS_SIZE_TYPE_INNER);
                $goodsCate = $this->cateRepositories->getCategoryFlag($inner->goods_type_id);

                $logicMain = new Main();
                $yearList =$logicMain->getCalendarYear('2018');

                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, [
                    'row' => $row,
                    'specInfo' => $specInfo,
                    'yearList' => $yearList,
                    'goodsCate' =>$goodsCate,
                    'tgl' =>GOODS_DIY_CATEGORY_CALENDAR,
                    'isTurn'=>config('goods.is_turn'),
                    'yn'=>config('goods.y_n'),
                    'specId' =>$inner->specifications_id,
                    'tid'=>$tid,
                    'isCross'=>config('goods.is_cross'),
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
    public function save(InnerChildRequest $request)
    {
        $params = $request->all();
        if(empty($params['inner_page_sort'])){
            $params['inner_page_sort'] = ZERO;
        }
        $ret = $this->repositories->save($params);
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('无任何修改无需提交');
        }
    }

    //处理列表数据
    public function getMakeInnertPagesTemp($data)
    {
        foreach ($data as $k=>$v){
            $data[$k]['size_is_cross'] = $v['inner_size_info'][0]['size_is_cross'];
            $data[$k]['size_design_w'] = $v['inner_size_info'][0]['size_design_w'];
            $data[$k]['size_design_h'] = $v['inner_size_info'][0]['size_design_h'];
            $data[$k]['size_location_top'] = $v['inner_size_info'][0]['size_location_top'];
            $data[$k]['size_location_left'] = $v['inner_size_info'][0]['size_location_left'];
            $data[$k]['size_location_bottom'] = $v['inner_size_info'][0]['size_location_bottom'];
            $data[$k]['size_location_right'] = $v['inner_size_info'][0]['size_location_right'];
        }
        return $data;
    }

    //克隆复制view
    public function copy(Request $request)
    {
        $post = $request->all('pageid');

        $htmlContents = $this->renderHtml($this->viewPath.'.copy', [
            'pageid' => $post['pageid'],
        ]);
        return $this->jsonSuccess(['html' => $htmlContents]);
    }

    /**
     * 克隆操作
     */
    public function docopy(Request $request)
    {
        $post = $request->all();

        if(empty($post['pageid']) || empty($post['clone_num'])) {
            return $this->jsonFailed('请确认参数完整再提交');
        }

        //获取排序最大的那个
        $pagesRet = $this->repositories->getInnerPagesList($post['pageid']);

        //获取商品类型
        $inner = $this->innerRepositories->getInnerTemp($pagesRet['inner_page_tid']);
        $goodsCate = $this->cateRepositories->getCategoryFlag($inner->goods_type_id);

        //复制逻辑(台历特殊、其他暂时都以照片书逻辑来处理)
        $logic = new Main();
        try {
            if($goodsCate->cate_flag == GOODS_DIY_CATEGORY_CALENDAR) {
                //台挂历子页复制
                $logic->copyCalendar($pagesRet, $post['clone_num']);
            } else {
                //照片书子页复制
                $logic->copyPhotoBook($pagesRet, $post['clone_num']);
            }

        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

        return $this->jsonSuccess([]);
    }






}