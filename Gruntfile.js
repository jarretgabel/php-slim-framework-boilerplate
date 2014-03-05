/*global module:false*/
module.exports = function(grunt) {

  /**
   * Init grunt config
   */
  grunt.initConfig({

    /**
     * Compass Compass
     * @type {Object}
     */
    compass: {
      dev: {
        options: {
          cssDir:                   "public/assets/stylesheets",
          sassDir:                  "app/assets/stylesheets",
          specify:                  'app/assets/stylesheets/styles.scss',
          outputStyle:              'expanded',
          noLineComments: true
        }
      }
    },

    sprite:{
      all: {
        src: 'app/assets/images/ui/*.png',
        destImg: 'public/assets/images/ui/ui.png',
        destCSS: 'app/assets/stylesheets/_ui.scss',
        'algorithm': 'binary-tree',
        'padding': 2,
        'engine': 'canvas',
        'imgPath': '/assets/images/ui/ui.png',
        'cssTemplate': 'app/assets/images/sprite_positions.scss.mustache',
        'engineOpts': {
          'imagemagick': true
        },
        'imgOpts': {
           'format': 'png',
           'quality': 100
        }
      }
    },

    /**
     * Concat javascripts & stylesheets
     */
    concat: {
      js: {
        src: [
          "app/assets/javascripts/App.js"
        ],
        dest: 'public/assets/javascripts/scripts.js'
      },
      mobile: {
        src: [
          "app/assets/javascripts/MobileApp.js"
        ],
        dest: 'public/assets/javascripts/mobile-scripts.js'
      },
      vendor: {
        src: [
          "app/assets/javascripts/vendor/jquery-1.10.2.min.js",
          "app/assets/javascripts/vendor/jquery.mousewheel.js",
          "app/assets/javascripts/vendor/jquery-ui-1.10.4.custom.js",
          "app/assets/javascripts/vendor/json2.js",
          "app/assets/javascripts/vendor/TweenLite.min.js",
          "app/assets/javascripts/vendor/CSSPlugin.min.js",
          "app/assets/javascripts/vendor/EasePack.min.js",
          "app/assets/javascripts/vendor/css3-multi-column.js",
          "app/assets/javascripts/vendor/AsyncAPI.js",
          "app/assets/javascripts/vendor/hammer.min.js"
        ],
        dest: 'public/assets/javascripts/plugins.js'
      }
    },

    /**
     * Watch the following files for changes and then run tasks
     */
    watch: {
      scripts: {
        files: [
          'Gruntfile.js',
          'app/assets/images/sprite_positions.scss.mustache',
          'app/assets/images/**/*.png',
          'app/assets/stylesheets/*.scss',
          'app/assets/stylesheets/*.css',
          'app/assets/javascripts/controllers/*.js',
          'app/assets/javascripts/views/*.js',
          'app/assets/javascripts/core/*.js',
          'app/assets/javascripts/MobileApp.js',
          'app/assets/javascripts/App.js',
          '**/*.haml',
          '!app/assets/stylesheets/_ui.scss',
          '!**/node_modules/**',
          '!**/.git/**'
        ],
        tasks: ['default'],
        options: {
          spawn: false
        },
      },
    }

  });

  /**
   * Load modules
   */
  grunt.loadNpmTasks('grunt-contrib-compass');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-haml');
  grunt.loadNpmTasks('grunt-spritesmith');

  /**
   * Register default tasks
   */
  grunt.registerTask('default', ['compass', 'concat:js', 'concat:mobile']);
  grunt.registerTask('compile-all', ['sprite', 'compass', 'concat:js', 'concat:mobile', 'concat:vendor']);
  grunt.registerTask('compile-vendor', ['concat:vendor']);
  // grunt.registerTask('compile-production', ['concat:vendor']);

};
