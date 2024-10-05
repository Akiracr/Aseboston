<?php

namespace Drupal\rrssb\Commands;

use Drush\Commands\DrushCommands;

/**
 * RRSSB drush commands.
 */
class RrssbCommands extends DrushCommands {

  /**
   * Regenerate rrssb.buttons.css in RRSSB+ library.
   *
   * @command rrssb:gen-css
   * @aliases rrssb-gen-css
   */
  public function genCss() {
    $css = "/* RRSSB+ per-button CSS. */
/* Not required when using the Drupal CMS integration module, which generates its own file. */
/* Generated from Drupal CMS module using 'drush rrssb-gen-css' */
";
    $css .= rrssb_calc_css(rrssb_button_config());
    $target = rrssb_library_path() . '/css/rrssb.buttons.css';
    file_put_contents($target, $css);
  }

}
