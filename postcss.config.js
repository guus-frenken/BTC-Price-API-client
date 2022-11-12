module.exports = {
    plugins: {
        tailwindcss: {},
        autoprefixer: {
            enabled: true,
            options: {
                overrideBrowserslist: ['last 2 versions', '> 1%'],
                cascade: true,
            },
        }
    }
}
