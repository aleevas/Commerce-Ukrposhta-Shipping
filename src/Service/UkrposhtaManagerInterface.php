<?php

namespace Drupal\commerce_ukrposhta\Service;

/**
 * Interface UkrposhtaManagerInterface.
 */
interface UkrposhtaManagerInterface {

  /**
   * Default Ukrposhta Language.
   */
  const LANGUAGE = 'ua';

  /**
   * Default Api Url for requests.
   */
  const API_URL = 'https://ukrposhta.ua/address-classifier-ws/';

  /**
   * Set api_bearer_uuid.
   *
   * @param string $api_bearer_uuid
   *   An authorization bearer uuid.
   *
   * @return \Drupal\commerce_ukrposhta\Service\UkrposhtaManagerInterface
   *   Self instance.
   */
  public function setApiBearerUuid(string $api_bearer_uuid): UkrposhtaManagerInterface;

  /**
   * Get api_bearer_uuid.
   *
   * @return string
   *   An authorization bearer uuid.
   */
  public function getApiBearerUuid(): string;

  /**
   * Check if Ukrposhta API configured.
   *
   * @return bool
   *   True if configured, otherwise False.
   */
  public function isConfigured(): bool;

  /**
   * Set API Url.
   *
   * @param string $url
   *   The Api Url for request to Ukrposhta.
   *
   * @return \Drupal\commerce_ukrposhta\Service\UkrposhtaManagerInterface
   *   Self instance.
   */
  public function setApiUrl($url): UkrposhtaManagerInterface;

  /**
   * Get API URL.
   *
   * @return string
   *   API Url.
   */
  public function getApiUrl(): string;

}
