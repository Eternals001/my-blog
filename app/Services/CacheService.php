<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CacheService
{
    // 缓存键常量
    const KEY_RECENT_POSTS = 'recent_posts';
    const KEY_POPULAR_POSTS = 'popular_posts';
    const KEY_CATEGORIES = 'categories';
    const KEY_TAGS = 'tags';
    const KEY_SIDEBAR_DATA = 'sidebar_data';
    const KEY_POST_PREFIX = 'post_';
    const KEY_POST_HTML_PREFIX = 'post_html_';

    // 缓存时间（秒）
    const TTL_SHORT = 300;        // 5分钟
    const TTL_MEDIUM = 900;      // 15分钟
    const TTL_LONG = 3600;       // 1小时
    const TTL_VERY_LONG = 86400; // 24小时

    /**
     * 获取最近文章列表
     */
    public function getRecentPosts(int $limit = 10)
    {
        return Cache::remember(
            self::KEY_RECENT_POSTS,
            config('blog.cache.recent_posts_ttl', self::TTL_MEDIUM),
            function () use ($limit) {
                return Post::visible()
                    ->with(['author', 'category'])
                    ->orderByPublished()
                    ->limit($limit)
                    ->get();
            }
        );
    }

    /**
     * 获取热门文章列表
     */
    public function getPopularPosts(int $limit = 10)
    {
        return Cache::remember(
            self::KEY_POPULAR_POSTS,
            config('blog.cache.popular_posts_ttl', self::TTL_MEDIUM),
            function () use ($limit) {
                return Post::visible()
                    ->with(['author', 'category'])
                    ->orderByViews()
                    ->limit($limit)
                    ->get();
            }
        );
    }

    /**
     * 获取分类列表（带文章数）
     */
    public function getCategories()
    {
        return Cache::remember(
            self::KEY_CATEGORIES,
            config('blog.cache.categories_ttl', self::TTL_LONG),
            function () {
                return Category::query()
                    ->withCount(['publishedPosts'])
                    ->orderByOrder()
                    ->orderBy('name')
                    ->get();
            }
        );
    }

    /**
     * 获取标签列表（带文章数）
     */
    public function getTags(int $limit = 50)
    {
        return Cache::remember(
            self::KEY_TAGS,
            config('blog.cache.tags_ttl', self::TTL_LONG),
            function () use ($limit) {
                return Tag::query()
                    ->hasPosts()
                    ->withCount('publishedPosts')
                    ->orderByPostCount()
                    ->limit($limit)
                    ->get();
            }
        );
    }

    /**
     * 获取侧边栏数据
     */
    public function getSidebarData()
    {
        return Cache::remember(
            self::KEY_SIDEBAR_DATA,
            config('blog.cache.sidebar_ttl', self::TTL_MEDIUM),
            function () {
                return [
                    'recent_posts' => $this->getRecentPosts(5),
                    'popular_posts' => $this->getPopularPosts(5),
                    'categories' => $this->getCategories(),
                    'tags' => $this->getTags(20),
                ];
            }
        );
    }

    /**
     * 获取单篇文章缓存
     */
    public function getPost(int $postId)
    {
        return Cache::remember(
            self::KEY_POST_PREFIX . $postId,
            config('blog.cache.post_ttl', self::TTL_MEDIUM),
            function () use ($postId) {
                return Post::with(['author', 'category', 'tags', 'approvedComments'])
                    ->find($postId);
            }
        );
    }

    /**
     * 获取文章渲染后的 HTML
     */
    public function getPostHtml(int $postId): ?string
    {
        return Cache::get(self::KEY_POST_HTML_PREFIX . $postId);
    }

    /**
     * 缓存文章渲染后的 HTML
     */
    public function setPostHtml(int $postId, string $html, ?int $ttl = null): void
    {
        Cache::put(
            self::KEY_POST_HTML_PREFIX . $postId,
            $html,
            $ttl ?? config('blog.cache.html_ttl', self::TTL_LONG)
        );
    }

    /**
     * 清除最近文章缓存
     */
    public function forgetRecentPosts(): void
    {
        Cache::forget(self::KEY_RECENT_POSTS);
    }

    /**
     * 清除热门文章缓存
     */
    public function forgetPopularPosts(): void
    {
        Cache::forget(self::KEY_POPULAR_POSTS);
    }

    /**
     * 清除分类缓存
     */
    public function forgetCategories(): void
    {
        Cache::forget(self::KEY_CATEGORIES);
    }

    /**
     * 清除标签缓存
     */
    public function forgetTags(): void
    {
        Cache::forget(self::KEY_TAGS);
    }

    /**
     * 清除侧边栏缓存
     */
    public function forgetSidebar(): void
    {
        Cache::forget(self::KEY_SIDEBAR_DATA);
    }

    /**
     * 清除单篇文章缓存
     */
    public function forgetPost(int $postId): void
    {
        Cache::forget(self::KEY_POST_PREFIX . $postId);
        Cache::forget(self::KEY_POST_HTML_PREFIX . $postId);
    }

    /**
     * 清除文章相关的所有缓存
     */
    public function forgetPostRelated(int $postId): void
    {
        $this->forgetRecentPosts();
        $this->forgetPopularPosts();
        $this->forgetSidebar();
        $this->forgetPost($postId);
    }

    /**
     * 清除所有博客缓存
     */
    public function flushAll(): void
    {
        // 清除主要缓存键
        $this->forgetRecentPosts();
        $this->forgetPopularPosts();
        $this->forgetCategories();
        $this->forgetTags();
        $this->forgetSidebar();

        // 清除 Feed 缓存
        Cache::forget('feed_rss');
        Cache::forget('feed_atom');
        Cache::forget('sitemap_xml');

        // 清除所有以 post_ 开头的缓存
        $this->flushPostCache();
    }

    /**
     * 刷新所有文章缓存
     */
    public function refreshPosts(): void
    {
        $this->forgetRecentPosts();
        $this->forgetPopularPosts();

        // 重新缓存最新文章
        $this->getRecentPosts();
        $this->getPopularPosts();
    }

    /**
     * 清除所有文章缓存
     */
    protected function flushPostCache(): void
    {
        $cachePrefix = config('cache.default') === 'redis' 
            ? self::KEY_POST_PREFIX 
            : self::KEY_POST_PREFIX;

        // 使用 Redis SCAN 或数据库查询获取所有文章 ID
        $postIds = Post::pluck('id')->toArray();
        
        foreach ($postIds as $postId) {
            Cache::forget(self::KEY_POST_PREFIX . $postId);
            Cache::forget(self::KEY_POST_HTML_PREFIX . $postId);
        }
    }

    /**
     * 预热缓存
     */
    public function warmUp(): void
    {
        // 预热最近文章
        $this->getRecentPosts();

        // 预热热门文章
        $this->getPopularPosts();

        // 预热分类
        $this->getCategories();

        // 预热标签
        $this->getTags();

        // 预热侧边栏
        $this->getSidebarData();
    }

    /**
     * 获取缓存统计信息
     */
    public function getStats(): array
    {
        $stats = [
            'recent_posts' => Cache::has(self::KEY_RECENT_POSTS),
            'popular_posts' => Cache::has(self::KEY_POPULAR_POSTS),
            'categories' => Cache::has(self::KEY_CATEGORIES),
            'tags' => Cache::has(self::KEY_TAGS),
            'sidebar' => Cache::has(self::KEY_SIDEBAR_DATA),
            'feed_rss' => Cache::has('feed_rss'),
            'feed_atom' => Cache::has('feed_atom'),
            'sitemap' => Cache::has('sitemap_xml'),
        ];

        return $stats;
    }
}
