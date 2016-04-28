'use strict';
var gulp = require('gulp');
var gutil = require('gulp-util');
var sass = require('gulp-sass');
var cleanCSS = require('gulp-clean-css');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');

gulp.task('sass', function () {
  return gulp.src('./assets/sass/estilo.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('./assets/css'));
});

gulp.task('sass:watch', function () {
  gulp.watch('./assets/sass/**/*.scss', ['sass']);
});

gulp.task('minifyJS', function() {
  var srcFiles = [
    './assets/libs/jquery/dist/jquery.js',
    './assets/libs/jquery-mask-plugin/dist/jquery.mask.js',
    './assets/libs/bootstrap-sass/assets/javascripts/bootstrap.js',
    './assets/libs/Chart.js/dist/Chart.js',
    './assets/js/main.js'
    ];
  return gulp.src(srcFiles)
    .pipe(concat('concat.js'))
    .pipe(uglify())
    .pipe(rename('scripts.min.js'))
    .pipe(gulp.dest('./assets/js'));
});

gulp.task('minifyCSS',['sass'], function() {
  return gulp.src('./assets/css/estilo.css')
    .pipe(cleanCSS({keepSpecialComments : 0, debug: true}, function(details) {
      var name = gutil.colors.cyan(details.name);
      var original = gutil.colors.magenta(details.stats.originalSize + ' bytes');
      var minify = gutil.colors.magenta(details.stats.minifiedSize + ' bytes');
      gutil.log('Original ', name, original);
      gutil.log('Minify   ', name, minify);
    }))
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest('./assets/css/'));
});

gulp.task('minifyCSS:watch', ['sass:watch'], function () {
  gulp.watch('./assets/css/estilo.css', ['minifyCSS']);
});

// Fonts
gulp.task('fonts', function() {
  return gulp.src(['./assets/libs/font-awesome/fonts/fontawesome-webfont.*'])
    .pipe(gulp.dest('./assets/fonts/'));
});

gulp.task('default',['fonts','minifyCSS','minifyJS']);