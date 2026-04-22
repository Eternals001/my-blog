<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password as PasswordRule;

class UpdatePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'current_password' => [
                'required',
                'string',
                'current_password:web',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:100',
                'confirmed',
                'different:current_password',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'current_password.required' => '请输入当前密码',
            'current_password.current_password' => '当前密码不正确',
            'password.required' => '请输入新密码',
            'password.min' => '密码至少 8 个字符',
            'password.max' => '密码最多 100 个字符',
            'password.confirmed' => '两次输入的密码不一致',
            'password.different' => '新密码不能与当前密码相同',
            'password.regex' => '密码必须包含大小写字母和数字',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'current_password' => '当前密码',
            'password' => '新密码',
            'password_confirmation' => '确认密码',
        ];
    }
}
