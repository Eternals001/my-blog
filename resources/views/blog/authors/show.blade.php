@extends('layouts.app')

@section('title', $user->name . '的文章')

@section('content')
<section class="author-header">
    <h1>{{ $user->name }}</h1>
    @if($user->bio)
    <p class="author-bio">{{ $user->bio }}</p>
    @endif
    <p class="author-stats">共 {{ $posts->total() }} 篇文章</p>
</section>

<section class="posts-list">
    @forelse($posts as $post)
    <article class="post-card">
        <h2><a href="{{ route('blog.post', $post->slug) }}">{{ $post->title }}</a></h2>
        <div class="post-meta">
            <span>{{ $post->published_at?->format('Y-m-d') }}</span>
            <span>浏览 {{ $post->view_count }} 次</span>
        </div>
        <p class="post-excerpt">{{ $post->excerpt ?? Str::limit(strip_tags($post->html_content), 150) }}</p>
    </article>
    @empty
    <p>该作者暂无已发布的文章</p>
    @endforelse
</section>

{{ $posts->links() }}
@endsection
