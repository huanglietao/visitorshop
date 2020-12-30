<?php

namespace App\Http\Requests\Merchant\Goods;

use App\Http\Requests\BaseRequest;

class CustomCategoryRequest extends BaseRequest
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
			'cate_name' => 'required|max:50',
			'cate_status' => 'required|integer',

        ];
    }
}
