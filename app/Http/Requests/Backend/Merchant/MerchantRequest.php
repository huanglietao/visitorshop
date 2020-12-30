<?php

namespace App\Http\Requests\Backend\Merchant;

use App\Http\Requests\BaseRequest;

class MerchantRequest extends BaseRequest
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
            'mch_name' => 'required|max:50',
            'mch_link_name' => 'required|max:50',
            'mch_mobile' => 'required|max:50',
        ];
    }
}
