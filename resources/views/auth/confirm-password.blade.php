@extends('layouts.app')

@section('title', '确认密码')

@section('content')
<div class="auth-container">
    <h1>确认密码</h1>

    <p>请先确认您的密码以继续。</p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="form-group">
            <label for="password">密码</label>
            <input type="password" name="password" id="password" required>
            @error('password')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">确认</button>
    </form>
</div>
@endsection
