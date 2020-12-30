<?php
namespace App\Http\Controllers\Erp\Order;

use App\Http\Controllers\Erp\BaseController;
use App\Http\Requests\Erp\Order\ImportRequest;
use App\Repositories\ErpTradeOrderRepository;
use App\Services\Outer\Erp\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
ini_set("max_execution_time", 0);
/**
 * 项目说明
 * 详细说明
 * @author:
 * @version: 1.0
 * @date:
 */
class ImportController extends BaseController
{
    protected $viewPath = 'erp.order.import';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(ErpTradeOrderRepository $Repository)
    {
        parent::__construct();
        $this->repositories = $Repository;
    }

    public function index()
    {
        if (session("capital")) {
            return $this->view('index');
        }else{
            //未登录，返回登陆页面
            return redirect('login/index');
        }
//        return $this->view('index');
    }


    public function tips(Request $request)
    {
        $post = $request->post();
        $filename = $post['filename'];
        $total = $post['total'];
        $num = $post['num'];
        if($num==1){
            $data = [
                "num"=>$num,
                "filename" => $filename
            ];
        }
        if($num==2){
            $data = [
                "num"=>$num,
                "filename" => $filename,
                "total"=>$total
            ];
        }
        $content = $this->renderHtml("erp.order.import.tips",['data' =>$data]);
        return response()->json(['status' => 200, 'html' => $content]);

    }


    public function ExcelImport(Request $request)
    {
        $userinfo = session("capital");
        $partner_code = $userinfo['data']['partner_code'];

        if (is_uploaded_file($_FILES['file']['tmp_name'])) {
            // 有Xls和Xlsx格式两种
            $objReader = IOFactory::createReader('Xlsx');

            $fileTmpname = $_FILES['file']['tmp_name'];
            $filename = $_FILES['file']['name'];
            $objPHPExcel = $objReader->load($fileTmpname);  //$filename可以是上传的表格，或者是指定的表格
            $sheet = $objPHPExcel->getSheet(0);   //excel中的第一张sheet
            $highestRow = $sheet->getHighestRow();       // 取得总行数
            // $highestColumn = $sheet->getHighestColumn();   // 取得总列数


            //定义$usersExits，循环表格的时候，找出已存在的用户。
            $total=0;
            //循环读取excel表格，整合成数组。如果是不指定key的二维，就用$data[i][j]表示。
            $key = 0;
            for ($j = 2; $j <= $highestRow; $j++) {
                $partner_number = $objPHPExcel->getActiveSheet()->getCell("A" . $j)->getValue();
                $partner_order_date = $objPHPExcel->getActiveSheet()->getCell("B" . $j)->getValue();
                $is_collect = $objPHPExcel->getActiveSheet()->getCell("C" . $j)->getValue();
                $partner_real_name = $objPHPExcel->getActiveSheet()->getCell("D" . $j)->getValue();
                $product_name = $objPHPExcel->getActiveSheet()->getCell("E" . $j)->getValue();
                $single_num = $objPHPExcel->getActiveSheet()->getCell("F" . $j)->getValue();
                $assign_express_type = $objPHPExcel->getActiveSheet()->getCell("G" . $j)->getValue();
                $recipient_person = $objPHPExcel->getActiveSheet()->getCell("H" . $j)->getValue();
                $recipient_phone = $objPHPExcel->getActiveSheet()->getCell("I" . $j)->getValue();
                $recipient_address = $objPHPExcel->getActiveSheet()->getCell("J" . $j)->getValue();
                $sender_person = $objPHPExcel->getActiveSheet()->getCell("K" . $j)->getValue();
                $sender_phone = $objPHPExcel->getActiveSheet()->getCell("L" . $j)->getValue();
                $sender_address = $objPHPExcel->getActiveSheet()->getCell("M" . $j)->getValue();
                $note = $objPHPExcel->getActiveSheet()->getCell("N" . $j)->getValue();
                $is_hurry = $objPHPExcel->getActiveSheet()->getCell("O" . $j)->getValue();

                //空行处理
                if($partner_number=="" || $partner_real_name=="" || $product_name==""){
                    continue;
                }
                //空格处理
                $replace  =["　"," "];
                $partner_number  = str_replace($replace,"",$partner_number);
                $is_collect  = str_replace($replace,"",$is_collect);
                $partner_real_name  = str_replace($replace,"",$partner_real_name);
                $product_name  = str_replace($replace,"",$product_name);
                $single_num  = str_replace($replace,"",$single_num);
                $assign_express_type  = str_replace($replace,"",$assign_express_type);
                $recipient_person  = str_replace($replace,"",$recipient_person);
                $recipient_address  = str_replace($replace,"",$recipient_address);
                $sender_person  = str_replace($replace,"",$sender_person);
                $sender_address  = str_replace($replace,"",$sender_address);
                $note  = str_replace($replace,"",$note);

                $data[$key] = [
                    'partner_number' => trim($partner_number),
                    'partner_order_date' => $partner_order_date,
                    'is_collect' => trim($is_collect),
                    'partner_real_name' => trim($partner_real_name),
                    'product_name' => trim($product_name),
                    'single_num' => trim($single_num),
                    'assign_express_type' => trim($assign_express_type),
                    'recipient_person' => trim($recipient_person),
                    'recipient_phone' => trim($recipient_phone),
                    'recipient_address' => trim($recipient_address),
                    'sender_person' => trim($sender_person),
                    'sender_phone' => trim($sender_phone),
                    'sender_address' => trim($sender_address),
                    'note' => trim($note),
					'is_hurry'=> $is_hurry == '1' ? strval($is_hurry) : '0'
                ];
                settype($data[$key]['single_num'],'int');
                settype($data[$key]['recipient_phone'],'string');
                settype($data[$key]['sender_phone'],'string');


                //判断是否合并发货
                if($data[$key]['is_collect']=='是')
                {
                    $data[$key]['is_collect']="1";
                }
                elseif($data[$key]['is_collect']=='否' || $data[$key]['is_collect']=='' )
                {
                    $data[$key]['is_collect']="0";
                }

                //快递模糊匹配
                if(strpos($data[$key]['assign_express_type'],'圆通') !== false){
                    $data[$key]['assign_express_type'] = 'yto';
                }
                if(strpos($data[$key]['assign_express_type'],'申通') !== false){
                    $data[$key]['assign_express_type'] = 'sto';
                }
                if(strpos($data[$key]['assign_express_type'],'中通') !== false){
                    $data[$key]['assign_express_type'] = 'zto';
                }
                if(strpos($data[$key]['assign_express_type'],'韵达') !== false){
                    $data[$key]['assign_express_type'] = 'yunda';
                }
                if(strpos($data[$key]['assign_express_type'],'百世') !== false){
                    $data[$key]['assign_express_type'] = 'best';
                }
                if(strpos($data[$key]['assign_express_type'],'顺丰寄') !== false){
                    $data[$key]['assign_express_type'] = 'sfj';
                }
                if(strpos($data[$key]['assign_express_type'],'顺丰到') !== false){
                    $data[$key]['assign_express_type'] = 'sfd';
                }
                if(strpos($data[$key]['assign_express_type'],'邮政') !== false){
                    $data[$key]['assign_express_type'] = 'ems';
                }
                if(strpos($data[$key]['assign_express_type'],'自提') !== false){
                    $data[$key]['assign_express_type'] = 'since';
                }
                if(strpos($data[$key]['assign_express_type'],'其他') !== false){
                    $data[$key]['assign_express_type'] = 'other';
                }



                //格式化客户订单时间
                if($data[$key]['partner_order_date']){
                    if (is_float($data[$key]['partner_order_date']))
                    {
                        $n = intval(($data[$key]['partner_order_date'] - 25569) * 3600 * 24); //转换成1970年以来的秒数
                        $data[$key]['partner_order_date'] = gmdate('Y-m-d H:i:s',$n);//格式化时间,不是用date哦, 时区相差8小时的
                    } else {
                        $data[$key]['partner_order_date'] = date('Y-m-d H:i:s', strtotime($data[$key]['partner_order_date']));
                    }
                }

                $md5_code = md5(json_encode($data[$key]));
                $ret = DB::table('erp_trade_order')->where('md5_code', $md5_code)->where('partner_code', $partner_code)->get()->toArray();
                if(!$ret)
                {
                    $data[$key]['md5_code'] = $md5_code;
                    $data[$key]['partner_code'] = $partner_code;
                    //存入数据库中
                    $new_id = DB::table('erp_trade_order')->insertGetId($data[$key]);

                    if($new_id){
                        $total++;
                }
                //避免数据字符集问题
                $result = (array)DB::table('erp_trade_order')->where('id',$new_id)->get()->toArray()[0];
                $data[$key]['partner_real_name'] = $result['partner_real_name'];
                $data[$key]['recipient_person'] = $result['recipient_person'];
                $data[$key]['recipient_address'] = $result['recipient_address'];
                $data[$key]['sender_person'] = $result['sender_person'];
                $data[$key]['sender_address'] = $result['sender_address'];
                $data[$key]['note'] = $result['note'];

                //请求接口
                $result = $this->getPort([$data[$key]]);
                if($result['code']==1){
                    DB::table('erp_trade_order')->where('id', $new_id)->update(['status' => 'success']);
                }
                if($result['code']==2){
                    DB::table('erp_trade_order')->where('id', $new_id)->update(['status' => 'error', 'err_msg' => $result['message']]);
                }
                    $key++;
                }

            }

        }
        $file_data = [
            'total'=>$total,//总共添加多少条记录
            'filename'=>$filename //文件名称
        ];
        return response()->json(['status' => 200, 'file_data' => $file_data]);
    }



    //请求接口信息
    public function getPort($trade_order_lines)
    {
        $trade_order_lines = json_encode($trade_order_lines);
        $data = [
            'trade_order_lines'=>$trade_order_lines
        ];
        $res_arr = new Api();
        $result_arr  = $res_arr->request(config('erp.interface_url').config('erp.trade_order'),$data);
        return $result_arr;
    }



   //添加/编辑操作
    public function save(ImportRequest $request)
    {
        $post = $request->all();
        $ret = $this->repositories->save($request->all());


        //空格处理
        $replace  =["　"," "];
        $partner_number  = str_replace($replace,"",$post['partner_number']);
        $is_collect  = str_replace($replace,"",$post['is_collect']);
        $partner_real_name  = str_replace($replace,"",$post['partner_real_name']);
        $product_name  = str_replace($replace,"",$post['product_name']);
        $single_num  = str_replace($replace,"",$post['single_num']);
        $assign_express_type  = str_replace($replace,"", $post['assign_express_type']);
        $recipient_person  = str_replace($replace,"",$post['recipient_person']);
        $recipient_address  = str_replace($replace,"",$post['recipient_phone']);
        $sender_person  = str_replace($replace,"",$post['sender_person']);
        $sender_address  = str_replace($replace,"",$post['sender_address']);
        $note  = str_replace($replace,"",$post['note']);

        if(!empty($post['id']) && $post['status']=="error") {

            $data = [
                'partner_number' => trim($partner_number),
                'partner_order_date' => $post['partner_order_date'],
                'is_collect' => trim($is_collect),
                'partner_real_name' => trim($partner_real_name),
                'product_name' => trim($product_name),
                'single_num' => trim($single_num),
                'assign_express_type' => trim($assign_express_type),
                'recipient_person' => trim($recipient_person),
                'recipient_phone' => trim($post['recipient_phone']),
                'recipient_address' => trim($recipient_address),
                'sender_person' => trim($sender_person),
                'sender_phone' => trim($post['sender_phone']),
                'sender_address' => trim($sender_address),
                'note' => trim($note),
                'is_hurry'=> $post['is_hurry']
            ];
            $result = $this->getPort([$data]);
            if($result['code']==1){
                DB::table('erp_trade_order')->where('id', $post['id'])->update(['status' => 'success']);
            }
            if($result['code']==2){
                DB::table('erp_trade_order')->where('id', $post['id'])->update(['status' => 'error', 'err_msg' => $result['message']]);
            }
        }

        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }

}
