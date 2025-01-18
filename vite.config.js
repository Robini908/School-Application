import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import dotenv from 'dotenv';

dotenv.config();

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', // Your main CSS file
                'resources/css/email.css', // Your email-specific CSS file
                'resources/js/app.js', // JavaScript entry point
            ],
            refresh: true,
        }),
    ],
    server: {
        host: 'localhost',
        port: process.env.VITE_PORT || 5173,
    },
});