<?php
namespace App\Http\Controllers\Backend\Templatecenter;

use App\Http\Controllers\Backend\BaseController;
use App\Repositories\SaasCategoryRepository;
use App\Repositories\SaasMainTemplatesPagesRepository;
use App\Repositories\SaasMainTemplatesRepository;
use App\Services\Template\Main;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\Templatecenter\MainChildRequest;
use App\Repositories\SaasSizeInfoRepository;

/**
 * 项目说明
 * 主模板子页控制器
 * @author: dai
 * @version: 1.0
 * @date: 2020/4/21
 */
class MainChildController extends BaseController
{
    protected $viewPath = 'backend.templatecenter.mainchild';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(SaasMainTemplatesPagesRepository $Repository,
                                SaasMainTemplatesRepository $MainRepository,
                                 SaasCategoryRepository $CateRepository,
                                SaasSizeInfoRepository $sizeInRepository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->mainRepositories = $MainRepository;
        $this->cateRepositories = $CateRepository;
        $this->sizeInfoRepositories = $sizeInRepository;
    }

    // 列表
    public function childindex(Request $request)
    {
        $post = $request->all('id');
        session(['tid' => $post['id']]);

        return view('backend.templatecenter.mainchild.childindex');
    }
    protected function table(Request $request)
    {
        $inputs = $request->all();
        $inputs['main_temp_page_tid'] = session('tid');

        $list = $this->repositories->getTableList($inputs)->toArray();
        $total = $list['total'];

        //获取规格id
        if(empty($list['data'])){
            $mainTempInfo = $this->mainRepositories->getById($inputs['main_temp_page_tid']);
            $specId = $mainTempInfo['specifications_id'];
        }else{
            $specId = $list['data'][0]['specifications_id'];
        }
        //组装table所需的数据格式
        $list = $this->repositories->getSpecTempList($list['data'],$specId);

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
                $main = $this->mainRepositories->getById($tid);//getMainTemp($tid);
                $pageType = $this->repositories->getSpecTypeList($main->specifications_id);

                $type = !empty($row['main_temp_page_type']) ? $row['main_temp_page_type'] : GOODS_SIZE_TYPE_INNER;
                if($type!=GOODS_SIZE_TYPE_INNER){ //封面时需要传数组
                    $type = [$row['main_temp_page_type']];
                }

                $specInfo = $this->sizeInfoRepositories->getSizeInfoDetail($main->specifications_id,$type);
                $goodsCate = $this->cateRepositories->getCategoryFlag($main->goods_type_id);

                $logicMain = new Main();
                $yearList =$logicMain->getCalendarYear('2018');

                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, [
                    'row' => $row,
                    'pageType'=>$pageType,
                    'configPage'=>config('goods.page_type'),
                    'specInfo' => $specInfo,
                    'yearList' => $yearList,
                    'goodsCate' =>$goodsCate,
                    'tgl' =>GOODS_DIY_CATEGORY_CALENDAR,
                    'isTurn'=>config('goods.is_turn'),
                    'yn'=>config('goods.y_n'),
                    'specId' =>$main->specifications_id,
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
    public function save(MainChildRequest $request)
    {
        $params = $request->all();
        if(empty($params['main_temp_page_sort'])){
            $params['main_temp_page_sort'] = ZERO;
        }
        $ret = $this->repositories->save($params);
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('无任何修改无需提交');
        }
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
        $pagesRet = $this->repositories->getMainPagesList($post['pageid']);
        //获取主模板
        $main = $this->mainRepositories->getById($pagesRet['main_temp_page_tid']);//getMainTemp($pagesRet['main_temp_page_tid']);
        //获取商品类型标识
        $goodsCate = $this->cateRepositories->getCategoryFlag($main->goods_type_id);

        //复制逻辑(台历特殊、其他暂时都以照片书逻辑来处理)
        $logic = new Main();
        try {
            if($goodsCate->cate_flag == GOODS_DIY_CATEGORY_CALENDAR) {
                //台挂历子页复制
                $logic->copyMainCalendar($pagesRet, $post['clone_num']);
            } else {
                //照片书子页复制
                $logic->copyMainPhotoBook($pagesRet, $post['clone_num']);
            }

        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

        return $this->jsonSuccess([]);
    }








}