<?php

namespace App\Http\Requests\Backend\TemplateLayout;

use App\Http\Requests\BaseRequest;

class MainRequest extends BaseRequest
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
			'temp_layout_name' => 'required|max:30',
			'temp_layout_type' => 'required|integer',
			'goods_type_id' => 'required|integer',
			'specifications_id' => 'required|integer',
        ];
    }

    /**
     * 提示消息
     */
    public function messages(){
        return [
         //  验证字段.验证规则 => 所提示的汉字
            'temp_layout_name.required'   => '布局名称不能为空',
           // 'temp_layout_name.max'       => '名称长度不能超过30个字符',
            'temp_layout_type.required'  => '布局版式不能为空',
            'goods_type_id.required'     => '商品分类不能为空',
            'specifications_id.required' => '请选择所属规格',
        ];
    }





}
