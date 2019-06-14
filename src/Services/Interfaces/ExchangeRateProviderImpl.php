<?php

namespace App\Services\Interfaces;

/**
 * Interface of Exchange rate provider.
 * Interface ExchangeRateProviderImpl
 * @package App\Services\Interfaces
 */
interface ExchangeRateProviderImpl {
  /**
   * This method should return an array of latest exchanges rates of today.
   * @param String $baseCurrency
   * @param array $targetCurrencies
   * @return array
   */
  public function getTodayRates(String $baseCurrency, array $targetCurrencies = array()): array;
}