<?php
namespace App\Http\Requests\Api\Template;

use App\Http\Requests\Api\BaseRequest;

/**
 * 保存布局数据接口数据验证
 *
 * 保存布局数据接口数据验证
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/5/7
 */
class saveLayoutDataRequest extends BaseRequest
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
            'id' => 'required',
            //'page_type' => 'required',
            'mask_count' => 'required',
            'page_width' => 'required',
            'page_height' => 'required',
            'dpi' => 'required|integer',
        ];
    }
}