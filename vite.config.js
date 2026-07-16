import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/backend/country/index.js',
                'resources/js/backend/trucks/index.js',
                'resources/js/backend/inventory/index.js',
                'resources/js/backend/regions/index.js',
                'resources/js/backend/social-media/index.js',
                'resources/js/backend/dealers/index.js',
                'resources/js/backend/restricted-rider/index.js',
                'resources/js/backend/groups/index.js',
                'resources/js/backend/signed-waivers/index.js',
                'resources/js/backend/import-vehicles/index.js',
                'resources/js/backend/models/index.js',
                'resources/js/backend/users/index.js',
                'resources/js/backend/waivers/index.js',
                'resources/js/backend/email-templates/index.js',
                'resources/js/backend/sms-templates/index.js',
                'resources/js/backend/data-management/index.js',
                'resources/js/backend/bikes-and-times/index.js',
                'resources/js/backend/bikes-and-times/edit.js',
                'resources/js/backend/pre-reg-emails/index.js',
                'resources/js/backend/pre-reg-html/index.js',
                'resources/js/backend/generate-cards/index.js',
                'resources/js/backend/surveys/index.js',
                'resources/js/backend/surveys/create.js',
                'resources/js/backend/surveys/edit.js',
                'resources/js/backend/survey-questions/create.js',
                'resources/js/backend/auth/login.js',
                'resources/js/backend/auth/register.js',
                'resources/css/backend/auth/register.css',
                'resources/css/backend/bikes-and-times/edit.css',
            ],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
