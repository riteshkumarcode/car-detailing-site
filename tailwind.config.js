import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './app/Filament/**/*.php',
    ],
    theme: {
        extend: {
            colors: {
                teal: {
                    DEFAULT: '#1F5E57',
                    deep: '#163F3A',
                    50: '#edf6f4',
                    100: '#d5eae5',
                    200: '#b0d6ce',
                    300: '#81bcb1',
                    400: '#549f93',
                    500: '#1F5E57',
                    600: '#163F3A',
                    700: '#1a554f',
                    800: '#184440',
                    900: '#163a37',
                    950: '#0b211f',
                },
                amber: {
                    DEFAULT: '#F2A93B',
                    50: '#fef7ec',
                    100: '#fdecd3',
                    200: '#fbd7a5',
                    300: '#f7be6e',
                    400: '#F2A93B',
                    500: '#e08f23',
                    600: '#c47119',
                    700: '#9f5317',
                    800: '#81431a',
                    900: '#6b381a',
                    950: '#3d1c0b',
                },
                ink: {
                    DEFAULT: '#13262B',
                    light: '#1f383e',
                },
                mist: '#EEF2F0',
                mint: '#DDE8E3',
                paper: '#F7F9F8',
                line: '#CBD5D1',
                muted: '#4A5D61',
                brick: '#A63A2B',
                chip: {
                    good: {
                        text: '#1F5E57',
                        bg: '#E1EEEA',
                    },
                    fair: {
                        text: '#7A4E07',
                        bg: '#FBEBCF',
                    },
                    attention: {
                        text: '#A63A2B',
                        bg: '#F6E0DB',
                    },
                },
            },
            fontFamily: {
                display: ['Archivo', ...defaultTheme.fontFamily.sans],
                body: ['Barlow', ...defaultTheme.fontFamily.sans],
                sans: ['Barlow', ...defaultTheme.fontFamily.sans],
            },
            borderRadius: {
                'brand': '10px',
            },
            boxShadow: {
                'brand': '0 4px 20px -2px rgba(19, 38, 43, 0.08)',
                'card': '0 2px 12px -1px rgba(19, 38, 43, 0.06)',
            },
        },
    },
    plugins: [forms, typography],
};
