<?php

return [

    /*
    |--------------------------------------------------------------------------
    | 博客名称
    |--------------------------------------------------------------------------
    */
    'name' => env('BLOG_NAME', '我的博客'),

    /*
    |--------------------------------------------------------------------------
    | 博客描述
    |--------------------------------------------------------------------------
    */
    'description' => env('BLOG_DESCRIPTION', '一个使用 Laravel 构建的个人博客'),

    /*
    |--------------------------------------------------------------------------
    | 博主信息
    |--------------------------------------------------------------------------
    */
    'author' => [
        'name' => env('BLOG_AUTHOR_NAME', '博主'),
        'email' => env('BLOG_AUTHOR_EMAIL', 'admin@example.com'),
        'bio' => env('BLOG_AUTHOR_BIO', ''),
        'avatar' => env('BLOG_AUTHOR_AVATAR', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Logo 设置
    |--------------------------------------------------------------------------
    */
    'logo' => env('BLOG_LOGO', ''),
    'logo_dark' => env('BLOG_LOGO_DARK', ''),
    'favicon' => env('BLOG_FAVICON', ''),

    /*
    |--------------------------------------------------------------------------
    | 分页设置
    |--------------------------------------------------------------------------
    */
    'per_page' => env('BLOG_PER_PAGE', 10),

    /*
    |--------------------------------------------------------------------------
    | 浏览量设置
    |--------------------------------------------------------------------------
    */
    'view_count_decay' => env('BLOG_VIEW_COUNT_DECAY', 30), // 同一 IP 计数的间隔（分钟）

    /*
    |--------------------------------------------------------------------------
    | 评论设置
    |--------------------------------------------------------------------------
    */
    'comments' => [
        'enabled' => env('BLOG_COMMENTS_ENABLED', true),
        'require_approval' => env('BLOG_COMMENTS_REQUIRE_APPROVAL', true),
        'allow_anonymous' => env('BLOG_COMMENTS_ALLOW_ANONYMOUS', true),
        'max_depth' => env('BLOG_COMMENTS_MAX_DEPTH', 3),
    ],

    /*
    |--------------------------------------------------------------------------
    | 订阅设置
    |--------------------------------------------------------------------------
    */
    'subscription' => [
        'enabled' => env('BLOG_SUBSCRIPTION_ENABLED', true),
        'confirmation_required' => env('BLOG_SUBSCRIPTION_CONFIRMATION_REQUIRED', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | 文章设置
    |--------------------------------------------------------------------------
    */
    'posts' => [
        'excerpt_length' => env('BLOG_EXCERPT_LENGTH', 200),
        'cover_image_width' => env('BLOG_COVER_IMAGE_WIDTH', 1200),
        'cover_image_height' => env('BLOG_COVER_IMAGE_HEIGHT', 630),
    ],

    /*
    |--------------------------------------------------------------------------
    | SEO 设置
    |--------------------------------------------------------------------------
    */
    'seo' => [
        'title_separator' => env('BLOG_SEO_TITLE_SEPARATOR', '|'),
        'keywords' => env('BLOG_SEO_KEYWORDS', ''),
        'default_og_image' => env('BLOG_SEO_DEFAULT_OG_IMAGE', ''),
        'google_site_verification' => env('BLOG_GOOGLE_SITE_VERIFICATION', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | 缓存设置
    |--------------------------------------------------------------------------
    */
    'cache' => [
        // 缓存时间（秒）
        'recent_posts_ttl' => env('BLOG_CACHE_RECENT_POSTS_TTL', 900),      // 15分钟
        'popular_posts_ttl' => env('BLOG_CACHE_POPULAR_POSTS_TTL', 900),    // 15分钟
        'categories_ttl' => env('BLOG_CACHE_CATEGORIES_TTL', 3600),         // 1小时
        'tags_ttl' => env('BLOG_CACHE_TAGS_TTL', 3600),                     // 1小时
        'sidebar_ttl' => env('BLOG_CACHE_SIDEBAR_TTL', 900),                // 15分钟
        'post_ttl' => env('BLOG_CACHE_POST_TTL', 900),                      // 15分钟
        'html_ttl' => env('BLOG_CACHE_HTML_TTL', 3600),                     // 1小时
        'feed_ttl' => env('BLOG_CACHE_FEED_TTL', 3600),                     // 1小时
        'sitemap_ttl' => env('BLOG_CACHE_SITEMAP_TTL', 3600),               // 1小时
    ],

    /*
    |--------------------------------------------------------------------------
    | 社交链接
    |--------------------------------------------------------------------------
    */
    'social' => [
        'github' => env('BLOG_SOCIAL_GITHUB', ''),
        'twitter' => env('BLOG_SOCIAL_TWITTER', ''),
        'weibo' => env('BLOG_SOCIAL_WEIBO', ''),
        'zhihu' => env('BLOG_SOCIAL_ZHIHU', ''),
        'juejin' => env('BLOG_SOCIAL_JUEJIN', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | 底部信息
    |--------------------------------------------------------------------------
    */
    'footer' => [
        'copyright' => env('BLOG_FOOTER_COPYRIGHT', ''),
        'icp' => env('BLOG_FOOTER_ICP', ''),
        'police' => env('BLOG_FOOTER_POLICE', ''),
        'police_number' => env('BLOG_FOOTER_POLICE_NUMBER', ''),
    ],

];
