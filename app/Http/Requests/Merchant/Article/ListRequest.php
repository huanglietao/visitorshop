<?php

namespace App\Http\Requests\Merchant\Article;

use App\Http\Requests\BaseRequest;

class ListRequest extends BaseRequest
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
            /*'mch_id' => '|integer',
			'art_title' => 'required|max:100',
			'art_sign' => 'required|max:20',
			'channel_id' => 'required|integer',
			'art_type' => 'required|integer',
			'art_content' => 'required',
			'art_author' => 'required|max:20',
			'art_intro' => '|max:255',
			'art_keywords' => '|max:20',
			'author_email' => '|max:150',
			'art_thumb' => '|max:200',
			'art_views' => '|integer',
			'is_open' => '|integer',
			'is_read' => '|integer',
			'art_type_status' => '|integer',
			'art_link' => '|max:200',
			'art_file_url' => '|max:200',
			'deleted_at' => '|integer',*/

        ];
    }
}
