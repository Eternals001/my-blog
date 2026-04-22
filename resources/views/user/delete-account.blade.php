{{-- resources/views/user/delete-account.blade.php --}}
{{-- 删除账户确认页面 --}}

@extends('layouts.app')

@section('title', '删除账户')

@section('content')
<div class="max-w-md mx-auto py-12 px-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-red-200 dark:border-red-800 p-8">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-2">删除账户</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                即将永久删除您的账户和所有相关数据，包括：
            </p>
            <ul class="text-sm text-gray-600 dark:text-gray-400 mt-2 text-left list-disc list-inside">
                <li>您的个人资料和头像</li>
                <li>您发布的评论</li>
                <li>您的订阅记录</li>
            </ul>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                @foreach ($errors->all() as $error)
                    <p class="text-sm text-red-700 dark:text-red-300">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('user.account.delete') }}" method="POST">
            @csrf
            @method('DELETE')

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    输入密码以确认
                </label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
            </div>

            <div class="mb-6">
                <label for="confirm" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    输入 <span class="font-mono bg-gray-100 dark:bg-gray-700 px-1 rounded">DELETE</span> 以确认
                </label>
                <input type="text" name="confirm" id="confirm" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
            </div>

            <div class="flex gap-3">
                <a href="{{ route('user.settings') }}" class="flex-1 px-4 py-2 text-center border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                    取消
                </a>
                <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    确认删除
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
