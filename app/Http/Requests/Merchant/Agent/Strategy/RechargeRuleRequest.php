<?php

namespace App\Http\Requests\Merchant\Agent\Strategy;

use App\Http\Requests\BaseRequest;

class RechargeRuleRequest extends BaseRequest
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
			'rec_rule_name' => 'required|max:100',
			'recharge_fee' => 'required',
			'present_fee' => 'required',
        ];
    }
}
