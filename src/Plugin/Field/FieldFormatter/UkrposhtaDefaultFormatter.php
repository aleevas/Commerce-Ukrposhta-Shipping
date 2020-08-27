<?php

namespace Drupal\commerce_ukrposhta\Plugin\Field\FieldFormatter;

use Drupal\commerce_ukrposhta\Service\UkrposhtaManagerInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'ukrposhta_default' formatter.
 *
 * @FieldFormatter(
 *   id = "ukrposhta_default",
 *   label = @Translation("Ukrposhta"),
 *   field_types = {
 *     "ukrposhta"
 *   }
 * )
 */
class UkrposhtaDefaultFormatter extends FormatterBase {

  /**
   * UkrposhtaManager definition.
   *
   * @var \Drupal\commerce_ukrposhta\Service\UkrposhtaManagerInterface
   */
  protected $ukrposhtaManager;

  /**
   * Constructs a UkrposhtaDefaultFormatter object.
   *
   * @param string $plugin_id
   *   The plugin_id for the formatter.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the formatter is associated.
   * @param array $settings
   *   The formatter settings.
   * @param string $label
   *   The formatter label display setting.
   * @param string $view_mode
   *   The view mode.
   * @param array $third_party_settings
   *   Any third party settings.
   * @param \Drupal\commerce_ukrposhta\Service\UkrposhtaManagerInterface $ukrposhta_manager
   *   Ukrposhta Manager service.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, UkrposhtaManagerInterface $ukrposhta_manager) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
    $this->ukrposhtaManager = $ukrposhta_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('commerce.ukrposhta.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      // Implement default settings.
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return [
      // Implement settings form.
    ] + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    $language = $this->ukrposhtaManager->getLanguage();
    foreach ($items as $delta => $item) {
      // @todo check API response
      if ($language === UkrposhtaManagerInterface::LANGUAGE) {
        $city = $item->city_data['PO_LONG'] ?? NULL;
        $postoffice = $item->postoffice_data['PO_LONG'] ?? NULL;
      }
      else {
        $city = $item->city_data['PO_LONG_RU'] ?? NULL;
        $postoffice = $item->postoffice_data['PO_LONG_RU'] ?? NULL;
      }

      $elements[$delta]['city'] = [
        '#type' => 'html_tag',
        '#tag' => 'div',
        '#value' => $city,
        '#attributes' => [
          'class' => ['ukrposhta-city-item'],
        ],
      ];

      $elements[$delta]['postoffice'] = [
        '#type' => 'html_tag',
        '#tag' => 'div',
        '#value' => $postoffice,
        '#attributes' => [
          'class' => ['ukrposhta-postoffice-item'],
        ],
      ];
    }

    return $elements;
  }

}
