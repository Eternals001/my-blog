<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Services\ArticleService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected ArticleService $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * 文章列表
     */
    public function index(Request $request)
    {
        $posts = $this->articleService->getHomepagePosts();

        // 获取热门标签侧边栏
        $popularTags = $this->articleService->getPopularTags(10);

        return view('blog.posts.index', compact('posts', 'popularTags'));
    }

    /**
     * 文章详情
     */
    public function show(string $slug)
    {
        $post = $this->articleService->getPostDetail($slug);

        if (!$post) {
            abort(404);
        }

        // 增加浏览量
        $post->incrementViewCount();

        // 获取相关推荐
        $relatedPosts = $this->articleService->getRelatedPosts($post);

        // 获取热门文章侧边栏
        $popularPosts = $this->articleService->getPopularPosts(5);

        return view('blog.posts.show', compact('post', 'relatedPosts', 'popularPosts'));
    }
}
