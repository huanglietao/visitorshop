<?php

namespace App\Http\Requests\Backend\Goods;

use App\Http\Requests\BaseRequest;

class ProductsSizeInfoRequest extends BaseRequest
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
            'size_design_w'     => 'require|number',
            'size_design_h'    => 'require|number',
            'size_location_top'     => 'require|number',
            'size_location_left'    => 'require|number',
            'size_tip_top'        => 'require|number',
            'size_tip_bottom'      => 'require|number',
            'size_tip_left'          => 'require|number',
            'size_tip_right'        => 'require|number',
            'size_cut_top'        => 'require|number',
            'size_cut_bottom'       => 'require|number',
            'size_cut_left'         => 'require|number',
            'size_cut_right'       => 'require|number',

        ];
    }
    public function messages(){
        return [
            'size_design_w.require'       => '请填写完整参数',
            'size_design_w.number'        => '参数必须为数字，不能有空格',
            'size_design_h.require'      => '请填写完整参数',
            'size_design_h.number'       => '参数必须为数字，不能有空格',
            'size_location_top.require'       => '请填写完整参数',
            'size_location_top.number'        => '参数必须为数字，不能有空格',
            'size_location_left.require'      => '请填写完整参数',
            'size_location_left.number'       => '参数必须为数字，不能有空格',
            'size_tip_top.require'          => '请填写完整参数',
            'size_tip_top.number'           => '参数必须为数字，不能有空格',
            'size_tip_bottom.require'        => '请填写完整参数',
            'size_tip_bottom.number'         => '参数必须为数字，不能有空格',
            'size_tip_left.require'            => '请填写完整参数',
            'size_tip_left.number'             => '参数必须为数字，不能有空格',
            'size_tip_right.require'          => '请填写完整参数',
            'size_tip_right.number'           => '参数必须为数字，不能有空格',
            'size_cut_top.require'          => '请填写完整参数',
            'size_cut_top.number'           => '参数必须为数字，不能有空格',
            'size_cut_bottom.require'         => '请填写完整参数',
            'size_cut_bottom.number'          => '参数必须为数字，不能有空格',
            'size_cut_left.require'           => '请填写完整参数',
            'size_cut_left.number'            => '参数必须为数字，不能有空格',
            'size_cut_right.require'         => '请填写完整参数',
            'size_cut_right.number'          => '参数必须为数字，不能有空格',
        ];
    }

}
