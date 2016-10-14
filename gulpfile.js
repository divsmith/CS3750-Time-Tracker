/*
This file in the main entry point for defining Gulp tasks and using Gulp plugins.
Click here to learn more. http://go.microsoft.com/fwlink/?LinkId=518007
*/

var gulp = require('gulp');
var watch = require('gulp-watch');

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