<?php

namespace App\Http\Requests\Erp\Finance;

use App\Http\Requests\BaseRequest;

class RecordRequest extends BaseRequest
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
            'prod_id' => 'required|integer',
			'prod_md_path' => 'required|max:200',
			'prod_md_ismain' => 'required|integer',
			'prod_md_type' => 'required|integer',
			'sort' => 'required|integer',
			'deleted_at' => 'required|integer',

        ];
    }
}
