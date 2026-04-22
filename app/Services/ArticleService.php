<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ArticleService
{
    /**
     * 获取首页文章列表
     */
    public function getHomepagePosts(int $perPage = null): LengthAwarePaginator
    {
        $perPage = $perPage ?? config('blog.per_page', 10);

        return Post::visible()
            ->with(['author:id,name,avatar', 'category:id,name,slug', 'tags:id,name,slug'])
            ->orderBySticky()
            ->orderByPublished()
            ->paginate($perPage);
    }

    /**
     * 获取置顶文章
     */
    public function getStickyPosts(int $limit = 5): Collection
    {
        return Cache::remember('sticky_posts', now()->addHour(), function () {
            return Post::visible()
                ->where('is_sticky', true)
                ->orderByPublished()
                ->limit(5)
                ->get();
        });
    }

    /**
     * 获取最新文章
     */
    public function getRecentPosts(int $limit = 10): Collection
    {
        return Cache::remember('recent_posts', now()->addMinutes(30), function () use ($limit) {
            return Post::visible()
                ->with(['author:id,name', 'category:id,name,slug'])
                ->orderByPublished()
                ->limit($limit)
                ->get();
        });
    }

    /**
     * 获取热门文章（按浏览量）
     */
    public function getPopularPosts(int $limit = 10): Collection
    {
        return Cache::remember('popular_posts', now()->addHour(), function () use ($limit) {
            return Post::visible()
                ->with(['author:id,name', 'category:id,name,slug'])
                ->orderByViewCount()
                ->limit($limit)
                ->get();
        });
    }

    /**
     * 获取分类文章
     */
    public function getPostsByCategory(string $slug, int $perPage = null): LengthAwarePaginator
    {
        $perPage = $perPage ?? config('blog.per_page', 10);

        return Post::visible()
            ->whereHas('category', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->with(['author:id,name,avatar', 'category:id,name,slug', 'tags:id,name,slug'])
            ->orderBySticky()
            ->orderByPublished()
            ->paginate($perPage);
    }

    /**
     * 获取标签文章
     */
    public function getPostsByTag(string $slug, int $perPage = null): LengthAwarePaginator
    {
        $perPage = $perPage ?? config('blog.per_page', 10);

        return Post::visible()
            ->whereHas('tags', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->with(['author:id,name,avatar', 'category:id,name,slug', 'tags:id,name,slug'])
            ->orderBySticky()
            ->orderByPublished()
            ->paginate($perPage);
    }

    /**
     * 获取作者文章
     */
    public function getPostsByAuthor(int $userId, int $perPage = null): LengthAwarePaginator
    {
        $perPage = $perPage ?? config('blog.per_page', 10);

        return Post::visible()
            ->where('user_id', $userId)
            ->with(['author:id,name,avatar', 'category:id,name,slug', 'tags:id,name,slug'])
            ->orderBySticky()
            ->orderByPublished()
            ->paginate($perPage);
    }

    /**
     * 搜索文章
     */
    public function search(string $keyword, int $perPage = null): LengthAwarePaginator
    {
        $perPage = $perPage ?? config('blog.per_page', 10);

        return Post::visible()
            ->search($keyword)
            ->with(['author:id,name,avatar', 'category:id,name,slug', 'tags:id,name,slug'])
            ->orderByPublished()
            ->paginate($perPage);
    }

    /**
     * 获取文章详情（包含关联数据）
     */
    public function getPostDetail(string $slug): ?Post
    {
        return Post::where('slug', $slug)
            ->visible()
            ->with(['author', 'category', 'tags', 'approvedComments.user'])
            ->first();
    }

    /**
     * 获取相关文章推荐
     */
    public function getRelatedPosts(Post $post, int $limit = 5): Collection
    {
        $tagIds = $post->tags->pluck('id');
        $categoryId = $post->category_id;

        return Post::visible()
            ->where('id', '!=', $post->id)
            ->where(function ($query) use ($categoryId, $tagIds) {
                $query->where('category_id', $categoryId)
                    ->orWhereHas('tags', function ($q) use ($tagIds) {
                        $q->whereIn('tags.id', $tagIds);
                    });
            })
            ->orderByPublished()
            ->limit($limit)
            ->get();
    }

    /**
     * 获取文章归档（按年月分组）
     */
    public function getArchives(int $limit = 12): Collection
    {
        return Cache::remember('archives', now()->addDay(), function () use ($limit) {
            return Post::visible()
                ->selectRaw('YEAR(published_at) as year, MONTH(published_at) as month, COUNT(*) as count')
                ->whereNotNull('published_at')
                ->groupByRaw('YEAR(published_at), MONTH(published_at)')
                ->orderByRaw('YEAR(published_at) DESC, MONTH(published_at) DESC')
                ->limit($limit)
                ->get()
                ->map(function ($item) {
                    return [
                        'year' => $item->year,
                        'month' => $item->month,
                        'count' => $item->count,
                        'label' => date('Y年F', mktime(0, 0, 0, $item->month, 1, $item->year)),
                    ];
                });
        });
    }

    /**
     * 获取热门标签
     */
    public function getPopularTags(int $limit = 20): Collection
    {
        return Cache::remember('popular_tags', now()->addHour(), function () use ($limit) {
            return Tag::hasPosts()
                ->orderByPostCount()
                ->limit($limit)
                ->get();
        });
    }

    /**
     * 获取最新评论
     */
    public function getRecentComments(int $limit = 10): Collection
    {
        return Comment::approved()
            ->with(['post:id,title,slug', 'user:id,name,avatar'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * 获取 RSS 所需的文章
     */
    public function getRssPosts(int $limit = 20): Collection
    {
        return Post::visible()
            ->with(['author:id,name,email'])
            ->orderByPublished()
            ->limit($limit)
            ->get();
    }

    /**
     * 统计数据
     */
    public function getStats(): array
    {
        return [
            'total_posts' => Post::visible()->count(),
            'total_views' => Post::visible()->sum('view_count'),
            'total_comments' => Comment::approved()->count(),
            'total_categories' => \App\Models\Category::count(),
            'total_tags' => Tag::hasPosts()->count(),
        ];
    }
}
