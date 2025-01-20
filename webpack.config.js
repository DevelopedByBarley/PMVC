const path = require('path');

module.exports = {
    mode: 'development',
    entry: './resources/js/index.js',
    output: {
        path: path.resolve(__dirname, 'resources/dist'),
        filename: 'bundle.js',
    },
    module: {
        rules: [
            {
                test: /\.css$/, // CSS fájlok kezelése
                use: [
                    'style-loader',  // Stílusok hozzáadása a DOM-hoz
                    'css-loader'     // CSS fájlok kezelése
                ],
            },
            // Ha SCSS-t is használsz, akkor hozzáadhatod a sass-loader-t:
            {
                test: /\.(sass|scss)$/,
                use: [
                    'style-loader',
                    'css-loader',
                    'sass-loader',  // Ha szükséges SCSS-t is használni
                ],
            },
        ],
    },
    devServer: {
        static: {
            directory: path.join(__dirname, 'public'),
        },
        proxy: [
            {
                context: ['/'],
                target: 'http://localhost:9090',
                secure: false,
            },
        ],
        compress: true,
        port: 3000,
        hot: 'only',
        open: true,
        liveReload: true
    },
};
