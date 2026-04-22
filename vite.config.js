import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

/**
 * Vite 配置文件
 */
export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: [
                'vendor/livewire/livewire/dist/**/*.js',
                'resources/views/**/*.php',
                'resources/views/**/*.blade.php',
                'app/Livewire/**/*.php',
            ],
        }),
    ],
    
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources'),
            '@resources': path.resolve(__dirname, 'resources'),
        },
    },
    
    css: {
        devSourcemap: true,
    },
    
    build: {
        assetsDir: 'assets',
        rollupOptions: {
            output: {
                manualChunks: {
                    'alpine': ['alpinejs'],
                },
            },
        },
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true,
            },
        },
    },
    
    server: {
        port: 5173,
        host: true,
        https: false,
        proxy: {
            '/api': {
                target: process.env.APP_URL || 'http://localhost',
                changeOrigin: true,
            },
        },
    },
    
    preview: {
        port: 4173,
    },
});