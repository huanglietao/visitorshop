<?php
namespace App\Http\Requests\Api\Template;

use App\Http\Requests\Api\BaseRequest;

/**
 * 保存模模数据验证器
 *
 * 功能详细说明
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/5/6
 */
class saveTemplateDataRequest extends BaseRequest
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
            'tid' => 'required',
            //'page_type' => 'required',
            'pages' => 'required',
            'mask_total_count' => 'required'
        ];
    }
}