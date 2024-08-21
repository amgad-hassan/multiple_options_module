<?php

namespace Drupal\multiple_options\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;

/**
 * Plugin implementation of the 'multiple_options_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "multiple_options_formatter",
 *   label = @Translation("Multiple Options Formatter"),
 *   field_types = {
 *     "multiple_options"
 *   }
 * )
 */
class MultipleOptionsFormatter extends FormatterBase implements ContainerFactoryPluginInterface {

  protected $logger;

  public function __construct($plugin_id, $plugin_definition, $field_definition, array $settings, $label, $view_mode, array $third_party_settings, LoggerChannelFactoryInterface $logger) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
    $this->logger = $logger->get('multiple_options');
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('logger.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $value = $item->value;
      if (is_string($value)) {
        $value = unserialize($value);
      }

      foreach ($value as $key => $val) {
        if (strpos($key, 'option_') === 0 && strpos($key, '_size') === false && !empty($val)) {
          $option_number = str_replace('option_', '', $key);
          $size_key = 'option_' . $option_number . '_size';
          if (isset($value[$size_key]) && !empty($value[$size_key])) {
            $size_value = ucfirst($value[$size_key]); 
            $elements[$delta][] = [
              '#markup' => $this->t('Option @number: @size', ['@number' => $option_number, '@size' => $size_value]) . '<br>',
            ];
          }
        }
      }
    }

    return $elements;
  }
}