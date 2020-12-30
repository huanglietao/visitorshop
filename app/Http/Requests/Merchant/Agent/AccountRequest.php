<?php

namespace App\Http\Requests\Merchant\Agent;

use App\Http\Requests\BaseRequest;

class AccountRequest extends BaseRequest
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
            'mch_id' => '',
			'agent_info_id' => '|integer',
			'is_main' => '|integer',
			'dms_adm_username' => 'required|max:11',
			'dms_adm_nickname' => '|max:50',
			'dms_adm_password' => 'max:50',
			'dms_adm_salt' => '|max:10',
			'dms_adm_avattar' => '|max:100',
			'dms_adm_email' => '|max:100',
			'dms_adm_mobile' => '|max:15',
			'dms_adm_logintime' => '|integer',
			'dms_adm_status' => 'required|integer',
			'dms_adm_group_id' => '|integer',
			'deleted_at' => '|integer',

        ];
    }
}
