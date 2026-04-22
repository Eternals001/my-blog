<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
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
            'email' => [
                'required',
                'email',
                'max:255',
                'exists:users,email',
            ],
            'token' => [
                'required',
                'string',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:100',
                'confirmed',
                // 密码强度：必须包含大小写字母和数字
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
            'email.required' => '请输入邮箱地址',
            'email.email' => '请输入有效的邮箱地址',
            'email.max' => '邮箱地址最多 255 个字符',
            'email.exists' => '该邮箱尚未注册',
            'token.required' => '重置令牌无效',
            'token.string' => '重置令牌格式错误',
            'password.required' => '请输入新密码',
            'password.min' => '密码至少 8 个字符',
            'password.max' => '密码最多 100 个字符',
            'password.confirmed' => '两次输入的密码不一致',
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
            'email' => '邮箱',
            'token' => '重置令牌',
            'password' => '新密码',
            'password_confirmation' => '确认密码',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // 规范化邮箱
        if ($this->has('email')) {
            $this->merge([
                'email' => strtolower(trim($this->email)),
            ]);
        }
    }
}
