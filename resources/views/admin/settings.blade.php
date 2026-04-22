@extends('layouts.admin')

@section('title', '系统设置')

@section('content')
<h1>系统设置</h1>

<form method="POST" action="{{ route('admin.settings.store') }}">
    @csrf

    <section class="settings-section">
        <h2>基础设置</h2>

        <div class="form-group">
            <label for="blog_name">博客名称</label>
            <input type="text" name="blog[name]" id="blog_name" value="{{ old('blog.name', config('blog.name')) }}">
        </div>

        <div class="form-group">
            <label for="blog_description">博客描述</label>
            <textarea name="blog[description]" id="blog_description" rows="2">{{ old('blog.description', config('blog.description')) }}</textarea>
        </div>

        <div class="form-group">
            <label for="blog_author_name">博主名称</label>
            <input type="text" name="blog[author][name]" id="blog_author_name" value="{{ old('blog.author.name', config('blog.author.name')) }}">
        </div>

        <div class="form-group">
            <label for="blog_author_email">博主邮箱</label>
            <input type="email" name="blog[author][email]" id="blog_author_email" value="{{ old('blog.author.email', config('blog.author.email')) }}">
        </div>
    </section>

    <section class="settings-section">
        <h2>功能设置</h2>

        <div class="form-group">
            <label for="blog_per_page">每页文章数</label>
            <input type="number" name="blog[per_page]" id="blog_per_page" value="{{ old('blog.per_page', config('blog.per_page')) }}">
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="blog[comments][enabled]" value="1" {{ config('blog.comments.enabled') ? 'checked' : '' }}>
                开启评论
            </label>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="blog[comments][require_approval]" value="1" {{ config('blog.comments.require_approval') ? 'checked' : '' }}>
                评论需要审核
            </label>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="blog[comments][allow_anonymous]" value="1" {{ config('blog.comments.allow_anonymous') ? 'checked' : '' }}>
                允许匿名评论
            </label>
        </div>

        <div class="form-group">
            <label for="blog_comments_max_depth">评论嵌套深度</label>
            <input type="number" name="blog[comments][max_depth]" id="blog_comments_max_depth" value="{{ old('blog.comments.max_depth', config('blog.comments.max_depth')) }}">
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="blog[subscription][enabled]" value="1" {{ config('blog.subscription.enabled') ? 'checked' : '' }}>
                开启订阅
            </label>
        </div>
    </section>

    <section class="settings-section">
        <h2>SEO 设置</h2>

        <div class="form-group">
            <label for="blog_seo_title_separator">标题分隔符</label>
            <input type="text" name="blog[seo][title_separator]" id="blog_seo_title_separator" value="{{ old('blog.seo.title_separator', config('blog.seo.title_separator')) }}">
        </div>

        <div class="form-group">
            <label for="blog_seo_default_keywords">默认关键词</label>
            <input type="text" name="blog[seo][default_keywords]" id="blog_seo_default_keywords" value="{{ old('blog.seo.default_keywords', config('blog.seo.default_keywords')) }}">
        </div>
    </section>

    <section class="settings-section">
        <h2>社交链接</h2>

        <div class="form-group">
            <label for="blog_social_github">GitHub</label>
            <input type="text" name="blog[social][github]" id="blog_social_github" value="{{ old('blog.social.github', config('blog.social.github')) }}">
        </div>

        <div class="form-group">
            <label for="blog_social_twitter">Twitter</label>
            <input type="text" name="blog[social][twitter]" id="blog_social_twitter" value="{{ old('blog.social.twitter', config('blog.social.twitter')) }}">
        </div>

        <div class="form-group">
            <label for="blog_social_weibo">微博</label>
            <input type="text" name="blog[social][weibo]" id="blog_social_weibo" value="{{ old('blog.social.weibo', config('blog.social.weibo')) }}">
        </div>
    </section>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">保存设置</button>
    </div>
</form>
@endsection
