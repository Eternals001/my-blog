@extends('layouts.admin')

@section('title', '新建用户')

@section('content')
<h1>新建用户</h1>

<form method="POST" action="{{ route('admin.users.store') }}">
    @csrf

    <div class="form-group">
        <label for="name">名称</label>
        <input type="text" name="name" id="name" value="{{ old('name') }}" required>
    </div>

    <div class="form-group">
        <label for="email">邮箱</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}" required>
    </div>

    <div class="form-group">
        <label for="password">密码</label>
        <input type="password" name="password" id="password" required>
    </div>

    <div class="form-group">
        <label for="password_confirmation">确认密码</label>
        <input type="password" name="password_confirmation" id="password_confirmation" required>
    </div>

    <div class="form-group">
        <label for="role">角色</label>
        <select name="role" id="role">
            <option value="subscriber" {{ old('role') == 'subscriber' ? 'selected' : '' }}>订阅者</option>
            <option value="editor" {{ old('role') == 'editor' ? 'selected' : '' }}>编辑</option>
            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>管理员</option>
        </select>
    </div>

    <div class="form-group">
        <label for="bio">个人简介</label>
        <textarea name="bio" id="bio" rows="3">{{ old('bio') }}</textarea>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">创建</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">取消</a>
    </div>
</form>
@endsection
