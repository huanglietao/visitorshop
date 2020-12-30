<?php

namespace App\Http\Requests\Merchant\Agent;

use App\Http\Requests\BaseRequest;

class InfoRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * 表单验证.
     *
     * @return array
     */
    public function rules()
    {
        return [
			'cust_lv_id' => 'required|integer',
			'agent_name' => 'required|max:50',
			'agent_balance' => '',
			'agent_type' => 'required|integer',
			'agent_logo' => '|max:255',
			'agent_business' => 'max:100',
			'agent_desc' => '|max:255',
			'agent_linkman' => 'required|max:50',
			'mobile' => 'required|max:20',
			'telephone' => '|max:20',
			'wechat' => '|max:30',
			'email' => '|max:30',
			'province' => '',
			'city' => '',
			'district' => '',
			'address' => '|max:120',
			'deleted_at' => '',

        ];
    }
}
