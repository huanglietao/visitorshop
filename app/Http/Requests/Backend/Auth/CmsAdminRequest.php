<?php

namespace App\Http\Requests\Backend\Auth;

use App\Http\Requests\BaseRequest;

class CmsAdminRequest extends BaseRequest
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
            'cms_adm_username' => 'required|max:15',
			'cms_adm_nickname' => '|max:20',
			'cms_adm_password' => 'max:16',
			//'cms_adm_salt' => '|max:30',
			//'cms_adm_avatar' => '|max:100',
			//'cms_adm_email' => '|max:100',
			//'cms_adm_mobile' => 'required|max:15',
			//'cms_adm_status' => 'required|integer',
			//'cms_adm_logintime' => '|integer',
			'cms_adm_group_id' => 'required',
			//'deleted_at' => '|integer',

        ];
    }
    /**
     * 提示消息
     */
    public function messages(){
        return [
//汉字提示，默认英文，可选
//            验证字段.验证规则 => 所提示的汉字
            'cms_adm_username.max' => '用户名长度不能超过15个字符',
            'cms_adm_nickname.max' => '用户名长度不能超过20个字符',
            'cms_adm_password.max' => '密码长度不能超过16个字符',
            'cms_adm_group_id.required' => '请选择所属的角色组',
        ];
    }


}
