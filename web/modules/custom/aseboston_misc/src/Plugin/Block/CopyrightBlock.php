<?php

namespace Drupal\aseboston_misc\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Provides a 'Copyright' Block.
 *
 * @Block(
 *   id = "aseboston_misc_copyright_block",
 *   admin_label = @Translation("Copyright"),
 *   category = @Translation("Copyright"),
 * )
 */
class CopyrightBlock extends BlockBase {

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
    $config = \Drupal::config('system.site');
    $year = date("Y");
    $siteName = $config->get('name');
    $optionsLink = [
      'attributes' => [
        'class' => [],
        'rel' => ['noopener'],
        'target' => ['_blank'],
        'title' => $this->t('Plasmático Media Lab'),
      ],
      'absolute'   => TRUE,
    ];
    $developerLink = Link::fromTextAndUrl($this->t('Plasmático Media Lab'), Url::fromUri('https://plasmaticocr.com', $optionsLink))->toString();
    $block = [
      'copyright' => [
        '#type' => "markup",
        '#markup' => "Copyright ©️ {$year}  {$siteName} | {$this->t('Developed by')}: {$developerLink}",
      ],
    ];
    return $block;
  }

}
