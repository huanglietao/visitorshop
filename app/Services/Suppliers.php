<?php
namespace App\Services;

use App\Repositories\SaasProductsRepository;
use App\Repositories\SaasSuppliersRepository;

/**
 * 供应商相关逻辑
 *
 * 供应商匹配、订单分配及推送下载相关的逻辑
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/20
 */
class Suppliers
{
    protected $repoSupplier;
    protected $repoProd;
    public function __construct(SaasSuppliersRepository $supp,SaasProductsRepository $prod)
    {
        $this->repoSupplier = $supp;
        $this->repoProd     = $prod;
    }

    /**
     * 商品id和收货信息匹配供货商
     * @param $prodId
     * @param $province
     * @param string $city
     * @param string $district
     * @return int
     */
    public function matchSupplier($prodId, $province, $city='', $district ='')
    {
        //获取商品对应的供货商
        $list = $this->repoProd->getSuppliersList($prodId);

        if (count($list) == 1) {  //只指定了一个
            return $list[0]['sup_id'];
        }

        if (count($list) == 0) { //没有说明不指定供应商
            return 0;
        }
        //一个商品对多个供应商情况
        //1,先进行地址匹配
        $usefulSuppliers = []; //可用的供货商
        $noAreaSuppliers =[];  //不能匹配地址的，通用
        foreach ($list as $k=>$v) {
            $supInfo = $this->repoSupplier->getByIdFromCache($v['sup_id']);
            if(!empty($supInfo['sup_service_area'])) {
                $serviceAreaList = explode(',', $supInfo['sup_service_area']);

                if (!empty($district) && in_array($district, $serviceAreaList)) { //区->市->省这样来匹配
                    $usefulSuppliers[] = $supInfo;
                    continue;
                }
                if (!empty($city) && in_array($city, $serviceAreaList)) { //区->市->省这样来匹配
                    $usefulSuppliers[] = $supInfo;
                    continue;
                }
                if (!empty($province) && in_array($province, $serviceAreaList)) { //区->市->省这样来匹配
                    $usefulSuppliers[] = $supInfo;
                    continue;
                }
            } else {
                //不能匹配地址取服务区域为空的
                $noAreaSuppliers[] = $supInfo;
            }

        }
        //正好匹配到一个，直接返回
        if (count($usefulSuppliers) == 1) {
            return $usefulSuppliers[0]['sup_id'];
        }

        if (count($usefulSuppliers) == 0) { //无法匹配地址
            $usefulSuppliers = $noAreaSuppliers;
            //正好匹配到一个直接返回
            if (count($noAreaSuppliers) == 1) {
                return $noAreaSuppliers[0]['sup_id'];
            }
            if($noAreaSuppliers == 0) {
                return 0;
            }
        }

        //主力/备选,还有多个的情况
        $mainSuppliers = [];
        foreach ($usefulSuppliers as $k=>$v) {
            $supType = empty($v['sup_type'])? 0 :$v['sup_type'];
            $mainSuppliers[$supType][] = $v;
        }

        if(!empty($mainSuppliers[SUPPLIER_TYPE_MAIN])) {
            return $mainSuppliers[SUPPLIER_TYPE_MAIN][0]['sup_id'];
        } else {
            return $mainSuppliers[SUPPLIER_TYPE_BACKUP][0]['sup_id'];
        }

        return 0;

    }
}