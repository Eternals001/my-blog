/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/livewire/livewire/dist/**/*.js',
    ],
    
    darkMode: 'class', // 暗黑模式使用 class 策略
    
    theme: {
        extend: {
            // 自定义配色方案
            colors: {
                // 主色调 - 靛蓝色
                primary: {
                    50: '#eef2ff',
                    100: '#e0e7ff',
                    200: '#c7d2fe',
                    300: '#a5b4fc',
                    400: '#818cf8',
                    500: '#6366f1',
                    600: '#4f46e5',
                    700: '#4338ca',
                    800: '#3730a3',
                    900: '#312e81',
                    950: '#1e1b4b',
                },
                // 强调色 - 琥珀色
                accent: {
                    50: '#fffbeb',
                    100: '#fef3c7',
                    200: '#fde68a',
                    300: '#fcd34d',
                    400: '#fbbf24',
                    500: '#f59e0b',
                    600: '#d97706',
                    700: '#b45309',
                    800: '#92400e',
                    900: '#78350f',
                    950: '#451a03',
                },
                // 灰色调
                gray: {
                    50: '#f9fafb',
                    100: '#f3f4f6',
                    200: '#e5e7eb',
                    300: '#d1d5db',
                    400: '#9ca3af',
                    500: '#6b7280',
                    600: '#4b5563',
                    700: '#374151',
                    800: '#1f2937',
                    900: '#111827',
                    950: '#030712',
                },
            },
            
            // 字体配置
            fontFamily: {
                sans: [
                    '-apple-system', 
                    'BlinkMacSystemFont', 
                    '"Segoe UI"', 
                    'Roboto', 
                    '"Helvetica Neue"', 
                    'Arial', 
                    'sans-serif',
                    '"Apple Color Emoji"', 
                    '"Segoe UI Emoji"', 
                    '"Segoe UI Symbol"',
                    '"Noto Sans SC"', // 中文支持
                    '"PingFang SC"',
                    '"Microsoft YaHei"'
                ],
                mono: [
                    'ui-monospace', 
                    'SFMono-Regular', 
                    'Menlo', 
                    'Monaco', 
                    'Consolas', 
                    '"Liberation Mono"', 
                    '"Courier New"', 
                    'monospace',
                ],
            },
            
            // 间距扩展
            spacing: {
                '18': '4.5rem',
                '88': '22rem',
                '128': '32rem',
            },
            
            // 圆角扩展
            borderRadius: {
                '4xl': '2rem',
            },
            
            // 动画扩展
            animation: {
                'fade-in': 'fadeIn 0.5s ease-out',
                'slide-up': 'slideUp 0.5s ease-out',
                'slide-down': 'slideDown 0.5s ease-out',
            },
            
            // 关键帧动画
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { opacity: '0', transform: 'translateY(10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideDown: {
                    '0%': { opacity: '0', transform: 'translateY(-10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
            },
            
            // 屏幕阅读器专用
            screens: {
                'sr-only': { 'raw': '1px, 1px, 1px, 1px' },
            },
        },
    },
    
    plugins: [
        require('@tailwindcss/typography'), // 文章内容排版插件
    ],
    
    // FluxUI 组件支持
    presets: [
        // 如果需要 FluxUI 预设可以在这里添加
    ],
}
