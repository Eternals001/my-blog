{{-- resources/views/user/settings.blade.php --}}
{{-- 用户设置页面 --}}

@extends('layouts.app')

@section('title', '账户设置')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">账户设置</h1>

    {{-- 提示消息 --}}
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('status'))
        <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm text-blue-700 dark:text-blue-300">{{ session('status') }}</span>
            </div>
        </div>
    @endif

    {{-- 邮箱验证提示 --}}
    @if (!auth()->user()->hasVerifiedEmail())
        <div class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div class="flex-1">
                    <p class="text-sm text-yellow-700 dark:text-yellow-300 mb-2">
                        您的邮箱尚未验证，请验证邮箱以获取完整功能。
                    </p>
                    <form action="{{ route('user.verification.send') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-yellow-700 dark:text-yellow-300 underline hover:no-underline">
                            点击发送验证邮件
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- 侧边栏 --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="text-center mb-6">
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-24 h-24 rounded-full mx-auto mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-2
                        {{ auth()->user()->role->color() === 'red' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' : '' }}
                        {{ auth()->user()->role->color() === 'blue' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : '' }}
                        {{ auth()->user()->role->color() === 'gray' ? 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300' : '' }}">
                        {{ auth()->user()->role->label() }}
                    </span>
                </div>
                
                <nav class="space-y-1">
                    <a href="#profile" class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-700 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        个人资料
                    </a>
                    <a href="#password" class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        修改密码
                    </a>
                    <a href="#security" class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        安全设置
                    </a>
                </nav>
            </div>
        </div>

        {{-- 主内容 --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- 个人资料 --}}
            <div id="profile" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">个人资料</h2>
                
                <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        {{-- 头像 --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">头像</label>
                            <div class="flex items-center gap-4">
                                <img src="{{ auth()->user()->avatar_url }}" alt="头像预览" class="w-20 h-20 rounded-full object-cover">
                                <div>
                                    <input type="file" name="avatar" id="avatar" accept="image/*" class="hidden" onchange="previewAvatar(this)">
                                    <label for="avatar" class="cursor-pointer inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        选择图片
                                    </label>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">支持 JPG、PNG、GIF 格式，最大 2MB</p>
                                </div>
                            </div>
                            @error('avatar')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- 昵称 --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">昵称</label>
                            <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- 邮箱 --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">邮箱</label>
                            <div class="flex items-center gap-3">
                                <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}"
                                    class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white @error('email') border-red-500 @enderror">
                                @if (auth()->user()->hasVerifiedEmail())
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                        已验证
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                        未验证
                                    </span>
                                @endif
                            </div>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- 个人简介 --}}
                        <div>
                            <label for="bio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">个人简介</label>
                            <textarea name="bio" id="bio" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white resize-none @error('bio') border-red-500 @enderror">{{ old('bio', auth()->user()->bio) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">最多 500 字</p>
                            @error('bio')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                            保存更改
                        </button>
                    </div>
                </form>
            </div>

            {{-- 修改密码 --}}
            <div id="password" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">修改密码</h2>
                
                <form action="{{ route('user.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        {{-- 当前密码 --}}
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">当前密码</label>
                            <input type="password" name="current_password" id="current_password"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white @error('current_password') border-red-500 @enderror">
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- 新密码 --}}
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">新密码</label>
                            <input type="password" name="password" id="password"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white @error('password') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">至少 8 个字符，必须包含大小写字母和数字</p>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- 确认新密码 --}}
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">确认新密码</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                            更新密码
                        </button>
                    </div>
                </form>
            </div>

            {{-- 安全设置 --}}
            <div id="security" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">安全设置</h2>
                
                <div class="space-y-4">
                    {{-- 邮箱验证状态 --}}
                    <div class="flex items-center justify-between py-4 border-b border-gray-200 dark:border-gray-700">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">邮箱验证</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                @if (auth()->user()->hasVerifiedEmail())
                                    您的邮箱已验证
                                @else
                                    您的邮箱尚未验证，点击发送验证邮件
                                @endif
                            </p>
                        </div>
                        @if (!auth()->user()->hasVerifiedEmail())
                            <form action="{{ route('user.verification.send') }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700">
                                    发送验证邮件
                                </button>
                            </form>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                已验证
                            </span>
                        @endif
                    </div>

                    {{-- 活跃设备 --}}
                    <div class="flex items-center justify-between py-4 border-b border-gray-200 dark:border-gray-700">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">活跃会话</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">在其他设备上的登录状态</p>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700">
                                退出其他设备
                            </button>
                        </form>
                    </div>

                    {{-- 账户操作 --}}
                    <div class="pt-4">
                        <h3 class="text-sm font-medium text-red-600 dark:text-red-400 mb-2">危险区域</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                            删除您的账户和所有相关数据。此操作不可撤销。
                        </p>
                        <button type="button" onclick="confirmDeleteAccount()"
                            class="px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 border border-red-300 dark:border-red-700 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20">
                            删除账户
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // 头像预览
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                input.parentElement.previousElementSibling.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // 确认删除账户
    function confirmDeleteAccount() {
        if (confirm('确定要删除您的账户吗？此操作不可撤销。')) {
            const confirmText = prompt('请输入 DELETE 以确认：');
            if (confirmText === 'DELETE') {
                // 提交删除表单
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('user.account.delete') }}';
                form.innerHTML = `
                    @csrf
                    <input type="hidden" name="password" value="">
                    <input type="hidden" name="confirm" value="DELETE">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    }

    // 平滑滚动到锚点
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
</script>
@endpush
