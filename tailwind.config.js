import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './node_modules/flowbite/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            keyframes: {
                'fade-down': {
                  '0%': { opacity: '0', transform: 'translateY(-20px)' },
                  '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                'fade-up': {
                  '0%': { opacity: '0', transform: 'translateY(20px)' },
                  '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                'fade-right': {
                  '0%': { opacity: '0', transform: 'translateX(-20px)' },
                  '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                'fade-left': {
                  '0%': { opacity: '0', transform: 'translateX(20px)' },
                  '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                'fade': {
                  '0%': { opacity: '0' },
                  '100%': { opacity: '1' },
                },
              },
              animation: {
                'fade-down': 'fade-down 0.5s ease-out',
                'fade-up': 'fade-up 0.5s ease-out',
                'fade-right': 'fade-right 0.5s ease-out',
                'fade-left': 'fade-left 0.5s ease-out',
                'fade': 'fade 0.5s ease-out',
              },
        },
    },

    plugins: [
        forms,
        require('flowbite/plugin')
    ],
};
