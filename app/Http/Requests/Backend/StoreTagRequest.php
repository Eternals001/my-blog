<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTagRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create tags');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'slug' => ['nullable', 'string', 'max:50', Rule::unique('tags', 'slug')],
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
            'name.required' => '请输入标签名称',
            'name.max' => '标签名称不能超过 50 个字符',
            'slug.unique' => '该 slug 已存在，请使用其他值',
            'slug.max' => 'slug 不能超过 50 个字符',
        ];
    }
}
