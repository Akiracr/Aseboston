<?php

namespace Drupal\aseboston_misc\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller to display Aseboston Index page.
 */
class AsebostonMiscController extends ControllerBase {

  public function index() {
     $element = array(
      '#markup' => '',
    );
    return $element;
  }

}
