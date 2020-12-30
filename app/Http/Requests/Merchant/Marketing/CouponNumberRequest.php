<?php

namespace App\Http\Requests\Merchant\Marketing;

use App\Http\Requests\BaseRequest;

class CouponNumberRequest extends BaseRequest
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
            'cou_id' => '',
			'cou_num_code' => '',
			'cou_num_money' => 'required',
			'cou_num_is_used' => 'required|integer',
			'user_id' => '|integer',
			'order_num' => '|max:50',
			'cou_num_use_time' => '|integer',
			'deleted_at' => '|integer',

        ];
    }
}
