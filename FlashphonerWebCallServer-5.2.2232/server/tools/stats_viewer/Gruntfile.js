module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        browserify: {
            viewerGlobalObject: {
                src: ['./src/stats_viewer.js'],
                dest: './src/viewer.js',
                options: {
                    browserifyOptions: {
                        standalone: 'Viewer'
                    }
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-browserify');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-string-replace');
    grunt.registerTask('build', [
        'browserify'
    ]);
};