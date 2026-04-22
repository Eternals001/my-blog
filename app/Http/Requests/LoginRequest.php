<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginRequest extends FormRequest
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
            ],
            'password' => [
                'required',
                'string',
            ],
            'remember' => [
                'sometimes',
                'boolean',
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
            'password.required' => '请输入密码',
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
            'password' => '密码',
            'remember' => '记住我',
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

    /**
     * Get the validated data with defaults.
     *
     * @return array<string, mixed>
     */
    public function validatedWithDefaults(): array
    {
        $validated = $this->validated();

        return array_merge([
            'remember' => false,
        ], $validated);
    }
}
