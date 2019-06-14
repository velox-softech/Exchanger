<?php

namespace App\Services;

use App\Services\Interfaces\ExchangeRateProviderImpl;
use GuzzleHttp\Client;

/**
 * Exchange Generate API implementation of Exchange rate of provider
 * Class ExchangeGenerate
 * @package App\Services
 */
class ExchangeGenerate implements ExchangeRateProviderImpl {

  private $BASE_URL = "https://api.exchangeratesapi.io/";

  /**
   * Fetch today rates of given base currency.
   *
   * @param String $baseCurrency
   * @param array $targetCurrencies
   * @return array
   */
  public function getTodayRates(String $baseCurrency, array $targetCurrencies = array()): array {
    $client = new Client(['base_uri' => $this->BASE_URL]);
    $response = $client->get('latest', array(
      'query' => array(
        'base' => $baseCurrency,
        'symbols' => implode(",", $targetCurrencies),
      ),
    ));
    $apiResponse = json_decode($response->getBody()->getContents(), TRUE);
    return $apiResponse['rates'];
  }

}