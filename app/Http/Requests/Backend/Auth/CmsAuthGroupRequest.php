<?php

namespace App\Http\Requests\Backend\Auth;

use App\Http\Requests\BaseRequest;

class CmsAuthGroupRequest extends BaseRequest
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
			'cms_group_name' => 'required|max:30',
			'rules' => 'required',
			'cms_group_status' => 'required',
        ];
    }
}
