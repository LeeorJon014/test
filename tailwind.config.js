const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                'dm-sans': ['DM Sans', 'sans-serif'],
                'public-sans': ['Public Sans', 'sans-serif'],
            },
            colors: {
                'carrot-orange': '#F59211',
                'midnight-express': '#15223D',
                'hawkes-blue': '#D6DCE9',
                'mountain-meadow': '#14BE81',
                'genoa': '#468B7E',
                'ronchi': '#E8B552',
                'alpine': '#B28839',
                'color-bg': '#FDFDFD',
                'red-color': '#D9303D',
            },
            screens: {
                '2xl': '1900px',
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
