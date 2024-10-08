<?php

/**
 * @file
 * Hooks provided by the Ridiculously Responsive Social Share Buttons module.
 */

/**
 * Provide configuration for social share buttons.
 *
 * @return array
 *   Array of button configuration. The key is the button name.
 *   The value is an associative array that may contain the following
 *   key-value pairs. You must pass at least one of share_url and follow_url,
 *   and you may pass both.
 *   - "svg": SVG definition, in the form of an <svg> tag and contained paths.
 *     The SVG should be minified, without any width/height/fill/etc.
 *   - "svgfile": Path to an SVG file, as an alternative to passing "svg".
 *     Defaults to `$name.min.svg` in the RRSSB library icons directory.
 *   - "share_url": URL for users to share your page using this social media
 *     site. Tokens [rrssb:*] will be replaced with values from the current
 *     page: url; title; image.
 *   - "follow_url": URL for users to follow you using this social media site.
 *     Token [rrssb:username] will be replaced with the user name you
 *     configured for this social media site.
 *   - "color": Background color to use for this button.
 *   - "color_hover": Background color to use for this button on hover.
 *   - "text": Text to use for this button. Defaults to the button name.
 *   - "popup": Whether to use the popup class for this button. Defaults to
 *     TRUE.
 *
 * @see rrssb_rrssb_buttons()
 */
function hook_rrssb_buttons() {
  return [];
}

/**
 * Alter the configuration for social share buttons provided by other modules.
 *
 * @param array $buttons
 *   Existing button configuration, see @hook_rrssb_buttons.
 */
function hook_rrssb_buttons_alter(&$buttons) {
  // Set the email button follow action to link to the simplenews subscribe
  // page.
  if (\Drupal::service('module_handler')->moduleExists('simplenews')) {
    $buttons['email']['follow_url'] = '/newsletter/subscriptions';
    $buttons['email']['title_follow'] = 'subscribe';
  }
}
