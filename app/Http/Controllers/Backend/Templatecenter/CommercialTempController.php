<?php
namespace App\Http\Controllers\Backend\Templatecenter;

use App\Http\Controllers\Backend\BaseController;
use App\Http\Requests\Backend\Templatecenter\RelationSizeRequest;
use App\Models\TempSizeRelation;
use App\Repositories\CommercialTempRepository;
use App\Services\Helper;
use Illuminate\Http\Request;
use App\Repositories\SaasProductsSizeRepository;
use App\Exceptions\CommonException;

/**
 * 项目说明
 * 详细说明
 * @author: dai
 * @version: 1.0
 * @date: 2020/4/15
 */
class CommercialTempController extends BaseController
{
    protected $viewPath = 'backend.templatecenter.commercialtemp';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $tpurl = '';    //当前定义图片接口域名

    public function __construct(CommercialTempRepository $Repository,SaasProductsSizeRepository $SizeRepository)

    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->sizeRepositories = $SizeRepository;

    }


    public function table(Request $request)
    {
        $inputs = $request->all();
        $limit = isset($inputs['limit']) ? $inputs['limit']:config('common.page_limit');  //这个10取配置里的
        $curPage = isset($inputs['page']) ? $inputs['page']: 1;
        //获取商印规格
        $sizeList = $this->sizeRepositories->getCommercialSize();
        $sizeList = Helper::ListToKV('size_id','size_name',$sizeList);

        $list = $this->repositories->getTableList();

        $offset = ($curPage-1)*$limit;
        if($limit>count($list)-$offset){
            $limit = count($list);
        };

        $total = count($list);
        $list = array_slice($list,$offset,$limit);

        $htmlContents = $this->renderHtml('',['list' =>$list,'sizeList'=>$sizeList]);
        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
    }

    protected function form(Request $request)
    {
        try {

            $post = $request->all();
            if(empty($post)){
                Helper::EasyThrowException('10010', __FILE__.__LINE__);
            }
            $tid = $post['id'];
            $cid = $post['cid'];

            $relationSize = TempSizeRelation::where(['tid'=>$post['id']])->first();
            if(empty($relationSize)){
                $sizeId = -1;
            }else{
                $sizeId = $relationSize['size_id'];
            }
            //获取商印规格
            $sizeList = $this->sizeRepositories->getCommercialSize();
            if(empty($sizeList)){
                $specList = ['空'];
            }else{
                $specList = Helper::ListToKV('size_id','size_name',$sizeList);
            }

            $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form,['specList'=> $specList,'tid'=>$tid,'sizeid'=>$sizeId,'cid'=>$cid]);
            return $this->jsonSuccess(['html' => $htmlContents]);


            return view($this->viewPath.'.'.$this->form,['specList'=> $specList,'tid'=>$tid,]);
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

    }




    //添加/编辑操作
    public function save(RelationSizeRequest $request)
    {
        $params = $request->all();

        if(empty($params['size_id'])){
            return $this->jsonFailed('请选择需要绑定的规格');
        }
        $ret = $this->repositories->save($params);
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }











}