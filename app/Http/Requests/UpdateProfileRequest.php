<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore(auth()->id()),
            ],
            'bio' => [
                'nullable',
                'string',
                'max:500',
            ],
            'avatar' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:2048', // 2MB
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
            'name.required' => '请输入昵称',
            'name.min' => '昵称至少 2 个字符',
            'name.max' => '昵称最多 100 个字符',
            'email.required' => '请输入邮箱地址',
            'email.email' => '请输入有效的邮箱地址',
            'email.unique' => '该邮箱已被其他用户使用',
            'email.max' => '邮箱地址最多 255 个字符',
            'bio.max' => '个人简介最多 500 个字符',
            'avatar.image' => '头像必须是图片文件',
            'avatar.mimes' => '头像支持 jpeg、png、jpg、gif 格式',
            'avatar.max' => '头像文件最大 2MB',
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
            'bio' => '个人简介',
            'avatar' => '头像',
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
