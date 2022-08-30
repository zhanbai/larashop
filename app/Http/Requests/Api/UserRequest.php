<?php

namespace App\Http\Requests\Api;

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->path()) {
            case 'api/users':
                return [
                    'phone' => 'required|phone:CN,mobile',
                    'sms_code' => 'required|string',
                ];
                break;
            case 'api/user/update':
                $userId = auth('api')->id();

                return [
                    'name' => 'between:3,25',
                    'email' => 'email|unique:users,email,' . $userId,
                    'introduction' => 'max:80',
                    'avatar_image_id' => 'exists:images,id,type,avatar,user_id,' . $userId,
                ];
                break;
        }
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'phone'           => '手机号',
            'name'            => '昵称',
            'sms_code'        => '短信验证码',
            'email'           => '邮箱',
            'introduction'    => '介绍',
            'avatar_image_id' => '头像图片 ID'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }
}
