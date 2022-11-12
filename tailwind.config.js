const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: [
        './assets/components/**/*.vue',
        './assets/views/**/*.vue',
        './assets/App.vue',
    ],
    safelist: [],
    theme: {
        extend: {},
        container: {
            center: true,
            padding: '1rem',
        },
    },
};
