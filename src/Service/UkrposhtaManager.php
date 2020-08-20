<?php

namespace Drupal\commerce_ukrposhta\Service;

use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Class UkrposhtaManager.
 */
class UkrposhtaManager implements UkrposhtaManagerInterface {

  /**
   * Api key.
   *
   * @var string
   */
  protected $apiBearerUuid;

  /**
   * API Url.
   *
   * @var string
   */
  protected $apiUrl;

  /**
   * Language.
   *
   * @var string
   */
  protected $language;

  /**
   * The configuration.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * Constructs a new UkrposhtaManager object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(
    ConfigFactoryInterface $config_factory
  ) {
    $this->config = $config_factory->get('commerce_ukrposhta.settings');
    // Set default settings for API.
    $this->setDefaults();
  }

  /**
   * Set default configurations for Ukrposhta API.
   */
  public function setDefaults() {
    $this->setApiBearerUuid($this->config->get('api_bearer_uuid'));
    $this->setApiUrl($this->config->get('api_url'));
    $this->setLanguage($this->config->get('language'));
  }

  /**
   * {@inheritdoc}
   */
  public function setApiBearerUuid(string $api_bearer_uuid): UkrposhtaManagerInterface {
    $this->apiBearerUuid = $api_bearer_uuid;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getApiBearerUuid(): string {
    return $this->apiBearerUuid;
  }

  /**
   * {@inheritdoc}
   */
  public function isConfigured(): bool {
    return (bool) $this->apiBearerUuid;
  }

  /**
   * {@inheritdoc}
   */
  public function setApiUrl($url = NULL): UkrposhtaManagerInterface {
    $this->apiUrl = $url ?: static::API_URL;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getApiUrl(): string {
    return $this->apiUrl ?: static::API_URL;
  }

  /**
   * {@inheritdoc}
   */
  public function setLanguage(string $language): UkrposhtaManagerInterface {
    $this->language = $language ?: static::LANGUAGE;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getLanguage(): string {
    return $this->language;
  }

}
