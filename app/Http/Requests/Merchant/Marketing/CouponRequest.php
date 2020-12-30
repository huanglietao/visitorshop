<?php

namespace App\Http\Requests\Merchant\Marketing;

use App\Http\Requests\BaseRequest;

class CouponRequest extends BaseRequest
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
			'sales_chanel_id' => 'required|integer',
			'goods_id' => '|max:50',
			'goods_category_id' => '|max:50',
			'cou_use_limits' => 'required|integer',
			'cou_name' => 'required|max:50',
			'cou_desc' => '|max:120',
			'cou_type' => 'required|integer',
			'cou_distribution_method' => 'required|integer',
			'cou_use_times' => 'integer',
			'cou_denomination' => '',
			'cou_use_rule' => 'required|integer',
			'cou_min_consumption' => '',
			'cou_nums' => 'required|integer',
			'cou_score' => '',
			'cou_start_time' => '',
			'cou_end_time' => '',
			'deleted_at' => '',

        ];
    }
}
