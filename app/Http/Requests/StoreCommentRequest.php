<?php

namespace App\Http\Requests;

use App\Services\SettingsService;
use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // 检查评论功能是否开启
        $settings = app(SettingsService::class);

        if (!$settings->isCommentsEnabled()) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $settings = app(SettingsService::class);

        $rules = [
            'content' => ['required', 'string', 'min:3', 'max:2000'],
            'parent_id' => ['nullable', 'exists:comments,id'],
        ];

        // 如果不允许匿名评论，添加额外验证
        if (!$settings->allowsAnonymousComments() && !auth()->check()) {
            $rules['author_name'] = ['required', 'string', 'max:100'];
            $rules['author_email'] = ['required', 'email', 'max:255'];
            $rules['author_url'] = ['nullable', 'url', 'max:500'];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'content.required' => '请输入评论内容',
            'content.min' => '评论内容至少需要 3 个字符',
            'content.max' => '评论内容不能超过 2000 个字符',
            'parent_id.exists' => '回复的评论不存在',
            'author_name.required' => '请输入昵称',
            'author_name.max' => '昵称不能超过 100 个字符',
            'author_email.required' => '请输入邮箱',
            'author_email.email' => '邮箱格式不正确',
            'author_email.max' => '邮箱不能超过 255 个字符',
            'author_url.url' => '网站链接格式不正确',
            'author_url.max' => '网站链接不能超过 500 个字符',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // 清理 URL 字段
        if ($this->has('author_url') && !empty($this->author_url)) {
            $url = $this->author_url;
            // 确保 URL 包含协议
            if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
                $url = 'https://' . $url;
            }
            $this->merge(['author_url' => $url]);
        }
    }
}
