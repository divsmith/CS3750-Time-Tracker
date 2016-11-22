/*
This file in the main entry point for defining Gulp tasks and using Gulp plugins.
Click here to learn more. http://go.microsoft.com/fwlink/?LinkId=518007
*/

var gulp = require('gulp');
var watch = require('gulp-watch');
var webserver = require('gulp-webserver');
var php = require('gulp-connect-php');
var browserSync = require('browser-sync');

gulp.task('fonts', function () {
    return gulp.src([
        'bower_components/bootstrap/dist/fonts/*'
    ])
.pipe(gulp.dest('css/fonts'))
});

gulp.task('JSdependencies', function () {
    return gulp.src([
        'bower_components/sweetalert2/dist/sweetalert2.js',
        'bower_components/es6-promise/es6-promise.js',
        'bower_components/sugarjs/dist/sugar.js',
        'bower_components/jquery/dist/jquery.js',
        'bower_components/momentjs/min/moment.min.js',
        'bower_components/jquery-validation/dist/jquery.validate.js',
        'bower_components/bootstrap/dist/js/bootstrap.min.js'
    ])
    .pipe(gulp.dest('js/libs'))
});

gulp.task('CSSdependencies', function () {
    return gulp.src([
        'bower_components/sweetalert2/dist/sweetalert2.css',
        'bower_components/bootstrap/dist/css/bootstrap-theme.min.css',
        'bower_components/bootstrap/dist/css/bootstrap.min.css'
    ])
    .pipe(gulp.dest('css/libs'))
});

gulp.task('start-all', ['CSSdependencies', 'JSdependencies', 'css', 'js', 'fonts', 'html'], function () {
    gulp.start('CSSdependencies');
    gulp.start('JSdependencies');
    gulp.start('fonts');
});

gulp.task('dist', function () {
    return gulp.src([
        'css/**/*',
        'js/**/*',
        '*.html',
        'img/**/*',
        'favicon.ico',
        'robots.txt',
        '.htaccess',
        'apple-touch-icon.png'
    ], { base: '.' })
    .pipe(gulp.dest('dist'));
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
    php.server({base: 'RoadHomeSymfony/app'}, function () {
        browserSync({
            proxy: '127.0.0.1:8000'
        });
    });

    gulp.watch('**/*.php').on('change', function () {
        browserSync.reload();
    });
});