const path = require('path');

module.exports = {
    entry: [
        './resources/assets/ts/app.ts'
    ],
    module: {
        rules: [
            {
                test: /\.tsx?$/,
                use: 'ts-loader',
                exclude: ['/node_modules/', '/vendor/']
            }
        ]
    },

    devtool: 'inline-source-map',
    resolve: {
        extensions: [ '.tsx', '.ts', '.js' ],
        modules: [path.join(__dirname, 'node_modules')],

        alias: {
            'jquery': require.resolve('jquery')
        }
    },
    output: {
        filename: 'core.js',
        path: path.resolve(__dirname, './public/js/')
    }
};
