<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;

class SendReviewRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'reviews'          => ['required', 'array'],
            'reviews.*.id'     => [
                'required',
                Rule::exists('order_items', 'id')->where('order_id', $this->route('order')->id)
            ],
            'reviews.*.rating' => ['required', 'integer', 'between:1,5'],
            'reviews.*.review' => ['required'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'reviews'          => '评价信息',
            'reviews.*.id'     => '订单 ID',
            'reviews.*.rating' => '评分',
            'reviews.*.review' => '评价内容',
        ];
    }
}
