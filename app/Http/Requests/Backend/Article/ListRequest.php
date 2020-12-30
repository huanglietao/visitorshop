<?php

namespace App\Http\Requests\Backend\Article;

use App\Http\Requests\BaseRequest;

class ListRequest extends BaseRequest
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
			'art_title' => 'required|max:30',
			'art_type' => 'required',
        ];
    }

    /**
     * 提示消息
     */
    public function messages(){
        return [
            'art_title.required'    => '标题不能为空',
            'art_title.max'         => '标题不能超过30个字符',
            'art_type.required'     => '请选择分类',
        ];
    }




}
