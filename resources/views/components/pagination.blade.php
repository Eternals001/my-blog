{{-- resources/views/components/pagination.blade.php --}}
{{-- 分页组件 --}}

@props([
    'paginator', // Illuminate\Contracts\Pagination\LengthAwarePaginator
    'variant' => 'default', // default | simple | compact
    'align' => 'center', // left | center | right
])

@if($paginator->hasPages())
    @php
        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();
        $perPage = $paginator->perPage();
        $total = $paginator->total();
        
        // 分页范围计算
        $onEachSide = 2;
        $from = max($currentPage - $onEachSide, 1);
        $to = min($currentPage + $onEachSide, $lastPage);
        
        // 确保显示足够多的页码
        if ($to - $from + 1 < $onEachSide * 2 + 1) {
            if ($from === 1) {
                $to = min($from + $onEachSide * 2, $lastPage);
            } else {
                $from = max($to - $onEachSide * 2, 1);
            }
        }
    @endphp
    
    <nav role="navigation" aria-label="分页导航" 
         class="pagination-{{ $align }}">
        
        @if($variant === 'simple')
            {{-- 简洁分页 --}}
            <div class="flex items-center justify-between sm:justify-center gap-4">
                {{-- 上一页 --}}
                @if($paginator->onFirstPage())
                    <span class="px-3 py-2 text-gray-400 cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" 
                       class="p-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                @endif
                
                {{-- 页码信息 --}}
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    第 {{ $currentPage }} / {{ $lastPage }} 页
                </span>
                
                {{-- 下一页 --}}
                @if($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" 
                       class="p-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @else
                    <span class="px-3 py-2 text-gray-400 cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                @endif
            </div>
            
        @elseif($variant === 'compact')
            {{-- 紧凑分页 --}}
            <div class="flex items-center gap-1">
                {{-- 首页 --}}
                @if($currentPage > 1)
                    <a href="{{ $paginator->url(1) }}" 
                       class="px-2 py-1 text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
                        ««
                    </a>
                @endif
                
                {{-- 上一页 --}}
                @if($paginator->onFirstPage())
                    <span class="px-2 py-1 text-gray-400 cursor-not-allowed">«</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" 
                       class="px-2 py-1 text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
                        «
                    </a>
                @endif
                
                {{-- 页码 --}}
                <span class="px-3 py-1 bg-primary-600 text-white rounded-lg">
                    {{ $currentPage }}
                </span>
                
                {{-- 下一页 --}}
                @if($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" 
                       class="px-2 py-1 text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
                        »
                    </a>
                @else
                    <span class="px-2 py-1 text-gray-400 cursor-not-allowed">»</span>
                @endif
                
                {{-- 末页 --}}
                @if($currentPage < $lastPage)
                    <a href="{{ $paginator->url($lastPage) }}" 
                       class="px-2 py-1 text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
                        »»
                    </a>
                @endif
            </div>
            
        @else
            {{-- 默认分页（带页码列表） --}}
            <div class="flex items-center justify-between sm:justify-center gap-2">
                
                {{-- 上一页 --}}
                @if($paginator->onFirstPage())
                    <span class="pagination-item opacity-50 cursor-not-allowed">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        上一页
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" 
                       class="pagination-item hover:text-primary-600 dark:hover:text-primary-400">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        上一页
                    </a>
                @endif
                
                {{-- 页码列表 --}}
                <div class="hidden sm:flex items-center gap-1">
                    {{-- 首页 --}}
                    @if($from > 1)
                        <a href="{{ $paginator->url(1) }}" 
                           class="pagination-item {{ $currentPage === 1 ? 'pagination-item-active' : '' }}">
                            1
                        </a>
                        @if($from > 2)
                            <span class="px-2 py-2 text-gray-400">...</span>
                        @endif
                    @endif
                    
                    {{-- 中间页码 --}}
                    @for($i = $from; $i <= $to; $i++)
                        @if($i == $currentPage)
                            <span class="pagination-item pagination-item-active" aria-current="page">
                                {{ $i }}
                            </span>
                        @else
                            <a href="{{ $paginator->url($i) }}" 
                               class="pagination-item">
                                {{ $i }}
                            </a>
                        @endif
                    @endfor
                    
                    {{-- 末页 --}}
                    @if($to < $lastPage)
                        @if($to < $lastPage - 1)
                            <span class="px-2 py-2 text-gray-400">...</span>
                        @endif
                        <a href="{{ $paginator->url($lastPage) }}" 
                           class="pagination-item {{ $currentPage === $lastPage ? 'pagination-item-active' : '' }}">
                            {{ $lastPage }}
                        </a>
                    @endif
                </div>
                
                {{-- 下一页 --}}
                @if($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" 
                       class="pagination-item hover:text-primary-600 dark:hover:text-primary-400">
                        下一页
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @else
                    <span class="pagination-item opacity-50 cursor-not-allowed">
                        下一页
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                @endif
            </div>
            
            {{-- 移动端简化显示 --}}
            <div class="sm:hidden flex items-center justify-center gap-4 mt-4">
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $currentPage }} / {{ $lastPage }}
                </span>
            </div>
        @endif
    </nav>
    
    {{-- 总数统计 --}}
    @if($variant !== 'compact')
        <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-4">
            共 {{ $total }} 条记录，每页 {{ $perPage }} 条
        </p>
    @endif
@endif
