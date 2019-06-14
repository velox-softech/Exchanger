<?php

namespace App\Repository;

use App\Entity\ExchangeRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Exchange Rate repository to execute DB related queries.
 * Class ExchangeRateRepository
 * @package App\Repository
 */
class ExchangeRateRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry) {
        parent::__construct($registry, ExchangeRate::class);
    }

   /**
    * Find all the exchanges of today.
    * @param $baseCurrency
    * @param array $currencies
    * @return array
    * @throws \Exception
    */
    public function findAllOfToday($baseCurrency, $currencies = array()) {
      $criteria = array(
        'baseCurrency' => $baseCurrency,
        'date' =>  new \DateTime()
      );
      if (count($currencies) > 0) {
        $criteria['targetCurrency'] = $currencies;
      }
      return $this->findBy($criteria, array('targetCurrency' => 'ASC'));
    }

   /**
    * Return exchange rate based on base currency, target currency and date.
    * @param $baseCurrency
    * @param $targetCurrency
    * @param $date
    * @return object|null
    */
    public function findRate($baseCurrency, $targetCurrency, $date)  {
      $rate = $this->findOneBy(array(
        'baseCurrency' => $baseCurrency,
        'targetCurrency' => $targetCurrency,
        'date' => $date,
      ));

      return $rate;
    }

}
