<?php

namespace App\Http\Requests\Backend\System;

use App\Http\Requests\BaseRequest;

class SmtpRequest extends BaseRequest
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
            'smtp_address' => 'required|max:150',
			'smtp_port' => 'required|integer',
			'smtp_username' => 'max:50',
			'smtp_password' => 'max:50',
			'sender' => 'required|max:150',
			'connecttype' => 'required|integer',
			'scene' => 'required|integer',
			'deleted_at' => '',

        ];
    }
}
