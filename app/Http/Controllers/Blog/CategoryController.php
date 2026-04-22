<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\ArticleService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected ArticleService $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * 分类文章列表
     */
    public function show(string $slug)
    {
        $category = Category::where('slug', $slug)
            ->with(['children'])
            ->firstOrFail();

        // 获取该分类及其子分类的所有 ID
        $categoryIds = $category->getAllDescendantIds();
        $categoryIds[] = $category->id;

        $posts = $this->articleService->getPostsByCategory($slug);

        // 获取该分类的所有子分类
        $subcategories = $category->children()->withCount('publishedPosts')->get();

        // 热门标签
        $popularTags = $this->articleService->getPopularTags(10);

        return view('blog.categories.show', compact('category', 'posts', 'subcategories', 'popularTags'));
    }
}
