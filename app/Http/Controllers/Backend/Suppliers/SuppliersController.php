<?php
namespace App\Http\Controllers\Backend\Suppliers;

use App\Exceptions\CommonException;
use App\Http\Controllers\Backend\BaseController;
use App\Http\Requests\Backend\Suppliers\SuppliersRequest;
use App\Models\SaasSuppliers;
use App\Repositories\MesAdminRepository;
use App\Repositories\SaasDeliveryRepository;
use App\Repositories\SaasSuppliersLogisticsCostsRepository;
use App\Repositories\SaasSuppliersRepository;
use App\Services\Helper;
use Illuminate\Http\Request;

/**
 * 项目说明 CMS系统 供应商管理--供应商列表
 * 详细说明 CMS系统 供应商管理--供应商列表，实现列表，添加，编辑，删除及组件结合
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/04/09
 */
class SuppliersController extends BaseController
{
    protected $viewPath = 'backend.suppliers.suppliers';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $noNeedRight = [];                    //无需检查权限

    public function __construct(SaasSuppliersRepository $Repository,MesAdminRepository $mesAdminRepository,
                                SaasSuppliersLogisticsCostsRepository $logisticsCostsRepository,
                                SaasDeliveryRepository $deliveryRepository)
    {
        parent::__construct();
        $this->sup_region = config('goods.sup_region');

        $this->repositories = $Repository;
        $this->mesAdminRepository = $mesAdminRepository;
        $this->logisticsCostsRepository = $logisticsCostsRepository;
        $this->deliveryRepository = $deliveryRepository;
    }



    /**
     * ajax获取列表项
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function table(Request $request)
    {

        $inputs = $request->all();
        $inputs['mch_id'] = 0;
        $list = $this->repositories->getTableList($inputs,'sup_id desc');
        $htmlContents = $this->renderHtml('',['list' =>$list,'sup_region'=>$this->sup_region]);

        $pagesInfo = $list->toArray();

        $total = $pagesInfo['total'];

        return $this->jsonSuccess(['html' => $htmlContents,'total' => $total]);
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
                $sup_list['sup_region'] = Helper::getChooseSelectData($this->sup_region);
                $sup_list['sup_service']=[];
                if(!empty($row)){
                    $result = $row->toArray();
                    $sup_service_area = $result['sup_service_area'];
                    $sup_service_area_list = explode(";",$sup_service_area);
                    for($i=0;$i<count($sup_service_area_list);$i++){
                        $result = $this->repositories->getArea($sup_service_area_list[$i]);
                        //得到配置地区的id和地区名
                        $sup_list['sup_service'][$i] = json_decode($result,true);
                    }
                }
                $htmlContents = $this->renderHtml($this->viewPath.'.'.$this->form, ['row' => $row,'sup_list'=>$sup_list]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }

    }


   //添加/编辑操作
    public function save(SuppliersRequest $request)
    {
        $params = $request->all();

        if(empty($params['province']) || empty($params['city']) || empty($params['district']) || $params['district']=='区' ){
            return $this->jsonFailed('地区请填写完整！');
        }
            $data = [
            'sup_id' => $params['sup_id'],
            'sup_name' => $params['sup_name'],
            'sup_code' => $params['sup_code'],
            'sup_contacts' => $params['sup_contacts'],
            'sup_telephone' => $params['sup_telephone'],
            'sup_region' => $params['sup_region'],
            'sup_province' => $params['province'],
            'sup_city' => $params['city'],
            'sup_area' => $params['district'],
            'sup_type' => $params['sup_type'],
            'sup_capacity' => $params['sup_capacity'],
            'sup_allocation_quantity' => $params['sup_allocation_quantity'],
            'sup_capacity_unit' => $params['sup_capacity_unit'],
            'sup_status' => $params['sup_status'],
            'sort' => $params['sort']??0,
            'sup_service_area' => $params['sup_service_area'],
        ];

        $ret = $this->repositories->save($data);
        if ($ret) {
            return $this->jsonSuccess([]);
        } else {
            return $this->jsonFailed('');
        }
    }



    /**
     * 账号表单展示
     * @param Request $request
     * @return mixed
     */
    public function account(Request $request)
    {
        try {
            if($request->ajax())
            {
                $sp_id = $request->input('id');
                $row = $this->mesAdminRepository->getBySupID($sp_id);

                $htmlContents = $this->renderHtml($this->viewPath.'._account', ['row' => $row,'sp_id'=>$sp_id]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }
    }

    /**
     *  供货商账号信息保存
     * @param Request $request
     */
    public function accountSave(Request $request)
    {
        try{
            \DB::beginTransaction();
            $params = $request->all();
            $params['mch_id']=PUBLIC_CMS_MCH_ID;
            $ret = $this->mesAdminRepository->save($params);
            if($ret){
                $ret = $this->repositories->updateIsCreate($params['sp_id'],PUBLIC_CMS_MCH_ID);
                if($ret){
                    \DB::commit();
                    return $this->jsonSuccess('');
                }else{
                    \DB::rollBack();
                    return $this->jsonFailed("程序出错,数据插入失败");
                }
            }
        }catch (CommonException $e){
            \DB::rollBack();
            return $this->jsonFailed($e->getMessage());
        }
    }

    /**
     * 供货商物流成本设置表单
     * Date: 2020/6/16
     * Time: 16:34
     * @param Request $request
     */
    public function logisticsCosts(Request $request)
    {
        try {
            if($request->ajax())
            {
                $sup_id = $request->input('id');
                //获取供应商名称
                $supModel = app(SaasSuppliers::class);
                $suppliers = $supModel->where(['sup_id'=>$sup_id])->select('sup_name')->first();

                $row = $this->logisticsCostsRepository->getCosts($sup_id);
                $area_fare=[];
                $input_area = [];
                $input_price = [];
                $area_name_list = [];
                if(!empty($row)){
                    $area_fare = json_decode($row['sup_log_cos_area_conf'],true);
                    //将选中运送方式的id，并切割为数组
                    $tran_name_list = $row['sup_log_cos_delivery_list'];
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
                $sup = [
                    'sup_id'=>$sup_id,
                    'sup_name'=>$suppliers['sup_name']
                ];
                $transport = $this->deliveryRepository->getDelivery();
                $htmlContents = $this->renderHtml($this->viewPath.'._cost', ['row' => $row,'sup'=>$sup,'area_fare'=>$area_fare,'transport'=>json_decode($transport,true),'input_area'=>$input_area,'input_price'=>$input_price,'area_name_list'=>$area_name_list]);
                return $this->jsonSuccess(['html' => $htmlContents]);
            }else{
                return view("admin.tips");
            }
        } catch (CommonException $e) {
            return $this->jsonFailed($e->getMessage());
        }
    }

    /**
     * 供货商物流成本表单保存
     * Date: 2020/6/16
     * Time: 18:18
     * @param Request $request
     * @return array
     */
    public function costSave(Request $request)
    {
        try{
            $post = $request->all();

            //得到选中物流公司的id，并切割为数组
            $trans_name = $post['sup_log_cos_delivery_list'];

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
                'sup_log_cos_id'=>$post['sup_log_cos_id'],
                'sup_id'=> $post['sup_id'],
                'sup_log_cos_delivery_list'=> $post['sup_log_cos_delivery_list'],
                'sup_log_cos_area_conf'=>json_encode($data),
            ];

            $ret = $this->logisticsCostsRepository->save($datas);

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