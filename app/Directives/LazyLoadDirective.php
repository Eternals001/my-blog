<?php

namespace App\Directives;

use Illuminate\Support\Facades\Blade;

/**
 * 图片懒加载指令
 * 
 * 使用方式:
 * @lazyimg($src, $alt, $class)
 * 
 * 或者在组件中使用 <x-lazy-image />
 */
class LazyLoadDirective
{
    /**
     * 注册指令
     */
    public static function register(): void
    {
        // 图片懒加载指令
        Blade::directive('lazyimg', function ($expression) {
            // 解析参数
            $args = self::parseArgs($expression);
            
            $src = $args['src'] ?? "''";
            $alt = $args['alt'] ?? "''";
            $class = $args['class'] ?? "''";
            $width = $args['width'] ?? 'null';
            $height = $args['height'] ?? 'null';
            
            return <<<HTML
<x-lazy-image 
    :src="{$src}"
    alt="{$alt}"
    class="{$class}"
    :width="{$width}"
    :height="{$height}"
/>
HTML;
        });
    }
    
    /**
     * 解析参数
     */
    private static function parseArgs(string $expression): array
    {
        $args = [];
        
        // 移除括号并分割参数
        $expression = trim($expression, '()');
        $parts = array_map('trim', explode(',', $expression));
        
        $keys = ['src', 'alt', 'class', 'width', 'height'];
        
        foreach ($parts as $index => $value) {
            if (isset($keys[$index])) {
                $args[$keys[$index]] = trim($value, "'\"");
            }
        }
        
        return $args;
    }
}
