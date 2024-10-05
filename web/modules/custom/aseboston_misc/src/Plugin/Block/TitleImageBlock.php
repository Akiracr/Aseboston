<?php

namespace Drupal\aseboston_misc\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\node\NodeInterface;
use Drupal\taxonomy\TermInterface;
use Drupal\webform\WebformInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Title Image' Block.
 *
 * @Block(
 *   id = "aseboston_misc_title_image_block",
 *   admin_label = @Translation("Title Image Block"),
 *   category = @Translation("Aseboston Misc"),
 * )
 */
class TitleImageBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The current route match service.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;


  /**
   * The renderer service.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * Constructs a new NodeImageBlock instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match service to get the current node.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service to handle entities.
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer service to render the image field.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RouteMatchInterface $route_match, EntityTypeManagerInterface $entity_type_manager, RendererInterface $renderer) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->routeMatch = $route_match;
    $this->entityTypeManager = $entity_type_manager;
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_route_match'),
      $container->get('entity_type.manager'),
      $container->get('renderer')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];

    // Get the current node from the route.
    $node = $this->routeMatch->getParameter('node');
    $webform = $this->routeMatch->getParameter('webform');
    $taxonomy = $this->routeMatch->getParameter('taxonomy_term');
    $current_route_name = \Drupal::service('current_route_match')->getRouteName();

    //ksm($current_route_name);

    // Make sure we have a node and it's of type NodeInterface.
    if ($node instanceof NodeInterface && $node->type->entity->id() == 'agreement') {
      $term = $node->field_category->entity;
      $render_image = $this->getHeaderImage($term);
      if (isset($render_image) && strlen($render_image) > 0) {
        $build['content'] = [
          '#markup' => $render_image,
        ];
      }
    }
    elseif ($node instanceof NodeInterface && $node->hasField('field_image')) {
      $render_image = $this->getHeaderImage($node);
      if (isset($render_image) && strlen($render_image) > 0) {
        $build['content'] = [
          '#markup' => $render_image,
        ];
      }
      else {
        $term = $node->field_section->entity;
        $render_image = $this->getHeaderImage($term);
        if (isset($render_image)) {
          $build['content'] = [
            '#markup' => $render_image,
          ];
        }
      }
    }
    elseif ($webform instanceof WebformInterface) {
      $terms = $this->entityTypeManager->getStorage('taxonomy_term')
        ->loadByProperties(['vid' => 'section', 'name' => 'Contacto']);
      $term = reset($terms);
      $render_image = $this->getHeaderImage($term);
      if (isset($render_image) && strlen($render_image) > 0) {
        $build['content'] = [
          '#markup' => $render_image,
        ];
      }
    }
    elseif ($taxonomy instanceof TermInterface) {
      $render_image = $this->getHeaderImage($taxonomy);
      if (isset($render_image)) {
        $build['content'] = [
          '#markup' => $render_image,
        ];
      }
    }
    elseif ($current_route_name == 'view.galleries.page_1') {
      $term = $this->getSectionByName('Galerias');
      if ($term !== FALSE) {
        $this->generateContent($build, $term);
      }
    }
    elseif ($current_route_name == 'entity.webform.canonical') {
      $term = $this->getSectionByName('Contacto');
      if ($term !== FALSE) {
        $this->generateContent($build, $term);
      }
    }
    else {
      $term = $this->getSectionByName('General');
      if ($term !== FALSE) {
        $this->generateContent($build, $term);
      }
    }
    return $build;
  }

  private function generateContent(&$build, $entity) {
    $render_image = $this->getHeaderImage($entity);
    if (isset($render_image) && strlen($render_image) > 0) {
      $build['content'] = [
        '#markup' => $render_image,
      ];
    }
  }

  private function getSectionByName($name) {
    $terms = $this->entityTypeManager->getStorage('taxonomy_term')
    ->loadByProperties(['vid' => 'section', 'name' => $name]);
    return reset($terms);
  }

  private function getHeaderImage($entity) {
    if (!$entity->get('field_image')->isEmpty()) {
      // Load the field image render array.
      $image = $entity->get('field_image')->view('title');
      // Render the image field and add it to the block output.
      return $this->renderer->render($image);
    }
    return NULL;
  }

}
