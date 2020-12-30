<?php

namespace App\Http\Requests\Erp\Reconciliation;

use App\Http\Requests\BaseRequest;

class BillRequest extends BaseRequest
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
            'oid' => 'required|max:255',
			'content' => 'required|max:255',
			'guige' => 'required|max:255',
			'num' => 'required|integer',
			'danwei' => 'required|max:255',
			'danshuanmian' => 'required|max:255',
			'price' => 'required|max:255',
			'other' => 'required|max:255',
			'jiagong' => 'required|max:255',
			'zherang' => 'required|max:255',
			'heji' => 'required|max:255',
			'beizhu' => 'required|max:255',
			'deleted_at' => '',

        ];
    }
}
