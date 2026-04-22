<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    /**
     * 标签列表
     */
    public function index(Request $request)
    {
        $query = Tag::withCount('publishedPosts');

        if ($search = $request->get('q')) {
            $query->search($search);
        }

        $tags = $query->orderBy('name')->paginate(20);

        return view('admin.tags.index', compact('tags'));
    }

    /**
     * 创建标签页面
     */
    public function create()
    {
        return view('admin.tags.create');
    }

    /**
     * 保存标签
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'slug' => ['nullable', 'string', 'max:50', Rule::unique('tags', 'slug')],
        ]);

        // 生成 slug
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        Tag::create($validated);

        return redirect()
            ->route('admin.tags.index')
            ->with('success', '标签创建成功');
    }

    /**
     * 编辑标签页面
     */
    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * 更新标签
     */
    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'slug' => ['nullable', 'string', 'max:50', Rule::unique('tags', 'slug')->ignore($tag->id)],
        ]);

        // 生成 slug
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $tag->update($validated);

        return back()->with('success', '标签更新成功');
    }

    /**
     * 删除标签
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()
            ->route('admin.tags.index')
            ->with('success', '标签已删除');
    }
}
