<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Backend\BaseController;
use App\Http\Requests\Backend\ExceptionRequest;
use App\Repositories\BaseExceptionRepository;
use Illuminate\Http\Request;

/**
 * 项目说明
 * 详细说明
 * @author:
 * @version: 1.0
 * @date:
 */
class ExceptionController extends BaseController
{
    protected $viewPath = 'backend.exception';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(BaseExceptionRepository $Repository)
    {
        parent::__construct();
        $this->repositories = $Repository;
    }

   //添加/编辑操作
    public function save(ExceptionRequest $request)
    {
        $ret = $this->repositories->save($request->all());
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }



//ajax方式获取列表
    public function table(Request $request)
    {
        $inputs = $request->all();
        $search = isset($inputs['exception']) ? $inputs['exception']:'';

        $limit = intval($inputs['limit']??config('common.page_limit'));
        $page = $inputs['page']??1;
        $offset = intval(($page-1)*$limit);

        $list = $this->repositories->getTableList($search);

        $total = count($list);
        $list = array_slice($list,$offset,$limit);

        $htmlContents = $this->renderHtml('backend.exception._table',['list' =>$list]);
        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);

    }

   /* public function getExceptionDate()
    {

        $data = [
            'partner_code'=>$partner_code,
            'start_date'=>$start_date,
            'end_date'=>$end_date
        ];
        $res_arr = new Api();
        $result = [];
        $result_arr  = $res_arr->request(config('erp.interface_url').config('erp.sale_order'),$data);
        if($result_arr['code'] == 1){
            $result = $result_arr['data'];
        }
        return $result;
    }*/

}