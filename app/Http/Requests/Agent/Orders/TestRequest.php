<?php

namespace App\Http\Requests\Agent\Orders;

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
        return false;
    }

    /**
     * 表单验证.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'mid' => 'require',
			'username' => 'require|max:50',
			'nickname' => 'require|max:50',
			'password' => 'require|max:32',
			'salt' => 'require|max:20',
			'is_main' => 'require',
			'token' => 'require|max:50',
			'avatar' => 'require|max:50',

        ];
    }
}
