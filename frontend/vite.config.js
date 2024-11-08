// vite.config.js
import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import path from 'path';

export default defineConfig({
    base: '/hms/',
    resolve: {
        alias: {
            '@apis': path.resolve(__dirname, 'src/apis'),
            '@assets': path.resolve(__dirname, 'src/assets'),
            '@configs': path.resolve(__dirname, 'src/configs'),
            '@views': path.resolve(__dirname, 'src/views'),
            '@layouts': path.resolve(__dirname, 'src/layouts'),
            '@components': path.resolve(__dirname, 'src/components'),
            '@contexts': path.resolve(__dirname, 'src/contexts'),
            '@hooks': path.resolve(__dirname, 'src/hooks'),
            '@store': path.resolve(__dirname, 'src/store'),
            '@utils': path.resolve(__dirname, 'src/utils'),
        },
    },
    plugins: [react()],
    server: {
        host: '0.0.0.0',
        open: true, // Automatically open the browser
    },
});
