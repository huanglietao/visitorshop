<?php

namespace App\Http\Requests\Backend\Article;

use App\Http\Requests\BaseRequest;

class TypeRequest extends BaseRequest
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
			'art_type_name' => 'required|max:30',
			'art_type_sign' => '|max:20',
			'channel_id' => 'required|integer',
        ];
    }

    /**
     * 提示消息
     */
    public function messages(){
        return [
            'art_type_name.required'   => '分类名称不能为空',
            'art_type_name.max'        => '分类名称不能超过30个字符',
            'channel_id.required'      => '请选择所属的渠道',
           // 'art_type_sign.required'   => '分类标识不能为空',
            'art_type_sign.max'        => '分类标识不能超过20个字符',
        ];
    }



}
