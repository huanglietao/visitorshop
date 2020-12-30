<?php

namespace App\Http\Requests\Merchant\User;

use App\Http\Requests\BaseRequest;

class MoneyRequest extends BaseRequest
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
			'recharge_no' => 'max:50',
			'trade_no' => 'max:50',
			'money_type' => 'integer',
			'amount' => 'required',
			'balance' => 'required',
			'operator' => 'required|max:100',
			'note' => 'max:255',
			'deleted_at' => '',

        ];
    }
}
