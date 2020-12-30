<?php
namespace App\Http\Controllers\Backend\Delivery;

use App\Exceptions\CommonException;
use App\Http\Controllers\Backend\BaseController;
use App\Http\Requests\Backend\Delivery\DeliveryRequest;
use App\Repositories\SaasDeliveryRepository;
use Illuminate\Http\Request;

/**
 * 项目说明  CMS系统 物流设置-运送方式
 * 详细说明  CMS系统 物流设置-运送方式，实现运送方式列表，添加，编辑，删除及组件结合
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/03/17
 */
class DeliveryController extends BaseController
{
    protected $viewPath = 'backend.delivery.delivery';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(SaasDeliveryRepository $Repository)
    {
        parent::__construct();
        $this->repositories = $Repository;
    }

    /**
     * ajax获取列表项
     * @参数 Request $request
     * @返回 \Illuminate\Http\JsonResponse
     */
    public function table(Request $request)
    {
        try{
            $inputs = $request->all();
            $inputs['mch_id'] = PUBLIC_CMS_MCH_ID;
            $list = $this->repositories->getTableList($inputs,"delivery_id desc");

            //将结果转换为数组，得到数据库的数据
            $result = $list->toArray();
            $data = $result['data'];
            foreach ($data as $k=>$v){
                $incl_express_name = [];
                //选中快递公司的ID,切割后都数据表中得到对应快递公司的名字
                $incl_express_list = $v['delivery_express_list'];
                $list_name = explode(",", $incl_express_list);
                foreach ($list_name as $key => $val){
                    $del_name = $this->repositories->getDelivery($val);
                    $incl_express_name[$key] = $del_name[0]['express_name'];
                }
                //将得到的快递公司的名字数组转换为字符串并赋给结果数组
                $incl_express_name = implode(",", $incl_express_name);
                $result['data'][$k]['delivery_express_list_name'] = $incl_express_name;
            };

            $htmlContents = $this->renderHtml('',['list' =>$result['data']]);
            $pagesInfo = $list->toArray();
            $total = $pagesInfo['total'];
            return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
        }catch (CommonException $e){
            $this->jsonFailed($e->getMessage());
        }
    }


    /**
     * 通用表单展示
     * @param Request $request
     * @return mixed
     */
    public function form(Request $request)
    {
        try {
            if($request->ajax())
            {
                $row = $this->repositories->getById($request->input('id'));
                $delivery = $this->repositories->getDelivery();
                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row,'delivery'=>json_decode($delivery,true)]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

    }

   //添加/编辑操作
    public function save(DeliveryRequest $request)
    {
        $post = $request->all();
        $data = [
            'delivery_id'=>$post['delivery_id'],
            'delivery_name'=> $post['delivery_name'],
            'delivery_show_name'=> $post['delivery_show_name'],
            'delivery_express_list'=> $post['delivery_express_list'],
            'delivery_desc'=> $post['delivery_desc'],
            'delivery_is_cash'=> $post['delivery_is_cash'],
            'delivery_status'=>$post['delivery_status'],
        ];
        $ret = $this->repositories->save($data);
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }

}
