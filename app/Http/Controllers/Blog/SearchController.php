<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Services\ArticleService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected ArticleService $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * 搜索结果页面
     */
    public function index(Request $request)
    {
        $query = $request->get('q', '');

        if (empty($query)) {
            return view('blog.search', [
                'query' => '',
                'posts' => collect(),
                'total' => 0,
            ]);
        }

        $posts = $this->articleService->search($query);
        $total = $posts->total();

        return view('blog.search', compact('query', 'posts', 'total'));
    }
}
