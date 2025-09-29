import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import path from 'path';
import vue from '@vitejs/plugin-vue';
import ui from '@nuxt/ui/vite'
import vueDevTools from 'vite-plugin-vue-devtools'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.ts'
            ],
            refresh: true,
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        ui({
            inertia: true,
            colorMode: false,
            components: {
                dirs: [
                    'resources/js/layouts',
                    'resources/js/components'
                ],
            },
            ui: {
                colors: {
                    primary: 'green',
                    neutral: 'slate',
                },
            },
        }),
        vueDevTools({
            appendTo: 'resources/js/app.ts'
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
            'ziggy-js': path.resolve('vendor/tightenco/ziggy'),
        },
    },
});
