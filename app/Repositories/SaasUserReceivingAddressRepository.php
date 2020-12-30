<?php
namespace App\Repositories;

use App\Models\SaasAreas;
use App\Models\SaasSalesChanel;
use App\Models\SaasUserReceivingAddress;

/**
 * 客户定价仓库
 *
 * 客户定价仓库
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/22
 */
class SaasUserReceivingAddressRepository extends BaseRepository
{
    public function __construct(SaasUserReceivingAddress $model)
    {
        $this->model = $model;
    }
    //获取用户第地址
    public function getAddressList($user_id,$cha_id)
    {
        $areasModel = app(SaasAreas::class);
        $addressArr = $this->model->where(['user_id' => $user_id,'cha_id' => $cha_id])->get()->toArray();

        foreach ($addressArr as $k => $v)
        {
            $addressArr[$k]['prov_name'] = $areasModel->where(['area_id' => $v['province']])->value('area_name');
            $addressArr[$k]['city_name'] = $areasModel->where(['area_id' => $v['city']])->value('area_name');
            $addressArr[$k]['area_name'] = $areasModel->where(['area_id' => $v['district']])->value('area_name');
        }
        return $addressArr;
    }
    //新增用戶地址
    public function newAddress($addressInfo,$user_id,$cha_id)
    {
        $data = [
            'user_id'      => $user_id,
            'cha_id'       => $cha_id,
            'rcv_username' => $addressInfo['consignee'],
            'rcv_phone'    => $addressInfo['ship_mobile'],
            'province'     => $addressInfo['province_code'],
            'city'         => $addressInfo['city_code'],
            'district'     => $addressInfo['district_code'],
            'rcv_address'  => $addressInfo['ship_addr'],
            'rcv_landline' => $addressInfo['ship_tel'],
            'zip_code'     => $addressInfo['ship_zip'],
        ];
        $flag = md5(json_encode($data));
        $isset = $this->model->where(['rcv_flag' => $flag])->first();
        if ($isset)
        {
            //地址已存在，无需新增
            return false;
        }else{
            //获取用户类型
            $channleModel = app(SaasSalesChanel::class);
            $data['user_type'] = $channleModel->where(['cha_id' => $cha_id])->value('cha_flag');
            $data['rcv_flag'] = $flag;
            $data['created_at'] = time();
            //新增地址
            $this->model->insert($data);
            return true;
        }
    }



}