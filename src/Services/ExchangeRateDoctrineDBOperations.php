<?php

namespace App\Services;

use App\Entity\ExchangeRate;
use App\Repository\ExchangeRateRepository;
use App\Services\Interfaces\ExchangeRateDBOperationsImpl;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Doctrine implementation of exchange rate DB operations.
 * Class ExchangeRateDoctrineDBOperations
 * @package App\Services
 */
class ExchangeRateDoctrineDBOperations implements ExchangeRateDBOperationsImpl {

  private $em;
  private $exchangeRateRepository;

  public function __construct(EntityManagerInterface $em, ExchangeRateRepository $exchangeRateRepository) {
    $this->em = $em;
    $this->exchangeRateRepository = $exchangeRateRepository;
  }

  /**
   * Save exchange rates in DB of given base currency.
   *
   * @param String $baseCurrency
   * @param array $rates
   * @param \DateTime $date
   * @return mixed|void
   */
  public function saveRates(String $baseCurrency, array $rates, \DateTime $date) {
    foreach ($rates as $targetCurrency => $rate) {
      $exchangeRate = $this->findRate($baseCurrency, $targetCurrency, $date);
      if (!$exchangeRate) {
        $exchangeRate = new ExchangeRate();
      }
      $exchangeRate->setBaseCurrency($baseCurrency);
      $exchangeRate->setTargetCurrency($targetCurrency);
      $exchangeRate->setDate($date);
      $exchangeRate->setRate($rate);
      $this->em->persist($exchangeRate);
    }
    $this->em->flush();
  }

  /**
   * Find exchange rate based on given parameters.
   *
   * @param String $baseCurrency
   * @param String $targetCurrency
   * @param \DateTime $date
   * @return mixed|object|null
   */
  public function findRate(String $baseCurrency, String $targetCurrency, \DateTime $date) {
    return $this->exchangeRateRepository->findRate($baseCurrency, $targetCurrency, $date);
  }

  /**
   * Get today's exchange rates of given base currency.
   *
   * @param String $baseCurrency
   * @param array $rates
   * @return array
   * @throws \Exception
   */
  public function getTodayRates(String $baseCurrency, array $rates = array()) : array {
    $rates = $this->exchangeRateRepository->findAllOfToday($baseCurrency, $rates);
    return $rates;
  }
}