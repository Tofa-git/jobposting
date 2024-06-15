import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/js/src/app.js',
                'resources/sass/login.scss',
                'resources/js/login.js',
                'resources/js/login/app.js',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '$':  'jQuery',
        },
    },
});
