import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0', // コンテナ内の全インターフェイスでリッスン
        port: 5173,
        hmr: {
            host: 'localhost', // ホスト側からアクセスする場合、ここを適切な値に変更（例：実際のIPアドレスなど）
            protocol: 'ws',
            port: 5173,
        },
        watch: {
            usePolling: true,
        }
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
