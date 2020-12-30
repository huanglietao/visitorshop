<?php
namespace App\Http\Controllers\Api\Sync\Taobao;

use App\Http\Controllers\Api\Sync\BaseController;
use App\Repositories\SaasSyncOrderConfRepository;
use Illuminate\Http\Request;

/**
 * 物流相关接口
 *
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date: 2020/06/08
 */
class CainiaoController extends BaseController
{
    /**
     * 面单所需数据
     * @desc 获取打印电子面单所需数据
     */
    public function printData(Request $request,SaasSyncOrderConfRepository $repoSyncConf)
    {
        //参数 agent_id 分销id data 面单所需的数据
        $agentId = $request->input('agent_id');
        $data = json_decode($request->input('data'), true);
        $settingInfo = $this->getSyncSetting($repoSyncConf, $agentId);

        //获取电子打单数据
        $req = new \CainiaoWaybillIiGetRequest();

        $param_waybill_cloud_print_apply_new_request = new \WaybillCloudPrintApplyNewRequest();

        $param_waybill_cloud_print_apply_new_request->cp_code = $data['cp_code'];
        $param_waybill_cloud_print_apply_new_request-> need_encrypt = true;
        $sender = new \UserInfoDto();
        $address = new \AddressDto();
        $address->city = $data['sender']['address']['city'];
        $address->detail = $data['sender']['address']['detail'];
        $address->district = $data['sender']['address']['district'];
        $address->province = $data['sender']['address']['province'];
        $address->town = $data['sender']['address']['town'];
        $sender->address = $address;
        $sender->mobile = $data['sender']['mobile'];
        $sender->name = $data['sender']['name'];
        $sender->phone = "";
        $param_waybill_cloud_print_apply_new_request->sender = $sender;
        $trade_order_info_dtos = new \TradeOrderInfoDto();
        $trade_order_info_dtos->logistics_services = $data['trade_order_info_dtos']['logistics_services'];
        $trade_order_info_dtos->object_id = $data['trade_order_info_dtos']['object_id'];
        $order_info = new \OrderInfoDto();
        $order_info->order_channels_type = $data['trade_order_info_dtos']['order_info']['order_channels_type'];
        $order_info->trade_order_list = $data['trade_order_info_dtos']['order_info']['trade_order_list'];
        $trade_order_info_dtos->order_info = $order_info;
        $package_info = new \PackageInfoDto();
        $package_info->id = $data['trade_order_info_dtos']['package_info']['id'];
        $items = new \Item();
        $items->count = $data['trade_order_info_dtos']['package_info']['item']['count'];
        $items->name = $data['trade_order_info_dtos']['package_info']['item']['name'];
        $package_info->items = $items;
        $package_info->volume = $data['trade_order_info_dtos']['package_info']['volume'];
        $package_info->weight =$data['trade_order_info_dtos']['package_info']['weight'];
        $trade_order_info_dtos->package_info = $package_info;
        $recipient = new \UserInfoDto();
        $address = new \AddressDto();
        $address->city = $data['trade_order_info_dtos']['recipient']['address']['city'];
        $address->detail = $data['trade_order_info_dtos']['recipient']['address']['detail'];
        $address->district = $data['trade_order_info_dtos']['recipient']['address']['district'];
        $address->province = $data['trade_order_info_dtos']['recipient']['address']['province'];
        $address->town = $data['trade_order_info_dtos']['recipient']['address']['town'];
        $recipient->address = $address;
        $recipient->mobile = $data['trade_order_info_dtos']['recipient']['mobile'];
        $recipient->name = $data['trade_order_info_dtos']['recipient']['name'];
        $recipient->phone =$data['trade_order_info_dtos']['recipient']['phone'];
        $trade_order_info_dtos->recipient = $recipient;
        $trade_order_info_dtos->template_url = $data['trade_order_info_dtos']['template_url'];
        $trade_order_info_dtos->user_id = $data['trade_order_info_dtos']['user_id'];
        $param_waybill_cloud_print_apply_new_request->trade_order_info_dtos = $trade_order_info_dtos;
        $param_waybill_cloud_print_apply_new_request->store_code =  $data['store_code'];
        $param_waybill_cloud_print_apply_new_request->resource_code = $data['resource_code'];
        $param_waybill_cloud_print_apply_new_request->dms_sorting = $data['dms_sorting'];
        $req->setParamWaybillCloudPrintApplyNewRequest(json_encode((array)$param_waybill_cloud_print_apply_new_request));

        $c = $settingInfo['handle'];
        $resp = $c->execute($req, $settingInfo['session_key']);

        if (!isset($resp['modules']['waybill_cloud_print_response'][0]['waybill_code'])){
            return $this->error($resp['code'],$resp);
        }

        return $this->success($resp);

    }

}