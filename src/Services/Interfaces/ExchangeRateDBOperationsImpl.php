<?php

namespace App\Services\Interfaces;

/**
 * Interface of Exchange rate DB Operations.
 * Interface ExchangeRateDBOperationsImpl
 * @package App\Services\Interfaces
 */
interface ExchangeRateDBOperationsImpl {
  /**
   * This method should save the given exchange rates of base currency in DB.
   * @param String $baseCurrency
   * @param array $rates
   * @param \DateTime $date
   * @return mixed
   */
  public function saveRates(String $baseCurrency, array $rates, \DateTime $date);

  /**
   * This method should find if there is exchange rate available in DB with given criteria
   * @param String $baseCurrency
   * @param String $targetCurrency
   * @param \DateTime $date
   * @return mixed
   */
  public function findRate(String $baseCurrency, String $targetCurrency, \DateTime $date);

  /**
   * This method should return today's exchange rates of given base currency.
   * If $rates are provided it should only return exchange rates of given currencies.
   *
   * @param String $baseCurrency
   * @param array $rates
   * @return array
   */
  public function getTodayRates(String $baseCurrency, array $rates = array()) : array;
}