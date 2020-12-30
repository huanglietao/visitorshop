<?php

namespace App\Http\Requests\Erpkf\Auth;

use App\Http\Requests\BaseRequest;

class KfUsersRequest extends BaseRequest
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
            'username' => 'required|max:20',
			//'nickname' => 'required|max:50',
			//'password' => 'required|max:32',
			//'salt' => 'required|max:30',
			//'avatar' => 'required|max:100',
			//'email' => 'required|max:100',
			//'logintime' => 'required|integer',
			//'token' => 'required|max:100',
			//'status' => 'required|integer',

        ];
    }
}
