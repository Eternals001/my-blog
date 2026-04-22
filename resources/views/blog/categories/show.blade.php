@extends('layouts.app')

@section('title', $category->name)

@section('content')
<section class="category-header">
    <h1>{{ $category->name }}</h1>
    @if($category->description)
    <p class="category-description">{{ $category->description }}</p>
    @endif
</section>

<section class="posts-list">
    @forelse($posts as $post)
    <article class="post-card">
        <h2><a href="{{ route('blog.post', $post->slug) }}">{{ $post->title }}</a></h2>
        <div class="post-meta">
            <span>{{ $post->author->name }}</span>
            <span>{{ $post->published_at?->format('Y-m-d') }}</span>
        </div>
        <p class="post-excerpt">{{ $post->excerpt ?? Str::limit(strip_tags($post->html_content), 150) }}</p>
    </article>
    @empty
    <p>该分类下暂无文章</p>
    @endforelse
</section>

{{ $posts->links() }}
@endsection
