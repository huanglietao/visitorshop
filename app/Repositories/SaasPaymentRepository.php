<?php
namespace App\Repositories;
use App\Models\SaasOrderPayLog;
use App\Models\SaasPayment;
use App\Services\Helper;

/**
 * 仓库模板
 * 仓库模板
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/04/01
 */
class SaasPaymentRepository extends BaseRepository
{
    protected $modelPayLog;
    public function __construct(SaasPayment $model,SaasOrderPayLog $payLog)
    {
        $this->model =$model;
        $this->modelPayLog = $payLog;
    }

    /**
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getTableList($where=null, $order=null)
    {
        $limit = isset($where['limit']) ? $where['limit']:config('common.page_limit');  //这个10取配置里的
        $where = $this->parseWhere($where);

        //order 必须以 'id desc'这种方式传入.
        $orderBy = [];
        if (!empty ($order)) {
            $arrOrder = explode(' ', $order);
            if(count($arrOrder) == 2) {
                $orderBy = $arrOrder;
            }
        }
        $query = $this->model;

        //查询时间
        if(isset($where['created_at'])){
            $created_at = $where['created_at'];
            $time_list = Helper::getTimeRangedata($created_at);
            $query = $query->whereBetween("created_at",[$time_list['start'],$time_list['end']]);
            unset($where['created_at']);
        }

        if(!empty ($where)) {
            $query =  $query->where($where);
        }

        if(!empty($order)) {
            $query =  $query->orderBy($orderBy[0],$orderBy[1]);
        }

        $list = $query->paginate($limit);
        return $list;
    }


    /**
     * 新增/修改
     * @param $data
     * @return boolean
     */
    public function save($data)
    {
        if(empty($data['pay_id'])) {
            unset($data['pay_id']);
            $data['created_at'] = time();
            $ret = $this->model->create($data);
        } else {
            $priKeyValue = $data['pay_id'];
            $data['updated_at'] = time();
            unset($data['pay_id']);
            $ret =$this->model->where('pay_id',$priKeyValue)->update($data);
        }
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
        $model->delete();

        if($model->trashed()){
            return true;
        }else{
            return true;
        }
    }

    /**
     * 获取官方设置的支付方式
     * @param
     * @return $row
     */
    public function getPayInfo($id=Null)
    {
        if(empty($id)){
            $row = $this->model->where('mch_id',0)->where('pay_status',1)->whereNull('deleted_at')->select('pay_id','pay_name')->get();
        }else{
            $row = $this->model->where('pay_id',$id)->whereNull('deleted_at')->get();
        }
        return json_decode($row,true);
    }

    /**
     * 记录日志数据
     * @param $logData 日志数据
     */
    public function recordPayLog($logData)
    {
        return $this->modelPayLog->create($logData);
    }

    //获取支付方式
    public function getPaymentList($mch_id)
    {
        $list = $this->model->where(['mch_id' => $mch_id])->get()->toArray();

        foreach ($list as $k => $v)
        {
            //图片取大后台的图片
            $list[$k]['pay_logo'] = $this->model->where(['mch_id' => PUBLIC_CMS_MCH_ID,'pay_class_name' => $v['pay_class_name']])->value('pay_logo');
        }
        return $list;
    }

    //获取支付方式，并组织封装数据
    public function getPayment($mch_id)
    {
        $payInfo = [];
        $payment = $this->model->where(['mch_id'=>$mch_id,'pay_status'=>PUBLIC_ENABLE])->select('pay_id','pay_name','pay_class_name')->get()->toArray();
        if(empty($payment)){
            $payment = $this->model->where(['mch_id'=>PUBLIC_CMS_MCH_ID,'pay_status'=>PUBLIC_ENABLE,'pay_class_name'=>'balance'])->select('pay_id','pay_name','pay_class_name')->get()->toArray();
        }
        foreach ($payment as $pk => $pv){
            $payInfo[$pv['pay_id']]['pay_name'] = $pv['pay_name'];
            $payInfo[$pv['pay_id']]['pay_class_name'] = $pv['pay_class_name'];
        }
        return $payInfo;
    }

    /**
     * 通过支付名称取支付方式id(cms定义支付方式)
     * @param $name 支付方式名称
     * @return 支付方式id
     */
    public function getPayByName($name)
    {
        return $this->model->where(['pay_class_name'=>"$name",'mch_id'=>0,'pay_status'=>PUBLIC_ENABLE])->select('pay_id')->first();
    }


    /**
     * 获取不同class_name的支付id
     * Author: LJH
     * Date: 2020/8/13
     * Time: 12:00
     * @param $mch_id
     * @param $class_name
     * @return mixed
     */
    public function getPayName($mch_id,$class_name)
    {
        $payInfo = $this->model->where(['mch_id'=>$mch_id,'pay_class_name'=>$class_name])->select('pay_id')->get()->toArray();
        if(empty($payInfo)){
            $payInfo = $this->model->where(['mch_id'=>ZERO,'pay_class_name'=>$class_name])->select('pay_id')->get()->toArray();
        }
        return $payInfo;
    }

}
