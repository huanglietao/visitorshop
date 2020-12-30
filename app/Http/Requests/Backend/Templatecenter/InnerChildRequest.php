<?php

namespace App\Http\Requests\Backend\Templatecenter;

use App\Http\Requests\BaseRequest;

class InnerChildRequest extends BaseRequest
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
            'inner_page_name' => 'required|max:30',
            /* 'main_temp_sort' => '|integer',
             'inner_temp_id' => 'required|integer',
             'cover_temp_id' => 'required|integer',
             'goods_type_id' => 'required|integer',
             'main_temp_theme_id' => 'required|integer',
             'specifications_id' => 'required|integer',
             'main_temp_description' => '|max:150',

             'main_temp_thumb' => '|max:200',
             'main_temp_photo_count' => '|integer',
             'main_temp_status' => 'required|integer',
             'main_temp_is_vip' => 'required|integer',
             'main_temp_check_status' => 'required|integer',
             'main_temp_is_ads_display' => 'required|integer',
             'main_temp_start_year' => '|integer',
             'temp_tag' => '|max:100',
             'main_temp_min_photo' => '|integer',
             'main_temp_max_photo' => '|integer',
             'main_temp_no' => '|max:100',
             'main_temp_use_times' => 'required|integer',
             'main_temp_avg_photo' => '',
             'deleted_at' => '|integer',*/

        ];
    }

    /**
     * 提示消息
     */
    public function messages(){
        return [
          //  验证字段.验证规则 => 所提示的汉字
            'inner_page_name.max' => '子页名称长度不能超过30个字符',
           // 'main_temp_sort.integer' => '排序必须为数字',
        ];
    }



}
