<?php

namespace App\Repositories;

use App\Models\OmsMerchantAccount;
use App\Models\OmsMerchantInfo;
use App\Services\Helper;

/**
 * 商户资料仓库模板
 * @author:cjx
 * @version: 1.0
 * @date:2020/03/30
 */
class OmsMerchantInfoRepository extends BaseRepository
{

    public function __construct(OmsMerchantInfo $model,OmsMerchantAccount $merchantAccount)
    {
        $this->model =$model;
        $this->omsAccountModel = $merchantAccount;
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

        if(empty($order)){
            $order='mch_id desc';
        }

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

        foreach ($list as $k=>$v){
            $is_exist = $this->omsAccountModel->where(['mch_id'=>$v['mch_id'],'is_main'=>1])->first();
            $list[$k]['exist_flag'] = empty($is_exist) ? 0 : 1;
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
        if(empty($data['id'])) {
            unset($data['id']);
            $data['created_at'] = time();
            $ret = $this->model->create($data);
        } else {
            $priKeyValue = $data['id'];
            unset($data['id']);
            $data['updated_at'] = time();
            $ret =$this->model->where('mch_id',$priKeyValue)->update($data);
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

    //获取商家的发件人信息
    public function getMerchantSender($mch_id)
    {
        //优先取商家表的配置
        $mchSenderInfo = $this->model->where('mch_id',$mch_id)->select('mch_sender_person','mch_sender_phone')->first();
        if (empty($mchSenderInfo) || empty($mchSenderInfo['mch_sender_person']) || empty($mchSenderInfo['mch_sender_phone'])){
            //直接取配置的发件人信息
            $mchSenderInfo = config('common.default_sender');
        }else{
            //只有两个都有的值才会返回商家的发件人配置
            $mchSenderInfo = $mchSenderInfo->toArray();
        }
        return $mchSenderInfo;
    }

    //获取所有商家的id和名字
    public function getAllMerchantInfo()
    {
        $info = $this->model->select('mch_id','mch_name')->get()->toArray();
        $infoList = Helper::ListToKV('mch_id','mch_name',$info);
        $infoList = Helper::getChooseSelectData($infoList);
        return $infoList;
    }

}
