<?php

namespace App\Http\Requests\Backend\Template;

use App\Http\Requests\BaseRequest;

class MaterialRequest extends BaseRequest
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
           /* 'mch_id' => 'required|integer',
			'material_type' => 'required|max:30',
			'material_name' => '|max:50',
			'material_cateid' => 'required|integer',
			'specification_id' => 'required|integer',
			'attachment_id' => 'required|integer',
			'material_sort' => 'required|integer',
			'material_status' => 'required|integer',
			'specification_style' => 'required|integer',
			'deleted_at' => '|integer',
			'material_use_type' => 'required|integer',
			'material_from_type' => 'required|integer',
			'template_id' => 'required|integer',
			'material_special_type' => 'required|integer',
			'material_ext_file' => '|max:150',*/

        ];
    }
}
