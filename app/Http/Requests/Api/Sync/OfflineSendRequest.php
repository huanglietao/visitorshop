<?php
namespace App\Http\Requests\Api\Sync;

use App\Http\Requests\Api\BaseRequest;

/**
 * 发货回写接口验证器
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/5/19
 */
class OfflineSendRequest extends BaseRequest
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
            'order_no' => 'required',
            //'page_type' => 'required',
            'agent_id' => 'required',
            'out_sid' => 'required',
            'company_code' => 'required',
        ];
    }
}