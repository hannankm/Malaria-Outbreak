/** @type {import('tailwindcss').Config} */

// tailwind.config.js
module.exports = {
  content: [
    './resources/**/*.blade.php',   // Laravel Blade files
    './resources/js/**/*.vue',      // Vue files
    './resources/js/**/*.js',       // JavaScript files
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
