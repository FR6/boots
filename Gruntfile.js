'use strict';
module.exports = function(grunt) {

	// Show elapsed time
	//require('time-grunt')(grunt);

	grunt.initConfig({

		less: {
			build: {
				options: {
					compress: true
				},
				files: {
					'public/css/index.css': 'public/css/main.less'
				}			
			}
		}
	});

	grunt.loadNpmTasks('grunt-contrib-less');

	grunt.registerTask('default', ['less']);
};