import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                grok: {
                    DEFAULT: '#2D4A6F',
                    500: '#2D4A6F',
                    600: '#1F3A5C'
                },
                grokTeal: '#8FA8C4',
                grokAmber: '#B8A88A',
                grokNavy: '#1A2942',
                surface: '#F0F2F5'
            },
            fontFamily: {
                sans: ['Manrope', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
