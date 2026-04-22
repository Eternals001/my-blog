<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * 评论列表
     */
    public function index(Request $request)
    {
        $query = Comment::with(['post:id,title,slug', 'user:id,name', 'parent:id,author_name']);

        // 审核状态筛选
        if ($request->has('status')) {
            if ($request->status === 'pending') {
                $query->pending();
            } elseif ($request->status === 'approved') {
                $query->approved();
            }
        }

        // 搜索
        if ($search = $request->get('q')) {
            $query->where('content', 'like', "%{$search}%");
        }

        $comments = $query->latest()->paginate(20);

        return view('admin.comments.index', compact('comments'));
    }

    /**
     * 待审核评论列表
     */
    public function pending(Request $request)
    {
        $query = Comment::with(['post:id,title,slug', 'user:id,name', 'parent:id,author_name'])
            ->pending();

        // 搜索
        if ($search = $request->get('q')) {
            $query->where('content', 'like', "%{$search}%");
        }

        $comments = $query->latest()->paginate(20);

        return view('admin.comments.pending', compact('comments'));
    }

    /**
     * 批准评论
     */
    public function approve(Comment $comment)
    {
        $comment->update(['is_approved' => true]);

        return back()->with('success', '评论已批准');
    }

    /**
     * 标记为垃圾评论
     */
    public function spam(Comment $comment)
    {
        $comment->update(['is_approved' => false]);
        // 可以在这里添加额外的垃圾标记逻辑

        return back()->with('success', '评论已标记为垃圾');
    }

    /**
     * 拒绝评论
     */
    public function reject(Comment $comment)
    {
        $comment->delete();

        return back()->with('success', '评论已拒绝并删除');
    }

    /**
     * 删除评论
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return back()->with('success', '评论已删除');
    }

    /**
     * 批量批准评论
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:comments,id',
        ]);

        Comment::whereIn('id', $request->ids)->update(['is_approved' => true]);

        return back()->with('success', '选中的评论已批准');
    }

    /**
     * 批量删除评论
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:comments,id',
        ]);

        Comment::whereIn('id', $request->ids)->delete();

        return back()->with('success', '选中的评论已删除');
    }

    /**
     * 按文章查看评论
     */
    public function byPost(Post $post)
    {
        $comments = $post->comments()
            ->with(['user:id,name', 'parent:id,author_name'])
            ->latest()
            ->paginate(20);

        return view('admin.comments.by-post', compact('comments', 'post'));
    }
}
