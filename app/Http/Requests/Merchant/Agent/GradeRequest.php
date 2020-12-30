<?php

namespace App\Http\Requests\Merchant\Agent;

use App\Http\Requests\BaseRequest;

class GradeRequest extends BaseRequest
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
			'cust_lv_name' => 'required|max:50',
			'cust_lv_desc' => 'required|max:255',
			'cust_lv_discount' => 'required|integer',
			'sort' => '',
			'deleted_at' => '',

        ];
    }
}
