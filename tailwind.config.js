import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'patria-green': '#556B2F', // Vintage Green
                'patria-brown': '#3E2723', // Dark Coffee
                'patria-cream': '#FAF9F6', // Off-white Background
            }
        },
    },

    plugins: [forms],
};
