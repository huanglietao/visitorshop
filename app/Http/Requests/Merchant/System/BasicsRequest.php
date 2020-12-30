<?php

namespace App\Http\Requests\Merchant\system;

use App\Http\Requests\BaseRequest;

class BasicsRequest extends BaseRequest
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
            'mch_id' => 'integer',
			'oms_name' => 'max:50',
			'dms_name' => 'max:50',
			'oms_record_num' => 'max:50',
			'oms_copyright' => 'max:255',
			'oms_linkman' => 'max:30',
			'oms_mobile' => 'max:11',
			'oms_address' => 'max:30',
			'oms_balance_reminder' => 'max:11',
			'deleted_at' => 'integer',

        ];
    }
}
