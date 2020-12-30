<?php

namespace App\Http\Requests\Agent;

use App\Http\Requests\BaseRequest;

class TestRequest extends BaseRequest
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
            'mid' => 'required',
			'username' => 'required|max:50',
			'nickname' => 'required|max:50',
			'password' => 'required|max:32',
			'salt' => 'required|max:20',
			'is_main' => 'required',
			'token' => 'required|max:50',
			'avatar' => 'required|max:50',
			'deleted_at' => '',

        ];
    }
}
