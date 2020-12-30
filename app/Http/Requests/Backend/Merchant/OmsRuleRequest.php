<?php

namespace App\Http\Requests\Backend\Merchant;

use App\Http\Requests\BaseRequest;

class OmsRuleRequest extends BaseRequest
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
          /*  'oms_auth_rule_type' => 'required',
			'oms_auth_rule_pid' => 'required|integer',
			'oms_auth_rule_name' => 'required|max:100',
			'oms_auth_rule_title' => 'required|max:50',
			'oms_auth_rule_icon' => 'required|max:50',
			'oms_auth_rule_condition' => 'required|max:255',
			'oms_auth_rule_remark' => 'required',
			'oms_auth_rule_ismenu' => 'required|integer',
			'oms_auth_rule_weigh' => 'required|integer',
			'oms_auth_rule_status' => 'required|max:30',
			'deleted_at' => '|integer',*/

        ];
    }
}
