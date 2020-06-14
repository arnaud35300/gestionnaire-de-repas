const Encore = require('@symfony/webpack-encore')
Encore
  .setOutputPath('public/build/')
  .setPublicPath('/build')
  .addStyleEntry('tailwind', './assets/css/tailwind.css')
  // enable post css loader
  .enablePostCssLoader((options) => {
             options.config = {
              // directory where the postcss.config.js file is stored
                     path: './postcss.config.js'
             };
    })
module.exports = Encore.getWebpackConfig()