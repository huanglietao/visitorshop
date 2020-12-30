<?php
namespace App\Http\Controllers\Merchant\Suppliers;

use App\Exceptions\CommonException;
use App\Http\Controllers\Merchant\BaseController;
use App\Http\Requests\Merchant\Suppliers\SuppliersRequest;
use App\Repositories\MesAdminRepository;
use App\Repositories\SaasSuppliersRepository;
use App\Services\Helper;
use Illuminate\Http\Request;

/**
 * 项目说明 OMS系统 供求管理--供应商列表
 * 详细说明 OMS系统 供求管理--供应商列表，实现列表，添加，编辑，删除及组件结合
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/04/10
 */
class SuppliersController extends BaseController
{
    protected $viewPath = 'merchant.suppliers.suppliers';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $noNeedRight = [];                    //无需检查权限
    protected $merchantID = '';

    public function __construct(SaasSuppliersRepository $Repository,MesAdminRepository $mesAdminRepository)
    {
        parent::__construct();
        $this->repositories = $Repository;
        $this->mesAdminRepository = $mesAdminRepository;
        $this->merchantID = empty(session('admin')) == false ? session('admin')['mch_id'] : ' ';
        $this->sup_region = config('goods.sup_region');
    }


    /**
     * ajax获取列表项
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function table(Request $request)
    {

        $inputs = $request->all();
        $inputs['mch_id'] = $this->merchantID;
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
            'mch_id' => $this->merchantID,
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
            $params['mch_id']=$this->merchantID;
            $ret = $this->mesAdminRepository->save($params);
            if($ret){
                $ret = $this->repositories->updateIsCreate($params['sp_id'],$this->merchantID);
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





}
