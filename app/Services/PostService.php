<?php

namespace App\Services;

use App\Enums\PostStatus;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Spatie\Markdown\Markdown;

class PostService
{
    protected Markdown $markdown;

    public function __construct()
    {
        $this->markdown = new Markdown([
            'markdown' => [
                'heading_permalink' => [
                    'symbol' => '#',
                ],
            ],
        ]);
    }

    /**
     * 创建文章
     */
    public function create(array $data): Post
    {
        // 生成 slug
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['title']);
        }

        // 处理内容：Markdown 转 HTML
        if (!empty($data['content'])) {
            $data['html_content'] = $this->convertMarkdownToHtml($data['content']);
        }

        // 自动生成摘要
        if (empty($data['excerpt'])) {
            $data['excerpt'] = $this->generateExcerpt($data['content'] ?? '', $data['html_content'] ?? '');
        }

        // 自动生成 SEO Meta
        if (empty($data['seo_title'])) {
            $data['seo_title'] = $data['title'];
        }
        if (empty($data['seo_description'])) {
            $data['seo_description'] = $data['excerpt'];
        }

        // 设置发布状态和时间
        if (($data['status'] ?? PostStatus::DRAFT) === PostStatus::PUBLISHED && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        // 创建文章
        $post = Post::create($data);

        // 关联标签
        if (!empty($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }

        $this->clearCache();

        return $post;
    }

    /**
     * 更新文章
     */
    public function update(Post $post, array $data): Post
    {
        // 如果标题或 slug 发生变化，生成新的唯一 slug
        if (isset($data['title']) && $data['title'] !== $post->title) {
            $data['slug'] = $this->generateUniqueSlug($data['title'], $post->id);
        }

        // 处理内容：Markdown 转 HTML
        if (!empty($data['content']) && $data['content'] !== $post->content) {
            $data['html_content'] = $this->convertMarkdownToHtml($data['content']);

            // 同时更新摘要（如果内容有变化）
            if (empty($data['excerpt'])) {
                $data['excerpt'] = $this->generateExcerpt($data['content'], $data['html_content']);
            }
        }

        // 自动生成摘要（如果手动清空了）
        if (isset($data['excerpt']) && empty($data['excerpt']) && !empty($data['content'])) {
            $data['excerpt'] = $this->generateExcerpt($data['content'], $data['html_content'] ?? '');
        }

        // 设置发布状态和时间
        $wasPublished = $post->status === PostStatus::PUBLISHED;
        $willBePublished = ($data['status'] ?? $post->status) === PostStatus::PUBLISHED;

        if ($willBePublished && !$wasPublished && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        // 更新文章
        $post->update($data);

        // 同步标签
        if (array_key_exists('tags', $data)) {
            $post->tags()->sync($data['tags'] ?? []);
        }

        $this->clearCache();

        return $post;
    }

    /**
     * 发布文章
     */
    public function publish(Post $post): Post
    {
        $post->update([
            'status' => PostStatus::PUBLISHED,
            'published_at' => $post->published_at ?? now(),
        ]);

        $this->clearCache();

        return $post;
    }

    /**
     * 取消发布文章
     */
    public function unpublish(Post $post): Post
    {
        $post->update([
            'status' => PostStatus::DRAFT,
        ]);

        $this->clearCache();

        return $post;
    }

    /**
     * 切换置顶状态
     */
    public function toggleSticky(Post $post): Post
    {
        $post->update([
            'is_sticky' => !$post->is_sticky,
        ]);

        $this->clearCache();

        return $post;
    }

    /**
     * 批量操作
     */
    public function bulkAction(array $ids, string $action): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        $posts = Post::whereIn('id', $ids)->get();

        foreach ($posts as $post) {
            try {
                switch ($action) {
                    case 'publish':
                        $this->publish($post);
                        break;
                    case 'unpublish':
                        $this->unpublish($post);
                        break;
                    case 'sticky':
                        $post->update(['is_sticky' => true]);
                        break;
                    case 'unsticky':
                        $post->update(['is_sticky' => false]);
                        break;
                    case 'delete':
                        $post->delete();
                        break;
                    default:
                        throw new \InvalidArgumentException("Unknown action: {$action}");
                }
                $results['success']++;
            } catch (\Exception $e) {
                $results['failed']++;
                $results['errors'][] = "ID {$post->id}: {$e->getMessage()}";
            }
        }

        $this->clearCache();

        return $results;
    }

    /**
     * 将 Markdown 转换为 HTML
     */
    public function convertMarkdownToHtml(string $markdown): string
    {
        return $this->markdown->toHtml($markdown);
    }

    /**
     * 生成文章摘要
     */
    public function generateExcerpt(string $markdown, string $html = ''): string
    {
        // 优先使用 HTML 内容
        if (!empty($html)) {
            $text = strip_tags($html);
        } else {
            $text = $markdown;
        }

        // 移除 Markdown 链接
        $text = preg_replace('/\[([^\]]+)\]\([^)]+\)/', '$1', $text);

        // 移除多余空白
        $text = preg_replace('/\s+/', ' ', $text);

        // 截取前 200 字符
        $excerpt = mb_substr(trim($text), 0, 200);

        if (mb_strlen($text) > 200) {
            $excerpt .= '...';
        }

        return $excerpt;
    }

    /**
     * 生成唯一的 Slug
     */
    public function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        $query = Post::where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;

            $query = Post::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $slug;
    }

    /**
     * 生成 SEO 标题
     */
    public function generateSeoTitle(string $title): string
    {
        $separator = config('blog.seo.title_separator', '|');
        $siteName = config('blog.name', '');

        if (empty($siteName)) {
            return $title;
        }

        return "{$title} {$separator} {$siteName}";
    }

    /**
     * 清除缓存
     */
    protected function clearCache(): void
    {
        Cache::forget('recent_posts');
        Cache::forget('popular_posts');
        Cache::forget('sticky_posts');
    }
}
