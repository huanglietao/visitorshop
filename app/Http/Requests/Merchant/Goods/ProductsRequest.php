<?php

namespace App\Http\Requests\Merchant\Goods;

use App\Http\Requests\BaseRequest;

class ProductsRequest extends BaseRequest
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
			'prod_name' => 'required|max:255',
			/*'prod_title' => '|max:255',
			'prod_sn' => '|max:50',
			'prod_unit' => '|max:20',
			'prod_stock_status' => 'required|integer',
			'prod_stock_inventory' => 'required|integer',
			'prod_stock_waring' => 'required|integer',
			'prod_brand_id' => 'required|integer',
			'prod_express_type' => 'required|integer',
			'prod_express_fee' => 'required',
			'prod_express_tpl_id' => 'required|integer',
			'prod_details_pc' => '',
			'prod_details_mobile' => '',
			'prod_return_flag' => '|max:50',
			'prod_comment_flag' => '|max:50',
			'prod_onsale_status' => 'required|integer',
			'prod_onsale_issingle' => 'required|integer',
			'prod_price_type' => 'required|integer',
			'prod_integral_sale' => 'required|integer',
			'prod_integral_level' => 'required|integer',
			'prod_keywords' => '|max:100',
			'prod_remark' => '|max:255',
			'sort' => 'required|integer',
			'deleted_at' => '|integer',*/

        ];
    }

    public function messages(){
        return [
            'prod_name.required' => '请先填写商品名称',
        ];
    }

}
