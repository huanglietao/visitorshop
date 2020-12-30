<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/22 0022
 * Time: 14:32
 */

namespace App\Services\Common\CallWayBillPrinter;


class WaybillDto
{
    public $mailNo;
    public $expressType;
    public $payMethod;
    public $returnTrackingNo;
    public $monthAccount;
    public $orderNo;
    public $zipCode;
    public $destCode;
    public $payArea;
    public $deliverCompany;
    public $deliverName;
    public $deliverMobile;
    public $deliverTel;
    public $deliverProvince;
    public $deliverCity;
    public $deliverCounty;
    public $deliverAddress;
    public $deliverShipperCode;
    public $consignerCompany;
    public $consignerName;
    public $consignerMobile;
    public $consignerTel;
    public $consignerProvince;
    public $consignerCity;
    public $consignerCounty;
    public $consignerAddress;
    public $consignerShipperCode;
    public $logo;
    public $sftelLogo;
    public $topLogo;
    public $topsftelLogo;
    public $appId;
    public $appKey;
    public $electric;
    public $cargoInfoDtoList;
    public $rlsInfoDtoList;
    public $insureValue;
    public $codValue;
    public $codMonthAccount;


    public $mainRemark;
    public $returnTrackingRemark;
    public $childRemark;
    public $custLogo;
    public $insureFee;

    public $encryptCustName; //加密寄件人及收件人名称
    public $encryptMobile; //加密寄件人及收件人联系手机
}