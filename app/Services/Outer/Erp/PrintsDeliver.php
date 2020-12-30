<?php
namespace App\Services\Outer\Erp;
use App\Repositories\ErpPrintsDeliverOrderRepository;


/**
 * 打单发货
 * @author: cjx
 * @version: 1.0
 * @date: 2020/03/08
 */

class PrintsDeliver
{

    protected $erpPrintsDeliverOrderRepository;

    public function __construct(ErpPrintsDeliverOrderRepository $erpPrintsDeliverOrderRepository)
    {
        $this->erpPrintsDeliverOrderRepository = $erpPrintsDeliverOrderRepository;
    }

    /**
     * 请求客户信息查询接口 http://60.30.76.54:806/ec/trade/do_search_cloud_trade_order_express_info_no_picking
     * @param string $requestUrl 请求地址 $postData 请求数据 $proName 产品简称
     * @return array
     */
    public function requestApi($proName,$limit_num=20)
    {
        //获取加急订单
        $hurryData = [
            'is_hurry'          =>  1,
            'product_name'      =>  $proName,
            'limit_num'         =>  intval($limit_num),
        ];
        $hurryArr = $this->getData($hurryData);

        //将加急订单的标识添加进数组
        $hurryStr['is_hurry'] = "是";
        array_walk($hurryArr, function (&$value, $key, $hurryStr) {
            $value = array_merge($value, $hurryStr);
        }, $hurryStr);

        //获取不加急订单
        $noHurryData = [
            'is_hurry'          =>  0,
            'product_name'      =>  $proName,
            'limit_num'         =>  intval($limit_num),
        ];
        $noHurryArr = $this->getData($noHurryData);
        //将非加急订单的标识添加进数组
        $noHurryStr['is_hurry'] = "否";
        array_walk($noHurryArr, function (&$value, $key, $noHurryStr) {
            $value = array_merge($value, $noHurryStr);
        }, $noHurryStr);

        //合并数组
        $data = array_merge($hurryArr,$noHurryArr);

        //将产品标识添加进数组
        $mask_20['product_name'] = $proName;

        array_walk($data, function (&$value, $key, $mask_20) {
            $value = array_merge($value, $mask_20);
        }, $mask_20);


        return $data;



    }
    public function getData($postData)
    {
        $requestApi = new Api();
        $requestUrl = config("erp.interface_url").config("erp.order_no_pick");
       /* $postData = [
            'is_hurry'          =>  0,
            'product_name'      =>  $proName,
            'limit_num'         =>  $limit_num,
        ];*/
        $res = $requestApi->request($requestUrl,$postData);
        if($res['code'] == 1){
            return $res['data'];
        }else{
            return [];
        }
    }

    /**
     * 接口返回数据存入数据表
     * @param array $data 返回数据
     * @return array
     */
    public function saveData($data)
    {

        $this->erpPrintsDeliverOrderRepository->saveInfo($data);
    }

    public function getOrderInfo($order_no)
    {
        $res = $this->erpPrintsDeliverOrderRepository->orderItemInfo($order_no);
        return $res;
    }

}