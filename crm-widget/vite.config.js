import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/admin.css',
                'resources/css/widget.css',
                'resources/js/app.js',
                'resources/js/widget.js',
            ],
            refresh: true,
        }),
    ],
});
