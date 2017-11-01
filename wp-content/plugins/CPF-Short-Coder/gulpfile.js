'use strict';

var gulp        = require('gulp');
var $           = require('gulp-load-plugins')();

var bourbon     = require('node-bourbon');
var neat        = require('node-neat');

var sassdoc     = require('sassdoc');

// Task Optimization
var runSequence     = require('run-sequence');
var browserSync     = require('browser-sync').create();

var paths = {
    css:    './*.css',
    scss:   './assets/scss/**/*.scss'
};

var destPaths = {
    css:    './assets/css/'
};

var cssSrc = './*.css';
var cssDist = './';

var scssSrc = 'assets/scss/**/*.scss';

gulp.task('sass', function () {
    return gulp.src(paths.scss)
        .pipe($.sourcemaps.init())
        .pipe($.plumber({
            errorHandler: function (error) {
                console.log(error.message);
                this.emit('end');
            }}))
        .pipe($.sass({
            includePaths: [].concat(
                require('bourbon').includePaths,
                require('node-neat').includePaths
            )
        }))
        .pipe($.sourcemaps.write())
        .pipe(gulp.dest(destPaths.css))
        .pipe(browserSync.stream());
});

gulp.task('inject-scss', function () {
    var target = gulp.src('./assets/scss/_import.scss');

    return target
        .pipe($.inject(gulp.src(['./assets/scss/01_atom/**/_*.scss'], {read: false}),
            {relative: true, starttag: '// atom:inject', endtag: '// endinject'})
        )
        .pipe($.inject(gulp.src(['./assets/scss/02_molecule/**/_*.scss'], {read: false}),
            {relative: true, starttag: '// molecule:inject', endtag: '// endinject'})
        )
        .pipe($.inject(gulp.src(['./assets/scss/03_organism/**/_*.scss'], {read: false}),
            {relative: true, starttag: '// organism:inject', endtag: '// endinject'})
        )
        .pipe($.inject(gulp.src(['./assets/scss/04_template/**/_*.scss'], {read: false}),
            {relative: true, starttag: '// template:inject', endtag: '// endinject'})
        )
        .pipe($.inject(gulp.src(['./assets/scss/05_page/**/_*.scss'], {read: false}),
            {relative: true, starttag: '// page:inject', endtag: '// endinject'})
        )
        .pipe(gulp.dest('./assets/scss'));
});

gulp.task('autoprefixer', function () {
    return gulp.src(cssSrc)
        .pipe($.autoprefixer({
            browsers: ['last 2 versions'],
            cascade: false
        }))
        .pipe(gulp.dest(cssDist));
});

gulp.task('gcmq', function(){
    gulp.src(cssSrc)
        .pipe($.groupCssMediaQueries())
        .pipe(gulp.dest(cssDist));
});

gulp.task('csscomb', function() {
    return gulp.src(cssSrc)
        .pipe($.csscomb())
        .pipe(gulp.dest(cssDist));
});

// Minify & Optimize
gulp.task('minify-css', function() {
    return gulp.src(cssSrc)
        .pipe($.sourcemaps.init())
        .pipe($.cleanCss({compatibility: 'ie8'}))
        .pipe($.rename({
            extname: '.min.css'
        }))
        .pipe($.sourcemaps.write())
        .pipe(gulp.dest(cssDist));
});

gulp.task('imagemin', function () {
    gulp.src('/{,**/}*.{png,jpg,gif}')
        .pipe($.imagemin({
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [$.pngquant()]
        }))
        .pipe(gulp.dest('./assets/img'));
});

gulp.task('svg', function () {
    gulp.src('./assets/svg/sprites/*.svg')
        .pipe($.svgmin())
        .pipe($.svgstore({ inlineSvg: true }))
        .pipe($.cheerio({
            run: function ($, file) {
                $('svg').addClass('hide');
                $('[fill]').removeAttr('fill');
            },
            parserOptions: { xmlMode: true }
        }))
        .pipe(gulp.dest('assets/svg'));
});

gulp.task('svg2png', function () {
    gulp.src('./assets/svg/sprites/*.svg')
        .pipe(svg2png(3))
        .pipe($.rename({ prefix: "icons.svg." }))
        .pipe($.imagemin())
        .pipe(gulp.dest('assets/svg'));
});

gulp.task('csslint', function() {
    gulp.src(cssSrc)
        .pipe($.csslint())
        .pipe($.csslint.reporter());
});

gulp.task('browser-sync-design', ['inject-scss', 'sass'], function() {
    browserSync.init({
        server: {
            baseDir: "./"
        }
    });
    gulp.watch(scssSrc, ['inject-scss', 'sass']);
    gulp.watch('./*.html').on('change', browserSync.reload);
    gulp.watch("./assets/css/**/*.css").on('change', browserSync.reload);
    gulp.watch("./assets/js/**/*.js").on('change', browserSync.reload);
    gulp.watch("./assets/img/**/*.*").on('change', browserSync.reload);
});

gulp.task('browser-sync-proxy', ['inject-scss', 'sass'], function() {
    browserSync.init({
        proxy: 'vccw.domain-name'
    });
    gulp.watch(scssSrc, ['inject-scss', 'sass']);
    gulp.watch('./*.html').on('change', browserSync.reload);
    gulp.watch('./**/*.php').on('change', browserSync.reload);
    gulp.watch("./assets/css/**/*.css").on('change', browserSync.reload);
    gulp.watch("./assets/js/**/*.js").on('change', browserSync.reload);
    gulp.watch("./assets/img/**/*.*").on('change', browserSync.reload);
});

gulp.task('sassdoc', function () {
    return gulp.src(scssSrc)
        .pipe(sassdoc());
});

gulp.task('gulp-uglify', function() {
    gulp.src(['./js/**/*.js','!./js/min/**/*.js'])
        .pipe($.uglify())
        .pipe(gulp.dest("./js/min"));
});

gulp.task('default', ['browser-sync-design']);

gulp.task('dist', function(callback) {
    return runSequence(
        'compass',
        ['autoprefixer','imagemin'],
        'gcmq',
        'csscomb',
        'minify-css',
        callback
    );
});