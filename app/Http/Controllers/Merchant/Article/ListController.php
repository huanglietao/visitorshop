<?php
namespace App\Http\Controllers\Merchant\Article;

use App\Http\Controllers\Merchant\BaseController;
use App\Http\Requests\Merchant\Article\ListRequest;
use App\Repositories\SaasArticleRepository;
use App\Repositories\SaasCategoryRepository;
use App\Services\Helper;
use Illuminate\Http\Request;

/**
 * 项目说明
 * 文章列表功能控制在其所属的渠道平台发布显示
 * @author: david
 * @version: 1.0
 * @date: 2020
 */
class ListController extends BaseController
{
    protected $viewPath = 'merchant.article.list';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(SaasArticleRepository $Repository,
                                SaasCategoryRepository $CategoryRepository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->cateRepositories = $CategoryRepository;
        //$this->channelRepositories = $chanelRepository;

        //获取商户id
        $merchantID = empty(session('admin')) == false ? session('admin')['mch_id'] : ' ';
        if ($merchantID){
            $this->mid = $merchantID;
        }else{
            return $this->jsonFailed("请重新登录");
        }

        //获取文章分类
        $this->artTypeList = $this->cateRepositories->getTypeArr('article');
     /*   //获取所属的渠道
        $channelList = $this->channelRepositories->getList()->toArray();
        $this->channelList = Helper::ListToKV('cha_id','cha_name',$channelList);*/
    }

    //列表
    public function index()
    {
        $artTypeList = Helper::getChooseSelectData($this->artTypeList);
        return view('merchant.article.list.index',['artTypeList'=>$artTypeList]);
    }

    //ajax数据
    protected function table(Request $request)
    {
        try {

            $inputs = $request->all();
            //加mid
            $inputs['mch_id'] = $this->mid;

            $list = $this->repositories->getTableList($inputs,'art_id desc');
            $htmlContents = $this->renderHtml('',['list' =>$list, 'artTypeList'=>$this->artTypeList]);
            //转数组取总数量
            $pagesInfo = $list->toArray();
            $total = $pagesInfo['total'];

            return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
        } catch (CommonException $e) {
            //统一收集错误再做处理
            return $this->jsonFailed($e->getMessage());
        }

    }

    //表单
    protected function form(Request $request)
    {
        try {
            if($request->ajax())
            {
                $row = $this->repositories->getByIdFromCache($request->input('id'));

                /*if($row){ //为转化渠道名称
                    $channel = $this->channelRepositories->getById($row['channel_id'])->toArray();
                    $row['cha_name'] = $channel['cha_name'];
                }*/

                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form,
                    [   'row' => $row,
                       // 'channelArr' => $this->channelList,
                        'artTypeList'=> $this->artTypeList,
                        'mid'        => $this->mid,
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
    public function save(ListRequest $request)
    {
        $post = $request->all();
        if(isset($post['_token'])){
            unset($post['_token']);
        }
        if (!isset($post['art_content'])){
            return $this->jsonFailed('文章详情内容不能为空');
        }

        $ret = $this->repositories->save($post);
        if ($ret['code']) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed($ret['msg']);
        }
    }

    public function getArticleType(Request $request)
    {
        $post = $request->all();
        $artType = $this->cateRepositories->getById($post['id'])->toArray();
        if(empty($artType['cate_flag'])){
            $artType = $this->cateRepositories->getById($artType['cate_parent_id'])->toArray();
        }
        //$artType = $this->artTypeRepositories->getById($post['id'])->toArray();
        return $this->jsonSuccess($artType);
    }





}