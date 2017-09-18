var gulp = require('gulp');
var cssMin = require('gulp-css');
var ngAnnotate = require('gulp-ng-annotate');
var babelMinify = require("gulp-babel-minify");


// Need because of `yii console`
var rename = require('gulp-rename');
var minimist = require('minimist');
var options = minimist(process.argv.slice(2), { string: 'src', string: 'dist' });
var destDir = options.dist.substring(0, options.dist.lastIndexOf("/"));
var destFile = options.dist.replace(/^.*[\\\/]/, '');

// Use `compress-js` task for JavaScript files
gulp.task('compress-js', function() {
	gulp.src(options.src)

		// .pipe(uglify())

		// .pipe(ngAnnotate())

		.pipe(ngAnnotate({add: true}))

		.pipe(babelMinify({
			mangle: {
				keepClassName: true
			}
		}))

		// .pipe(minifyjs())

		.pipe(rename(destFile))
		.pipe(gulp.dest(destDir))
});

// Use `compress-css` task for CSS files
gulp.task('compress-css', function() {
	gulp.src(options.src)
		.pipe(cssMin())

		.pipe(rename(destFile))
		.pipe(gulp.dest(destDir))
});