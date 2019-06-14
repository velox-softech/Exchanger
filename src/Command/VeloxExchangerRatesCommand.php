<?php

namespace App\Command;

use App\Controller\BaseController;
use App\Services\Interfaces\ExchangeRateDBOperationsImpl;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Command Class to get the today's exchange rates in JSON dump
 *
 * Class VeloxExchangerRatesCommand
 * @package App\Command
 */
class VeloxExchangerRatesCommand extends Command
{
    protected static $defaultName = 'velox:exchanger:rates';
    private $exchangeRateDBOperations;

    public function __construct(string $name = null, ExchangeRateDBOperationsImpl $exchangeRateDBOperations) {
      parent::__construct($name);
      $this->exchangeRateDBOperations = $exchangeRateDBOperations;
    }

    /**
     * Configure the command with description and arguments.
     */
    protected function configure() {
        $this
            ->setDescription('Returns JSON dump of today\' s exchange rates.')
            ->addArgument('target_currencies', InputArgument::OPTIONAL, 'Comma separated currency codes to fetch, Defaults to all.');
    }

    /**
     * Implementation of command execution. Fetching rates using service and dumping JSON list.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $io = new SymfonyStyle($input, $output);
        $targetCurrencies = $input->getArgument('target_currencies');

        if ($targetCurrencies) {
          $targetCurrencies = explode(",", $targetCurrencies);
        }
        else {
          $targetCurrencies = array();
        }
        $exchangeRates = $this->exchangeRateDBOperations->getTodayRates(BaseController::$DEFAULT_BASE_CURRENCY, $targetCurrencies);
        if (count($exchangeRates) == 0) {
          $io->warning('Rates not found. Please execute ' . VeloxExchangerRefreshRatesCommand::getDefaultName(). ' to fetch latest exchange rates.');
          return;
        }
        $rates = array(
          'base_currency' => BaseController::$DEFAULT_BASE_CURRENCY,
          'rates' => array(),
        );
        foreach ($exchangeRates as $exchangeRate) {
          $rates['rates'][$exchangeRate->getTargetCurrency()] = $exchangeRate->getRate();
        }

        $io->write(json_encode($rates));
        $io->write("\n");
    }
}
