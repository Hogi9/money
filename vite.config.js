import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '');
    const isLocal = env.APP_ENV === 'local';

    return {
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
            tailwindcss(),
        ],
        server: {
            host: '0.0.0.0',
            port: 5173,
            hmr: {
                host: isLocal ? 'localhost' : 'money.fulgenz.net',
            },
            watch: {
                usePolling: true,
                ignored: ['**/storage/framework/views/**'],
            },
        },
    };
});
