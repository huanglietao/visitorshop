<?php

namespace App\Http\Requests\Backend\Auth;

use App\Http\Requests\BaseRequest;

class RuleRequest extends BaseRequest
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
           /* 'cms_auth_rule_type' => 'required',
			'cms_auth_rule_pid' => 'required|integer',
			'cms_auth_rule_name' => 'required|max:100',
			'cms_auth_rule_title' => 'required|max:50',
			'cms_auth_rule_icon' => 'required|max:50',
			'cms_auth_rule_condition' => 'required|max:255',
			'cms_auth_rule_remark' => 'required',
			'cms_auth_rule_ismenu' => 'required|integer',
			'cms_auth_rule_weigh' => 'required|integer',
			'cms_auth_rule_status' => 'required|max:30',
			'deleted_at' => '|integer',*/

        ];
    }
}
