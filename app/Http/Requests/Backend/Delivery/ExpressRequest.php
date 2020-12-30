<?php

namespace App\Http\Requests\Backend\Delivery;

use App\Http\Requests\BaseRequest;

class ExpressRequest extends BaseRequest
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
            'express_name' => 'max:150',
            'express_desc' => 'max:255',
			'express_logo' => 'max:255',
			'express_code' => 'max:20',
            'express_type' => 'integer',
            'express_status' => 'integer',
        ];
    }
}
