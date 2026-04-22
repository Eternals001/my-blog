<?php

namespace App\Http\Requests\Backend;

use App\Enums\PostStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create posts');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:posts,slug'],
            'content' => ['required', 'string'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'cover_image' => ['nullable', 'string', 'max:500', 'url'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
            'status' => ['nullable', Rule::in(PostStatus::values())],
            'published_at' => ['nullable', 'date', 'after_or_equal:today'],
            'is_sticky' => ['nullable', 'boolean'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
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
            'title.required' => '请输入文章标题',
            'title.max' => '文章标题不能超过 255 个字符',
            'slug.unique' => '该 slug 已存在，请使用其他值',
            'content.required' => '请输入文章内容',
            'excerpt.max' => '摘要不能超过 500 个字符',
            'cover_image.url' => '封面图片链接格式不正确',
            'category_id.exists' => '请选择有效的分类',
            'tags.*.exists' => '请选择有效的标签',
            'published_at.after_or_equal' => '发布日期不能早于今天',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // 合并默认值
        $this->merge([
            'status' => $this->status ?? PostStatus::DRAFT,
            'is_sticky' => $this->boolean('is_sticky'),
        ]);
    }
}
