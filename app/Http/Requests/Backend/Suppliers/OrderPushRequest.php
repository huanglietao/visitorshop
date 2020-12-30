<?php

namespace App\Http\Requests\Backend\Suppliers;

use App\Http\Requests\BaseRequest;

class OrderPushRequest extends BaseRequest
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
            'mch_id' => 'required|integer',
			'order_id' => 'required|integer',
			'order_push_status' => 'required',
			'err_msg' => '|max:255',
			'start_time' => '|integer',
			'end_time' => '|integer',
			'deleted_at' => '|integer',

        ];
    }
}
