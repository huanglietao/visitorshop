<?php

namespace App\Http\Requests\Merchant\Goods;

use App\Http\Requests\BaseRequest;

class ProductsAttributeRequest extends BaseRequest
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
			'cate_id' => 'required|integer',
			'attr_name' => 'required|max:255',
        ];
    }
    public function messages(){
        return [
            'cate_id.required' => '请先选择所属分类',
            'attr_name.required' => '请先填写属性名称',
        ];
    }
}
