<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * 用户列表
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role = $request->get('role')) {
            $query->where('role', $role);
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * 创建用户页面
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * 保存用户
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'role' => ['nullable', Rule::in(UserRole::values())],
            'bio' => 'nullable|string|max:500',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        if (!isset($validated['role'])) {
            $validated['role'] = UserRole::SUBSCRIBER;
        }

        $user = User::create($validated);

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('success', '用户创建成功');
    }

    /**
     * 编辑用户页面
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * 更新用户
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:100',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'bio' => 'nullable|string|max:500',
        ];

        // 如果要更新密码
        if ($request->filled('password')) {
            $rules['password'] = 'confirmed|min:8';
        }

        // 只有管理员可以修改角色
        if (auth()->user()->isAdmin()) {
            $rules['role'] = ['nullable', Rule::in(UserRole::values())];
        }

        $validated = $request->validate($rules);

        // 如果要更新密码
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return back()->with('success', '用户更新成功');
    }

    /**
     * 删除用户
     */
    public function destroy(User $user)
    {
        // 不能删除自己
        if ($user->id === auth()->id()) {
            return back()->with('error', '不能删除自己');
        }

        // 不能删除最后一个管理员
        if ($user->isAdmin() && User::admins()->count() <= 1) {
            return back()->with('error', '不能删除最后一个管理员');
        }

        // 将用户的文章转移给第一个管理员
        $admin = User::admins()->first();
        if ($admin) {
            $user->posts()->update(['user_id' => $admin->id]);
        }

        // 评论保留但标记为已删除用户
        $user->comments()->update([
            'author_name' => '[已删除用户]',
            'user_id' => null,
        ]);

        // 记录管理员操作日志（如果 AdminLog 模型存在）
        $this->logAdminAction('delete_user', 'User', $user->id);

        // 软删除用户
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', '用户已删除');
    }

    /**
     * 记录管理操作日志
     */
    protected function logAdminAction(string $action, string $targetType, int $targetId): void
    {
        // 检查 AdminLog 模型是否存在
        if (class_exists(\App\Models\AdminLog::class)) {
            \App\Models\AdminLog::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'target_type' => $targetType,
                'target_id' => $targetId,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }
    }
}
