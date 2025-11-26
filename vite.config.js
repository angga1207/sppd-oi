import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        https: false,
        hmr: {
            host: process.env.VITE_DEV_SERVER_HOST || 'localhost',
            port: 5173,
        },
        cors: {
            origin: [
                'https://sppd-oi.in',
                'http://sppd-oi.in',
                'https://localhost',
                'http://localhost',
                'http://localhost:8000',
                'https://localhost:8000',
                /^https?:\/\/.*\.in$/,  // Allow any .in domain
                /^https?:\/\/localhost(:\d+)?$/,  // Allow localhost with any port
            ],
            credentials: true,
        },
    },
    build: {
        outDir: 'public/build',
        manifest: 'manifest.json',
        rollupOptions: {
            output: {
                manualChunks: undefined,
            },
        },
    },
});
