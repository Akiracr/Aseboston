# Aseboston - Main Site Drupal

Drupal site.

# Instalation

1. Clone the web/sites/default/base.settings.local.php and rename as settings.local.php
2. Edit settings.local.php to setup database and trusted_host_patterns settings.

# Theming

This site used a subtheme of [Barrio Bootstrap 5 Theme](https://www.drupal.org/project/bootstrap_barrio).

## Instalation

The subtheme was generated using [Bootstrap 5 - SASS Starter Kit](https://www.drupal.org/docs/contributed-themes/bootstrap-4-sass-barrio-starter-kit/installation), is locate on web/themes/custom/, the code is compile using Gulp, execute the following steps to install and setup.

 1. Install Gulp: `npm install --global gulp-cli`
 2. Install dependencies including Bootstrap latest version: `npm install`
 3. Change permissions to node modules folderchmod -R u+x node_modules
 4. Clone the base.gulpfile.js and rename as gulpfile.js
 5. Update line#60 of the gulpfile.js file with your own domain.

```php
browserSync.init({
  proxy: "http://yourdomain.com",
});
```

# Code Standards Test

Use PHP CodeSniffer and Drupal Coder to test and improved the syntax of your code.

1. Execute in the root of the project ./vendor/bin/phpcs --standard=Drupal --extensions=php,module,inc,install,test,profile,theme,css,info,txt,md,yml [DIRECTORY_PATH] and fix any error.

Nota: In some cases could be required to prepare your environment add the install path of drupal coder with the following command: ./vendor/bin/phpcs --config-set installed_paths vendor/drupal/coder/coder_sniffer
