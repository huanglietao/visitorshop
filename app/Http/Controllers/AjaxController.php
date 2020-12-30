<?php
namespace App\Http\Controllers;

use App\Repositories\AreasRepository;
use App\Repositories\SaasAreasRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mockery\Exception;

/**
 *
 * ajax请求控制器
 * @author: cjx
 * @version: 1.0
 * @date: 2019/8/21
 */

class AjaxController extends Controller
{
    protected $areas;protected $region;
    protected $suffix = ['jpg','jpeg','gif','png'];
    public function __construct(AreasRepository $areas, SaasAreasRepository $region)
    {
        $this->areas = $areas;
        $this->region = $region;
    }

    //获取省市区数据
    public function getAreas(Request $request)
    {
        $id = $request->post("id");
        //$list = $this->areas->getAreasList($id);
        $list = $this->region->getAreasLists($id);
        return response()->json(['status' => 200, 'list' => $list]);
    }

    //图片上传处理
    public function upload(Request $request)
    {
        $file=$request->file('file');

        //获取后缀
        $ext = $file->getClientOriginalExtension();

        //生成文件名称
        $filename = time() . str_random(6) . "." . $ext;

        //文件保存路径
        $date = date('Ymd');
        $filepath = config('common.image_path').$date;

        if (!file_exists($filepath)) {
            @mkdir($filepath);
        }
        $res = $file->move($filepath,$filename);
        return config('common.static_url').'/'.$date.'/'.$filename;

    }

    //删除图片
    public function del(Request $request)
    {
        $params = $request->post("path");

        //检查后缀
        $ext = explode(".",$params);
        if(in_array($ext[count($ext)-1],$this->suffix)){
            $path = @public_path($params);
            unlink($path);
            return response()->json(['status' => 200, 'msg' => '']);
        }else{
            return response()->json(['status' => 'error', 'msg' => 'error']);
        }

    }
}