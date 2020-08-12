// Initialize modules
// Importing specific gulp API functions lets us write them below as series() instead of gulp.series()
const { src, dest, watch, series, parallel } = require('gulp');
// Importing all the Gulp-related packages we want to use
const sourcemaps = require('gulp-sourcemaps');
const sass = require('gulp-sass');
const concat = require('gulp-concat');
const babel = require('gulp-babel');
const webpack = require('webpack-stream');
const named = require('vinyl-named');
const uglify = require('gulp-uglify');
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');

// File paths
const files = {
  scssPath: 'sass/**/*.scss',
  jsPath: 'js/**/*.js',
};

// Sass task: compiles the style.scss file into style.css
function scssTask() {
  return src(files.scssPath)
    .pipe(sourcemaps.init()) // initialize sourcemaps first
    .pipe(sass()) // compile SCSS to CSS
    .pipe(postcss([autoprefixer(), cssnano()])) // PostCSS plugins
    .pipe(sourcemaps.write('.')) // write sourcemaps file in current directory
    .pipe(dest('dist/css')); // put final CSS in dist folder
}

// JS task: concatenates and uglifies JS files to script.js
function jsTask() {
  /*
  return src([
    files.jsPath,
    //,'!' + 'includes/js/jquery.min.js', // to exclude any specific files
  ])
  */
  return src('js/main.js')
    .pipe(named())
    .pipe(webpack({ devtool: 'eval-source-map' }))
    .pipe(dest('dist/js'));
}

// Watch task: watch SCSS and JS files for changes
// If any change, run scss and js tasks simultaneously
function watchTask() {
  watch(
    [files.scssPath, files.jsPath],
    { interval: 1000, usePolling: true }, //Makes docker work
    series(parallel(scssTask, jsTask)),
  );
}

// Export the default Gulp task so it can be run
// Runs the scss and js tasks simultaneously
// then runs cacheBust, then watch task
exports.default = series(parallel(scssTask, jsTask), watchTask);
