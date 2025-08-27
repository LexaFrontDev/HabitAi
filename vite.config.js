import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import path from 'path';

export default defineConfig({
    root: 'assets/react',
    base: '/build/',
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
    plugins: [react()],
});
