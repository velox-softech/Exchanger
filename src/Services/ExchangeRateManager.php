<?php

namespace App\Services;

use App\Services\Interfaces\ExchangeRateDBOperationsImpl;
use App\Services\Interfaces\ExchangeRateManagerImpl;
use App\Services\Interfaces\ExchangeRateProviderImpl;

/**
 * Implementation of Exchange rate manager .
 * Class ExchangeRateManager
 * @package App\Services
 */
class ExchangeRateManager implements ExchangeRateManagerImpl {

  private $exchangeRateProvider = NULL;
  private $exchangeRateDBOperations = NULL;

  public function __construct(ExchangeRateProviderImpl $exchangeRateProvider, ExchangeRateDBOperationsImpl $exchangeRateDBOperations) {
    $this->exchangeRateProvider = $exchangeRateProvider;
    $this->exchangeRateDBOperations = $exchangeRateDBOperations;
  }

  /**
   * Refresh today's exchange rates of given base currency.
   * @param String $baseCurrency
   * @param array $targetCurrencies
   * @return mixed|void
   * @throws \Exception
   */
  public function refreshTodayRates(String $baseCurrency, array $targetCurrencies = array()) {
    $rates = $this->exchangeRateProvider->getTodayRates($baseCurrency, $targetCurrencies);
    $this->exchangeRateDBOperations->saveRates($baseCurrency, $rates, new \DateTime());
  }
}