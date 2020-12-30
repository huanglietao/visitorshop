<?php
namespace App\Http\Controllers\Backend\Advertisement;

use App\Services\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Backend\BaseController;
use App\Http\Requests\Backend\Advertisement\AdPositionRequest;
use App\Repositories\SaasAdPositionRepository;
use App\Repositories\SaasSalesChanelRepository;

/**
 * 项目说明
 * 广告位置功能定义广告投放在所属渠道的具体哪里位置
 * @author: david
 * @version: 1.0
 * @date: 2020/5/15
 */
class AdPositionController extends BaseController
{
    protected $viewPath = 'backend.advertisement.adposition';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(SaasAdPositionRepository $Repository,SaasSalesChanelRepository $chanelRepository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->channelRepositories = $chanelRepository;

        //获取所属的渠道
        $channelList = $this->channelRepositories->getList()->toArray();
        $this->channelList = Helper::ListToKV('cha_id','cha_name',$channelList);
        $this->channelLists = ['0'=>'全部']+ $this->channelList;
        if (session('channel')){
            $this->firstChannel = session('channel');
        }else{
            $this->firstChannel = key($this->channelLists);
        }

    }

    //列表
    public function index()
    {
        return view('backend.advertisement.adposition.index',['channelList'=> $this->channelLists,'firstChannel'=>$this->firstChannel]);
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
            //获取分类标识
            if (session('channel')&& !isset($inputs['channel_id'])){
                //情况1：页面刷新时
                $inputs['channel_id'] = session('channel');
            }else{
                $inputs['channel_id'] = $inputs['channel_id']??$this->firstChannel;
            }

            session(['channel' => $inputs['channel_id']]);
            //加入默认为0的查询条件改变条件
            if(isset($inputs['channel_id'])&&$inputs['channel_id']==ZERO){
                $inputs['channel_id']=null;
            }

            $list = $this->repositories->getTableList($inputs,'ad_pos_id desc');
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
                $channel = session('channel');
                if(empty($channel)){
                    $channel = 'all';
                }else{
                    $channel = session('channel');
                }

                $row = $this->repositories->getById($request->input('id'));

                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row,'channel'=>$channel,'channelArr'=>$this->channelList]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

    }
   //添加/编辑操作
    public function save(AdPositionRequest $request)
    {
        $post = $request->all();
        if(isset($post['_token'])){
            unset($post['_token']);
        }
        if($post['channel']=='all'){
            unset($post['channel']);
        }else{
            $post['channel_id'] = $post['channel'];
            unset($post['channel']);
        }
        $ret = $this->repositories->save($post);

        if ($ret['code']) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed($ret['msg']);
        }
    }

}