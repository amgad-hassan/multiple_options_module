<?php

namespace Drupal\multiple_options_field\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'multiple_options' field type.
 *
 * @FieldType(
 *   id = "multiple_options",
 *   label = @Translation("Multiple Options"),
 *   description = @Translation("A field type for selecting multiple options with conditional size selection."),
 *   default_widget = "multiple_options_widget",
 *   default_formatter = "multiple_options_formatter"
 * )
 */
class MultipleOptionsItem extends FieldItemBase {
  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'value' => [
          'type' => 'text',
          'size' => 'normal',
          'not null' => FALSE,
        ],
      ],
    ];
  }

    /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('options')->getValue();
    return empty($value);
  }

  /**
   * {@inheritdoc}
   */
  public function setValue($values, $notify = TRUE) {
    $this->set('options', $values);
    parent::setValue($values, $notify);
  }

  /**
   * {@inheritdoc}
   */
  public function getValue() {
    return $this->get('options')->getValue();
  }

    /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['value'] = DataDefinition::create('string')
      ->setLabel(t('Serialized Options'));

    return $properties;
  }
}