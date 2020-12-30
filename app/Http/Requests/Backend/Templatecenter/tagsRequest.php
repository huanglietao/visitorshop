<?php

namespace App\Http\Requests\Backend\Templatecenter;

use App\Http\Requests\BaseRequest;

class TagsRequest extends BaseRequest
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
			'temp_tages_name' => 'required|max:30',
			'temp_tags_sign' => '|max:20',
			'temp_tags_desc' => '|max:150',
        ];
    }

    /**
     * 提示消息
     */
    public function messages(){
        return [
       // 验证字段.验证规则 => 所提示的汉字
            'temp_tages_name.max' => '名称长度不能超过20个字符',
            'temp_tags_sign.max' => '标识长度不能超过15个字符',
            'temp_tags_desc.max' => '描述最多不能超过60个字符',
        ];
    }





}
