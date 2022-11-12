const Encore = require('@symfony/webpack-encore');
const {DefinePlugin} = require('webpack');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')

    .addEntry('app', './assets/app.js')

    .enablePostCssLoader()
    .enableVueLoader(() => {
    }, {
        runtimeCompilerBuild: false,
    })

    .splitEntryChunks()
    .enableSingleRuntimeChunk()

    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    .configureBabel((config) => {
        config.plugins.push('@babel/plugin-proposal-class-properties');
    })

    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    .addPlugin(new DefinePlugin({
        __VUE_PROD_DEVTOOLS__: true,
        __VUE_OPTIONS_API__: false,
    }))
;

module.exports = Encore.getWebpackConfig();
