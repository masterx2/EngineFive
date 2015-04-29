module.exports = function(grunt) {
    grunt.initConfig({
        php: {
            dist: {
                options: {
                    port: 8080,
                    base: 'public',
                    open: true,
                    keepalive: true
                }
            }
        },
        phplint: {
            options: {  
                swapPath: '/tmp'
            },
            all: ['public/*.php', 'mvc/*.php']
        },
        less: {
            base: {
                options: {
                    compress: false
                },
                files: [
                    {
                        expand: true,
                        cwd: 'assets/less/base',
                        src: ['*.less'],
                        dest: 'assets/css/base',
                        ext: '.css'
                    }
                ]
            },
            project: {
                options: {
                    compress: false
                },
                files: [
                    {
                        expand: true,
                        cwd: 'assets/less',
                        src: ['*.less'],
                        dest: 'assets/css',
                        ext: '.css'
                    }
                ]
            }
        },
        coffee: {
            project: {
                files: [
                    {
                        expand: true,
                        cwd: 'assets/coffee',
                        src: ['*.coffee'],
                        dest: 'assets/js',
                        ext: '.js'
                    }
                ]
            }
        },
        cssmin: {
            options: {
                shorthandCompacting: false,
                roundingPrecision: -1
            },
            base: {
                files: [
                    {
                        expand: true,
                        cwd: 'assets/css/base',
                        src: ['*.css'],
                        dest: 'public/css/base',
                        ext: '.min.css'
                    }
                ]
            },
            project: {
                files: [
                    {
                        expand: true,
                        cwd: 'assets/css',
                        src: ['*.css'],
                        dest: 'public/css',
                        ext: '.min.css'
                    }
                ]
            }
        },
        concat: {
            options: {
                separator: ';',
                process: true
            },
            js_base: {
                src: [
                    'bower_components/jquery/dist/jquery.js',
                    'bower_components/bootstrap/dist/js/bootstrap.js',
                    'bower_components/moment/moment.js'
                ],
                dest: 'assets/js/base/base.js'
            }
        },
        uglify: {
            options: {
                mangle: true
            },
            base: {
                files: [
                    {
                        expand: true,
                        cwd: 'assets/js/base',
                        src: ['*.js'],
                        dest: 'public/js/base',
                        ext: '.min.js'
                    }
                ]
            },
            project: {
                files: [
                    {
                        expand: true,
                        cwd: 'assets/js',
                        src: ['*.js'],
                        dest: 'public/js',
                        ext: '.min.js'
                    }
                ]
            }
        },
        copy: {
            fonts: {
                files: [
                    {
                        expand: true,
                        dest: 'public/fonts/',
                        cwd: 'bower_components/bootstrap/fonts/',
                        src: '**/*',
                        flatten: true,
                        filter: 'isFile'
                    }
                ]
            }
        },
        watch: {
            js_project: {
                files: ['assets/js/*.js'],
                tasks: ['uglify:project'],
                options: {
                    livereload: true
                }
            },
            less_project: {
                files: ['assets/less/*.less'],
                tasks: ['less:project', 'cssmin:project'],
                options: {
                    livereload: true
                }
            },
            coffee: {
                files: ['assets/coffee/*.coffee'],
                tasks: ['coffee'],
                options: {
                    livereload: true
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-php');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-coffee');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-phpunit');
    grunt.loadNpmTasks('grunt-phplint');

    grunt.registerTask('default', ['watch']);
    grunt.registerTask('compile', ['copy:fonts', 'less', 'coffee', 'concat', 'cssmin', 'uglify']);
    grunt.registerTask('server', ['less', 'coffee', 'concat', 'uglify', 'php']);
    grunt.registerTask('check', ['phplint:all']);
};