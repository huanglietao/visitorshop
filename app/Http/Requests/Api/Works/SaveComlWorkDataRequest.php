<?php
namespace App\Http\Requests\Api\Works;

use App\Http\Requests\Api\BaseRequest;


/**
 * 作品接口相关验证器
 *
 * 功能详细说明
 * @author: david
 * @version: 1.0
 * @date: 2020/7/7
 */
class SaveComlWorkDataRequest extends BaseRequest
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
            'works_id'     => 'required',
            'temp_id'      => 'required',
            'works_name'   => 'required',
            'is_submit'    => 'required',
            'is_mobile'    => 'required',
            'orig_data'    => 'required',
        ];
    }
}