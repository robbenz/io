const elixir = require('laravel-elixir')
require('laravel-elixir-livereload')

elixir.config.autoprefix = {
  enabled: true,

  // https://www.npmjs.com/package/gulp-autoprefixer#api
  options: {
    browsers: ['last 2 versions', 'ie 9-11'],
    cascade: false
  }
}

elixir(function (mix) {
  mix
    .webpack('main.js')
    .sass('style.scss')
    .livereload()
})
