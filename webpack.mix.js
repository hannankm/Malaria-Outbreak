

const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css')
    .postCss('resources/css/app.css', 'public/css', [
        require('tailwindcss'),
    ]);
    

    // const path = require('path');
    // mix.webpackConfig({
    //   resolve: {
    //     alias: {
    //       '@': path.resolve(__dirname, 'resources/js'),
    //     },
    //   },
    // });
    


// Extend Webpack config
mix.extend('myCustomWebpackConfig', new class {
    apply(compiler) {
        compiler.hooks.afterEnvironment.tap('MyCustomWebpackConfig', () => {
            const config = compiler.options;
            config.resolve = {
                extensions: ['.js', '.vue', '.json'], // Add .vue here
            };
        });
    }
});

// Use the custom extension
mix.myCustomWebpackConfig();


   