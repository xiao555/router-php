var webpack = require('webpack')

module.exports = {
  entry: {
    style: './src/styles/index.js', // alias, realpath is ./src/styles/index.js
    main: './src/scripts/index.js', // alias, realpath is ./src/scripts/index.js
  },
  output: {
    path: 'build',
    js: '[name].js', // 打包输出的文件
    css: '[name].css'
  },
  module: {
    loaders: [
      {
        test: /\.js$/, // test 去判断是否为.js,是的话就是进行es6和jsx的编译
        loader: 'babel-loader',
        query: {
          presets: ['es2015', 'react']
        }
      }
    ]
  }，
  resolve: {
    // 现在你require文件的时候可以直接使用require('file')，不用使用require('file.coffee')
    extensions: ['', '.js', '.json', '.coffee']
  }
}