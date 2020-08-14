// Initialize modules
// Importing specific gulp API functions lets us write them below as series() instead of gulp.series()
const { src, dest, watch, series, parallel } = require('gulp');
// Importing all the Gulp-related packages we want to use
const sourcemaps = require('gulp-sourcemaps');
const sass = require('gulp-sass');
const webpack = require('webpack-stream');
const named = require('vinyl-named');
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');
const purify = require('gulp-purifycss');

// File paths
const files = {
  scssPath: 'sass/**/*.scss',
  jsPath: 'js/**/*.js',
};

function scssCriticalTask() {
  const criticalFiles = [
    'template-parts/section-header.php',
    'template-parts/home-hero.php',
    'template-parts/blog-card.php',
    'template-parts/categories-links.php',
    'archive.php',
    'index.php',
    'search.php',
    'searchform.php',
    'single.php',
  ];
  const purifyOptions = {
    whitelist: [
      '*figure*',
      '*is-half*',
      '*columns*',
      '*columns:not(.is-desktop)*',
      '*button-cta*',
      '*is-size-2*',
      '*is-size-4-touch*',
      '*project-detail*',
      '*projects*',
      '*button-filter*',
      '*is-hidden-touch*',
      '*is-hidden-desktop*',
      '*blog-categories*',
      '*widget_categories*',
      '*wp-block-image*',
      '*content*',
      'hr',
    ],
  };

  return src(files.scssPath)
    .pipe(sass())
    .pipe(purify(criticalFiles, purifyOptions))
    .pipe(postcss([autoprefixer(), cssnano()]))
    .pipe(dest('css'));
}

// Sass task: compiles the style.scss file into style.css
function scssTask() {
  const purifyOptions = {
    whitelist: [
      '*wpcf7*',
      '*svg*',
      '*wp-block-gallery*',
      '*wp-block-column*',
      '*wp-block-image*',
      '*blocks-gallery-grid*',
      '*content*',
      '*widget_categories*',
      '*comment*',
      '*slide-down-out*',
      '*ol*',
      '*dl*',
      '*dt*',
      '*dd*',
      '*blockquote*',
      '*iframe*',
      '*video*',
      '*table*',
      '*td*',
      '*th*',
      '*aside*',
      '*code*',
    ],
  };

  return src(files.scssPath)
    .pipe(sourcemaps.init()) // initialize sourcemaps first
    .pipe(sass()) // compile SCSS to CSS
    .pipe(purify(['**/*.php', 'js/*.js'], purifyOptions))
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
    .pipe(webpack({ devtool: 'source-map' }))
    .pipe(dest('dist/js'));
}

// Watch task: watch SCSS and JS files for changes
// If any change, run scss and js tasks simultaneously
function watchTask() {
  watch(
    [files.scssPath, files.jsPath],
    { interval: 1000, usePolling: true }, //Makes docker work
    series(parallel(scssCriticalTask, scssTask, jsTask)),
  );
}

// Export the default Gulp task so it can be run
// Runs the scss and js tasks simultaneously
// then runs cacheBust, then watch task
exports.default = series(
  parallel(scssCriticalTask, scssTask, jsTask),
  watchTask,
);
