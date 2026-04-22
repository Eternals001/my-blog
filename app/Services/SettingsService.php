<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class SettingsService
{
    /**
     * 默认设置
     */
    protected array $defaults = [
        'blog' => [
            'name' => 'My Blog',
            'description' => 'A personal blog built with Laravel',
            'per_page' => 10,
            'author' => [
                'name' => '',
                'email' => '',
                'bio' => '',
                'avatar' => '',
            ],
            'logo' => '',
            'logo_dark' => '',
            'favicon' => '',
            'comments' => [
                'enabled' => true,
                'require_approval' => true,
                'allow_anonymous' => true,
                'max_depth' => 3,
            ],
            'subscription' => [
                'enabled' => true,
                'confirmation_required' => true,
            ],
            'seo' => [
                'title_separator' => '|',
                'default_keywords' => '',
                'default_description' => '',
            ],
            'social' => [
                'github' => '',
                'twitter' => '',
                'weibo' => '',
                'zhihu' => '',
                'juejin' => '',
            ],
            'footer' => [
                'copyright' => '',
                'icp' => '',
                'police' => '',
                'police_number' => '',
            ],
        ],
    ];

    /**
     * 获取设置值
     */
    public function get(string $key, $default = null)
    {
        $settings = $this->all();
        $keys = explode('.', $key);

        $value = $settings;
        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }

        return $value ?? $default;
    }

    /**
     * 获取所有设置
     */
    public function all(): array
    {
        return Cache::remember('site_settings', now()->addHour(), function () {
            $settings = $this->defaults;

            // 从 .env 或配置文件覆盖默认值
            $settings['blog']['name'] = env('BLOG_NAME', $settings['blog']['name']);
            $settings['blog']['description'] = env('BLOG_DESCRIPTION', $settings['blog']['description']);
            $settings['blog']['per_page'] = (int) env('BLOG_PER_PAGE', $settings['blog']['per_page']);

            // 作者信息
            $settings['blog']['author']['name'] = env('BLOG_AUTHOR_NAME', $settings['blog']['author']['name']);
            $settings['blog']['author']['email'] = env('BLOG_AUTHOR_EMAIL', $settings['blog']['author']['email']);
            $settings['blog']['author']['bio'] = env('BLOG_AUTHOR_BIO', $settings['blog']['author']['bio']);

            // Logo
            $settings['blog']['logo'] = env('BLOG_LOGO', $settings['blog']['logo']);
            $settings['blog']['logo_dark'] = env('BLOG_LOGO_DARK', $settings['blog']['logo_dark']);
            $settings['blog']['favicon'] = env('BLOG_FAVICON', $settings['blog']['favicon']);

            // 评论设置
            $settings['blog']['comments']['enabled'] = env('BLOG_COMMENTS_ENABLED', $settings['blog']['comments']['enabled']) === 'true';
            $settings['blog']['comments']['require_approval'] = env('BLOG_COMMENTS_REQUIRE_APPROVAL', $settings['blog']['comments']['require_approval']) === 'true';
            $settings['blog']['comments']['allow_anonymous'] = env('BLOG_COMMENTS_ALLOW_ANONYMOUS', $settings['blog']['comments']['allow_anonymous']) === 'true';
            $settings['blog']['comments']['max_depth'] = (int) env('BLOG_COMMENTS_MAX_DEPTH', $settings['blog']['comments']['max_depth']);

            // 订阅设置
            $settings['blog']['subscription']['enabled'] = env('BLOG_SUBSCRIPTION_ENABLED', $settings['blog']['subscription']['enabled']) === 'true';
            $settings['blog']['subscription']['confirmation_required'] = env('BLOG_SUBSCRIPTION_CONFIRMATION_REQUIRED', $settings['blog']['subscription']['confirmation_required']) === 'true';

            // SEO 设置
            $settings['blog']['seo']['title_separator'] = env('BLOG_SEO_TITLE_SEPARATOR', $settings['blog']['seo']['title_separator']);
            $settings['blog']['seo']['default_keywords'] = env('BLOG_SEO_DEFAULT_KEYWORDS', $settings['blog']['seo']['default_keywords']);
            $settings['blog']['seo']['default_description'] = env('BLOG_SEO_DEFAULT_DESCRIPTION', $settings['blog']['seo']['default_description']);

            // 社交链接
            $settings['blog']['social']['github'] = env('BLOG_SOCIAL_GITHUB', $settings['blog']['social']['github']);
            $settings['blog']['social']['twitter'] = env('BLOG_SOCIAL_TWITTER', $settings['blog']['social']['twitter']);
            $settings['blog']['social']['weibo'] = env('BLOG_SOCIAL_WEIBO', $settings['blog']['social']['weibo']);
            $settings['blog']['social']['zhihu'] = env('BLOG_SOCIAL_ZHIHU', $settings['blog']['social']['zhihu']);
            $settings['blog']['social']['juejin'] = env('BLOG_SOCIAL_JUEJIN', $settings['blog']['social']['juejin']);

            // 底部信息
            $settings['blog']['footer']['copyright'] = env('BLOG_FOOTER_COPYRIGHT', $settings['blog']['footer']['copyright']);
            $settings['blog']['footer']['icp'] = env('BLOG_FOOTER_ICP', $settings['blog']['footer']['icp']);
            $settings['blog']['footer']['police'] = env('BLOG_FOOTER_POLICE', $settings['blog']['footer']['police']);
            $settings['blog']['footer']['police_number'] = env('BLOG_FOOTER_POLICE_NUMBER', $settings['blog']['footer']['police_number']);

            return $settings;
        });
    }

    /**
     * 设置设置值
     */
    public function set(string $key, $value): void
    {
        $envKey = 'BLOG_' . strtoupper(str_replace('.', '_', $key));
        $envValue = is_bool($value) ? ($value ? 'true' : 'false') : $value;

        $this->updateEnvFile($envKey, $envValue);
        $this->clearCache();
    }

    /**
     * 批量设置
     */
    public function setMany(array $settings): void
    {
        foreach ($settings as $key => $value) {
            $envKey = 'BLOG_' . strtoupper(str_replace('.', '_', $key));
            $envValue = is_bool($value) ? ($value ? 'true' : 'false') : $value;

            $this->updateEnvFile($envKey, $envValue);
        }

        $this->clearCache();
    }

    /**
     * 更新 .env 文件
     */
    protected function updateEnvFile(string $key, string $value): void
    {
        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);

        // 如果键已存在，更新值
        if (preg_match("/^{$key}=.*/m", $envContent)) {
            $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $envContent);
        } else {
            // 添加新键
            $envContent .= "\n{$key}={$value}";
        }

        file_put_contents($envPath, $envContent);
    }

    /**
     * 清除缓存
     */
    public function clearCache(): void
    {
        Cache::forget('site_settings');
    }

    /**
     * 获取站点名称
     */
    public function getSiteName(): string
    {
        return $this->get('blog.name', config('app.name', 'Blog'));
    }

    /**
     * 获取站点描述
     */
    public function getSiteDescription(): string
    {
        return $this->get('blog.description', '');
    }

    /**
     * 获取作者信息
     */
    public function getAuthor(): array
    {
        return $this->get('blog.author', []);
    }

    /**
     * 获取评论设置
     */
    public function isCommentsEnabled(): bool
    {
        return $this->get('blog.comments.enabled', true);
    }

    /**
     * 是否需要审核评论
     */
    public function requiresCommentApproval(): bool
    {
        return $this->get('blog.comments.require_approval', true);
    }

    /**
     * 是否允许匿名评论
     */
    public function allowsAnonymousComments(): bool
    {
        return $this->get('blog.comments.allow_anonymous', true);
    }

    /**
     * 获取 SEO 标题分隔符
     */
    public function getTitleSeparator(): string
    {
        return $this->get('blog.seo.title_separator', '|');
    }

    /**
     * 生成 SEO 标题
     */
    public function generateSeoTitle(string $title): string
    {
        $separator = $this->getTitleSeparator();
        $siteName = $this->getSiteName();

        if (empty($siteName)) {
            return $title;
        }

        return "{$title} {$separator} {$siteName}";
    }

    /**
     * 获取社交链接
     */
    public function getSocialLinks(): array
    {
        return $this->get('blog.social', []);
    }

    /**
     * 获取备案信息
     */
    public function getIcpInfo(): array
    {
        return [
            'icp' => $this->get('blog.footer.icp', ''),
            'police' => $this->get('blog.footer.police', ''),
            'police_number' => $this->get('blog.footer.police_number', ''),
        ];
    }

    /**
     * 重置为默认值
     */
    public function reset(): void
    {
        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);

        // 移除所有 BLOG_ 开头的配置
        $envContent = preg_replace('/^BLOG_.*$/m', '', $envContent);

        // 清理多余的空行
        $envContent = preg_replace("/\n{3,}/", "\n\n", $envContent);

        file_put_contents($envPath, $envContent);

        $this->clearCache();
    }
}
