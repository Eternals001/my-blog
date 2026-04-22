<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class FeedController extends Controller
{
    /**
     * RSS 2.0 格式订阅
     */
    public function rss(Request $request)
    {
        $posts = Cache::remember('feed_rss', config('blog.cache.feed_ttl', 3600), function () {
            return Post::visible()
                ->with(['author', 'category', 'tags'])
                ->orderByPublished()
                ->limit(50)
                ->get();
        });

        $content = $this->generateRss($posts);

        return Response($content, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=' . config('blog.cache.feed_ttl', 3600),
        ]);
    }

    /**
     * Atom 格式订阅
     */
    public function atom(Request $request)
    {
        $posts = Cache::remember('feed_atom', config('blog.cache.feed_ttl', 3600), function () {
            return Post::visible()
                ->with(['author', 'category', 'tags'])
                ->orderByPublished()
                ->limit(50)
                ->get();
        });

        $content = $this->generateAtom($posts);

        return Response($content, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=' . config('blog.cache.feed_ttl', 3600),
        ]);
    }

    /**
     * 生成 RSS 2.0 XML
     */
    protected function generateRss($posts): string
    {
        $blogName = config('blog.name');
        $blogDescription = config('blog.description');
        $blogUrl = config('app.url');
        $language = config('app.locale', 'zh-CN');
        $lastBuildDate = $posts->first()?->updated_at?->toRfc2822String() ?? now()->toRfc2822String();

        $items = '';
        foreach ($posts as $post) {
            $title = htmlspecialchars($post->title, ENT_XML1, 'UTF-8');
            $description = htmlspecialchars($post->excerpt ?? strip_tags($post->html_content), ENT_XML1, 'UTF-8');
            $link = url($post->url);
            $pubDate = $post->published_at?->toRfc2822String() ?? $post->updated_at->toRfc2822String();
            $guid = $link;
            $author = htmlspecialchars($post->author->email ?? config('blog.author.email'), ENT_XML1, 'UTF-8');

            $categories = '';
            if ($post->category) {
                $categories .= "<category><![CDATA[{$post->category->name}]]></category>";
            }
            foreach ($post->tags as $tag) {
                $tagName = htmlspecialchars($tag->name, ENT_XML1, 'UTF-8');
                $categories .= "<category><![CDATA[{$tagName}]]></category>";
            }

            $enclosure = '';
            if ($post->cover_image_url) {
                $enclosure = sprintf(
                    '<enclosure url="%s" type="image/jpeg" length="0"/>',
                    htmlspecialchars($post->cover_image_url, ENT_XML1, 'UTF-8')
                );
            }

            $items .= <<<XML
        <item>
            <title>{$title}</title>
            <link>{$link}</link>
            <guid isPermaLink="true">{$guid}</guid>
            <description><![CDATA[{$description}]]></description>
            <author>{$author}</author>
            <pubDate>{$pubDate}</pubDate>
            {$categories}
            {$enclosure}
        </item>
XML;
        }

        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" 
    xmlns:atom="http://www.w3.org/2005/Atom"
    xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:dc="http://purl.org/dc/elements/1.1/">
    <channel>
        <title><![CDATA[{$blogName}]]></title>
        <link>{$blogUrl}</link>
        <description><![CDATA[{$blogDescription}]]></description>
        <language>{$language}</language>
        <lastBuildDate>{$lastBuildDate}</lastBuildDate>
        <atom:link href="{$blogUrl}/feed/rss" rel="self" type="application/rss+xml"/>
        <generator>Laravel Blog</generator>
        {$items}
    </channel>
</rss>
XML;
    }

    /**
     * 生成 Atom XML
     */
    protected function generateAtom($posts): string
    {
        $blogName = config('blog.name');
        $blogDescription = config('blog.description');
        $blogUrl = config('app.url');
        $updated = $posts->first()?->updated_at?->toIso8601String() ?? now()->toIso8601String();

        $entries = '';
        foreach ($posts as $post) {
            $title = htmlspecialchars($post->title, ENT_XML1, 'UTF-8');
            $summary = htmlspecialchars($post->excerpt ?? strip_tags($post->html_content), ENT_XML1, 'UTF-8');
            $link = url($post->url);
            $id = $link;
            $published = $post->published_at?->toIso8601String() ?? $post->updated_at->toIso8601String();
            $updated = $post->updated_at->toIso8601String();
            $authorName = htmlspecialchars($post->author->name ?? 'Unknown', ENT_XML1, 'UTF-8');
            $authorEmail = htmlspecialchars($post->author->email ?? '', ENT_XML1, 'UTF-8');

            $categories = '';
            if ($post->category) {
                $catTerm = htmlspecialchars($post->category->slug, ENT_XML1, 'UTF-8');
                $categories .= "<category term=\"{$catTerm}\"/>";
            }
            foreach ($post->tags as $tag) {
                $tagTerm = htmlspecialchars($tag->slug, ENT_XML1, 'UTF-8');
                $categories .= "<category term=\"{$tagTerm}\"/>";
            }

            $entries .= <<<XML
        <entry>
            <title>{$title}</title>
            <link href="{$link}" rel="alternate"/>
            <id>{$id}</id>
            <published>{$published}</published>
            <updated>{$updated}</updated>
            <author>
                <name>{$authorName}</name>
                <email>{$authorEmail}</email>
            </author>
            <summary type="text"><![CDATA[{$summary}]]></summary>
            {$categories}
        </entry>
XML;
        }

        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
    <id>{$blogUrl}/feed/atom</id>
    <title><![CDATA[{$blogName}]]></title>
    <subtitle><![CDATA[{$blogDescription}]]></subtitle>
    <link href="{$blogUrl}" rel="alternate"/>
    <link href="{$blogUrl}/feed/atom" rel="self"/>
    <updated>{$updated}</updated>
    <generator uri="https://laravel.com">Laravel Blog</generator>
    <rights>Copyright (c) {$blogName}</rights>
    {$entries}
</feed>
XML;
    }
}
