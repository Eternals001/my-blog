@extends('layouts.admin')

@section('title', '编辑用户')

@section('content')
<h1>编辑用户</h1>

<form method="POST" action="{{ route('admin.users.update', $user) }}">
    @csrf @method('PUT')

    <div class="form-group">
        <label for="name">名称</label>
        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
    </div>

    <div class="form-group">
        <label for="email">邮箱</label>
        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
    </div>

    <div class="form-group">
        <label for="password">新密码 (留空不修改)</label>
        <input type="password" name="password" id="password">
    </div>

    <div class="form-group">
        <label for="password_confirmation">确认新密码</label>
        <input type="password" name="password_confirmation" id="password_confirmation">
    </div>

    @if(auth()->user()->isAdmin())
    <div class="form-group">
        <label for="role">角色</label>
        <select name="role" id="role">
            <option value="subscriber" {{ old('role', $user->role->value) == 'subscriber' ? 'selected' : '' }}>订阅者</option>
            <option value="editor" {{ old('role', $user->role->value) == 'editor' ? 'selected' : '' }}>编辑</option>
            <option value="admin" {{ old('role', $user->role->value) == 'admin' ? 'selected' : '' }}>管理员</option>
        </select>
    </div>
    @endif

    <div class="form-group">
        <label for="bio">个人简介</label>
        <textarea name="bio" id="bio" rows="3">{{ old('bio', $user->bio) }}</textarea>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">保存</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">取消</a>
    </div>
</form>
@endsection
