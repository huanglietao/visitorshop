<?php
namespace  App\Http\Requests\Api\Material;
use App\Http\Requests\Api\BaseRequest;


/**
 * 功能简介
 *
 * 功能详细说明
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/27
 */
class UploadRequest extends BaseRequest
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
            'type' => 'required',
            //'page_type' => 'required',
            'm_type' => 'required',

        ];
    }
}