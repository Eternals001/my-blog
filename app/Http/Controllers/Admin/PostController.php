<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PostStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    protected PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * 文章列表
     */
    public function index(Request $request)
    {
        $query = Post::with(['author:id,name', 'category:id,name']);

        // 状态筛选
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        // 分类筛选
        if ($categoryId = $request->get('category')) {
            $query->where('category_id', $categoryId);
        }

        // 搜索
        if ($search = $request->get('q')) {
            $query->search($search);
        }

        // 排序
        $sortBy = $request->get('sort', 'created_at');
        $sortDir = $request->get('direction', 'desc');

        if (in_array($sortBy, ['title', 'view_count', 'published_at', 'created_at'])) {
            $query->orderBy($sortBy, $sortDir === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest();
        }

        $posts = $query->paginate(15)->withQueryString();
        $categories = Category::orderByOrder()->get();

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    /**
     * 创建文章页面
     */
    public function create()
    {
        $categories = Category::orderByOrder()->get();
        $tags = Tag::all();

        return view('admin.posts.create', compact('categories', 'tags'));
    }

    /**
     * 保存文章
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'cover_image' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'status' => ['nullable', Rule::in(PostStatus::values())],
            'published_at' => 'nullable|date',
            'is_sticky' => 'nullable|boolean',
        ]);

        // 作者为当前用户
        $validated['user_id'] = auth()->id();

        // 使用 PostService 创建文章
        $post = $this->postService->create($validated);

        return redirect()
            ->route('admin.posts.edit', $post)
            ->with('success', '文章创建成功');
    }

    /**
     * 编辑文章页面
     */
    public function edit(Post $post)
    {
        $categories = Category::orderByOrder()->get();
        $tags = Tag::all();
        $post->load('tags:id,name,slug');

        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * 更新文章
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('posts')->ignore($post->id)],
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'cover_image' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'status' => ['nullable', Rule::in(PostStatus::values())],
            'published_at' => 'nullable|date',
            'is_sticky' => 'nullable|boolean',
        ]);

        // 使用 PostService 更新文章
        $this->postService->update($post, $validated);

        return back()->with('success', '文章更新成功');
    }

    /**
     * 删除文章（软删除）
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('success', '文章已删除');
    }

    /**
     * 发布文章
     */
    public function publish(Post $post)
    {
        $this->postService->publish($post);

        return back()->with('success', '文章已发布');
    }

    /**
     * 取消发布文章
     */
    public function unpublish(Post $post)
    {
        $this->postService->unpublish($post);

        return back()->with('success', '文章已取消发布');
    }

    /**
     * 切换置顶状态
     */
    public function sticky(Post $post)
    {
        $this->postService->toggleSticky($post);

        $message = $post->is_sticky ? '文章已取消置顶' : '文章已置顶';

        return back()->with('success', $message);
    }

    /**
     * 批量操作
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|string|in:publish,unpublish,sticky,unsticky,delete',
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:posts,id',
        ]);

        $results = $this->postService->bulkAction($request->ids, $request->action);

        $message = "成功处理 {$results['success']} 篇文章";
        if ($results['failed'] > 0) {
            $message .= "，{$results['failed']} 篇处理失败";
        }

        return back()->with('success', $message);
    }
}
