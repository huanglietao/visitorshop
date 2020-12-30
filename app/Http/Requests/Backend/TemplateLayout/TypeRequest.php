<?php

namespace App\Http\Requests\Backend\TemplateLayout;

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
			'temp_layout_type_name' => 'required|max:30',
			'temp_layout_type_intro' => '|max:150',

        ];
    }
}
