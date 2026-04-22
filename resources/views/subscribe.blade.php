@extends('layouts.app')

@section('title', '订阅博客 - ' . config('blog.name'))

@section('meta')
<meta name="description" content="订阅 {{ config('blog.name') }}，第一时间获取最新文章和博客更新">
<meta name="robots" content="noindex, nofollow">
@endsection

@section('content')
<div class="max-w-2xl mx-auto px-4 py-16">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
        <div class="text-center mb-8">
            <div class="text-5xl mb-4">📬</div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-3">
                订阅博客更新
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                订阅后，您将在新文章发布时收到邮件通知
            </p>
        </div>

        @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="text-green-700 dark:text-green-400">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if(session('info'))
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <span class="text-blue-700 dark:text-blue-400">{{ session('info') }}</span>
            </div>
        </div>
        @endif

        @error('email')
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span class="text-red-700 dark:text-red-400">{{ $message }}</span>
            </div>
        </div>
        @enderror

        <form action="{{ route('subscribe.subscribe') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    邮箱地址
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    placeholder="your@email.com"
                    required
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg 
                           focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 
                           focus:border-transparent dark:bg-gray-700 dark:text-white
                           transition-colors"
                >
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    我们不会向第三方透露您的邮箱地址
                </p>
            </div>

            <button 
                type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg
                       transition-colors duration-200 flex items-center justify-center space-x-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>订阅</span>
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                订阅的好处
            </h3>
            <ul class="space-y-3">
                <li class="flex items-start space-x-3">
                    <span class="text-xl">📝</span>
                    <span class="text-gray-600 dark:text-gray-400">第一时间获取最新文章</span>
                </li>
                <li class="flex items-start space-x-3">
                    <span class="text-xl">🔔</span>
                    <span class="text-gray-600 dark:text-gray-400">重要更新自动通知</span>
                </li>
                <li class="flex items-start space-x-3">
                    <span class="text-xl">🔒</span>
                    <span class="text-gray-600 dark:text-gray-400">完全免费，完全可取消</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
