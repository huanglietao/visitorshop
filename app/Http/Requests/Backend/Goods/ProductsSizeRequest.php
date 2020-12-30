<?php

namespace App\Http\Requests\Backend\Goods;

use App\Http\Requests\BaseRequest;

class ProductsSizeRequest extends BaseRequest
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
            'size_cate_id' => 'required|integer',
            'size_name' => 'required|max:100',
            'size_dpi' => 'required|integer',
            /*,


			*/

        ];
    }
    public function messages(){
        return [
            'size_cate_id.required' => '请选择所属分类',
            'size_type.required'    => '请选择规格标签',
            'size_name.required'     => '请填写规格名称',
            'size_name.max'         => '规格名称长度不能超过100',
            'size_dpi.required'      => '请填写规格dpi',
            'size_dpi.integer'      => '规格dpi需为正整数',
        ];
    }

}
