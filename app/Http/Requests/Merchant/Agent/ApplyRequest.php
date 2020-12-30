<?php

namespace App\Http\Requests\Merchant\Agent;

use App\Http\Requests\BaseRequest;

class ApplyRequest extends BaseRequest
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
			'agent_name' => 'required|max:50',
			'agent_type' => 'required|integer',
			'agent_logo' => 'max:255',
			'agent_business' => '|max:100',
			'agent_desc' => '|max:255',
			'agent_linkman' => 'required|max:50',
			'mobile' => 'required|max:20',
			'telephone' => '|max:20',
			'wechat' => '|max:30',
			'email' => '|max:30',
			'province' => 'required|integer',
			'city' => 'required|integer',
			'district' => 'required|integer',
			'address' => 'max:120',
			'is_create_adm' => '',
			'review_status' => 'required|integer',
			'review_failed_msg' => '|max:255',
			'deleted_at' => '',

        ];
    }
}
