<?php

namespace App\Http\Requests\Backend\Templatecenter;

use App\Http\Requests\BaseRequest;

class FaceRequest extends BaseRequest
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
			'cover_temp_name' => 'required|max:30',
			'goods_type_id' => 'required|integer',
			'cover_temp_theme_id' => 'required|integer',
			'specifications_id' => 'required|integer',
			//'cover_temp_no' => '|max:100',
			'cover_temp_desc' => '|max:100',
			'cover_temp_photo_count' => '|integer',
			//'cover_temp_sort' => '|integer',
			//'cover_temp_thumb' => '|max:200',
			//'cover_temp_check_status' => 'required|integer',
			//'cover_temp_start_year' => '|integer',
		//	'cover_temp_dpi' => '|integer',
			//'cover_temp_stage' => '',
			//'cover_real_page_w' => '|integer',
			//'cover_real_page_h' => '|integer',
			//'cover_temp_status' => 'required|integer',
			//'deleted_at' => '|integer',

        ];
    }
}
