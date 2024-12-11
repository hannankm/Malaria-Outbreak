const mix = require("laravel-mix");

// Import the Node 'path' module
const path = require("path");

// Set up the alias
mix.webpackConfig({
    resolve: {
        alias: {
            "@": path.resolve(__dirname, "resources/js"),
        },
    },
});

mix.js("resources/js/app.js", "public/js")
    .vue()
    .sass("resources/sass/app.scss", "public/css");
