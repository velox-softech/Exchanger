<?php

namespace App\Services\Interfaces;

/**
 * Interface of exchange rate manager of the app.
 * Interface ExchangeRateManagerImpl
 * @package App\Services\Interfaces
 */
interface ExchangeRateManagerImpl {
  /**
   * This method should refresh the today's exchange rates of given base currency.
   * If target currencies are provided it should only refreshes exchange rates of given currencies.
   *
   * @param String $baseCurrency
   * @param array $targetCurrencies
   * @return mixed
   */
  public function refreshTodayRates(String $baseCurrency, array $targetCurrencies = array());
}