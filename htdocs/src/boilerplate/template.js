'use strict';

// Basic template description.
exports.description = 'Scaffolds a new project with GruntJS, SASS, MODX and optionally SUSY.';

// Template-specific notes to be displayed after question prompts.
exports.after = 'You should now install project dependencies with _npm ' +
    'install_. After that, you may execute project tasks with _grunt_. For ' +
    'more information about installing and configuring Grunt, please see ' +
    'the Getting Started guide:' +
    '\n\n' +
    'http://gruntjs.com/getting-started';

// Any existing file or directory matching this wildcard will cause a warning.
exports.warnOn = '*';

// The actual init template.
exports.template = function(grunt, init, done) {

    init.process({}, [
        // Prompt for these values.
        // init.prompt('name'),
        // init.prompt('title'),
        // init.prompt('description'),
        // init.prompt('version'),
        // init.prompt('SUSY', 'no')
    ], function(err, props) {
        // Files to copy (and process).
        var files = init.filesToCopy(props);

        // Actually copy (and process) files.
        init.copyAndProcess(files, props);

        // // Empty folders won't be copied over so make them here
        // grunt.file.mkdir('assets/fonts');
        // grunt.file.mkdir('assets/lib');
        // grunt.file.mkdir('assets/js/vendor');
        // grunt.file.mkdir('assets/js/plugins');


        // // Copy files/folders depending on any options chosen
        // if(props.SUSY == 'yes') {
        //     init.copy('config.rb');
        //     init.copy('assets/css/scss/_base.scss');
        // }

        // Generate package.json file, used by npm and grunt.
        init.writePackageJSON('package.json', {
                // "name": props.name,
                // "description": props.description,
                // "version": props.version,
                // "title": props,
                "name": "frameworkmvcphp",
                "version": "0.1.0",
                "title": "Framework MVC PHP",
                "description": "",
                "license": "MIT",
                "private": true,
                "author": {
                    "name": "Pedro Mello"
                },
                "keywords": [
                    "framework",
                    "boilerplate",
                    "project",
                    "sass",
                    "compass",
                    "grunt",
                    "bower",
                    "php"
                ],
                "repository": {
                    "type": "",
                    "url": ""
                },
                "bugs": {
                    "url": ""
                },
                "engines": {
                    "node": ">=8.10.0",
                    "npm": ">=8.10"
                },
                "devDependencies": {
                    "grunt": "^1.2.1",
                    "grunt-autoprefixer": "~3.0.4",
                    "grunt-contrib-compass": "^1.1.1",
                    "grunt-contrib-connect": "^2.1.0",
                    "grunt-contrib-jshint": "~2.1.0",
                    "grunt-contrib-uglify": "~4.0.1",
                    "grunt-contrib-watch": "^1.1.0",
                    "grunt-shell": "~3.0.1",
                    "load-grunt-tasks": "~5.1.0",
                    "time-grunt": "~2.0.0",
                    "bootstrap-sass": "^3.4.1"
                },
                "dependencies": {
                    "jquery": "^3.5.1",
                    "bootstrap": "4.5.0",
                    "datatables.net-bs4": "^1.10.21"
                }
            }



        );

        // All done!
        done();
    });
};