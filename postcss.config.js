let tailwindcss = require('tailwindcss');

module.exports = {
    plugins: [
        //!tailwindcss('./tailwind.config.js'), // your tailwind.js configuration file path
        require('tailwindcss'),
        require('autoprefixer'),
        require('postcss-import')
    ]
}