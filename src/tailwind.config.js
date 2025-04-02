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
                'custom-gray': '#939393', // 背景色
                'chart-do-gray': '#DBDBDB', // グラフの背景色
                'light-gray': '#F7F8FA',// 画面の背景色
                'chart-gray': '#EBEBEB', //グラフの背景色
                'chart-border-gray': '#C4C4C4', //グラフの枠線
                'light-red': '#ffd4d4', // 赤
                'custom-red': '#FFA1A1', // 青
                'light-blue': '#C8EBFF', // 青
                'custom-blue': '#00A6FF', // 青
                'button-blue': '#86D4FE', // 青
                'good-blue': '#99DBFF', // 青
            },
            width: {
                '49/50': '98%',
            },
        },
    },

    plugins: [forms],
};
