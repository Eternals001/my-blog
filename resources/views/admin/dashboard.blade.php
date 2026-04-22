@extends('layouts.admin')

@section('title', '仪表盘')

@section('content')
<h1>仪表盘</h1>

<div class="stats-grid">
    <div class="stat-card">
        <h3>文章总数</h3>
        <p class="stat-value">{{ $stats['posts_count'] }}</p>
        <p class="stat-detail">已发布: {{ $stats['published_posts'] }} | 草稿: {{ $stats['draft_posts'] }}</p>
    </div>
    <div class="stat-card">
        <h3>评论总数</h3>
        <p class="stat-value">{{ $stats['comments_count'] }}</p>
        <p class="stat-detail">待审核: {{ $stats['pending_comments'] }}</p>
    </div>
    <div class="stat-card">
        <h3>用户总数</h3>
        <p class="stat-value">{{ $stats['users_count'] }}</p>
    </div>
    <div class="stat-card">
        <h3>订阅总数</h3>
        <p class="stat-value">{{ $stats['subscriptions_count'] }}</p>
    </div>
</div>

<div class="dashboard-grid">
    <section class="dashboard-section">
        <h2>最近文章</h2>
        <table>
            <thead>
                <tr>
                    <th>标题</th>
                    <th>作者</th>
                    <th>状态</th>
                    <th>发布时间</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentPosts as $post)
                <tr>
                    <td><a href="{{ route('admin.posts.edit', $post) }}">{{ $post->title }}</a></td>
                    <td>{{ $post->author->name ?? '未知' }}</td>
                    <td><span class="badge badge-{{ $post->status->color() }}">{{ $post->status->label() }}</span></td>
                    <td>{{ $post->published_at?->format('Y-m-d') ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">暂无文章</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <section class="dashboard-section">
        <h2>热门文章</h2>
        <table>
            <thead>
                <tr>
                    <th>标题</th>
                    <th>浏览量</th>
                </tr>
            </thead>
            <tbody>
                @forelse($popularPosts as $post)
                <tr>
                    <td><a href="{{ route('blog.post', $post->slug) }}" target="_blank">{{ $post->title }}</a></td>
                    <td>{{ $post->view_count }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2">暂无数据</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </section>
</div>
@endsection
