import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            ssr: 'resources/js/ssr.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    // ==== Descomentar esto para servidor local ====
    server: {
        host: '192.168.100.52',
        port: 5173,
        strictPort: true,
        hmr: {
            host: '192.168.100.52',
        },
    },
});
