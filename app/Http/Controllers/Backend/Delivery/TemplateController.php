<?php
namespace App\Http\Controllers\Backend\Delivery;

use App\Http\Controllers\Backend\BaseController;
use App\Http\Requests\Backend\Delivery\TemplateRequest;
use App\Repositories\SaasDeliveryTemplateRepository;
use Illuminate\Http\Request;
use App\Exceptions\CommonException;

/**
 * 项目说明  CMS系统 物流设置-物流模板
 * 详细说明  CMS系统 物流设置-物流模板，实现物流模板列表，添加，编辑，删除及组件结合
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/03/20
 */
class TemplateController extends BaseController
{
    protected $viewPath = 'backend.delivery.template';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    public function __construct(SaasDeliveryTemplateRepository $Repository)
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
            $list = $this->repositories->getTableList($inputs,"del_temp_id desc");

            $result = $list->toArray();
            $data = $result['data'];
            foreach ($data as $k=>$v){
                $tran_name = [];
                $tran_name_list = $v['del_temp_delivery_list'];
                $list_name = explode(",", $tran_name_list);
                foreach ($list_name as $key => $val){
                    $del_name = $this->repositories->getTransportName($val);
                    $tran_name[$key] = $del_name[0]['delivery_name'];
                }
                $tran_name = implode(",", $tran_name);
                $result['data'][$k]['del_temp_delivery_list_name'] = $tran_name;
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
     * @参数 Request $request
     * @返回 mixed
     */
    public function form(Request $request)
    {
        try {
            if($request->ajax())
            {
                $row = $this->repositories->getById($request->input('id'));
                $area_fare=[];
                $input_area = [];
                $input_price = [];
                $area_name_list = [];
                if(!empty($row)){
                    $result = $row->toArray();
                    $area_fare = json_decode($result['del_temp_area_conf'],true);
                    //将选中运送方式的id，并切割为数组
                    $tran_name_list = $result['del_temp_delivery_list'];
                    $list_name = explode(",", $tran_name_list);
                    //根据上面切割的数组做操作
                    foreach ($list_name as $key => $val){
                        $area_fare_list = $area_fare[$val];
                        //得到选中地区的默认运费，并转换为数组
                        $area_fare[$val][0] = explode(",",$area_fare[$val][0]);

                        //不同运费下的地区数据进行分割
                        for ($i=0;$i<count($area_fare_list[1]);$i++){
                            //得到不同运费的不同地区的配置
                            $input_array = implode(";",$area_fare[$val][1][$i]);
                            //将配置分割成地区ID和运费的数据
                            $array = explode(";",$input_array);
                            //特定地区的值
                            $input_area['area'.$val][$i] = $array[0];
                            //特定地区运费的数据
                            $input_price['price'.$val][$i] = $array[1];
                            //将特定地区运费切割为数组，用于前端赋值
                            $area_fare[$val][1][$i][0] = explode(",",$input_price['price'.$val][$i]);
                            //根据特定地区的ID找对应的地名
                            $area_name = explode(",",$array[0]);
                            for($j=0;$j<count($area_name);$j++){
                                $result = $this->repositories->getArea($area_name[$j]);
                                //得到配置地区的id和地区名
                                $area_name_list['name'.$val][$i][$j] = json_decode($result,true);
                            }
                        }
                        //配置不同运费地区的数量
                        $area_fare['tr_num'.$val] = count($area_fare[$val][1]);
                    }
                }

                //得到运送方式的id和名字
                $transport = $this->repositories->getTransportName();

                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row,'area_fare'=>$area_fare,'transport'=>json_decode($transport,true),'input_area'=>$input_area,'input_price'=>$input_price,'area_name_list'=>$area_name_list]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }
    }


   //添加/编辑操作
    public function save(TemplateRequest $request)
    {
        try{
            $post = $request->all();

            //得到选中运送方式的id，并切割为数组
            $trans_name = $post['del_temp_delivery_list'];

            $transArray = explode(",",$trans_name);

            foreach ($transArray as $key=>$value) {
                //对默认运费进行整合
                $data[$value][0] = $post['default' . $value]['default_first_weight'] . "," . $post['default' . $value]['default_first_price'] . ","
                    . $post['default' . $value]['default_continuation_weight'] . "," . $post['default' . $value]['default_continuation_price'];
                //得到配置不同运费地区的数量
                $tr_num = $post['tr_num' . $value];

                $area_fare_list[$key] = [];
                for ($i = 1; $i <= $tr_num; $i++) {
                    //判断地区和指定地区是否都有值
                    if(!empty($post['area' . $value . $i]) || !empty($post['area' . $value]['continuation_weight'][$i - 1])
                        || !empty($post['area' . $value]['first_price'][$i - 1]) || !empty($post['area' . $value]['first_weight'][$i - 1])
                        || !empty($post['area' . $value]['continuation_price'][$i - 1]))
                    {
                        if (empty($post['area' . $value . $i])
                            || (empty($post['area' . $value]['first_weight'][$i - 1]) && $post['area' . $value]['first_weight'][$i - 1] != "0")
                            || (empty($post['area' . $value]['first_price'][$i - 1]) && $post['area' . $value]['first_price'][$i - 1] != "0")
                            || (empty($post['area' . $value]['continuation_weight'][$i - 1]) && $post['area' . $value]['continuation_weight'][$i - 1] != "0")
                            || (empty($post['area' . $value]['continuation_price'][$i - 1]) && $post['area' . $value]['continuation_price'][$i - 1] != "0"))
                        {
                            return $this->jsonFailed('请将第' . $value . '个运送方式中第' . $i . '个指定地区的运费和地区都填写完整！');
                        }
                    }else{
                        continue;
                    }

                    //根据不同运费地区的整合，不同运费下配置的地区整合
                    $area_price = $post['area'.$value]['first_weight'][$i-1].','.$post['area'.$value]['first_price'][$i-1].','
                        .$post['area'.$value]['continuation_weight'][$i-1].','.$post['area'.$value]['continuation_price'][$i-1];
                    $area_fare[0][0] =$post['area'.$value.$i].";".$area_price;
                    $lenght = count($area_fare_list[$key]);
                    $area_fare_list[$key][$lenght] = $area_fare[0];
                }
                //多个配送方式整合
                $data[$value][1]=$area_fare_list[$key];
            }

            //数据整合
            $datas = [
                'del_temp_id'=>$post['del_temp_id'],
                'del_temp_name'=> $post['del_temp_name'],
                'del_temp_desc'=> $post['del_temp_desc'],
                'del_temp_delivery_list'=> $post['del_temp_delivery_list'],
                'del_temp_area_conf'=>json_encode($data),
                'del_temp_priority'=>$post['del_temp_priority'],
                'del_temp_status'=>$post['del_temp_status'],
            ];
            $ret = $this->repositories->save($datas);

            if ($ret) {
                return $this->jsonSuccess([]);
            } else {
                return $this->jsonFailed('');
            }
        }catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

    }

}
