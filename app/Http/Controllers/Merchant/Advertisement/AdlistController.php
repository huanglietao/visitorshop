<?php
namespace App\Http\Controllers\Merchant\Advertisement;

use App\Http\Controllers\Merchant\BaseController;
use App\Http\Requests\Merchant\Advertisement\AdListRequest;
use App\Repositories\SaasAdvertisementRepository;
use App\Repositories\SaasSalesChanelRepository;
use App\Repositories\SaasAdPositionRepository;
use App\Services\Helper;
use Illuminate\Http\Request;

/**
 * 项目说明
 * 详细说明
 * @author:
 * @version: 1.0
 * @date:
 */
class AdlistController extends BaseController
{
    protected $viewPath = 'merchant.advertisement.adlist';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(SaasAdvertisementRepository $Repository,
                                SaasSalesChanelRepository $chanelRepository,
                                SaasAdPositionRepository $adPosRepository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->channelRepositories = $chanelRepository;
        $this->adPosRepositories = $adPosRepository;

        //获取商户id
        $merchantID = empty(session('admin')) == false ? session('admin')['mch_id'] : ' ';
        if ($merchantID){
            $this->mid = $merchantID;
        }else{
            return $this->jsonFailed("请重新登录");
        }

        $this->adtype = config('advertise.ad_type');
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
        $adType = Helper::getChooseSelectData($this->adtype);
        return view('merchant.advertisement.adlist.index',['adType'=> $adType,'channelList'=> $this->channelLists,'firstChannel'=>$this->firstChannel]);
    }

    //数据加载
    protected function table(Request $request)
    {
        try {

            $inputs = $request->all();
            if ($this->mid){
                //添加商户判断
                $inputs['mch_id'] = $this->mid;
            }else{
                return $this->jsonFailed("请重新登录");
            }

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
            //dd($inputs);
            //广告位置数据
            $adPosArr = $this->adPosRepositories->getAdPositionList([],$this->mid);
            $adPosList = Helper::ListToKV('ad_pos_id','ad_position',$adPosArr);

            $list = $this->repositories->getTableList($inputs,'ad_id desc')->toArray();
            $total = $list['total'];
            //根据搜索条件不同处理数据返回
            if(!empty($list['data'])){
                $list = $this->repositories->getMakeAdList($list['data']);
            }else{
                $list = $list['data'];
            }
            $htmlContents = $this->renderHtml('',
                [   'list' =>$list,
                    'channelArr'=> $this->channelList,
                    'adType'    => config('advertise.ad_type'),
                    'posList'   => $adPosList,
                ]);


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
                //渠道数据
                $channelArr = Helper::getChooseSelectData($this->channelList);
                //广告位置数据
                if($row['ad_id']){
                    $adPosArr = $this->adPosRepositories->getAdPositionList(['channel_id'=>$row['channel_id']],$this->mid);
                    $adPosList = Helper::ListToKV('ad_pos_id','ad_position',$adPosArr);
                }else{
                    if($channel == 'all'){
                        $adPosList = [];
                    }else{
                        $adPosArr = $this->adPosRepositories->getAdPositionList(['channel_id'=>$channel],$this->mid);
                        $adPosList = Helper::ListToKV('ad_pos_id','ad_position',$adPosArr);
                    }
                }

                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form,
                    [   'row' => $row,
                        'channel'=>$channel,
                        'channelArr'=>$channelArr,
                        'adPosList'=>$adPosList
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
    public function save(AdListRequest $request)
    {
        $post = $request->all();
        if (empty($post['ad_images']))
        {
            return $this->jsonFailed('请上传广告图');
        }
        if($post['ad_position']==ONE && strpos($post['ad_images'],',')){
            return $this->jsonFailed('专属布局只能传单张图片');
        }
        if($post['ad_type']==ONE && strpos($post['ad_images'],',')){
            return $this->jsonFailed('单图只能传单张图片');
        }
        if(isset($post['_token'])){
            unset($post['_token']);
        }
        //数据类型转换或处理
        if(empty($post['ad_sort'])){
            $post['ad_sort'] = ZERO;
        }
        if(!strpos($post['ad_images'],',')){
            $post['ad_type'] = ONE;
        }
        if($post['channel']=='all'){
            unset($post['channel']);
        }else{
            $post['channel_id'] = $post['channel'];
            unset($post['channel']);
        }
        //识别中文逗号报错
        $is_dou = strpos($post['ad_url'], "，");
        if ($is_dou)
        {
            return $this->jsonFailed('不能包含中文逗号');
        }
        if($post['ad_position']!=ONE){
            unset($post['display_type']);
        }
        $post['mch_id'] = $this->mid;

        $ret = $this->repositories->save($post);
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('操作失败');
        }
    }

    //获取广告位置
    public function getAdPosList(Request $request)
    {
        $post = $request->all();
        if($post){
            $adPosArr = $this->adPosRepositories->getAdPositionList(['channel_id'=>$post['id']],$this->mid);
        }
        $posArray = [];
        if(!empty($adPosArr)){
            $posArray = Helper::ListToKV('ad_pos_id','ad_position',$adPosArr);
        }

        return $this->jsonSuccess($posArray);
    }

    //获取广告位置详情
    public function getPositionInfo(Request $request)
    {
        $post = $request->all();
        if($post){
            $adPosInfo = $this->adPosRepositories->getAdPositionInfo($post['id']);
        }

        return $this->jsonSuccess($adPosInfo);
    }

    //查看示意图
    public function posthumb(Request $request)
    {
        $post = $request->all();
        if($post['id']==ZERO){
            $posSrc = '';
        }else{
            $adPosInfo = $this->adPosRepositories->getAdPositionInfo($post['id']);
            $posSrc = $adPosInfo['ad_thumb'];
        }
        return view('backend.advertisement.adlist.posthumb',['imgsrc'=>$posSrc]);
        $htmlContents = $this->renderHtml($this->viewPath.'.posthumb',['imgsrc'=>$posSrc]);
        return $this->jsonSuccess(['html' => $htmlContents]);
    }









}