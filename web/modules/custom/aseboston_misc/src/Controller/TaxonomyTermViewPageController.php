<?php

/**
 * @file
 * Contains \Drupal\aseboston_misc\Controller\TaxonomyTermViewPageController.
 */

namespace Drupal\aseboston_misc\Controller;

use Drupal\views\Routing\ViewPageController;
use Drupal\Core\Routing\RouteMatchInterface;

/**
 * Class TaxonomyTermViewPageController.
 *
 * @package Drupal\aseboston_misc\Controller
 */
class TaxonomyTermViewPageController extends ViewPageController {

  /**
   * {@inheritdoc}
   */
  public function handle($view_id, $display_id, RouteMatchInterface $route_match) {
    // Drupal Defaults.
    $view_id = 'termino_de_taxonomia';
    $display_id = 'page_1';

    // Entity of the requested term.
    $term = $route_match->getParameter('taxonomy_term');

    // Get the vid (vocabulary machine name) of the current term.
    $vid = $term->get('vid')->first()->getValue();
    $vid = $vid['target_id'];

    // If the vocabulary is 'categories_of_agreements'.
    if ($vid == 'categories_of_agreements') {
      // Change view.
      $display_id = 'page_2';
    }

    return parent::handle($view_id, $display_id, $route_match);
  }

}
