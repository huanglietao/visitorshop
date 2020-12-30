<?php
namespace App\Http\Controllers\Backend;

use App\Exceptions\CommonException;
use App\Http\Controllers\Backend\BaseController;
use App\Http\Requests\Backend\AreasetingRequest;
use App\Repositories\SaasAreasRepository;
use App\Services\Common\Log\LogInterface;
use fast\Tree;
use Illuminate\Http\Request;

/**
 * 项目说明
 *
 * 地址库管理，可查看全国3级别的地址，如果地址库没有的可添加新地区
 * @author: daiyd
 * @version: 1.0
 * @date:2020/3/3
 */
class AreasetingController extends BaseController
{
    protected $viewPath = 'backend.areaseting';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(SaasAreasRepository $Repository)
    {
        parent::__construct();
        $this->repositories = $Repository;
    }

   //添加/编辑操作
    public function save(AreasetingRequest $request)
    {
        $post = $request->all();

        if($post['level']==AREA_LEVEL_PROVINCE && $post['province']!=ZERO){
            return $this->jsonFailed('省级无所属上级');
        }
        if($post['level']==AREA_LEVEL_CITY){
            if( $post['province']==ZERO || $post['city']!=-1){
                return $this->jsonFailed('市级只需选择所属省级');
            }
        }
        if($post['level']==AREA_LEVEL_DISTRICT){
            if($post['province']==ZERO || $post['city']==-1){
                return $this->jsonFailed('请选择所属省级和市级');
            }
        }

        $data=$this->repositories->logicData($post);

        $ret = $this->repositories->save($data);
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }


    protected function table(Request $request)
    {
        $inputs = $request->all();

        $list = $this->repositories->getTableList($inputs,'pinyin asc');
        $areaNameList = $this->repositories->getPidList();

        $htmlContents = $this->renderHtml('',['list' =>$list,'pidList'=>$areaNameList]);

        $pagesInfo = $list->toArray();

        $total = $pagesInfo['total'];

        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
    }

    //获取地区的pid
    public function getAreasPid(Request $request)
    {
        $id = $request->post('id');
        $pid = $this->repositories->getAreaIdList($id);
        return $pid;

    }


    protected function form(Request $request)
    {
        try {
            if($request->ajax())
            {
                $row = $this->repositories->getById($request->input('id'));

                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

    }

}