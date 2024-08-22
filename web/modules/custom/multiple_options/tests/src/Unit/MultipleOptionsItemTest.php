<?php

namespace Drupal\Tests\multiple_options\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\multiple_options\Plugin\Field\FieldType\MultipleOptionsItem;

class MultipleOptionsItemTest extends UnitTestCase {

  public function testPropertyDefinitions() {
    $field_definition = $this->getMockBuilder('Drupal\Core\Field\FieldStorageDefinitionInterface')->getMock();
    $properties = MultipleOptionsItem::propertyDefinitions($field_definition);

    $this->assertArrayHasKey('value', $properties);
    $this->assertInstanceOf('Drupal\Core\TypedData\DataDefinition', $properties['value']);
  }

  public function testSchema() {
    $field_definition = $this->getMockBuilder('Drupal\Core\Field\FieldStorageDefinitionInterface')->getMock();
    $schema = MultipleOptionsItem::schema($field_definition);

    $this->assertArrayHasKey('columns', $schema);
    $this->assertArrayHasKey('value', $schema['columns']);
  }
}