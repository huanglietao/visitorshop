<?php
namespace App\Repositories;
use App\Models\SaasDelivery;
use App\Models\SaasDeliveryTemplate;
use App\Models\SaasExpress;
use App\Services\Helper;

/**
 * 仓库模板
 * 仓库模板
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/03/16
 */
class SaasExpressRepository extends BaseRepository
{

    public function __construct(SaasExpress $model)
    {
        $this->model =$model;
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
        if(empty($order)){$order='express_id desc';}
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
        if(empty($data['express_id'])) {
            unset($data['express_id']);
            $data['created_at'] = time();
            $ret = $this->model->create($data);
        } else {
            $priKeyValue = $data['express_id'];
            unset($data['express_id']);
            $data['updated_at'] = time();
            $ret =$this->model->where('express_id',$priKeyValue)->update($data);
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

    //根据标识获取快递名称
    public function getDeliveryName($code)
    {
        $deliveryName = $this->model->where('express_code',strtolower($code))->value('express_name');
        return $deliveryName;
    }


    //获取所有启用的快递方式
    public function getExpress()
    {
        $expressList = $this->model->where(['express_status'=>ONE])->select('express_id','express_name')->get()->toArray();
        $helper = app(Helper::class);
        $expList = $helper->ListToKV('express_id','express_name',$expressList);
        return $expList;
    }

    //根据快递标识跟商户id匹配物流方式,支持多商品匹配
    public function codeGetDeliveryId($deliveryCode='yto',$mchId = 0,$goodsId=0)
    {
        $expressModel = app(SaasExpress::class);
        $deliveryModel = app(SaasDelivery::class);
        $deliveryRepository = app(SaasDeliveryRepository::class);

        $expressId = $expressModel->where('express_code',$deliveryCode)->first();
        if (empty($expressId)){
            return [
                'code' => 0,
                'status' => 'error',
                'msg'  => '快递标识无法解析',
            ];
        }else{
            $expressId = $expressId['express_id'];
            if (empty($goodsId))
            {
                //无商品id
                //获取物流表中包含此快递方式的物流
                //先查看是否有mid的，优先取mid的没有则取mid为0的
                $deliveryArr = $deliveryModel->whereRaw('FIND_IN_SET('.$expressId.',delivery_express_list)')->where('mch_id',$mchId)->orderBy('weight','desc')->first();
                if (empty($deliveryArr)){
                    $deliveryZeroArr = $deliveryModel->whereRaw('FIND_IN_SET('.$expressId.',delivery_express_list)')->where('mch_id',ZERO)->orderBy('weight','desc')->first();
                    if (empty($deliveryZeroArr)){
                        return [
                            'code' => 2,
                            'status' => 'error',
                            'msg'  => '获取不到物流方式'
                        ];
                    }else{
                        return [
                            'code' => 1,
                            'status' => 'success',
                            'delivery_id'  => $deliveryZeroArr['delivery_id']
                        ];
                    }
                }else{
                    return [
                        'code' => 1,
                        'status' => 'success',
                        'delivery_id'  => $deliveryArr['delivery_id']
                    ];
                }
            }else{
                //有商品id，先获取商品的物流模板
                $res = $deliveryRepository->getDefaultDeliveryId($goodsId);
                if (!empty($res['code']))
                {
                     //判断这个物流方式的快递列表是否包含该快递
                    $deliveryArr = $deliveryModel->where('delivery_id',$res['delivery_id'])->first();
                    $dArr = explode(',',$deliveryArr['delivery_express_list']);
                    if (in_array($expressId,$dArr)){
                        //存在，
                        return [
                            'code' => 1,
                            'status' => 'success',
                            'delivery_id'  => $res['delivery_id']
                        ];
                    }else{
                        //不存在
                        return [
                            'code' => 3,
                            'status' => 'error',
                            'msg'  => '该商品不发此快递'
                        ];
                    }

                }else{
                    return [
                        'code' => 0,
                        'status' => 'error',
                        'msg'  => $res['msg']
                    ];
                }
            }
        }


    }
}
