<?php

namespace App\Http\Requests\Backend\OperationLog;

use App\Http\Requests\BaseRequest;

class IndexRequest extends BaseRequest
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
            'cms_name' => 'required|max:50',
            'oms_name' => 'required|max:50',
            'ams_name' => 'required|max:50',

        ];
    }
}
