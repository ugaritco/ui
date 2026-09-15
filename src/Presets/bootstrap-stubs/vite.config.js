import { defineConfig } from 'vite';
import ugarit from 'ugarit-vite-plugin';

export default defineConfig({
    plugins: [
        ugarit({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
