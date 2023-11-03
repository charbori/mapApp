import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from "path";
import { forEach } from 'lodash';
var fs = require('fs');
var files = fs.readdirSync('resources/static/images/');
var input_file_list = [];
files.forEach((file) => input_file_list.push("resources/static/images/" + file));

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/master.css',
                'resources/css/home.css',
                'resources/css/edit.css',
                //'resources/css/bootstrap.css',
                //'resources/js/jquery-ui.js',
                'resources/js/jquery-3.6.1.min.js',
                'resources/js/bootstrap.js',
                'resources/js/popper.js',
                'resources/js/edit.js',
                'resources/js/myChart.js',
                'resources/js/masternav.js',
                'resources/js/alarm.js',
                ...input_file_list
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            "~bootstrap": path.resolve(__dirname, "node_modules/bootstrap"),
            "~fontawesome": path.resolve(
                __dirname,
                "node_modules/@fortawesome/fontawesome-free"
            ),
            "@": "/resources",
        }
    },
    build : {
        modulePreload: false,
        rollupOptions: {
            output: {
                assetFileNames: (asset) => {
                    switch (asset.name.split('.').pop()) {
                        case 'css':
                            return 'css/' + `[name]` + '.css';
                        case 'png':
                        case 'jpg':
                        case 'ico':
                        case 'svg':
                            return 'images/' + `[name]` + `[extname]`;
                        //TODO how to handle svg fonts? (they can be both font or image?)
                        case 'ttf':
                        case 'otf':
                        case 'woff':
                        case 'woff2':
                            return 'fonts/' + `[name]` + `[extname]`;
                        case 'js':
                            return 'js/' + `[name]` + `.js`;
                        default:
                            return 'other/' + `[name]` + `[extname]`;
                    }
                },
                entryFileNames: 'js/' + `[name]` + `.js`,
            },
        },
    }
});
