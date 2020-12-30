<?php

namespace App\Http\Requests\Erpkf\Auth;

use App\Http\Requests\BaseRequest;

class GroupRequest extends BaseRequest
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
         /*   'pid' => 'required|integer',
			'name' => 'required|max:50',
			'rules' => 'required',
			'status' => 'required|max:255',
			'deleted_at' => 'required|integer',*/

        ];
    }
}
