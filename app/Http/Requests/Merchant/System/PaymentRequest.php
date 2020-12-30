<?php

namespace App\Http\Requests\Merchant\System;

use App\Http\Requests\BaseRequest;

class PaymentRequest extends BaseRequest
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
            'pay_name' => 'required|max:30',
            'pay_class_name' => 'max:30',
            'pay_desc' => 'max:255',
            'pay_logo' => 'max:255',
            'pay_note' => 'max:255',
            'pay_status' => 'integer',
            'pay_config_param' => '',

        ];
    }
}
