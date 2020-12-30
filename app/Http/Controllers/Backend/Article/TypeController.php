<?php
namespace App\Http\Controllers\Backend\Article;

use App\Http\Controllers\Backend\BaseController;
use App\Http\Requests\Backend\Article\TypeRequest;
use App\Repositories\SaasArticleTypeRepository;
use App\Repositories\SaasSalesChanelRepository;
use App\Services\Helper;
use Illuminate\Http\Request;

/**
 * 项目说明
 *  文章分类管理：对文章进行归整分类
 * @author: david
 * @version: 1.0
 * @date:2020/05/20
 */
class TypeController extends BaseController
{
    protected $viewPath = 'backend.article.type';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(SaasArticleTypeRepository $Repository,
                                SaasSalesChanelRepository $chanelRepository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->channelRepositories = $chanelRepository;

        //获取所属的渠道
        $channelList = $this->channelRepositories->getList()->toArray();
        $this->channelList = Helper::ListToKV('cha_id','cha_name',$channelList);

    }

    //列表
    public function index()
    {
        $channelList = Helper::getChooseSelectData($this->channelList);
        return view('backend.article.type.index',['channelList'=> $channelList]);
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

            $list = $this->repositories->getTableList($inputs,'art_type_id desc');
            $htmlContents = $this->renderHtml('',['list' =>$list,'channelArr'=>$this->channelList]);
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
                $row = $this->repositories->getById($request->input('id'));

                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form,
                    [   'row' => $row,
                        'channelArr'=>$this->channelList,
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
    public function save(TypeRequest $request)
    {
        $ret = $this->repositories->save($request->all());
        if ($ret['code']) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed($ret['msg']);
        }
    }

}