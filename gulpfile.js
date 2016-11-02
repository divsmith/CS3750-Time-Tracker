/*
This file in the main entry point for defining Gulp tasks and using Gulp plugins.
Click here to learn more. http://go.microsoft.com/fwlink/?LinkId=518007
*/

var gulp = require('gulp');
var watch = require('gulp-watch');
var webserver = require('gulp-webserver');
var php = require('gulp-connect-php');
var browserSync = require('browser-sync');

var destination = '/wamp/www';

gulp.task('css', function () {
    // place code for your default task here
    return watch('css/**/*.css', { ignoreInitial: false })
    .pipe(gulp.dest('/wamp/www/css'));
});

gulp.task('js', function () {
    // place code for your default task here
    return watch('js/**/*.js', { ignoreInitial: false })
    .pipe(gulp.dest('/wamp/www/js'));
});

gulp.task('fonts', function () {
    // place code for your default task here
    return watch('fonts/**/*.*', { ignoreInitial: false })
    .pipe(gulp.dest('/wamp/www/fonts'));
});

gulp.task('html', function () {
    // place code for your default task here
    return watch('*.html', { ignoreInitial: false })
    .pipe(gulp.dest('/wamp/www'));
});

gulp.task('JSdependencies', function () {
    return gulp.src([
        'bower_components/sweetalert2/dist/sweetalert2.js',
        'bower_components/es6-promise/es6-promise.js',
        'bower_components/sugarjs/dist/sugar.js',
        'bower_components/jquery/dist/jquery.js',
        'bower_components/momentjs/min/moment.min.js',
        'bower_components/jquery-validation/dist/jquery.validate.js'
    ])
    .pipe(gulp.dest('js/libs'))
});

gulp.task('CSSdependencies', function () {
    return gulp.src([
        'bower_components/sweetalert2/dist/sweetalert2.css'
    ])
    .pipe(gulp.dest('css/libs'))
});

gulp.task('start-all', ['CSSdependencies', 'JSdependencies', 'css', 'js', 'fonts', 'html'], function () {
    gulp.start('CSSdependencies');
    gulp.start('JSdependencies');
    gulp.start('css');
    gulp.start('js');
    gulp.start('fonts');
    gulp.start('html');
});

gulp.task('webserver', function () {
    gulp.src('./')
      .pipe(webserver({
          livereload: true,
          open: true,
          fallback: 'index.html',
          port: 8080
      }));
});

gulp.task('php', function () {
    php.server({ port: 8010, keepalive: true, open: true });
});