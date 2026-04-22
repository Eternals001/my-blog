<?php

namespace App\Directives;

use Illuminate\Support\Facades\Blade;

/**
 * 性能优化指令
 * 
 * 使用方式:
 * @defer
 *     <script src="your-script.js"></script>
 * @enddefer
 * 
 * @lazy
 *     <script src="lazy-script.js"></script>
 * @endlazy
 */
class PerformanceDirective
{
    /**
     * 注册指令
     */
    public static function register(): void
    {
        // 延迟加载脚本
        Blade::directive('defer', function () {
            return '<script defer>';
        });
        
        Blade::directive('enddefer', function () {
            return '</script>';
        });
        
        // 懒加载脚本（页面加载完成后加载）
        Blade::directive('lazy', function () {
            return '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    // 动态创建脚本元素
                    const script = document.createElement("script");
                    script.src = "";
                    script.async = true;
                    document.body.appendChild(script);
                });
            </script>';
        });
        
        Blade::directive('endlazy', function () {
            return '';
        });
        
        // 异步加载指令
        Blade::directive('async', function () {
            return '<script async>';
        });
        
        Blade::directive('endasync', function () {
            return '</script>';
        });
    }
}
