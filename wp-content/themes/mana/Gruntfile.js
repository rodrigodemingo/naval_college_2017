module.exports = function(grunt){

	css_files = [
		'framework/Pagebuilder/css/font-awesome.min.css',
        'framework/Pagebuilder/css/bootstrap-grid.css',
        'framework/Pagebuilder/css/prettyPhoto.css',
        'framework/Pagebuilder/css/device-mockups/device-mockups.css',
        'framework/Pagebuilder/css/device-mockups/device-mockups2.css',
        'framework/Pagebuilder/css/blox-frontend.css',
        'framework/Pagebuilder/css/blox-buttons.css',
        'framework/Pagebuilder/css/blox-icons.css',
        'framework/Pagebuilder/css/blox-callouts.css',
        'framework/Pagebuilder/css/blox-content-box.css',
        'framework/Pagebuilder/css/blox-notification-box.css',
        'framework/Pagebuilder/css/blox-placeholder.css',
        'framework/Pagebuilder/css/blox-image-frame.css',
        'framework/Pagebuilder/css/blox-audio.css',
        'framework/Pagebuilder/css/blox-price-table.css',
        'framework/Pagebuilder/css/blox-colors.css',
        'framework/Pagebuilder/css/blox-blog.css',
        'framework/Pagebuilder/css/blox-animations.css'
	];

	js_files = [
		'framework/Pagebuilder/js/jquery.cycle2.min.js',
        'framework/Pagebuilder/js/jplayer/jquery.jplayer.min.js',
        'framework/Pagebuilder/js/jquery.prettyPhoto.js',
        'framework/Pagebuilder/js/waypoints.min.js',
        'framework/Pagebuilder/js/knob.js',
        'framework/Pagebuilder/js/blox-frontend.js'
	];

	scripts_file = [
		'assets/js/respond.min.js',
        'assets/js/skrollr.min.js',
        'assets/js/jquery.isotope.min.js',
        'assets/js/jqueryslidemenu.js',
        'assets/js/jquery.fitvids.js',
        'assets/js/scripts.js'
	];

	// Project configuration.
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		// Concating js files
		uglify: {
			pack_combine: {
				options: {
					beautify: true,
					mangle: false,
					compress: false,
					preserveComments: 'all'
				},
				src: js_files,
				dest: 'framework/Pagebuilder/js/packages.js',
			},
			pack_compress: {
				src: js_files,
				dest: 'framework/Pagebuilder/js/packages.min.js',
			},
			scripts_compress: {
				src: scripts_file,
				dest: 'assets/js/scripts.min.js',
			}
		},

		cssmin: {
			options: {
				rebase: true
			},
			combine: {
				files: {
					'framework/Pagebuilder/css/packages.min.css': css_files
				}
			}
		},

		// Build all files when Less file changes
		watch: {
			css: {
				files: css_files,
				tasks: ['cssmin']
			},
			js_packs: {
				files: js_files,
				tasks: ['uglify:pack_compress']
			},
			scripts: {
				files: scripts_file,
				tasks: ['uglify:scripts_compress']
			}
		}
	});


	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-watch');

	defined_tasks = [
		'uglify:pack_compress',
		'uglify:scripts_compress',
		'cssmin',
		'watch'
	];

	grunt.registerTask('default', defined_tasks);

}
