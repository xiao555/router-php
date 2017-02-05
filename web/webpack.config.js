var nib                 = require('nib');
var path                = require('path');
var glob                = require('glob');
var webpack             = require('webpack');
var poststylus          = require('poststylus');
var ExtractTextPlugin   = require("extract-text-webpack-plugin");
var BrowserSyncPlugin   = require('browser-sync-webpack-plugin');
var HtmlWebpackPlugin   = require('html-webpack-plugin');


// 文档: https://webpack.github.io/docs/configuration.html

var paths = {
  root: path.join(__dirname, './'),
  src: path.join(__dirname, './src/'),
  scripts: path.join(__dirname, './src/scripts'),
  styles: path.join(__dirname, './src/styles'),
  assets: path.join(__dirname, './src/assets'),
}

// var entries = getEntry('src/scripts/page/**/*.js', 'src/scripts/page/');

var config = {
  context: paths.root,
  debug: true,
  watch: true,
  separateStylesheet: true,
  devtool: 'source-map',
  entry: {
    style: 'styles', // alias, realpath is ./src/styles/index.js
    main: 'scripts', // alias, realpath is ./src/scripts/index.js
  },
  output: {
    path: 'build',
    filename: "assets/[name].js",
    chunkFilename: "assets/[id].js"
  },
  module: {
    loaders: [
      { test: /\.js$/, loader: 'babel'},
      { test: /\.(jpe?g|png|gif|svg)$/, loader: 'file-loader?name=static/[hash].[ext]'},
      { test: /\.(woff|woff2)$/, loader: 'file-loader?name=static/[hash].[ext]'},
      { test: /\.(ttf|eot|otf)$/, loader: 'file-loader?name=static/[hash].[ext]'},
      { test: /\.txt$/, loader: 'raw-loader'},
      { test: /\.html$/, loader: 'file-loader?name=[name].html!nunjucks-html-loader?' +
                JSON.stringify({
                    'searchPaths': [
                        'views'
                    ]
                }) },   //避免压缩html,https://github.com/webpack/html-loader/issues/50
      // Extract css files
      { test: /\.css$/, loader: ExtractTextPlugin.extract("style-loader", "css-loader?sourceMap") },
      // Optionally extract less files
      // or any other compile-to-css language
      { test: /\.styl$/, loader: ExtractTextPlugin.extract("style-loader", "css-loader!stylus-loader?sourceMap") }
      // You could also use other loaders the same way. I. e. the autoprefixer-loader
    ]
  },
  externals: {
    jquery: 'jQuery'
  },
  resolve: {
    root: path.join(__dirname, './'),
    modulesDirectories: ['node_modules', 'bower_components'],
    extensions: ['', '.es6.js', '.js', '.vue'],
    alias: {
      'assets': paths.assets,
      'styles': paths.styles,
      'scripts': paths.scripts,
      'masonry': 'masonry-layout',
      'isotope': 'isotope-layout',
    }
  },
  resolveLoader: {
    alias: {
      'copy': 'file-loader?name=assets/[name].[ext]', //&context=./src
    }
  },
  // User Custom Config
  stylus: {
    use: [
      poststylus([ 'autoprefixer' ]),
    ],
    import: [
      '~nib/lib/nib/index.styl',
      path.join(paths.styles, 'stylus/variables.styl')
    ]
  },
  babel: {
    presets: ['es2015'],
    plugins: ['transform-runtime']
  },
  plugins: [
    new webpack.optimize.OccurrenceOrderPlugin(),
    new webpack.NoErrorsPlugin(),
    new webpack.ProvidePlugin({
      $: 'jquery'
    }),
    new ExtractTextPlugin("assets/[name].css"),
    new BrowserSyncPlugin(
      // BrowserSync options 
      {
        // browse to http://localhost:3000/ during development 
        host: 'localhost',
        port: 4000,
        // proxy the Webpack Dev Server endpoint 
        // (which should be serving on http://localhost:3100/) 
        // through BrowserSync 
        // proxy: 'http://localhost:3100/'
        server: { 
          baseDir: ['build'],
          directory: true
        }
      },
      // plugin options 
      {
        // prevent BrowserSync from reloading the page 
        // and let Webpack Dev Server take care of this 
        reload: true
      }
    )
    // new MutiHtmlWebpackPlugin({
    //     templatePath: './views',
    //     loader: 'html?attrs=img:src img:data-src!compile-nunjucks',
    //     templateSuffix: '.html',
    //     path: '../',
    //     ignore: []
    // })
  ]
};

var pages = Object.keys(getEntry('views/*.html', 'build/'));
pages.forEach(function(pathname) {
    var conf = {
        filename: 'build/' + pathname + '.html', //生成的html存放路径，相对于path
        template:  pathname + '.html', //html模板路径
        inject: false,    //js插入的位置，true/'head'/'body'/false
        /*
        * 压缩这块，调用了html-minify，会导致压缩时候的很多html语法检查问题，
        * 如在html标签属性上使用{{...}}表达式，所以很多情况下并不需要在此配置压缩项，
        * 另外，UglifyJsPlugin会在压缩代码的时候连同html一起压缩。
        * 为避免压缩html，需要在html-loader上配置'html?-minimize'，见loaders中html-loader的配置。
         */
        // minify: { //压缩HTML文件
        //     removeComments: true, //移除HTML中的注释
        //     collapseWhitespace: false //删除空白符与换行符
        // }
    };
    // if (pathname in config.entry) {
    //     conf.favicon = 'src/imgs/favicon.ico';
    //     conf.inject = 'body';
    //     conf.chunks = ['vendors', pathname];
    //     conf.hash = true;
    // }
    config.plugins.push(new HtmlWebpackPlugin(conf));
});

function getEntry(globPath, pathDir) {
    var files = glob.sync(globPath);
    var entries = {},
        entry, dirname, basename, pathname, extname;

    for (var i = 0; i < files.length; i++) {
        entry = files[i];
        dirname = path.dirname(entry);
        extname = path.extname(entry);
        basename = path.basename(entry, extname);
        pathname = path.join(dirname, basename);
        pathname = pathDir ? pathname.replace(new RegExp('^' + pathDir), '') : pathname;
        entries[pathname] = ['./' + entry];
    }
    return entries;
}

module.exports = config;



