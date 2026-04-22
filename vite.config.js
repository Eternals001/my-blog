import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

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
            ],
        }),
    ],
    
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources'),
        },
    },
    
    css: {
        devSourcemap: true,
    },
    
    build: {
        minify: false,
    },
});