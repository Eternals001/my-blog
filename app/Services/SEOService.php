<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class SEOService
{
    protected array $meta = [];
    protected ?Post $post = null;

    /**
     * 从配置初始化默认 SEO 数据
     */
    public function __construct()
    {
        $this->meta = [
            'title' => config('blog.name'),
            'description' => config('blog.description'),
            'keywords' => config('blog.seo.keywords', ''),
            'author' => config('blog.author.name', ''),
            'og_type' => 'website',
            'og_image' => config('blog.seo.default_og_image', asset('images/og-image.png')),
            'canonical' => request()->url(),
            'robots' => 'index, follow',
        ];
    }

    /**
     * 设置文章 SEO 数据
     */
    public function setPost(Post $post): self
    {
        $this->post = $post;

        // 自定义 SEO 标题
        $title = $post->seo_title ?: $post->title;
        
        // 自定义 SEO 描述
        $description = $post->seo_description ?: $post->excerpt ?: $this->truncateText(strip_tags($post->html_content), 160);

        // Open Graph 图片
        $ogImage = $post->cover_image_url ?: config('blog.seo.default_og_image', asset('images/og-image.png'));

        $this->meta = array_merge($this->meta, [
            'title' => $title . ' - ' . config('blog.name'),
            'description' => $description,
            'og_type' => 'article',
            'og_image' => $ogImage,
            'article_published_time' => $post->published_at?->toIso8601String(),
            'article_modified_time' => $post->updated_at->toIso8601String(),
            'article_author' => $post->author->name ?? '',
            'article_section' => $post->category?->name ?? '',
            'article_tag' => $post->tags->pluck('name')->toArray(),
        ]);

        return $this;
    }

    /**
     * 设置页面标题
     */
    public function setTitle(string $title, bool $suffix = true): self
    {
        $this->meta['title'] = $suffix 
            ? $title . ' - ' . config('blog.name')
            : $title;
        return $this;
    }

    /**
     * 设置描述
     */
    public function setDescription(string $description): self
    {
        $this->meta['description'] = $description;
        return $this;
    }

    /**
     * 设置关键词
     */
    public function setKeywords(array|string $keywords): self
    {
        $this->meta['keywords'] = is_array($keywords) 
            ? implode(', ', $keywords) 
            : $keywords;
        return $this;
    }

    /**
     * 设置 Open Graph 图片
     */
    public function setOgImage(string $image): self
    {
        $this->meta['og_image'] = $image;
        return $this;
    }

    /**
     * 设置 Canonical URL
     */
    public function setCanonical(string $url): self
    {
        $this->meta['canonical'] = $url;
        return $this;
    }

    /**
     * 设置 robots
     */
    public function setRobots(string $robots): self
    {
        $this->meta['robots'] = $robots;
        return $this;
    }

    /**
     * 生成结构化数据（JSON-LD）
     */
    public function generateJsonLd(): string
    {
        if ($this->post) {
            return $this->generateArticleSchema();
        }

        return $this->generateWebsiteSchema();
    }

    /**
     * 生成网站结构化数据
     */
    protected function generateWebsiteSchema(): string
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => config('blog.name'),
            'url' => config('app.url'),
            'description' => config('blog.description'),
            'publisher' => [
                '@type' => 'Person',
                'name' => config('blog.author.name'),
                'email' => config('blog.author.email'),
            ],
        ];

        // 如果有搜索功能
        if (route('blog.search', [], false)) {
            $schema['potentialAction'] = [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => url(route('blog.search', [], false) . '?q={search_term_string}'),
                ],
                'query-input' => 'required name=search_term_string',
            ];
        }

        return json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * 生成文章结构化数据
     */
    protected function generateArticleSchema(): string
    {
        $post = $this->post;

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $post->title,
            'description' => $post->seo_description ?: $post->excerpt ?: $this->truncateText(strip_tags($post->html_content), 160),
            'image' => $post->cover_image_url,
            'datePublished' => $post->published_at?->toIso8601String(),
            'dateModified' => $post->updated_at->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $post->author->name ?? 'Unknown',
                'url' => route('blog.author', $post->author, false),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => config('blog.name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => config('blog.logo') ?: asset('images/logo.png'),
                ],
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => url($post->url),
            ],
            'keywords' => $post->tags->pluck('name')->implode(', '),
        ];

        // 添加分类
        if ($post->category) {
            $schema['articleSection'] = $post->category->name;
        }

        // 添加标签
        if ($post->tags->isNotEmpty()) {
            $schema['keywords'] = $post->tags->pluck('name')->toArray();
        }

        return json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * 生成 BreadcrumbList 结构化数据
     */
    public function generateBreadcrumbSchema(array $items): string
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [],
        ];

        $position = 1;
        foreach ($items as $item) {
            $schema['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $position,
                'name' => $item['name'],
                'item' => $item['url'],
            ];
            $position++;
        }

        return json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * 获取所有 meta 标签
     */
    public function getMetaTags(): array
    {
        return $this->meta;
    }

    /**
     * 生成 HTML meta 标签
     */
    public function renderMetaTags(): string
    {
        $html = [];

        // 基础 meta
        $html[] = '<title>' . e($this->meta['title']) . '</title>';
        $html[] = '<meta name="description" content="' . e($this->meta['description']) . '">';
        $html[] = '<meta name="keywords" content="' . e($this->meta['keywords']) . '">';
        $html[] = '<meta name="author" content="' . e($this->meta['author']) . '">';
        $html[] = '<meta name="robots" content="' . e($this->meta['robots']) . '">';
        $html[] = '<link rel="canonical" href="' . e($this->meta['canonical']) . '">';

        // Open Graph
        $html[] = '<meta property="og:title" content="' . e($this->meta['title']) . '">';
        $html[] = '<meta property="og:description" content="' . e($this->meta['description']) . '">';
        $html[] = '<meta property="og:type" content="' . e($this->meta['og_type']) . '">';
        $html[] = '<meta property="og:url" content="' . e($this->meta['canonical']) . '">';
        $html[] = '<meta property="og:image" content="' . e($this->meta['og_image']) . '">';
        $html[] = '<meta property="og:site_name" content="' . e(config('blog.name')) . '">';
        $html[] = '<meta property="og:locale" content="' . app()->getLocale() . '_' . strtoupper(app()->getLocale()) . '">';

        // Twitter Card
        $html[] = '<meta name="twitter:card" content="summary_large_image">';
        $html[] = '<meta name="twitter:title" content="' . e($this->meta['title']) . '">';
        $html[] = '<meta name="twitter:description" content="' . e($this->meta['description']) . '">';
        $html[] = '<meta name="twitter:image" content="' . e($this->meta['og_image']) . '">';

        // 文章特定 meta
        if ($this->post) {
            if (!empty($this->meta['article_published_time'])) {
                $html[] = '<meta property="article:published_time" content="' . e($this->meta['article_published_time']) . '">';
            }
            if (!empty($this->meta['article_modified_time'])) {
                $html[] = '<meta property="article:modified_time" content="' . e($this->meta['article_modified_time']) . '">';
            }
            if (!empty($this->meta['article_author'])) {
                $html[] = '<meta property="article:author" content="' . e($this->meta['article_author']) . '">';
            }
            if (!empty($this->meta['article_section'])) {
                $html[] = '<meta property="article:section" content="' . e($this->meta['article_section']) . '">';
            }

            // 标签
            foreach ($this->meta['article_tag'] as $tag) {
                $html[] = '<meta property="article:tag" content="' . e($tag) . '">';
            }
        }

        return implode("\n        ", $html);
    }

    /**
     * 截断文本
     */
    protected function truncateText(string $text, int $length = 160): string
    {
        $text = trim(strip_tags($text));
        
        if (mb_strlen($text) <= $length) {
            return $text;
        }

        return mb_substr($text, 0, $length) . '...';
    }

    /**
     * 静态方法：快速获取 SEO 标签
     */
    public static function forPost(Post $post): self
    {
        return (new self())->setPost($post);
    }

    /**
     * 静态方法：快速获取页面 SEO 标签
     */
    public static function forPage(string $title, string $description = ''): self
    {
        return (new self())
            ->setTitle($title, false)
            ->setDescription($description ?: config('blog.description'));
    }
}
