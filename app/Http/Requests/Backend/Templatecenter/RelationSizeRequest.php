<?php

namespace App\Http\Requests\Backend\Templatecenter;

use App\Http\Requests\BaseRequest;

class RelationSizeRequest extends BaseRequest
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

        ];
    }







}
