<?php
namespace App\Repositories;

use App\Exceptions\CommonException;
use App\Models\DmsAgentInfo;
use App\Models\SaasOrderProducts;
use App\Models\SaasProducts;
use App\Models\SaasProductsSku;
use App\Models\SaasProjects;
use App\Models\SaasProjectsOrderTemp;
use App\Models\SaasSalesChanel;
use App\Services\Common\Mongo;
use App\Services\Helper;
use Illuminate\Support\Facades\DB;
use Matrix\Exception;

/**
 * diy作品仓库
 *
 * diy作品的相关数据操作
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/19
 */
class SaasProjectsRepository extends BaseRepository
{
    public function __construct(SaasProjects $model,SaasProducts $prodModel,SaasProductsSku $prodSkuModel,SaasSalesChanel $chanelModel,
                                    SaasProjectsOrderTemp $projectsOrderTempModel,DmsAgentInfo $agentInfoModel,SaasOrderProducts $orderProducts)
    {
        $this->model =$model;
        $this->prodModel =$prodModel;
        $this->prodSkuModel =$prodSkuModel;
        $this->chanelModel =$chanelModel;
        $this->agentInfoModel =$agentInfoModel;
        $this->projectsOrderTempModel =$projectsOrderTempModel;
        $this->ordProductsModel =$orderProducts;
        $this->merchantID = isset(session('admin')['mch_id']) ? session('admin')['mch_id'] : ' ';
        $this->agentID = isset(session('admin')['agent_info_id']) ? session('admin')['agent_info_id'] : null;

    }


    /**
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getTableList($where=null, $order=null)
    {
        $limit = isset($where['limit']) ? $where['limit']:config('common.page_limit');  //这个10取配置里的
        $temp_where = [];
        $prod_where=[];
        $prodSku_where=[];
        //临时订购表查询信息
        if(!empty($where['prj_outer_account'])){
            $temp_where['prj_outer_account'] = $where['prj_outer_account'];
        }
        if(!empty($where['prj_rcv_phone'])){
            $temp_where['prj_rcv_phone'] = $where['prj_rcv_phone'];
        }
        if(!empty($where['order_no'])){
            $temp_where['order_no'] = $where['order_no'];
        }
        //商品货号
        if(!empty($where['prod_sku_sn'])){
            $prodSku_where['prod_sku_sn'] = $where['prod_sku_sn'];
        }
        unset($where['prj_outer_account']);
        unset($where['prj_rcv_phone']);
        unset($where['order_no']);
        unset($where['prod_sku_sn']);

        $where = $this->parseWhere($where);

        //order 必须以 'id desc'这种方式传入.
        $orderBy = [];
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }

        //当作品临时订购表的搜索条件为空时，SQL语句如下
        if(empty($temp_where)){
            $query = $this->model->withTrashed()
                ->WhereHas(
                    'prod',function($query) use ($prod_where) {
                    if (!empty($prod_where)) {
                        $query->where($prod_where);
                    }
                })->WhereHas(
                    'prodSku',function($query) use ($prodSku_where) {
                    if (!empty($prodSku_where)) {
                        $query->where($prodSku_where);
                    }
                })->with(['prjTemp','prod','prodSku']);
        }
        else{
            //当作品临时订购表的搜索条件不为空时，SQL语句如下
            $query = $this->model->withTrashed()
                ->WhereHas(
                    'prjTemp',function($query) use ($temp_where) {
                    if (!empty($temp_where)) {
                        $query->where($temp_where);
                    }
                })->WhereHas(
                    'prod',function($query) use ($prod_where) {
                    if (!empty($prod_where)) {
                        $query->where($prod_where);
                    }
                })->WhereHas(
                    'prodSku',function($query) use ($prodSku_where) {
                    if (!empty($prodSku_where)) {
                        $query->where($prodSku_where);
                    }
                })->with(['prjTemp','prod','prodSku']);
        }


        //作品状态
        if(isset($where['prj_status'])&&$where['prj_status']=="4"){
            $query = $query->withTrashed()->where('prj_status',$where['prj_status']);
            unset($where['prj_status']);
        }

        //作品便签
        if(isset($where['prj_label'])){
            $query = $query->withTrashed()->where('prj_label',$where['prj_label']);
            unset($where['prj_label']);
        }

        //查询时间
        if(isset($where['created_at'])){
            $created_at = $where['created_at'];
            $time_list = Helper::getTimeRangedata($created_at);
            $query = $query->whereBetween("created_at",[$time_list['start'],$time_list['end']]);
            unset($where['created_at']);
        }
        //作品名称
        if(isset($where['prj_name']) && !empty($where['prj_name'])){
            $query = $query->where('prj_name', 'like', '%'.$where['prj_name'].'%');
            unset($where['prj_name']);
        }

        if(!empty ($where)) {
            $query =  $query->where($where);
        }
        if(!empty($order)) {
            $query =  $query->orderBy($orderBy[0],$orderBy[1])->orderBy("updated_at",'desc');
        }

        $list = $query->paginate($limit);

        foreach ($list as $k=>$v){
            //渠道
            $chanelInfo = $this->chanelModel->where(['cha_id'=>$v['cha_id']])->select('cha_name')->first();
            $list[$k]['cha_name'] = $chanelInfo['cha_name'];
            //分销商
            $agent_info = $this->agentInfoModel->where(['agent_info_id'=>$v['user_id'],'mch_id'=>$v['mch_id']])->select('agent_name')->first();
            $list[$k]['agent_name'] = $agent_info['agent_name'];
        }


        return $list;
    }


    /**
     * 新增/修改
     * @param $data
     * @return boolean
     */
    public function save($data)
    {
        if(empty($data['prj_id'])) {
            unset($data['prj_id']);
            $data['created_at'] = time();
            $ret = $this->model->insertGetId($data);
            $priKeyValue = $ret;

        } else {
            $priKeyValue = $data['prj_id'];
            unset($data['prj_id']);
            $data['updated_at'] = time();
            //恢复软删除
            $this->model->withTrashed()->where('prj_id',$priKeyValue)->restore();
            $ret =$this->model->where('prj_id',$priKeyValue)->update($data);
            //恢复作品临时订购表的信息
            $temp = $this->projectsOrderTempModel->withTrashed()->where(['prj_id'=>$priKeyValue])->get()->toArray();
            foreach ($temp as $k=>$v){
                $this->projectsOrderTempModel->withTrashed()->where('prj_info_id',$v['prj_info_id'])->restore();
                $ret =$this->projectsOrderTempModel->where('prj_info_id',$v['prj_info_id'])->update(['updated_at'=>time()]);
            }
        }
        //判断是否需要更新缓存
        if (isset($this->isCache)&&$this->isCache === true){
            $table_name = $this->model->getTable();
            $redis = app('redis.connection');
            $data['prj_id'] = $priKeyValue;
            //将数据写入缓存
            $redis->set($table_name.'_'.$priKeyValue , json_encode($data));
        }
        return $ret;
    }



    /**
     * 修改作品信息
     * @param $data
     * @return boolean
     */
    public function editSave($data)
    {
        $priKeyValue = $data['prj_id'];
        unset($data['prj_id']);
        $temp_data =[
            'prj_outer_account'=>$data['prj_outer_account'],
            'prj_rcv_phone'=>$data['prj_rcv_phone'],
            'ord_quantity' => $data['ord_quantity'],
            'order_no'=>$data['order_no'],
            'updated_at'=>time()
        ];
        //修改作品临时订购信息表信息
        $r_ret = $this->projectsOrderTempModel->where(['prj_id'=>$priKeyValue])->update($temp_data);
        //当数据不存在时，新增一条数据
        if(!$r_ret){
            unset($temp_data['updated_at']);
            $temp_data['created_at'] = time();
            $temp_data['user_id']=session('admin')['agent_info_id'];
            $user_type=$this->agentInfoModel->where(['agent_info_id'=>$temp_data['user_id']])->select('agent_type')->first();
            $temp_data['user_type']=$user_type['agent_type'];
            $temp_data['prj_id']=$priKeyValue;
            $r_ret = $this->projectsOrderTempModel->insertGetId($temp_data);
        }
        $prj_data = [
            'prj_name'=>$data['prj_name'],
            'prj_status'=>$data['prj_status'],
            'updated_at'=>time()
        ];
        //更新作品表信息
        $ret =$this->model->where('prj_id',$priKeyValue)->update($prj_data);

        return $ret;
    }

    /**
     * 修改作品标签
     * @param $data
     * @return boolean
     */
    public function labelSave($prj_id,$label)
    {
        $priKeyValue = $prj_id;
        $prj_data = [
            'prj_label'=>$label,
            'updated_at'=>time()
        ];
        $ret =$this->model->where('prj_id',$priKeyValue)->update($prj_data);
        return $ret;

    }


    /**
     * 删除(软删除)
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $model = $this->model->find($id);
        if(empty($model)){
            return true;
        }
        $ret = $this->model->where(['prj_id'=>$id])->update(['prj_status'=>4,'updated_at'=>time()]);
        if($ret){
            $temp = $this->projectsOrderTempModel->where(['prj_id'=>$id])->get()->toArray();
            foreach ($temp as $k=>$v){
                $temp_model = $this->projectsOrderTempModel->find($v['prj_info_id']);
                $temp_model->delete();
            }

            $model->delete();
        }

        $workLog = [
            'user_id'    => $this->agentID,
            'works_id'   => $id,
            'action'     => "删除作品",
            'note'       => "",
            'createtime' => time(),
            'operator'   => session('admin')['dms_adm_username'],
        ];
        $mongo = new Mongo();
        $mongo->insert('diy_works_log',$workLog);

        if($model->trashed()){
            return true;
        }else{
            return false;
        }
    }

    //根据prj_id获取作品信息
    public function getProjectInfo($prj_id)
    {
        $project = $this->model->where('prj_id',$prj_id)->get()->toArray();
        return $project;
    }

    //根据prj_id获取商品的物流方式
    public function getProdExpressType($prj_id)
    {
        $prod_id = $this->model->where('prj_id',$prj_id)->select('prod_id')->first();
        $prod_express_type = $this->prodModel->where('prod_id',$prod_id['prod_id'])->select('prod_express_fee')->first();
        return $prod_express_type;
    }


    /**
     *  各状态作品数量统计
     * @return array
     */
    public function projectStatusCount()
    {
        $where['mch_id'] = $this->merchantID;

        if(!empty($this->agentID)){
            $where['user_id'] = $this->agentID;
        }

        $all = count($this->model->withTrashed()->where($where)->get());                                                                   //全部
        $making = count($this->model->where('prj_status',WORKS_DIY_STATUS_MAKING)->where($where)->get());                   //制作中
        $wait_confirm = count($this->model->where('prj_status',WORKS_DIY_STATUS_WAIT_CONFIRM)->where($where)->get());       //待确认
        $order = count($this->model->where('prj_status',WORKS_DIY_STATUS_ORDER)->where($where)->get());                     //已订购
        $delete = count($this->model->withTrashed()->where('prj_status',WORKS_DIY_STATUS_DELETE)->where($where)->get());    //回收站

        return [$all,$making,$wait_confirm,$order,$delete];
    }


    /**
     * 作品提交成功页数据处理
     * @param $param
     * @return bool
     */
    public function handleWorkSuccess($param)
    {
        $url = http_build_query($param);
        $ht = config('app.manage_url')."/ds/ed.html?";
        $url = $ht.$url;

        $agent_url =config('app.agent_url');
        $data['url'] = $url;
        if(!isset($param['w'])){
            return;
        }

        $work_info = $this->getById($param['w']);
        if(empty($work_info)){
            return;
        }
        //根据userid查询店铺名称
        $agentInfo = $this->agentInfoModel->where('agent_info_id',$work_info['user_id'])->first();
        //根据作品id查询订单号是否存在
        $projOrderTemp = $this->projectsOrderTempModel->where('prj_id',$work_info['prj_id'])->first();
        if(!empty($projOrderTemp['order_no'])){ //存在订单号跳转到diy助手
            $data['make_url'] = $agent_url.'/diy_assistant?aid='.$work_info['user_id'].'&order_no='.$projOrderTemp['order_no'];
        }else{
            $data['make_url'] = $agent_url.'/ds/ed.html?w='.$param['w'].'&sp='.$work_info['mch_id'];
        }
        if(!empty($work_info)){
            $data['project_no'] = $work_info['prj_sn'];
            $data['qr_code'] = 'http://'.$agent_url.'/ds_m/?w='.$param['w'].'&sp='.$work_info['mch_id'];
            //$data['qr_code'] = $agent_url.'/qco/index?wid='.$param['w'].'&url='.$agent_url.'/mdiy/works';
            $data['agent_name'] = $agentInfo['agent_name'];
        }

        return $data;
    }

    //获取时间段作品待处理的数量
    public function peddingHandleCount($timeArr,$merchant)
    {
        $where['prj_status'] = WORKS_DIY_STATUS_WAIT_CONFIRM;
        if (!empty($merchant) && $merchant!='all')
        {
            $where['mch_id'] = $merchant;
        }
        //获取0-2小时内待处理的作品
        $data['now_hours'] = $this->model->where($where)->whereBetween('updated_at', [$timeArr['two_hours'],time()])->count();
        //获取2-4小时内待处理的作品
        $data['two_hours'] = $this->model->where($where)->whereBetween('updated_at', [$timeArr['four_hours'],$timeArr['two_hours']])->count();
        //获取4-6小时内待处理的作品
        $data['four_hours'] = $this->model->where($where)->whereBetween('updated_at', [$timeArr['six_hours'],$timeArr['four_hours']])->count();
        //获取6-12小时内待处理的作品
        $data['six_hours'] = $this->model->where($where)->whereBetween('updated_at', [$timeArr['tew_hours'],$timeArr['six_hours']])->count();
        //获取12-24小时内待处理的作品
        $data['tew_hours'] = $this->model->where($where)->whereBetween('updated_at', [$timeArr['tf_hours'],$timeArr['tew_hours']])->count();
        //获取24小时以外待处理的作品
        $data['tf_hours'] = $this->model->where($where)->where('updated_at', '<',$timeArr['tf_hours'])->count();
        return $data;
    }

    /**
     * @author: cjx
     * @time: 2020-07-21
     *  更新作品状态为制作中
     *  param $oid 订单id $user_id 分销id $username操作人
     */
    public function changeProjectStatus($oid,$user_id,$username)
    {
        $info = $this->ordProductsModel->where(['ord_id'=>$oid])->select('prj_id')->get()->toArray();
        if(empty($info)){
            //该订单记录不存在
            Helper::EasyThrowException('70030',__FILE__.__LINE__);
        }

        $mongo = new Mongo();
        foreach ($info as $k=>$v){
            $this->model->where(['prj_id'=>$v['prj_id']])->update(['prj_status'=>WORKS_DIY_STATUS_MAKING]);

            //记录作品操作日志
            $workLog = [
                'user_id'    => $user_id,
                'works_id'   => $v['prj_id'],
                'action'     => "取消订单",
                'note'       => "作品状态更新为制作中",
                'createtime' => time(),
                'operator'   => $username,
            ];
            $mongo->insert('diy_works_log',$workLog);
        }
    }

    /**
     * 商印作品提交成功页数据处理
     * @param $param
     * @return bool
     */
    public function handleComlWorkSuccess($param)
    {
        try{
            if(!isset($param['w']) || !isset($param['uprodsku'])){
                //无效或非法链接访问
                Helper::EasyThrowException('10023',__FILE__.__LINE__);
            }
            //获取作品信息
            $work_info = $this->getRow(['coml_works_id'=>$param['w']]);
            //预览链接
            if($param['is_mobile']){ //手机端
                $data['url'] = config('template.coml_mobile_url')."/userDesign/".$param['w']."?uprodsku=".$param['uprodsku'];
            }else{
                $data['url'] = config('template.coml_pc_url')."/design?id=".$param['w']."&mode=user&uprodsku=".$param['uprodsku'];
            }
            if(empty($work_info)){
                //该作品记录不存在
                Helper::EasyThrowException('60001',__FILE__.__LINE__);
            }

            //根据userid查询店铺名称
            $agentInfo = $this->agentInfoModel->where('agent_info_id',$work_info['user_id'])->first();
            if(empty($agentInfo)){
                //该分销记录不存在
                Helper::EasyThrowException('11101',__FILE__.__LINE__);
            }
            if(!empty($work_info)){
                $data['project_no'] = $work_info['prj_sn'];
                $data['qr_code'] = config('template.coml_mobile_url')."/userDesign/".$param['w']."?uprodsku=".$param['uprodsku'];
                $data['agent_name'] = $agentInfo['agent_name'];
            }

            return $data;
        }catch (CommonException $e){
             dump($e->getMessage());
        }

    }





}