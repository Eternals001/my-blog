<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * 分类列表
     */
    public function index()
    {
        $categories = Category::with('parent:id,name')
            ->orderByOrder()
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * 创建分类页面
     */
    public function create()
    {
        $parentCategories = Category::root()->orderByOrder()->get();

        return view('admin.categories.create', compact('parentCategories'));
    }

    /**
     * 保存分类
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => ['nullable', 'string', 'max:100', Rule::unique('categories', 'slug')],
            'description' => 'nullable|string|max:500',
            'parent_id' => 'nullable|exists:categories,id',
            'order' => 'nullable|integer|min:0',
        ]);

        // 生成 slug
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        Category::create($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', '分类创建成功');
    }

    /**
     * 编辑分类页面
     */
    public function edit(Category $category)
    {
        $parentCategories = Category::root()
            ->where('id', '!=', $category->id)
            ->orderByOrder()
            ->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * 更新分类
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => ['nullable', 'string', 'max:100', Rule::unique('categories', 'slug')->ignore($category->id)],
            'description' => 'nullable|string|max:500',
            'parent_id' => ['nullable', 'exists:categories,id', function ($attribute, $value, $fail) use ($category) {
                if ($value == $category->id) {
                    $fail('不能将自己设为父分类');
                }
                // 防止选择后代作为父分类
                if (in_array($value, $category->getAllDescendantIds())) {
                    $fail('不能选择后代分类作为父分类');
                }
            }],
            'order' => 'nullable|integer|min:0',
        ]);

        // 生成 slug
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category->update($validated);

        return back()->with('success', '分类更新成功');
    }

    /**
     * 删除分类
     */
    public function destroy(Request $request, Category $category)
    {
        // 如果有子分类，将子分类的父级设为当前分类的父级
        if ($category->children()->exists()) {
            $newParentId = $category->parent_id;
            $category->children()->update(['parent_id' => $newParentId]);
        }

        // 如果有文章，将文章转移到父分类或 null（无分类）
        $targetCategoryId = $request->input('move_to') ?? $category->parent_id;
        $category->posts()->update(['category_id' => $targetCategoryId]);

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', '分类已删除，相关文章已转移');
    }
}
