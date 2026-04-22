<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create categories');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100', Rule::unique('categories', 'slug')],
            'description' => ['nullable', 'string', 'max:500'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'order' => ['nullable', 'integer', 'min:0'],
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
            'name.required' => '请输入分类名称',
            'name.max' => '分类名称不能超过 100 个字符',
            'slug.unique' => '该 slug 已存在，请使用其他值',
            'description.max' => '分类描述不能超过 500 个字符',
            'parent_id.exists' => '请选择有效的父分类',
            'order.min' => '排序值不能为负数',
        ];
    }
}
