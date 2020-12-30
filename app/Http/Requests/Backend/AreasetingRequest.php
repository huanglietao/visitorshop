<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

class AreasetingRequest extends BaseRequest
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
          //  'name' => 'required|max:30',
		//	'pid' => 'required|integer',
		//	'sname' => 'required|max:30',
			//'level' => 'required|integer',
		//	'citycode' => 'required|max:20',
		//	'yzcode' => 'required|max:20',
		//	'mername' => 'required|max:150',
		//	'deleted_at' => '',

        ];
    }
}
