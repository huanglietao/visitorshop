<?php

namespace App\Http\Requests\Agent;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

/**
 * 表单验证类
 *
 * 实现对应的表单验证控制
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/7/30
 */
class DemoRequest extends BaseRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|max:10',
            'nickname' => 'required|max:12'
        ];
    }
}
