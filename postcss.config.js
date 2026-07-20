module.exports = {
    plugins: [
        // Same pipeline the laravel-mix build used, in the same order.
        require('postcss-nested'),
        require('postcss-import'),
        // Inline the CSS variables so the WordPress-themed values are baked in.
        require('postcss-css-variables'),
        require('postcss-prefixer')({
            prefix: 'paver__',
            ignore: [/paver/, '.inside', /postbox/],
        }),
        require('cssnano')({ preset: 'default' }),
    ],
}
