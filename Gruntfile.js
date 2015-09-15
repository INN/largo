module.exports = function(grunt) {
    'use strict';

    // Force use of Unix newlines
    grunt.util.linefeed = '\n';

    // Find what the current theme's directory is, relative to the WordPress root
    var path = process.cwd();
    path = path.replace(/^[\s\S]+\/wp-content/, "\/wp-content");

    var CSS_LESS_FILES = {
        'css/style.css': 'less/style.less',
        'css/editor-style.css': 'less/editor-style.less',
        'homepages/assets/css/single.css': 'homepages/assets/less/single.less',
        'homepages/assets/css/top-stories.css': 'homepages/assets/less/top-stories.less',
        'homepages/assets/css/legacy-three-column.css': 'homepages/assets/less/legacy-three-column.less'
    };

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        less: {
            compile: {
                options: {
                    paths: ['less'],
                    sourceMap: true,
                    outputSourceFiles: true,
                    sourceMapBasepath: path,
                },
                files: CSS_LESS_FILES
            }
        },

        uglify: {
            target: {
                options: {
                    report: 'gzip'
                },
                files: [{
                    expand: true,
                    cwd: 'js',
                    src: [
                        'custom-less-variables.js',
                        'custom-sidebar.js',
                        'custom-term-icons.js',
                        'featured-media.js',
                        'image-widget.js',
                        'largoCore.js',
                        'load-more-posts.js',
                        'top-terms.js',
                        'update-page.js',
                        'widgets-php.js',
                        '!*.min.js'
                    ],
                    dest: 'js',
                    ext: '.min.js'
                }]
            }
        },

        cssmin: {
            target: {
                options: {
                    report: 'gzip'
                },
                files: [
                    {
                        expand: true,
                        cwd: 'css',
                        src: ['*.css', '!*.min.css'],
                        dest: 'css',
                        ext: '.min.css'
                    },
                    {
                        expand: true,
                        cwd: 'homepages/assets/css',
                        src: ['*.css', '!*.min.css'],
                        dest: 'homepages/assets/css',
                        ext: '.min.css'
                    }
                ]
            }
        },

        shell: {
            apidocs: {
                command: [
                    'cd docs',
                    'rm -Rf api _build/html/api _build/doctrees/api',
                    'make php',
                ].join('&&'),
                options: {
                    stdout: true
                }
            },
            sphinx: {
                command: [
                    'cd docs',
                    'make html',
                ].join('&&'),
                options: {
                    stdout: true
                }
            }
        },

        watch: {
            less: {
                files: [
                    'less/**/*.less',
                    'less/**/**/*.less',
                    'homepages/assets/less/**/*.less'
                ],
                tasks: [
                    'less:compile',
                    'cssmin'
                ]
            },
            sphinx: {
                files: ['docs/*.rst', 'docs/*/*.rst'],
                tasks: ['docs']
            }
        },

        pot: {
            options: {
                text_domain: 'largo',
                dest: 'lang/',
                keywords: [ //WordPress localization functions
                    '__:1',
                    '_e:1',
                    '_x:1,2c',
                    'esc_html__:1',
                    'esc_html_e:1',
                    'esc_html_x:1,2c',
                    'esc_attr__:1',
                    'esc_attr_e:1',
                    'esc_attr_x:1,2c',
                    '_ex:1,2c',
                    '_n:1,2',
                    '_nx:1,2,4c',
                    '_n_noop:1,2',
                    '_nx_noop:1,2,3c'
                ]
            },
            files: {
                src: '**/*.php',
                expand: true
            }
        },

        po2mo: {
            files: {
                src: 'lang/*.po',
                expand: true
            }
        }
    });

    require('load-grunt-tasks')(grunt, { scope: 'devDependencies' });
    grunt.loadNpmTasks('grunt-shell');
    grunt.registerTask('apidocs', ['shell:apidocs']);
    grunt.registerTask('docs', ['shell:sphinx']);
}
