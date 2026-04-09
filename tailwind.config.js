import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.{js,jsx,ts,tsx}',
    ],
    safelist: [
        // Dynamic badge/category colors used via PHP variables
        'bg-blue-500', 'bg-green-500', 'bg-purple-500', 'bg-pink-500', 'bg-red-500',
        'bg-yellow-500', 'bg-orange-500', 'bg-indigo-500', 'bg-teal-500',
        'bg-blue-600', 'bg-indigo-600', 'bg-purple-600',
        'bg-purple-100', 'bg-pink-100', 'bg-yellow-100', 'bg-green-100',
        'bg-orange-100', 'bg-red-100', 'bg-blue-100', 'bg-gray-100',
        'text-purple-600', 'text-pink-600', 'text-yellow-600', 'text-green-600',
        'text-orange-600', 'text-red-600', 'text-blue-600',
        'shadow-blue-200', 'shadow-xl',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
