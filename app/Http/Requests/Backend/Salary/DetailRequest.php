<?php

namespace App\Http\Requests\Backend\Salary;

use App\Http\Requests\BaseRequest;

class DetailRequest extends BaseRequest
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
            'salary_detail_id' => 'required|integer',
			'workers_name' => 'required|max:255',
			'salary_worker_position' => 'required|integer',
			'shift' => '|integer',
			'finish_time' => '|integer',
			'output_totals' => '|integer',
			'univalence' => '',
			'salary' => '',
			'deleted_at' => '|integer',

        ];
    }
}
