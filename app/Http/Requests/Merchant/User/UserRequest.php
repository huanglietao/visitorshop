<?php

namespace App\Http\Requests\Merchant\User;

use App\Http\Requests\BaseRequest;

class UserRequest extends BaseRequest
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
			'cust_lv_id' => 'required|',
			'user_name' => 'required|max:100',
			'user_nickname' => 'required|max:100',
			'password' => 'max:100',
			'user_mobile' => 'max:25',
			'user_email' => 'max:50',
			'user_avatar' => 'max:255',
			'user_birthday' => '',
			'balance' => '',
			'score' => '',
			'status' => '',
			'deleted_at' => '',

        ];
    }
}
