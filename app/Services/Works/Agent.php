<?php
namespace App\Services\Works;

use App\Repositories\SaasDiyAssistantRepository;
use App\Services\Helper;

/**
 * 分销作品处理类
 *
 * 功能详细说明
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/5/9
 */

class Agent extends WorksAbstract
{
    /**
     * 保存分销的临时提交数据
     */
    public function saveExtraInfo()
    {
        $worksExtra = json_decode($this->work_extra, true);
        //$worksExtra = json_decode($this->test(), true);
        if(empty($this->agent_id)) {
            Helper::apiThrowException('60035',__FILE__.__LINE__);
        }
        if(empty($this->mid)) {
            Helper::apiThrowException('60036',__FILE__.__LINE__);
        }

        $saveData['user_type'] = CHANEL_TERMINAL_AGENT;
        $saveData['user_id'] = $this->agent_id;
        $saveData['prj_id'] = $this->works_id;
        if(isset($worksExtra['user_name'])) {
            $saveData['prj_outer_account'] = $worksExtra['user_name'];
        }
        if(isset($worksExtra['order_id']) && !empty($worksExtra['order_id'])) {
            $saveData['order_no'] = $worksExtra['order_id'];

            $res = app(SaasDiyAssistantRepository::class)->getOrderCacheData($saveData['order_no'],$saveData['user_id']);
            if(!empty($res)) {
                if (isset($res[0]['order_info'])) {
                    $orderData = json_decode($res[0]['order_info'], true);
                    if ($orderData['success'] == 'true' && isset($orderData['result']['trade'])) {
                        $saveData['prj_outer_account'] = $orderData['result']['trade']['buyer_nick'];
                    }
                }

            }

        }

        if(isset($worksExtra['full_name'])) {
            $saveData['prj_rcv_user'] = $worksExtra['full_name'];
        }

        if(isset($worksExtra['tel_num'])) {
            $saveData['prj_rcv_phone'] = $worksExtra['tel_num'];
        }

        if(isset($worksExtra['address_detail'])) {
            $saveData['prj_rcv_address'] = $worksExtra['address_detail'];
        }
        if(isset($worksExtra['location_id_str'])) {
            $arrLocation = json_decode($worksExtra['location_id_str'], true);
            $saveData['prj_province'] = isset($arrLocation[0]['id']) ? $arrLocation[0]['id'] :0;
            $saveData['prj_city']     =isset($arrLocation[1]['id']) ? $arrLocation[1]['id'] :0;
            $saveData['prj_district'] = isset($arrLocation[2]['id']) ? $arrLocation[2]['id'] :0;
        }

        if(isset($worksExtra['buy_quantity'])) {
            $saveData['ord_quantity'] = empty($worksExtra['buy_quantity']) ? 1 :$worksExtra['buy_quantity'];
        }

        $extInfo = $this->repoWorksExt->getRow(['prj_id' =>$this->works_id ]);

        //新增
        if (empty($extInfo)) {
            $saveData['created_at'] = time();
            $this->repoWorksExt->insert($saveData);
        } else {
            $saveData['updated_at'] = time();
            $this->repoWorksExt->update(['prj_info_id' => $extInfo['prj_info_id']],$saveData);
        }
    }

    private function test()
    {
        $json_addr = json_encode( [
            ['id' =>320000, 'area_name' => '江苏省'],
            ['id' =>320400, 'area_name' => '常州市'],
            ['id' =>320412, 'area_name' => '武进区'],

        ]);
        $data = [
            'user_name' => 'talent003',
            'order_id'  => '18234324123123',
            'tel_num'   =>'15915774779',
            'remark'    => '弄好点哦',
            'full_name' => "严小山",
            'address_detail'    =>'天盈创意园1103',
            'location_id_str'   => $json_addr,
            'buy_quantity'      =>2,
        ];

        return json_encode($data);
    }
}