/*global require */
/*eslint no-console: 1 */
"use strict";
const gulp = require( 'gulp' );
const uglify = require( 'gulp-uglify' );
const rename = require( 'gulp-rename' );
const sass = require( 'gulp-sass' );
const cssnano = require( 'gulp-cssnano' );
const mq = require( 'gulp-combine-mq' );
const autoprefixer = require( 'gulp-autoprefixer' );

const ASSET_DIR = 'assets/';
const CONF = {
	js : {
		src : ASSET_DIR + 'js/src/',
		dest: ASSET_DIR + 'js/dist/'
	},
	css: {
		src : ASSET_DIR + 'scss/',
		dest: ASSET_DIR + 'css/'
	}
};

gulp.task( 'scripts', function() {
	const dest = CONF.js.dest;

	gulp.src( [ CONF.js.src + '/*.js' ] )
		.pipe( gulp.dest( dest ) )
		.pipe( rename( {
			extname: '.min.js'
		} ) )
		.pipe( uglify( {
			output: {
				ascii_only: true
			}
		} ) )
		.pipe( gulp.dest( dest ) );
} );

gulp.task( 'styles', function() {
	gulp.src( CONF.css.src + '*.scss' )
		.pipe( sass( {
			indentType : 'tab',
			indentWidth: 1,
			outputStyle: 'expanded'
		} ) )
		.pipe( mq() )
		.pipe( autoprefixer( {
			cascade: false
		} ) )
		.pipe( gulp.dest( CONF.css.dest ) )
		.pipe( cssnano( { 'zindex': false } ) )
		.pipe( rename( { suffix: '.min' } ) )
		.pipe( gulp.dest( CONF.css.dest ) )
} );

// Main task
gulp.task( 'default', [ 'styles', 'scripts' ] );