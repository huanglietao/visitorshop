<?php

namespace App\Http\Requests\Backend\Templatecenter;

use App\Http\Requests\BaseRequest;

class InnerRequest extends BaseRequest
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
          /*  'mch_id' => 'required|integer',
			'inner_temp_name' => 'required|max:255',
			'goods_type_id' => 'required|integer',
			'inner_temp_theme_id' => 'required|integer',
			'specifications_id' => 'required|integer',
			'inner_temp_no' => '|max:100',
			'inner_temp_desc' => '|max:150',
			'inner_temp_photo_count' => '|integer',
			'inner_temp_sort' => 'required|integer',
			'inner_temp_thumb' => '|max:200',
			'inner_temp_check_status' => 'required|integer',
			'inner_temp_start_year' => '|integer',
			'inner_spec_style' => 'required|integer',
			'inner_temp_status' => 'required|integer',
			'deleted_at' => '|integer',*/

        ];
    }
}
