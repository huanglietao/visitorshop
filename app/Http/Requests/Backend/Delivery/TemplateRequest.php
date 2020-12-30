<?php

namespace App\Http\Requests\Backend\Delivery;

use App\Http\Requests\BaseRequest;

class TemplateRequest extends BaseRequest
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
            'del_temp_name' => 'max:100',
            'del_temp_desc' => 'max:255',
            'del_temp_delivery_list' => 'max:255',
            'del_temp_area_conf' => '',
            'del_temp_status' => 'integer',
        ];
    }
}
