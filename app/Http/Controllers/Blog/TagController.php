<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Services\ArticleService;
use Illuminate\Http\Request;

class TagController extends Controller
{
    protected ArticleService $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * 标签文章列表
     */
    public function show(string $slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        $posts = $this->articleService->getPostsByTag($slug);

        // 热门标签
        $popularTags = $this->articleService->getPopularTags(10);

        return view('blog.tags.show', compact('tag', 'posts', 'popularTags'));
    }
}
