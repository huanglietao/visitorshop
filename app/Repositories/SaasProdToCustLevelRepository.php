<?php
namespace App\Repositories;
use App\Models\SaasProdToCustLevel ;

/**
 * 商品销售渠道仓库模板
 * 商品销售渠道仓库模板
 * @author: hlt <vali12138@163.com>
 * @version: 1.0
 * @date:  2020/04/07
 */
class SaasProdToCustLevelRepository extends BaseRepository
{

    public function __construct(SaasProdToCustLevel $model)
    {
        $this->model = $model;
    }

    public function getList($where=[], $order='created_at', $sort = "desc")
    {
        return parent::getList($where, $order, $sort); // TODO: Change the autogenerated stub
    }

    public function getProdCustList($prodId,$mid,$cha_id)
    {
        $array = $this->model->where(['prod_id' => $prodId,'mch_id' => $mid,'cha_id'=>$cha_id])->get()->toArray();
        $prodCustChaArr = [];
        $prodCustLevelArr = [];
        foreach ($array as $k=>$v)
        {
            $prodCustChaArr[]=$v['cha_id'];
            $prodCustLevelArr[] = $v['cust_lv_id'];
        }
        //该商品，该渠道有组别数据
        if (!empty($prodCustChaArr)){
            $prodCustCha = 1;
        }else{
            $prodCustCha = 0;
        }
        $data = [
            'prodCustCha' => $prodCustCha,
            'prodCustLevelArr' => $prodCustLevelArr,

        ];
        return $data;

    }



}

