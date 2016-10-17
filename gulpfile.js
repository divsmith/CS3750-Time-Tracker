/*
This file in the main entry point for defining Gulp tasks and using Gulp plugins.
Click here to learn more. http://go.microsoft.com/fwlink/?LinkId=518007
*/

var gulp = require('gulp');
var watch = require('gulp-watch');

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
        'bower_components/sugarjs/dist/sugar.js'
    ])
    .pipe(gulp.dest('js/libs'))
});

gulp.task('CSSdependencies', function () {
    return gulp.src([
        'bower_components/sweetalert2/dist/sweetalert2.css'
    ])
    .pipe(gulp.dest('css/libs'))
});