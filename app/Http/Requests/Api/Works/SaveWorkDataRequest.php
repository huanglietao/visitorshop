<?php
namespace App\Http\Requests\Api\Works;

use App\Http\Requests\Api\BaseRequest;


/**
 * 作品接口相关验证器
 *
 * 功能详细说明
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/5/9
 */
class SaveWorkDataRequest extends BaseRequest
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
            'wid' => 'required',
            //'page_type' => 'required',
            'sp_id'             => 'required',
            'pid'               => 'required',
            'work_name'         => 'required',
            'pages'             => 'required',
        ];
    }
}