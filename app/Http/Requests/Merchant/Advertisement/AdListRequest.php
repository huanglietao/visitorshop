<?php

namespace App\Http\Requests\Merchant\Advertisement;

use App\Http\Requests\BaseRequest;

class AdListRequest extends BaseRequest
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
            /*'mch_id' => 'required|integer',
			'ad_title' => 'required|max:30',
			'channel_id' => 'required|integer',
			'ad_type' => 'required|integer',
			'display_type' => '|integer',
			'ad_position' => 'required|integer',
			'ad_flag' => '|max:50',
			'ad_images' => 'required',
			'ad_url' => '|max:200',
			'ad_sort' => '|integer',
			'deleted_at' => '|integer',*/

        ];
    }
}
