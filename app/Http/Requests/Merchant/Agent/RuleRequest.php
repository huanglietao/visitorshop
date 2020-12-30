<?php

namespace App\Http\Requests\Merchant\Agent;

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
			'dms_auth_rule_name' => 'required',
			'dms_auth_rule_title' => 'required|max:50',
        ];
    }

    /**
     * 提示消息
     */
    public function messages(){
        return [
            'dms_auth_rule_title.max' => '名称长度不能超过50个字符',
            'dms_auth_rule_title.required' => '名称不能为空',
            'dms_auth_rule_name.required' => '请填写正确的规则',
        ];
    }

}
