<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * 作者文章列表
     */
    public function show(User $user)
    {
        $posts = $user->publishedPosts()
            ->orderByPublished()
            ->paginate(config('blog.per_page', 10));

        return view('blog.authors.show', compact('user', 'posts'));
    }
}
