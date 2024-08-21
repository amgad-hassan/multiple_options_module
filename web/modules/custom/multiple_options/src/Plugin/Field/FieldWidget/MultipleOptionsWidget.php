<?php

namespace Drupal\multiple_options\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;

/**
 * Provides a widget for the Multiple Options field.
 *
 * @FieldWidget(
 *   id = "multiple_options_widget",
 *   label = @Translation("Multiple Options Widget"),
 *   field_types = {
 *     "multiple_options"
 *   }
 * )
 */
class MultipleOptionsWidget extends WidgetBase implements ContainerFactoryPluginInterface {

  protected $logger;

  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, array $third_party_settings, LoggerChannelFactoryInterface $logger) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);
    $this->logger = $logger->get('multiple_options');
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['third_party_settings'],
      $container->get('logger.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $value = $items[$delta]->value ?? '';
    $options_count = \Drupal::config('multiple_options_field.settings')->get('options_count');
    $field_name = $items->getName(); // Get the field name dynamically.

    // Unserialize the value if it's a string.
    if (is_string($value)) {
      $value = unserialize($value);
    }

    for ($i = 1; $i <= $options_count; $i++) {
      $element['option_' . $i] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Option @number', ['@number' => $i]),
        '#default_value' => $value['option_' . $i] ?? 0,
      ];

      $element['option_' . $i . '_size'] = [
        '#type' => 'select',
        '#options' => ['' => '- Select -', 'small' => 'Small', 'medium' => 'Medium', 'large' => 'Large'],
        '#default_value' => $value['option_' . $i . '_size'] ?? '',
        '#states' => [
          'visible' => [
            ':input[name="' . $field_name . '[' . $delta . '][option_' . $i . ']"]' => ['checked' => TRUE],
          ],
          'required' => [
            ':input[name="' . $field_name . '[' . $delta . '][option_' . $i . ']"]' => ['checked' => TRUE],
          ],
        ],
      ];
    }

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach ($values as $delta => $value) {
      $serialized = [];
      foreach ($value as $key => $val) {
        if (strpos($key, 'option_') === 0) {
          $serialized[$key] = $val;
        }
      }
      $values[$delta]['value'] = serialize($serialized);
      // Debugging statement
      $this->logger->debug('Massaged form values: @values', ['@values' => print_r($values, TRUE)]);
    }
    return $values;
  }
}