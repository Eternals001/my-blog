{{-- resources/views/components/backend/datatable.blade.php --}}
{{-- 数据表格组件 --}}

@props([
    'columns',      // 列配置: [['key' => 'name', 'label' => '名称', 'sortable' => true], ...]
    'data',         // 数据集合
    'actions',      // 操作按钮配置: [['label' => '编辑', 'route' => 'xxx.edit', 'icon' => 'edit'], ...]
    'primaryKey' => 'id',
    'sortable' => true,
])

<div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        
        {{-- 表头 --}}
        <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
                @foreach($columns as $column)
                    <th scope="col" 
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider
                               {{ $column['class'] ?? '' }}">
                        @if(isset($column['sortable']) && $column['sortable'] && $sortable)
                            <a href="{{ request()->fullUrlWithQuery(['sort' => $column['key'], 'order' => request('order') === 'asc' && request('sort') === $column['key'] ? 'desc' : 'asc']) }}"
                               class="flex items-center gap-1 group">
                                {{ $column['label'] }}
                                <svg class="w-4 h-4 opacity-50 group-hover:opacity-100 {{ request('sort') === $column['key'] ? 'opacity-100 text-primary-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if(request('sort') === $column['key'] && request('order') === 'asc')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    @endif
                                </svg>
                            </a>
                        @else
                            {{ $column['label'] }}
                        @endif
                    </th>
                @endforeach
                
                @if(!empty($actions))
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">操作</span>
                    </th>
                @endif
            </tr>
        </thead>
        
        {{-- 表体 --}}
        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($data as $item)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    @foreach($columns as $column)
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 {{ $column['class'] ?? '' }}">
                            @if(isset($column['render']))
                                {!! $column['render']($item) !!}
                            @elseif(isset($column['type']) && $column['type'] === 'image')
                                @if($item->{$column['key']})
                                    <img src="{{ $item->{$column['key']} }}" 
                                         alt=""
                                         class="w-10 h-10 rounded-lg object-cover">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-800"></div>
                                @endif
                            @elseif(isset($column['type']) && $column['type'] === 'badge')
                                <span class="badge-{{ $item->{$column['key']} ? 'primary' : 'gray' }}">
                                    {{ $item->{$column['key']} ? '是' : '否' }}
                                </span>
                            @elseif(isset($column['type']) && $column['type'] === 'custom')
                                {{ $column['value']($item) }}
                            @else
                                {{ $item->{$column['key']} ?? '-' }}
                            @endif
                        </td>
                    @endforeach
                    
                    @if(!empty($actions))
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                @foreach($actions as $action)
                                    @if(isset($action['show']) && !$action['show']($item))
                                        @continue
                                    @endif
                                    
                                    @if($action['type'] ?? '' === 'delete')
                                        <form method="POST" action="{{ route($action['route'], $item) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-1.5 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                                    onclick="return confirm('确定要删除吗？')"
                                                    title="{{ $action['label'] }}">
                                                @if(isset($action['icon']))
                                                    @switch($action['icon'])
                                                        @case('delete')
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                            @break
                                                    @endswitch
                                                @else
                                                    {{ $action['label'] }}
                                                @endif
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route($action['route'], $item) }}"
                                           class="p-1.5 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors {{ $action['class'] ?? '' }}"
                                           title="{{ $action['label'] }}">
                                            @if(isset($action['icon']))
                                                @switch($action['icon'])
                                                    @case('edit')
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                        @break
                                                    @case('view')
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                        @break
                                                @endswitch
                                            @else
                                                {{ $action['label'] }}
                                            @endif
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) + (!empty($actions) ? 1 : 0) }}" 
                        class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                        暂无数据
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
