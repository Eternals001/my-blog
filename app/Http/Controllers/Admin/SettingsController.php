<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    protected SettingsService $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    /**
     * 设置页面
     */
    public function index()
    {
        $settings = $this->settingsService->all();

        return view('admin.settings', compact('settings'));
    }

    /**
     * 保存设置
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            // 基础设置
            'blog.name' => 'required|string|max:100',
            'blog.description' => 'nullable|string|max:500',
            'blog.author.name' => 'required|string|max:100',
            'blog.author.email' => 'required|email|max:255',
            'blog.author.bio' => 'nullable|string|max:500',

            // 功能设置
            'blog.per_page' => 'required|integer|min:1|max:50',
            'blog.comments.enabled' => 'nullable|boolean',
            'blog.comments.require_approval' => 'nullable|boolean',
            'blog.comments.allow_anonymous' => 'nullable|boolean',
            'blog.comments.max_depth' => 'required|integer|min:1|max:10',
            'blog.subscription.enabled' => 'nullable|boolean',
            'blog.subscription.confirmation_required' => 'nullable|boolean',

            // SEO 设置
            'blog.seo.title_separator' => 'nullable|string|max:10',
            'blog.seo.default_keywords' => 'nullable|string|max:500',
            'blog.seo.default_description' => 'nullable|string|max:500',

            // Logo
            'blog.logo' => 'nullable|string|max:500',
            'blog.logo_dark' => 'nullable|string|max:500',
            'blog.favicon' => 'nullable|string|max:500',

            // 社交链接
            'blog.social.github' => 'nullable|string|max:255',
            'blog.social.twitter' => 'nullable|string|max:255',
            'blog.social.weibo' => 'nullable|string|max:255',
            'blog.social.zhihu' => 'nullable|string|max:255',
            'blog.social.juejin' => 'nullable|string|max:255',

            // 底部信息
            'blog.footer.copyright' => 'nullable|string|max:255',
            'blog.footer.icp' => 'nullable|string|max:100',
            'blog.footer.police' => 'nullable|string|max:255',
            'blog.footer.police_number' => 'nullable|string|max:100',
        ]);

        // 使用 SettingsService 保存设置
        $this->settingsService->setMany($validated);

        return back()->with('success', '设置已保存');
    }

    /**
     * 重置设置为默认值
     */
    public function reset()
    {
        $this->settingsService->reset();

        return back()->with('success', '设置已重置为默认值');
    }
}
