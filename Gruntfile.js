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
            },
            tinymce: {
                files: [
                    {
                        expand: true,
                        dest: 'public/js/themes/',
                        cwd: 'bower_components/tinymce-dist/themes/',
                        src: '*/*',
                        flatten: false,
                        filter: 'isFile'
                    },
                    {
                        expand: true,
                        dest: 'public/js/skins/',
                        cwd: 'bower_components/tinymce-dist/skins/',
                        src: '**/*',
                        flatten: false,
                        filter: 'isFile'
                    },
                    {
                        expand: true,
                        dest: 'public/js/plugins/',
                        cwd: 'bower_components/tinymce-dist/plugins/',
                        src: '**/*',
                        flatten: false,
                        filter: 'isFile'
                    },
                    {
                        src: ['bower_components/tinymce-dist/tinymce.jquery.min.js'],
                        dest: 'public/js/tinymce.jquery.min.js',
                        filter: 'isFile'
                    }
                ]
            }
        },
        less: {
            development: {
                options: {
                    compress: false
                },
                files: {
                    "assets/css/global.css": "assets/less/global.less"
                }
            }
        },
        coffee: {
            compile: {
                files: {
                    'assets/js/main.js': ['assets/coffee/*.coffee']
                }
            }
        },
        concat: {
            options: {
                separator: ';',
                process: true
            },
            css_frontend: {
                src: ['assets/css/global.css'],
                dest: 'public/css/style.css'
            },
            js_frontend: {
                src: [
                    'bower_components/jquery/dist/jquery.js',
                    'bower_components/bootstrap/dist/js/bootstrap.js',
                    'bower_components/moment/moment.js',
                    'assets/js/main.js'
                ],
                dest: 'assets/js/script.join.js'
            }
        },
        uglify: {
            options: {
                mangle: true
            },
            frontend: {
                files: {
                    'public/js/app.min.js': 'assets/js/script.join.js'
                }
            }
        },
        cssmin: {
            options: {
                shorthandCompacting: false,
                roundingPrecision: -1
            },
            target: {
                files: {
                    'public/css/style.min.css': ['public/css/style.css']
                }
            }
        },
        watch: {
            js_frontend: {
                files: [
                    'bower_components/jquery/jquery.js',
                    'bower_components/bootstrap/dist/js/bootstrap.js',
                    'assets/js/main.js'
                ],
                tasks: ['concat:js_frontend', 'uglify:frontend'],
                options: {
                    livereload: true
                }
            },
            less: {
                files: ['assets/less/*.less'],
                tasks: ['less', 'concat:css_frontend'],
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
    grunt.registerTask('compile', ['copy:fonts', 'copy:tinymce', 'less', 'coffee', 'concat', 'cssmin', 'uglify']);
    grunt.registerTask('server', ['less', 'coffee', 'concat', 'uglify', 'php']);
    grunt.registerTask('check', ['phplint:all']);
};