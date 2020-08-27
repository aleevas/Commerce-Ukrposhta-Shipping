<?php

namespace Drupal\commerce_ukrposhta\Plugin\Field\FieldWidget;

use Drupal\commerce_ukrposhta\Service\UkrposhtaManagerInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'ukrposhta_default' widget.
 *
 * @FieldWidget(
 *   id = "ukrposhta_default",
 *   module = "commerce_ukrposhta",
 *   label = @Translation("Ukrposhta"),
 *   field_types = {
 *     "ukrposhta"
 *   }
 * )
 */
class UkrposhtaDefaultWidget extends WidgetBase {

  /**
   * UkrposhtaManager definition.
   *
   * @var \Drupal\commerce_ukrposhta\Service\UkrposhtaManagerInterface
   */
  protected $ukrposhtaManager;

  /**
   * Constructs a UkrposhtaDefaultWidget object.
   *
   * @param string $plugin_id
   *   The plugin_id for the widget.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the widget is associated.
   * @param array $settings
   *   The widget settings.
   * @param array $third_party_settings
   *   Any third party settings.
   * @param \Drupal\commerce_ukrposhta\Service\UkrposhtaManagerInterface $ukrposhta_manager
   *   Ukrposhta Manager.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, array $third_party_settings, UkrposhtaManagerInterface $ukrposhta_manager) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);
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
      $configuration['third_party_settings'],
      $container->get('commerce.ukrposhta.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    if ($items[$delta]->isEmpty()) {
      $items[$delta]->city = NULL;
      $items[$delta]->postoffice = NULL;
      $items[$delta]->city_data = NULL;
      $items[$delta]->postoffice_data = NULL;
    }

    $element = [
      '#type' => 'address_ukrposhta',
      '#default_value' => $items[$delta]->getValue(),
    ] + $element;

    return $element;
  }

}
