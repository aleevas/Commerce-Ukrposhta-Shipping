<?php

namespace Drupal\commerce_ukrposhta\Plugin\Field\FieldType;

use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\TypedData\MapDataDefinition;

/**
 * Plugin implementation of the 'ukrposhta' field type.
 *
 * @FieldType(
 *   id = "ukrposhta",
 *   label = @Translation("Ukrposhta"),
 *   category = @Translation("Address"),
 *   description = @Translation("Field containing Ukrposhta address"),
 *   default_widget = "ukrposhta_default",
 *   default_formatter = "ukrposhta_default"
 * )
 */
class UkrposhtaItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultStorageSettings() {
    return [
      'max_length' => 36,
    ] + parent::defaultStorageSettings();
  }

  /**
   * {@inheritdoc}
   */
  public static function mainPropertyName() {
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['city'] = DataDefinition::create('string')
      ->setLabel(t('City'));

    $properties['postoffice'] = DataDefinition::create('string')
      ->setLabel(t('Postoffice'));

    $properties['city_data'] = MapDataDefinition::create()
      ->setLabel(t('Serialized City Data'));

    $properties['postoffice_data'] = MapDataDefinition::create()
      ->setLabel(t('Serialized Postoffice Data'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = [
      'columns' => [
        'city' => [
          'description' => 'The city ref.',
          'type' => 'varchar',
          'length' => (int) $field_definition->getSetting('max_length'),
        ],
        'postoffice' => [
          'description' => 'The postoffice ref.',
          'type' => 'varchar',
          'length' => (int) $field_definition->getSetting('max_length'),
        ],
        'city_data' => [
          'description' => 'Serialized City Data.',
          'type' => 'blob',
          'not null' => TRUE,
          'serialize' => TRUE,
        ],
        'postoffice_data' => [
          'description' => 'Serialized Postoffice Data.',
          'type' => 'blob',
          'not null' => TRUE,
          'serialize' => TRUE,
        ],
      ],
    ];

    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public function getConstraints() {
    $constraints = parent::getConstraints();

    if ($max_length = $this->getSetting('max_length')) {
      $constraint_manager = Drupal::typedDataManager()
        ->getValidationConstraintManager();
      $constraints[] = $constraint_manager->create('ComplexData', [
        'city' => [
          'Length' => [
            'max' => $max_length,
            'maxMessage' => $this->t('%name: may not be longer than @max characters.', [
              '%name' => $this->getFieldDefinition()->getLabel(),
              '@max' => $max_length,
            ]),
          ],
        ],
      ]);
    }

    return $constraints;
  }

  /**
   * {@inheritdoc}
   */
  public static function generateSampleValue(FieldDefinitionInterface $field_definition) {
    $random = new Random();
    $values['city'] = $random->word(mt_rand(1, $field_definition->getSetting('max_length')));
    return $values;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $city = $this->get('city')->getValue();
    $postoffice = $this->get('postoffice')->getValue();

    return $city === NULL || $city === '' || $postoffice === NULL || $postoffice === '';
  }

}
