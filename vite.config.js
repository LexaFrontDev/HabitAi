import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import path from 'path';

export default defineConfig(({ mode }) => {
    const isProd = mode === 'production';

    return {
        root: 'assets/react',
        base: isProd ? '/build/' : '/',
        build: {
            outDir: '../../public/build',
            emptyOutDir: true,
            manifest: true,
            rollupOptions: {
                input: {
                    main: path.resolve(__dirname, 'assets/react/main.tsx'),
                },
            },
        },
        server: {
            host: true,
            proxy: {
                '/api': {
                    target: 'http://taskflow.local:8000',
                    changeOrigin: true,
                    secure: false,
                }
            }
        },
        plugins: [react()],
    };
});
