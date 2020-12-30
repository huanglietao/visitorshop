<?php

namespace App\Http\Requests\Backend\Delivery;

use App\Http\Requests\BaseRequest;

class DeliveryRequest extends BaseRequest
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
            'delivery_name' => 'max:50',
            'delivery_show_name' => 'max:100',
            'delivery_express_list' => 'max:100',
            'delivery_desc' => 'max:255',
            'delivery_is_cash' => 'integer',
            'delivery_status' => 'integer',

        ];
    }
}
