<?php

declare(strict_types=1);

namespace Drupal\Tests\content_translation\Functional\Views;

use Drupal\Tests\views_ui\Functional\UITestBase;

/**
 * Tests the views UI when content_translation is enabled.
 *
 * @group content_translation
 */
class ContentTranslationViewsUITest extends UITestBase {

  /**
   * Views used by this test.
   *
   * @var array
   */
  public static $testViews = ['test_view'];

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['content_translation'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Tests the views UI.
   */
  public function testViewsUI(): void {
    $this->drupalGet('admin/structure/views/view/test_view/edit');
    $this->assertSession()->titleEquals('Test view (Views test data) | Drupal');
  }

}
