<?php

declare(strict_types=1);

namespace Drupal\Tests\field\Functional\EntityReference;

use Drupal\Tests\SchemaCheckTestTrait;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\node\Entity\Node;
use Drupal\Tests\BrowserTestBase;

/**
 * Tests entity reference field default values storage in CMI.
 *
 * @group entity_reference
 */
class EntityReferenceFieldDefaultValueTest extends BrowserTestBase {

  use SchemaCheckTestTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['field_ui', 'node'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * A user with permission to administer content types, node fields, etc.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Create default content type.
    $this->drupalCreateContentType(['type' => 'reference_content']);
    $this->drupalCreateContentType(['type' => 'referenced_content']);

    // Create admin user.
    $this->adminUser = $this->drupalCreateUser([
      'access content',
      'administer content types',
      'administer node fields',
      'administer node form display',
      'bypass node access',
    ]);
    $this->drupalLogin($this->adminUser);
  }

  /**
   * Tests that default values are correctly translated to UUIDs in config.
   */
  public function testEntityReferenceDefaultValue(): void {
    // Create a node to be referenced.
    $referenced_node = $this->drupalCreateNode(['type' => 'referenced_content']);

    $field_name = $this->randomMachineName();
    $field_storage = FieldStorageConfig::create([
      'field_name' => $field_name,
      'entity_type' => 'node',
      'type' => 'entity_reference',
      'settings' => ['target_type' => 'node'],
    ]);
    $field_storage->save();
    $field = FieldConfig::create([
      'field_storage' => $field_storage,
      'bundle' => 'reference_content',
      'settings' => [
        'handler' => 'default',
        'handler_settings' => [
          'target_bundles' => ['referenced_content'],
          'sort' => ['field' => '_none'],
        ],
      ],
    ]);
    $field->save();

    // Set created node as default_value.
    $field_edit = [
      'set_default_value' => '1',
      'default_value_input[' . $field_name . '][0][target_id]' => $referenced_node->getTitle() . ' (' . $referenced_node->id() . ')',
    ];
    $this->drupalGet('admin/structure/types/manage/reference_content/fields/node.reference_content.' . $field_name);
    $this->submitForm($field_edit, 'Save settings');

    // Check that default value is selected in default value form.
    $this->drupalGet('admin/structure/types/manage/reference_content/fields/node.reference_content.' . $field_name);
    $this->assertSession()->responseContains('name="default_value_input[' . $field_name . '][0][target_id]" value="' . $referenced_node->getTitle() . ' (' . $referenced_node->id() . ')');

    // Check if the ID has been converted to UUID in config entity.
    $config_entity = $this->config('field.field.node.reference_content.' . $field_name)->get();
    $this->assertTrue(isset($config_entity['default_value'][0]['target_uuid']), 'Default value contains target_uuid property');
    $this->assertEquals($referenced_node->uuid(), $config_entity['default_value'][0]['target_uuid'], 'Content uuid and config entity uuid are the same');
    // Ensure the configuration has the expected dependency on the entity that
    // is being used a default value.
    $this->assertEquals([$referenced_node->getConfigDependencyName()], $config_entity['dependencies']['content']);

    // Clear field definitions cache in order to avoid stale cache values.
    \Drupal::service('entity_field.manager')->clearCachedFieldDefinitions();

    // Create a new node to check that UUID has been converted to numeric ID.
    $new_node = Node::create(['type' => 'reference_content']);
    $this->assertEquals($new_node->get($field_name)->offsetGet(0)->target_id, $referenced_node->id());

    // Ensure that the entity reference config schemas are correct.
    $field_config = $this->config('field.field.node.reference_content.' . $field_name);
    $this->assertConfigSchema(\Drupal::service('config.typed'), $field_config->getName(), $field_config->get());
    $field_storage_config = $this->config('field.storage.node.' . $field_name);
    $this->assertConfigSchema(\Drupal::service('config.typed'), $field_storage_config->getName(), $field_storage_config->get());
  }

  /**
   * Tests that dependencies due to default values can be removed.
   *
   * @see \Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem::onDependencyRemoval()
   */
  public function testEntityReferenceDefaultConfigValue(): void {
    // Create a node to be referenced.
    $referenced_node_type = $this->drupalCreateContentType(['type' => 'referenced_config_to_delete']);
    $referenced_node_type2 = $this->drupalCreateContentType(['type' => 'referenced_config_to_preserve']);

    $field_name = $this->randomMachineName();
    $field_storage = FieldStorageConfig::create([
      'field_name' => $field_name,
      'entity_type' => 'node',
      'type' => 'entity_reference',
      'settings' => ['target_type' => 'node_type'],
      'cardinality' => FieldStorageConfig::CARDINALITY_UNLIMITED,
    ]);
    $field_storage->save();
    $field = FieldConfig::create([
      'field_storage' => $field_storage,
      'bundle' => 'reference_content',
      'settings' => [
        'handler' => 'default',
        'handler_settings' => [
          'sort' => ['field' => '_none'],
        ],
      ],
    ]);
    $field->save();

    // Set created node as default_value.
    $field_edit = [
      'set_default_value' => '1',
      'default_value_input[' . $field_name . '][0][target_id]' => $referenced_node_type->label() . ' (' . $referenced_node_type->id() . ')',
      'default_value_input[' . $field_name . '][1][target_id]' => $referenced_node_type2->label() . ' (' . $referenced_node_type2->id() . ')',
    ];
    $this->drupalGet('admin/structure/types/manage/reference_content/fields/node.reference_content.' . $field_name);
    $this->assertSession()->fieldExists("default_value_input[{$field_name}][0][target_id]");
    $this->assertSession()->fieldNotExists("default_value_input[{$field_name}][1][target_id]");
    $this->submitForm([], 'Add another item');
    $this->assertSession()->fieldExists("default_value_input[{$field_name}][1][target_id]");
    $this->submitForm($field_edit, 'Save settings');

    // Check that the field has a dependency on the default value.
    $config_entity = $this->config('field.field.node.reference_content.' . $field_name)->get();
    $this->assertContains($referenced_node_type->getConfigDependencyName(), $config_entity['dependencies']['config'], 'The node type referenced_config_to_delete is a dependency of the field.');
    $this->assertContains($referenced_node_type2->getConfigDependencyName(), $config_entity['dependencies']['config'], 'The node type referenced_config_to_preserve is a dependency of the field.');

    // Check that the field does not have a dependency on the default value
    // after deleting the node type.
    $referenced_node_type->delete();
    $config_entity = $this->config('field.field.node.reference_content.' . $field_name)->get();
    $this->assertNotContains($referenced_node_type->getConfigDependencyName(), $config_entity['dependencies']['config'], 'The node type referenced_config_to_delete not a dependency of the field.');
    $this->assertContains($referenced_node_type2->getConfigDependencyName(), $config_entity['dependencies']['config'], 'The node type referenced_config_to_preserve is a dependency of the field.');
  }

}
