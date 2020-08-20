<?php

namespace Drupal\commerce_ukrposhta\Form;

use Drupal\commerce_ukrposhta\Service\UkrposhtaManagerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure Commerce Ukrposhta settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * UkrposhtaManager definition.
   *
   * @var \Drupal\commerce_ukrposhta\Service\UkrposhtaManagerInterface
   */
  protected $ukrposhtaManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->ukrposhtaManager = $container->get('commerce.ukrposhta.manager');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'commerce_ukrposhta_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['commerce_ukrposhta.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('commerce_ukrposhta.settings');

    $form['api_information'] = [
      '#type' => 'details',
      '#title' => $this->t('API information'),
      '#description' => $this->isCredentialsProvided() ? $this->t('Please update your Ukrposhta API information.') : $this->t('Please fill in your Ukrposhta API information.'),
      '#weight' => $this->isCredentialsProvided() ? 10 : -10,
      '#open' => !$this->isCredentialsProvided(),
    ];

    $form['api_information']['api_bearer_uuid'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('API Bearer Uuid'),
      '#description' => $this->t('Please enter your Ukrposhta Bearer Uuid. This information should be in your contract with Ukrposhta company'),
      '#default_value' => $config->get('api_bearer_uuid'),
    ];

    $form['api_information']['api_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API request Url'),
      '#description' => $this->t('Ukrposhta API request Url'),
      '#default_value' => $config->get('api_url') ?: UkrposhtaManagerInterface::API_URL,
      '#disabled' => TRUE,
    ];

    $form['language'] = [
      '#type' => 'select',
      '#title' => $this->t('Language'),
      '#options' => [
        'ua' => $this->t('Ukrainian'),
        'ru' => $this->t('Russian'),
      ],
      '#description' => $this->t('Please select an ukrposhta language. By default Ukrainian language will be used here.'),
      '#default_value' => $config->get('language') ?? $this->ukrposhtaManager->getLanguage(),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    // @todo make a test connection request to API.
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('commerce_ukrposhta.settings')
      ->set('api_bearer_uuid', $form_state->getValue('api_bearer_uuid'))
      ->set('api_url', $form_state->getValue('api_url'))
      ->set('language', $form_state->getValue('language'))
      ->save();
  }

  /**
   * Checks if provided enough data to connect to Ukrposhta.
   *
   * @return bool
   *   TRUE if provided enough information to connect, FALSE otherwise.
   */
  protected function isCredentialsProvided() {
    $config = $this->config('commerce_ukrposhta.settings');
    return !empty($config->get('api_bearer_uuid'));
  }

}
