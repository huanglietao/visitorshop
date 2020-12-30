<?php
namespace App\Http\Requests\Api\Material;

use App\Http\Requests\Api\BaseRequest;

/**
 * getMaterialList接口的验证器
 *
 * 功能详细说明
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/5/3
 */
class GetMaterialListRequest extends BaseRequest
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
            'material_kind' => 'required',
            //'page_type' => 'required',
            'style_id'      => 'required',
            'tid'           => 'required',
            'size'          => 'required',
            'index'         => 'required',

        ];
    }
}