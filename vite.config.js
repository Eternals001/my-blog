import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

/**
 * Vite 配置文件
 * 
 * 配置说明：
 * - Livewire 插件：自动处理 Livewire 组件的热更新
 * - 别名配置：@ 指向 resources 目录
 * - CDN 域名：配置外部资源域名
 */
export default defineConfig({
    plugins: [
        laravel({
            // 输入文件
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            // Livewire 3 支持
            refresh: [
                'vendor/livewire/livewire/dist/**/*.js',
                'resources/views/**/*.php',
                'resources/views/**/*.blade.php',
                'app/Livewire/**/*.php',
            ],
        }),
    ],
    
    // 路径别名配置
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources'),
            '@resources': path.resolve(__dirname, 'resources'),
            '@js': path.resolve(__dirname, 'resources/js'),
            '@css': path.resolve(__dirname, 'resources/css'),
            '@components': path.resolve(__dirname, 'resources/views/components'),
            '@layouts': path.resolve(__dirname, 'resources/views/layouts'),
        },
    },
    
    // CSS 配置
    css: {
        // CSS 源码映射
        devSourcemap: true,
    },
    
    // 构建配置
    build: {
        // 资源公共路径
        assetsDir: 'assets',
        // 资源版本哈希
        rollupOptions: {
            output: {
                // 手动分包
                manualChunks: {
                    'alpine': ['alpinejs'],
                },
            },
        },
        // 压缩配置
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true,
            },
        },
    },
    
    // 开发服务器配置
    server: {
        // 端口
        port: 5173,
        // 主机
        host: true,
        // HTTPS
        https: false,
        // 代理配置（如需要）
        proxy: {
            // API 请求代理
            '/api': {
                target: process.env.APP_URL || 'http://localhost',
                changeOrigin: true,
            },
            // WebSocket 代理（用于实时功能）
            '/ws': {
                target: process.env.APP_URL || 'http://localhost',
                ws: true,
            },
        },
    },
    
    // 预览服务器配置
    preview: {
        port: 4173,
    },
    
    // CDN 域名白名单配置
    cdn: {
        // 允许的 CDN 域名
        allowedDomains: [
            'cdn.jsdelivr.net',
            'unpkg.com',
            'fonts.googleapis.com',
            'fonts.gstatic.com',
            'images.unsplash.com',
            'picsum.photos',
        ],
    },
});
