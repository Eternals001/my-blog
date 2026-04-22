@extends('layouts.admin')

@section('title', '数据备份 - 管理后台')

@section('header', '数据备份')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-lg shadow">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">备份列表</h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.backups.create', ['type' => 'full']) }}" 
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        完整备份
                    </span>
                </a>
            </div>
        </div>
    </div>

    <div class="p-6">
        @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

        @if(empty($backups))
        <div class="text-center py-12">
            <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
            </svg>
            <p class="mt-4 text-gray-500 dark:text-gray-400">暂无备份记录</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            文件名
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            大小
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            创建时间
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            操作
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($backups as $backup)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                </svg>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $backup['filename'] }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $backup['size_formatted'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $backup['created_at']->format('Y-m-d H:i:s') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <a href="{{ route('admin.backups.download', $backup['filename']) }}" 
                               class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 mr-3">
                                下载
                            </a>
                            <form action="{{ route('admin.backups.destroy', $backup['filename']) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('确定要删除此备份吗？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                    删除
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

<div class="mt-6 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300 mb-2">备份说明</h3>
    <ul class="text-sm text-blue-700 dark:text-blue-400 space-y-1">
        <li>• 完整备份包含数据库和上传文件</li>
        <li>• 建议定期进行备份，并下载到本地保存</li>
        <li>• 备份文件保存在服务器 storage/app/backups 目录</li>
    </ul>
</div>
@endsection
