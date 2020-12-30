<?php

namespace App\Http\Requests\Backend\Suppliers;

use App\Http\Requests\BaseRequest;

class SuppliersRequest extends BaseRequest
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
            'mch_id' => 'integer',
			'sup_name' => 'required|max:50',
			'sup_code' => 'required|max:30',
			'sup_contacts' => 'required|max:30',
			'sup_telephone' => 'required|max:30',
			'sup_region' => 'required|max:30',
			'sup_type' => 'required|integer',
			'sup_service_area' => 'max:255',
			'sup_capacity' => '',
			'sup_allocation_quantity' => '',
			'sup_capacity_unit' => 'max:10',

        ];
    }
}
