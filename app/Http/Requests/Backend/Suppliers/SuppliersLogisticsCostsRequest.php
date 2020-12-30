<?php

namespace App\Http\Requests\Backend\Suppliers;

use App\Http\Requests\BaseRequest;

class SuppliersLogisticsCostsRequest extends BaseRequest
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
            'sup_id' => 'integer',
			'sup_log_cos_area_conf'=>''

        ];
    }
}
