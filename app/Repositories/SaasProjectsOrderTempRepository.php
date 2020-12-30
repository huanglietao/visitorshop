<?php
namespace App\Repositories;

use App\Models\SaasProjects;
use App\Models\SaasProjectsOrderTemp;

/**
 * 作品订单临时表仓库
 *
 * 功能详细说明
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/5/9
 */

class SaasProjectsOrderTempRepository extends BaseRepository
{
    public function __construct(SaasProjectsOrderTemp $model)
    {
        $this->model = $model;
    }

    //克隆作品时，克隆一份临时订购信息
    public function cloneOrderTemp($prj_id,$new_prj_id)
    {
        $orderTemp = $this->model->where(['prj_id'=>$prj_id])->first()->toArray();
        unset($orderTemp['prj_info_id']);
        $orderTemp['prj_id'] = $new_prj_id;
        $orderTemp['created_at'] = time();
        $orderTemp['updated_at'] = null;
        $ret = $this->model->insertGetId($orderTemp);
        return $ret;
    }
    //获取外部订单的作品列表
    public function getTbOrderWorksInfo($order_no,$sku_id,$status=[],$pc=ZERO)
    {
        if(empty($status)){
            $status = [WORKS_DIY_STATUS_MAKING,WORKS_DIY_STATUS_WAIT_CONFIRM,WORKS_DIY_STATUS_ORDER];
        }
        $pcWhere = [];
        if (!empty($pc)){
            $pcWhere = ['saas_projects.prj_page_num'=>$pc];
        }
        $worksInfo = $this->model
            ->where(['saas_projects_order_temp.order_no' => $order_no])
            ->leftJoin('saas_projects', 'saas_projects_order_temp.prj_id', '=', 'saas_projects.prj_id')
            ->where(['saas_projects.sku_id' => $sku_id])
            ->where($pcWhere)
            ->whereIn('saas_projects.prj_status',$status)
            ->whereNull('saas_projects.deleted_at')
            ->whereNull('saas_projects_order_temp.deleted_at')
            ->select('saas_projects_order_temp.prj_info_id','saas_projects_order_temp.prj_id','saas_projects_order_temp.ord_quantity','saas_projects_order_temp.prj_outer_account','saas_projects.sku_id','saas_projects.prj_name','saas_projects.prj_sn','saas_projects.prj_image','saas_projects.prj_status','saas_projects.cha_id','saas_projects.empty_mask_count','saas_projects.updated_at')
            ->get()
            ->toArray();
        return $worksInfo;

    }


    //获取外部订单的作品制作情况
    public function getTbOrderWorksCount($order_no,$sku_id,$num,$pc=ZERO)
    {
        $worksInfo = $this->getTbOrderWorksInfo($order_no,$sku_id,[],$pc);

        $reData['waiting_make'] = 0;  //待制作
        $reData['already_submit'] = 0; //已提交
        $reData['making'] = 0; //制作中
        $reData['making_works_id'] = [];
        $reData['submit_works_id'] = [];

        if (empty($worksInfo))
        {
            //该商品还未开始制作作品
            $reData['waiting_make'] = $num;
            $reData['already_submit'] = 0;
            $reData['making'] = 0;
        }else{
            foreach ($worksInfo as $k => $v)
            {
                if ($v['prj_status'] == WORKS_DIY_STATUS_MAKING)
                {
                    //制作中
                    $reData['making'] += $v['ord_quantity'];
                }else{
                    //已制作
                    $reData['already_submit'] += $v['ord_quantity'];
                }
            }
            //待制作
            $reData['waiting_make'] = $num-($reData['making'] + $reData['already_submit']);
        }
        return $reData;




    }


    //判断外部订单作品是否出现缺图情况（此时的订单作品确保是待确认状态）
    public function checkEmptyPictures($outer_order_no)
    {
        $projectModel = app(SaasProjects::class);
        //获取作品列表
        $projectInfo = $this->model->where('order_no',$outer_order_no)->pluck('prj_id')->toArray();
        //获取是否缺图
        $error = [];
        if (!empty($projectInfo)){
            $maskCountArr = $projectModel->whereIn('prj_id',$projectInfo)->select('empty_mask_count','prj_id')->get()->toArray();
            foreach ($maskCountArr as $k=>$v){
                if (!empty($v)){
                    //含有空图
                    $error[] = $v['prj_id']."作品含有".$v['empty_mask_count'].'空图，无法同步';
                }
            }
        }else{
            $error[] = "作品列表为空，无法判断是否缺图";
        }
        //判断是否有错误信息
        if (empty($error)){
            return [
                'code' => 1,
                'msg'  => 'ok'
            ];
        }else{
            $error_msg = implode(",",$error);
            return [
                'code' => 1,
                'msg'  => $error_msg
            ];
        }
    }
}