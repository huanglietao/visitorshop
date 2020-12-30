<?php

namespace App\Http\Requests\Backend\Advertisement;

use App\Http\Requests\BaseRequest;

class AdPositionRequest extends BaseRequest
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
			'ad_position' => 'required|max:30',
			'channel_id' => 'required',
        ];
    }

    /**
     * 提示消息
     */
    public function messages(){
        return [
            'ad_position.required'   => '广告位置不能为空',
            'ad_position.max'        => '广告位置不能超过30个字符',
            'channel_id.required' => '请选择所属的渠道',
        ];
    }



}
