<?php

namespace App\Http\Requests\Erp\Order;

use App\Http\Requests\BaseRequest;

class ImportRequest extends BaseRequest
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
//            'partner_number' => 'required|max:255',
//			'is_collect' => 'required|max:255',
//			'partner_real_name' => 'required|max:255',
//			'product_code' => 'required|max:255',
//			'single_num' => 'required',
//			'assign_express_type' => 'required|max:255',
//			'recipient_person' => 'required|max:255',
//			'recipient_phone' => 'required|max:255',
//			'recipient_address' => 'required|max:255',
//			'sender_person' => 'required|max:255',
//			'sender_phone' => 'required|max:255',
//			'sender_address' => 'required|max:255',
//			'note' => 'required|max:255',
//			'is_hurry' => 'required|max:255',
//			'deleted_at' => '|integer',

        ];
    }
}
