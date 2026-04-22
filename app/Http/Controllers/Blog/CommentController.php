<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Services\SettingsService;
use App\Services\SpamDetectionService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected SpamDetectionService $spamService;
    protected SettingsService $settingsService;

    public function __construct(SpamDetectionService $spamService, SettingsService $settingsService)
    {
        $this->spamService = $spamService;
        $this->settingsService = $settingsService;
    }

    /**
     * 存储新评论
     */
    public function store(Request $request, Post $post)
    {
        // 检查评论是否开启
        if (!$this->settingsService->isCommentsEnabled()) {
            return back()->with('error', '评论功能已关闭');
        }

        // 检查文章是否可以评论
        if (!$post->canComment()) {
            return back()->with('error', '该文章不允许评论');
        }

        $rules = [
            'content' => 'required|string|min:3|max:2000',
        ];

        // 检查是否允许匿名评论
        if (!$this->settingsService->allowsAnonymousComments() && !auth()->check()) {
            $rules['author_name'] = 'required|string|max:100';
            $rules['author_email'] = 'required|email|max:255';
            $rules['author_url'] = 'nullable|url|max:500';
        }

        $request->validate($rules);

        // 垃圾评论检测
        if ($this->spamService->isSpam($request)) {
            // 记录 IP
            $this->spamService->recordIpAddress($request->ip());

            return back()
                ->with('error', '您的评论可能被视为垃圾评论，请修改后重试')
                ->withInput();
        }

        $data = [
            'post_id' => $post->id,
            'content' => $request->content,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'is_approved' => !$this->settingsService->requiresCommentApproval(),
        ];

        // 如果用户已登录
        if (auth()->check()) {
            $data['user_id'] = auth()->id();
            $data['author_name'] = auth()->user()->name;
            $data['author_email'] = auth()->user()->email;
        } else {
            $data['author_name'] = $request->author_name;
            $data['author_email'] = $request->author_email;
            $data['author_url'] = $request->author_url;
        }

        // 父评论
        if ($request->parent_id) {
            $parentComment = Comment::where('id', $request->parent_id)
                ->where('post_id', $post->id)
                ->first();

            if ($parentComment) {
                $data['parent_id'] = $parentComment->id;
            }
        }

        Comment::create($data);

        $message = $this->settingsService->requiresCommentApproval()
            ? '评论已提交，等待审核'
            : '评论发布成功';

        return back()->with('success', $message);
    }
}
