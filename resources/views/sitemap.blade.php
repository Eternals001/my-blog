{{--<?xml version="1.0" encoding="UTF-8"?>--}}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
    {{-- 首页 --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->toIso8601String() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    
    {{-- 文章 --}}
    @foreach($posts as $post)
    <url>
        <loc>{{ url($post->url) }}</loc>
        <lastmod>{{ $post->updated_at->toIso8601String() }}</lastmod>
        <changefreq>{{ $post->is_sticky ? 'daily' : 'weekly' }}</changefreq>
        <priority>{{ $post->is_sticky ? '1.0' : '0.9' }}</priority>
    </url>
    @endforeach
    
    {{-- 分类 --}}
    @foreach($categories as $category)
    <url>
        <loc>{{ route('blog.category', $category->slug, false) }}</loc>
        <lastmod>{{ $category->updated_at?->toIso8601String() ?? now()->toIso8601String() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach
    
    {{-- 标签 --}}
    @foreach($tags as $tag)
    <url>
        <loc>{{ route('blog.tag', $tag->slug, false) }}</loc>
        <lastmod>{{ $tag->updated_at?->toIso8601String() ?? now()->toIso8601String() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach
</urlset>
