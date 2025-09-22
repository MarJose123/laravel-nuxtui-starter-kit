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
            input: ['resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
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
            components: {
                dirs: ['resources/js/components'],
            },
            ui: {
                colors: {
                    primary: 'green',
                    neutral: 'neutral',
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
