const path = require('path');
const glob = require('glob');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const FixStyleOnlyEntriesPlugin = require("webpack-fix-style-only-entries");
const { PurgeCSSPlugin } = require('purgecss-webpack-plugin');

const modeProduction = process.env.NODE_ENV === 'production';
/* const modeDevelopment = process.env.NODE_ENV === 'development'; */
const PATHS = { src: path.join(__dirname, '.') }

const exportStyleSCSSPure = {
    entry: { './css/style': './dev/sass/style.scss', },
    output: { path: path.resolve(__dirname, './assets/'), },
    plugins: [
        new FixStyleOnlyEntriesPlugin(),
        new MiniCssExtractPlugin({ filename: "./[name].css", }),
        new PurgeCSSPlugin({
            paths: glob.sync(`${PATHS.src}/**/*`, { nodir: true }),
            defaultExtractor: (content) => content.match(/[\w-/:./%]+(?<!:)/g) || [],
            safelist: {
                greedy: [/fancybox/, /swiper/]
            }
        })
    ],
    module: {
        rules: [
            {
                test: /\.scss$/,
                exclude: /node_modules/,
                use: [
                    MiniCssExtractPlugin.loader,
                    {
                        loader: "css-loader",
                        options: {
                            url: false,
                            modules: false,
                            sourceMap: true,
                            importLoaders: 2,
                        }
                    },
                    {
                        loader: "postcss-loader",
                        options: {
                            postcssOptions: {
                                plugins: [
                                    [
                                        "autoprefixer",
                                    ],
                                ],
                            },
                        },
                    },
                    {
                        loader: 'sass-loader',
                        options: {
                            sourceMap: true,
                        }
                    }
                ]
            }
        ],
    },
};

module.exports = {
    // ...
    externals: {
      // only define the dependencies you are NOT using as externals!
      canvg: "canvg",
      html2canvas: "html2canvas",
      dompurify: "dompurify"
    }
  };

const exportStyleSCSS = {
    entry: { './css/style': './dev/sass/style.scss', },
    output: { path: path.resolve(__dirname, './assets/'), },
    plugins: [
        new FixStyleOnlyEntriesPlugin(),
        new MiniCssExtractPlugin({ filename: "./[name].css", }),
    ],
    module: {
        rules: [
            {
                test: /\.scss$/,
                exclude: /node_modules/,
                use: [
                    MiniCssExtractPlugin.loader,
                    {
                        loader: "css-loader",
                        options: {
                            url: false,
                            modules: false,
                            sourceMap: true,
                            importLoaders: 2,
                        }
                    },
                    {
                        loader: "postcss-loader",
                        options: {
                            postcssOptions: {
                                plugins: [
                                    [
                                        "autoprefixer",
                                    ],
                                ],
                            },
                        },
                    },
                    {
                        loader: 'sass-loader',
                        options: {
                            sourceMap: true,
                            implementation: require('sass'),
                        }
                    }
                ]
            }
        ],
    },
};

const exportScriptJS = {
    entry: { './js/script': './dev/script/script.js' },
    output: { path: path.resolve(__dirname, './assets/'), },
    module: {
        rules: [
            {
                test: /\.(glsl|frag|vert)$/,
                use: ['glslify-import-loader', 'raw-loader', 'glslify-loader']
            },
            {
                test: /\.(js|jsx)$/i,
                loader: 'babel-loader',
            },
        ],
    },
};

module.exports = () => {
    if(modeProduction) {
        exportStyleSCSSPure.mode = 'production';
        exportScriptJS.mode = 'production';
        return [exportStyleSCSSPure, exportScriptJS];
    }
    else {
        exportStyleSCSS.mode = 'development';
        exportStyleSCSS.devtool = 'source-map'
        exportScriptJS.mode = 'development';
        exportScriptJS.devtool = 'source-map';
        return [exportStyleSCSS, exportScriptJS];
    }
};