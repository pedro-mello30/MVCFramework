"use strict";
module.exports = function(grunt) {
    require("load-grunt-tasks")(grunt);
    require("time-grunt")(grunt);
    var appConfig = {
        dirs: {
            // PATHS SRC
            js: "assets/js",
            sass: "assets/sass",
            img: "assets/images",
            libs: "assets/lib",
            fonts: "assets/fonts",
            vendor: "assets/vendor",
            node: "node_modules",

            // FRAMEWORK PATHS
            base: '../admin',
            controller: '../admin/Controllers',
            model: '../admin/Models',
            views: '../admin/Views',
            lib: '../admin/Lib',

            // ASSETS FRAMEWORK
            css: "out/css",
            jsPath: "out/js",
            imgdest: "out/img",
            fontsPath: "out/fonts"
        },
        pkg: grunt.file.readJSON("package.json"),
        banner:
            "\n" +
            "/*\n" +
            " * -------------------------------------------------------\n" +
            " * Project: <%= pkg.title %>\n" +
            " * Version: <%= pkg.version %>\n" +
            " * Author:  <%= pkg.author.name %> \n" +
            " *\n" +
            " * Copyright (c) <%= grunt.template.today(\"yyyy\") %> <%= pkg.title %>\n" +
            " * -------------------------------------------------------\n" +
            " */\n" +
            "\n",
        watch: {
            options: {
                livereload: true,
                spawn: false
            },
            css: {
                files: "<%= dirs.sass %>/**",
                tasks: ["compass","autoprefixer"],
                options: {
                    spawn: false
                }
            },
            js: {
                files: "<%= jshint.all %>",
                tasks: ["jshint", "uglify"]
            },
        },
        compass: {
            dist: {
                options: {
                    force: true,
                    sassDir: "<%= dirs.sass %>",
                    fontsDir: "<%= dirs.fonts %>",
                    outputStyle: "compressed",
                    relativeAssets: true,
                    noLineComments: false,
                    cssDir: "<%= dirs.css %>",
                    fontsPath: "<%= dirs.fontsPath %>",
                }
            }
        },
        autoprefixer: {
            options: {
                browsers: ['last 5 version']
            },
            single_file: {
                src: '<%= dirs.css %>style.css'
            }

        },
        jshint: {
            options: {
                jshintrc: ".jshintrc"
            },
            all: [
                "Gruntfile.js",
                "<%= dirs.js %>/main.js"
            ]
        },
        uglify: {
            options: {
                mangle: false,
                banner: "<%= banner %>"
            },
            dist: {
                files: {
                    "<%= dirs.jsPath %>/site.min.js": [
                        "<%= dirs.vendor %>/Jquery/*.js",
                        "<%= dirs.vendor %>/bootstrap-4.0.0/dist/js/*.js",
                        "<%= dirs.vendor %>/DataTables/*.js",
                        "<%= dirs.js %>/main.js",
                    ]
                }
            }
        },
    };

    grunt.initConfig(appConfig);
    grunt.registerTask("default", ["watch"]);
};
