{{-- resources/views/auth/change-password.blade.php --}}
{{-- 修改密码页面 --}}

@extends('layouts.app')

@section('title', '修改密码')

@section('content')
<div class="max-w-md mx-auto py-12 px-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-8">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">修改密码</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                请输入您的新密码
            </p>
        </div>

        {{-- 状态消息 --}}
        @if (session('status'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-sm text-green-700 dark:text-green-300">{{ session('status') }}</span>
                </div>
            </div>
        @endif

        {{-- 错误消息 --}}
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm text-red-700 dark:text-red-300">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                {{-- 当前密码 --}}
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        当前密码
                    </label>
                    <input type="password" name="current_password" id="current_password" required autofocus
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white @error('current_password') border-red-500 @enderror"
                        placeholder="请输入当前密码">
                </div>

                {{-- 新密码 --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        新密码
                    </label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white @error('password') border-red-500 @enderror"
                        placeholder="请输入新密码">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        至少 8 个字符，必须包含大小写字母和数字
                    </p>
                </div>

                {{-- 确认新密码 --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        确认新密码
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                        placeholder="请再次输入新密码">
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 font-medium">
                    更新密码
                </button>
            </div>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('user.settings') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
                返回设置页面
            </a>
        </div>
    </div>
</div>
@endsection
