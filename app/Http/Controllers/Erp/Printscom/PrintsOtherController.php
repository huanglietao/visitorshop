<?php
/**
 * Created by sass.
 * Author: cjx
 * Date: 2020/03/13
 * Time: 9:48
 */
namespace App\Http\Controllers\Erp\Printscom;

use App\Http\Controllers\Erp\BaseController;
use App\Services\Outer\Erp\Api;
use Illuminate\Http\Request;


class PrintsOtherController extends BaseController
{
    protected $viewPath = 'erp.print';  //当前控制器所的view所在的目录
    protected $modules = 'sys';//当前控制器所属模块


    public function index()
    {
        return view('erp.printscom.print_other');
    }

    public function writeBack(Request $request)
    {
        $data = $request->post("data");

        $requestApi = new Api();

        $post_data = [
            'express_num'   => $data['express_num'],
            'express_type'  => $data['express_type']
        ];

        if($data['order_type'] == 0){
            //贸易订单编号
            $post_data['trade_order_name'] = $data['trade_order_name'];
        }else{
            //贸易出货单编号
            $post_data['trade_stock_move_name'] = $data['trade_stock_move_name'];
        }

        $requestUrl = config("erp.interface_url").config("erp.write_back_delivery");

        $res = $requestApi->request($requestUrl,$post_data);
        if(empty($res)) {
            echo json_encode(['success' => 'false','msg' =>"请求接口错误！请联系管理员!" ]);exit;
        }

        if($res['code'] ==  1) {
            echo json_encode(['success' => 'true']);
        } else {
            echo json_encode(['success' => 'false','msg' =>$res['message'] ]);
        }
        exit;
    }

}
