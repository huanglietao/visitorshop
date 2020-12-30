<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

class CategoryRequest extends BaseRequest
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
            'cate_parent_id' => 'required|integer',
			'cate_name' => 'required|max:50',
			'cate_nickname' => 'required|max:50',
			'cate_unit' => 'required|max:50',
			'cate_keywords' => 'required|max:50',
			'cate_status' => 'required|integer',
			'deleted_at' => '',

        ];
    }
}
