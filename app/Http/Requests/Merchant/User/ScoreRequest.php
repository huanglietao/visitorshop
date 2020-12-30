<?php

namespace App\Http\Requests\Merchant\User;

use App\Http\Requests\BaseRequest;

class ScoreRequest extends BaseRequest
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
            'mch_id' => '',
			'score_rule_name' => 'required|max:255',
			'score_rule_way' => 'required|integer',
			'score_rule_money' => '',
			'score_rule_score' => 'required|integer',
			'score_rule_status' => 'required|integer',
			'deleted_at' => '',

        ];
    }
}
