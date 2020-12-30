<?php
namespace App\Http\Controllers\Backend\Statistics;

use App\Http\Controllers\Backend\BaseController;
use App\Repositories\SaasCategoryRepository;
use App\Repositories\SaasMainTemplatesRepository;
use App\Services\Helper;
use Illuminate\Http\Request;
use App\Exceptions\CommonException;

/**
 * 项目说明
 * 主模板相同名称下的分类数据统计使用量显示
 * @author: david
 * @version: 1.0
 * @date: 2020/7/9
 */
class TempController extends BaseController
{
    protected $viewPath = 'backend.statistics.temp';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(SaasMainTemplatesRepository $Repository,
                                SaasCategoryRepository $CategoryRepository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->cateRepositories = $CategoryRepository;

    }



    //ajax数据
    protected function table(Request $request)
    {
        try {

            $inputs = $request->all();

            //获取模板主题分类数据
            $tempThemeList = $this->cateRepositories->getTList(GOODS_MAIN_CATEGORY_TEMPLATE);
            $tempThemeList = Helper::ListToKV('cate_id','cate_name',$tempThemeList);

            $list = $this->repositories->getSameTempTimes($inputs);

            $htmlContents = $this->renderHtml('',['list' =>$list,'tempThemeList'=>$tempThemeList]);
            //转数组取总数量
            $pagesInfo = $list->toArray();
            $total = $pagesInfo['total'];

            return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
        } catch (CommonException $e) {
            //统一收集错误再做处理
            return $this->jsonFailed($e->getMessage());
        }

    }

    //导出
    public function export(Request $request)
    {
        //临时更改限制文件的大小
        ini_set('memory_limit', '2G');
        try{
            $param = $request->data;

            $data = json_decode($param,true);
            if($data['main_temp_name']==''){
                $data=[];
            }else{
                $data['main_temp_name'] = $data['main_temp_name'];
            }

            $this->repositories->export($data);

        }catch (CommonException $e){
            return $this->jsonFailed($e->getMessage());
        }

    }




}