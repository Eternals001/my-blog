@extends('layouts.admin')

@section('title', '用户管理')

@section('content')
<div class="page-header">
    <h1>用户管理</h1>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">新建用户</a>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>名称</th>
            <th>邮箱</th>
            <th>角色</th>
            <th>文章数</th>
            <th>注册时间</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @forelse($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td><span class="badge badge-{{ $user->role->color() }}">{{ $user->role->label() }}</span></td>
            <td>{{ $user->posts()->count() }}</td>
            <td>{{ $user->created_at->format('Y-m-d') }}</td>
            <td>
                <a href="{{ route('admin.users.edit', $user) }}">编辑</a>
                @if($user->id !== auth()->id())
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" onclick="return confirm('确定删除？')">删除</button>
                </form>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">暂无用户</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $users->links() }}
@endsection
