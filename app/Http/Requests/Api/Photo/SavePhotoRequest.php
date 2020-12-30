<?php
namespace App\Http\Requests\Api\Photo;

use App\Http\Requests\Api\BaseRequest;

/**
 * 用户上传照片相关验证器
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/5/8
 */
class SavePhotoRequest extends BaseRequest
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
            'ph_id' => 'required',
            //'page_type' => 'required',
            'uid' => 'required|integer',
            'sp_id' => 'required|integer',
            'wid' => 'required|integer',
            //'org_name' => 'required',
            'org_width' => 'required|numeric',
            'org_height' => 'required|numeric',
            'org_size' => 'sometimes|required|numeric',
            //'ext' => 'required',
            //'url' => 'required',
        ];
    }
}