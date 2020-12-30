<?php
namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Agent\BaseController;
use App\Repositories\OmsAgentDeployRepository;
use App\Repositories\SaasAdvertisementRepository;
use App\Repositories\SaasArticleRepository;
use App\Repositories\SaasCategoryRepository;
use App\Repositories\SaasMainTemplatesRepository;
use App\Repositories\SaasProjectsRepository;
use Illuminate\Http\Request;
use App\Repositories\SaasSalesChanelRepository;


/**
 * 功能简介
 *
 * 文章功能，列表 详情
 * @author: david
 * @version: 1.0
 * @date: 2020/5/21
 */
class ArticlesController extends BaseController
{
    protected $viewPath = 'agent.articles';    //当前控制器所的view所在的目录
    protected $modules = 'sys';             //当前控制器所属模块
    protected $sysId = 'agent';             //当前控制器所属模块
    protected $merchantID = "";
    protected $noNeedRight = ['*'];
    protected $deployInfo;                  //站点配置信息
    protected $wtArtList;
    protected $artType;


    public function __construct(SaasMainTemplatesRepository $mainTemplatesRepository,
                                OmsAgentDeployRepository $agentDeployRepository,
                                SaasProjectsRepository $projectsRepository,
                                SaasAdvertisementRepository $adListRepository,
                               // SaasSalesChanelRepository $chanelRepository,
                                SaasArticleRepository $articleRepository,
                                SaasCategoryRepository $CategoryRepository)
    {
        parent::__construct();
        $this->mainTemplatesRepository = $mainTemplatesRepository;
        $this->merchantID = empty(session('admin')) == false ? session('admin')['mch_id'] : $this->agent_mid;
        $this->deployInfo = $agentDeployRepository->getDeployInfo($this->merchantID);
        $this->projectRepository = $projectsRepository;
        $this->adListRepositories = $adListRepository;
        //$this->channelRepositories = $chanelRepository;
        $this->articleRepositories = $articleRepository;
        $this->cateRepositories = $CategoryRepository;

        //$channel = $this->channelRepositories->getAgentChannleId();//获取渠道id
        //获取文章分类
       // $this->artType =  $this->articleTypeRepositories->getArtTpyeList($channel);
        $artCate =  $this->cateRepositories->getRow(['cate_parent_id'=>ZERO,'cate_uid'=>'article','cate_flag'=>GOODS_MAIN_CATEGORY_HELP]);
        $this->artType = $this->cateRepositories->getList(['cate_parent_id'=>$artCate['cate_id']]);

        //获取最新发布的文章
        $this->wtArtList = $this->articleRepositories->getRows(['art_sign'=>GOODS_MAIN_CATEGORY_HELP,'is_open'=>ONE,'mch_id'=>[0,$this->merchantID]],'created_at','desc',4);
    }

    public function index()
    {
        //分类组合数据
        $typeArticles = $this->articleRepositories->getTypeArticleList($this->merchantID);

        return view($this->viewPath.'.index',
            ['deployInfo'=>$this->deployInfo,
            'wtArtList'=>$this->wtArtList,
            'artType'=>$this->artType,
            'typeArticles'=>$typeArticles,
                'num'=>0,
            ]);

    }

    //详情
    public function detail(Request $request)
    {
        $post= $request->all();
        $artInfo = $this->articleRepositories->getById($post['id']);
        $frontAfter = $this->articleRepositories->getArticleInfo($post['id'],$this->merchantID);

        //分类组合数据
        $typeArticles = $this->articleRepositories->getTypeArticleList($this->merchantID);

        return view($this->viewPath.'.detail',
            ['deployInfo'=>$this->deployInfo,
            'wtArtList'=>$this->wtArtList,
                'artInfo'=>$artInfo,
                'artPage'=>$frontAfter,
                'typeArticles'=>$typeArticles,
                'artType'=>$this->artType]);
    }





}