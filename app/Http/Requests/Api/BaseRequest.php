<?php
namespace  App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 功能简介
 *
 * 功能详细说明
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/27
 */
class BaseRequest extends FormRequest
{
    public function failedValidation( \Illuminate\Contracts\Validation\Validator $validator )
    {
        exit(json_encode(array(
            'success' => 'false',
            'err_code'    => 10001,
            'err_msg'     => array_values($validator->getMessageBag()->toArray())[0][0],
            'message' => '接口参数不合法!',
            'errors' => array_values($validator->getMessageBag()->toArray())[0][0]
        ),JSON_UNESCAPED_UNICODE));
    }
}