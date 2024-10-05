# Ridiculously Responsive Social Sharing Buttons

The Ridiculously Responsive Social Sharing Buttons module provides the ability
to use the Ridiculously Responsive Social Share Buttons to a Drupal site.

RRSSB are social sharing buttons that you can drop into any website with
attractive SVG-based icons, small download, and browser compatibility. No
3rd-party scripts.

You can choose to add the buttons to the end of certain node types or use the
block to put them wherever you want.

Originally designed as share buttons (where a visitor is encouraged to share
your page on their social stream), the buttons can equally be used as follow
buttons, where the visitor is encouraged to visit your social stream.

- For a full description of the module, visit the [project page].
- To submit bug reports and feature suggestions, or to track changes, use the
  [issue queue].

[Project page]: https://www.drupal.org/project/rrssb
[issue queue]: https://www.drupal.org/project/issues/rrssb

## Table of contents

- Requirements
- Installation
- Configuration
- Maintainers

## Requirements

This module requires the external [RRSSB+ library] which will be automatically
installed by Composer, the supported installation method.

If you don't user Composer, you need to manually download the library zip file,
and extract the files to `libraries/rrssb-plus`. Use the URL on the site
"Status report" page to ensure that you get the right version.

[RRSSB+ library]: https://symfony.com/doc/current/mailer.html

## Installation

Install as you would normally install a contributed Drupal module. For further
information, see _[Installing Drupal Modules]_.

[Installing Drupal Modules]: https://www.drupal.org/docs/extending-drupal/installing-modules

## Configuration

1. Once the module has been installed and enabled, you can choose your
   buttons and settings on the module configuration page:
   Administration > Configuration > Content > RRSSB > Default.
1. You can add multiple share/follow blocks with different settings, by
   clicking the 'Add RRSSB Button Set' button on the RRSSB configuration
   page.
1. After your RRSSB block or blocks are created, you can add them to page
   layout in the block user interface Administration > Structure > Block the
   way you add other blocks.

## Maintainers

- Adam Shepherd - [AdamPS](https://www.drupal.org/u/adamps)
- Himanshu Dixit - [himanshu-dixit](https://www.drupal.org/u/himanshu-dixit)
- Michael Roberts - [mike.roberts](https://www.drupal.org/u/mike.roberts)
