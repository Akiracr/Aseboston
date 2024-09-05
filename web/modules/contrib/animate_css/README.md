Description
-------------------------
Animate is an integration of animate.css, a bunch of cool, fun, and
cross-browser animations for you to use in your projects.
Great for emphasis, home pages, sliders, and general just-add-water-awesomeness.

Dependencies
-------------------------
Libraries
Animate in libraries/animate (can be downloaded from https://github.com/animate-css/animate.css/)

Module usage
-------------------------

This module allows you to implement Animate css properties in you js/css.
So once you have this module enabled, you can use its properties.
Eg : in your Js you can write : $('#yourElement').addClass('animated bounceOutLeft');

Installation and Configuration
-------------------------

1. Download and ADD the library from
  https://github.com/animate-css/animate.css/ in libraries/animate.css
2. Install the module as usual.
3. In your js file you can write the code.
  Library usage : https://github.com/animate-css/animate.css/blob/main/README.md

Installation via Composer
-------------------------
It is assumed you are installing Drupal through Composer using the Drupal
Composer facade. See https://www.drupal.org/docs/develop/using-composer/using-composer-to-manage-drupal-site-dependencies#drupal-packagist

Before you add the module using composer, you should add an installer path
so that the animate.css JavaScript library is installed in the correct location.
You might have an entry similar to below in your composer.json already if
you had used [drupal-composer/drupal-project](https://github.com/drupal-composer/drupal-project)
to create your project.
```
    "extra": {
        "installer-paths": {
            "web/libraries/{$name}": ["type:drupal-library"]
        }
    }
```

Where web/libraries/ is the path to the libraries directory relative to your
project root. Add daneden/animate.css repository.
```
    "repositories": [
        {
            "type": "package",
            "package": {
                "name": "daneden/animate.css",
                "version": "3.5.3",
                "type": "drupal-library",
                "dist": {
                    "url": "https://github.com/ange187/animate.css/archive/3.5.3.zip",
                    "type": "zip"
                }
            }
        },
    ],
```

Then, run the following composer command:

```
composer require daneden/animate.css
composer require drupal/animate_css
```
