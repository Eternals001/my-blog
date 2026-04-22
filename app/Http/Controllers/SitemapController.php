<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class SitemapController extends Controller
{
    /**
     * 生成站点地图
     */
    public function index(Request $request)
    {
        $sitemap = Cache::remember('sitemap_xml', config('blog.cache.sitemap_ttl', 3600), function () {
            $posts = Post::visible()
                ->select(['slug', 'updated_at', 'published_at'])
                ->orderByPublished()
                ->get();

            $categories = Category::whereHas('posts', function ($q) {
                $q->where('status', 'published');
            })->get();

            $tags = Tag::hasPosts()->get();

            $content = $this->generateSitemap($posts, $categories, $tags);

            return $content;
        });

        return Response($sitemap, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    /**
     * 生成 XML 内容
     */
    protected function generateSitemap($posts, $categories, $tags): string
    {
        $baseUrl = config('app.url');
        $now = now()->toIso8601String();

        $urls = [];

        // 首页
        $urls[] = $this->buildUrl($baseUrl, $now, '1.0', 'home');

        // 文章
        foreach ($posts as $post) {
            $lastmod = $post->updated_at?->toIso8601String() ?? $now;
            $priority = '0.9';
            $changefreq = 'weekly';

            if ($post->is_sticky) {
                $priority = '1.0';
                $changefreq = 'daily';
            }

            $urls[] = $this->buildUrl(
                url($post->url),
                $lastmod,
                $priority,
                $changefreq
            );
        }

        // 分类
        foreach ($categories as $category) {
            $urls[] = $this->buildUrl(
                route('blog.category', $category->slug, false),
                $now,
                '0.7',
                'weekly'
            );
        }

        // 标签
        foreach ($tags as $tag) {
            $urls[] = $this->buildUrl(
                route('blog.tag', $tag->slug, false),
                $now,
                '0.6',
                'weekly'
            );
        }

        $urlElements = implode("\n        ", $urls);

        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
        {$urlElements}
</urlset>
XML;
    }

    /**
     * 构建单个 URL 元素
     */
    protected function buildUrl(
        string $loc,
        string $lastmod,
        string $priority,
        string $changefreq = 'weekly'
    ): string {
        $loc = htmlspecialchars($loc, ENT_XML1, 'UTF-8');
        return <<<XML
<url>
            <loc>{$loc}</loc>
            <lastmod>{$lastmod}</lastmod>
            <changefreq>{$changefreq}</changefreq>
            <priority>{$priority}</priority>
        </url>
XML;
    }
}
