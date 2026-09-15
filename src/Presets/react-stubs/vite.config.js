import { defineConfig } from 'vite';
import ugarit from 'ugarit-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        ugarit({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        react(),
    ],
});
