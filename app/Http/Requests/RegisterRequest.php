<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
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
            'terms' => ['required', 'accepted'],
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
            'name.required' => '请输入昵称',
            'name.min' => '昵称至少需要 2 个字符',
            'name.max' => '昵称最多 100 个字符',
            'email.required' => '请输入邮箱地址',
            'email.email' => '请输入有效的邮箱地址',
            'email.unique' => '该邮箱已被注册',
            'email.max' => '邮箱地址最多 255 个字符',
            'password.required' => '请输入密码',
            'password.min' => '密码至少 8 个字符',
            'password.max' => '密码最多 100 个字符',
            'password.confirmed' => '两次输入的密码不一致',
            'password.regex' => '密码必须包含大小写字母和数字',
            'terms.required' => '请阅读并同意服务条款',
            'terms.accepted' => '请阅读并同意服务条款',
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
            'name' => '昵称',
            'email' => '邮箱',
            'password' => '密码',
            'terms' => '服务条款',
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

        // 规范化昵称
        if ($this->has('name')) {
            $this->merge([
                'name' => trim($this->name),
            ]);
        }
    }
}
