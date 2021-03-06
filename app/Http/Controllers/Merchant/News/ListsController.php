<?php
namespace App\Http\Controllers\Merchant\News;

use App\Http\Controllers\Merchant\BaseController;
use App\Repositories\OmsNewsRepository;
use App\Repositories\SaasArticleRepository;
use App\Repositories\SaasCategoryRepository;
use App\Services\Helper;
use Illuminate\Http\Request;

/**
 * 项目说明
 * 文章列表功能控制在其所属的渠道平台发布显示
 * @author: david
 * @version: 1.0
 * @date: 2020/5/26
 */
class ListsController extends BaseController
{
    protected $viewPath = 'merchant.news';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(SaasArticleRepository $Repository,
                                SaasCategoryRepository $CategoryRepository,
                                OmsNewsRepository $omsNewsRepository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->cateRepositories = $CategoryRepository;
        $this->omsNewsRepositories = $omsNewsRepository;

        //获取商户id
        $merchantID = empty(session('admin')) == false ? session('admin')['mch_id'] : ' ';
        if ($merchantID){
            $this->mid = $merchantID;
        }else{
            return $this->jsonFailed("请重新登录");
        }

        //获取文章分类
        $this->artTypeList = $this->cateRepositories->getTypeArr('article');
    }

    //ajax数据
    protected function table(Request $request)
    {
        try {
            $inputs = $request->all();
            //加入默认为0的查询条件改变条件
            if(isset($inputs['art_sign'])&&$inputs['art_sign']=='all'){
                $inputs['art_sign']=null;
            }
            //带入商家id查询用于查询商家已读文章
            $inputs['mch_id'] = $this->mid;
            $list = $this->repositories->getTableNewsList($inputs,'art_id desc')->toArray();
            $total = $list['total'];

            $htmlContents = $this->renderHtml('',['list' =>$list['data'], 'artTypeList'=>$this->artTypeList]);
            return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
        } catch (CommonException $e) {
            //统一收集错误再做处理
            return $this->jsonFailed($e->getMessage());
        }

    }


    //消息文章详情（并把已读保存进商户对应的消息表）
    public function detail(Request $request)
    {
        $post= $request->all();
        $content= $this->repositories->getById($post['id']);

        //对比每个商户的公告文章是否存在，不存在就保存，避免数据重复
        $this->omsNewsRepositories->getSaveNews($post['id'],$this->mid);

        return view("merchant.news.detail",['content'=>$content]);

    }





}