var gulp = require('gulp'),
    connect = require('gulp-connect-php');


gulp.task('default', function () {
    connect.server();
});
