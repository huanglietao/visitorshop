<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

/**
 * 表单验证基础类
 *
 * 主要重写Request基础类的一些方法，例如json返回格式重写
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/8/8
 */
class BaseRequest extends FormRequest
{
    public function failedValidation( \Illuminate\Contracts\Validation\Validator $validator )
    {
        exit(json_encode(array(
            'success' => 'false',
            'code'    => 442,
            'message' => '表单字段验证失败!',
            'errors' => $validator->getMessageBag()->toArray()
        )));
    }
}