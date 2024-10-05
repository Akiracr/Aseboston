/**
 * @file
 * Global utilities.
 *
 */
(function($, Drupal) {

  'use strict';

  Drupal.behaviors.aseboston = {
    attach: function(context, settings) {

      // Add icon tu views button.
      once('addMagnifyingGlassIcon', '.views-exposed-form .btn', context).forEach(function (button) {
        // Prepend the magnifying glass icon at the beginning of the button
        $(button).prepend('<i class="fa-solid fa-magnifying-glass"></i> ');
      });

    }
  };

})(jQuery, Drupal);
