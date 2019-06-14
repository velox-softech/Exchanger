<?php

namespace App\Command;

use App\Controller\BaseController;
use App\Services\Interfaces\ExchangeRateManagerImpl;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Command class to refresh today's exchange rates.
 *
 * Class VeloxExchangerRefreshRatesCommand
 * @package App\Command
 */
class VeloxExchangerRefreshRatesCommand extends Command
{
    protected static $defaultName = 'velox:exchanger:refresh-rates';
    private $exchangeRateManager;

    public function __construct(string $name = null, ExchangeRateManagerImpl $exchangeRateManager) {
      parent::__construct($name);
      $this->exchangeRateManager = $exchangeRateManager;
    }

    /**
     * Configure the command with description and arguments.
     */
    protected function configure() {
        $this
            ->setDescription('Updates today\'s exchange rates')
            ->addArgument('target_currencies', InputArgument::OPTIONAL, 'Comma separated currency codes to refresh, Defaults to all.');
    }

    /**
     * Implementation of command execution. Refreshing rates using service.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $io = new SymfonyStyle($input, $output);
        $targetCurrencies = $input->getArgument('target_currencies');
        $infoMessage = 'Refreshing today rates';
        $successMessage = "Exchanges rates been refreshed";
        if ($targetCurrencies) {
          $targetCurrencies = explode(",", $targetCurrencies);
          $infoMessage .= ' for '.implode(", ", $targetCurrencies);
          $successMessage = "Exchanges rates (".implode(", ", $targetCurrencies).") has been refreshed successfully.";
        }
        else {
          $targetCurrencies = array();
        }

        $this->exchangeRateManager->refreshTodayRates(BaseController::$DEFAULT_BASE_CURRENCY, $targetCurrencies, new \DateTime());
        $io->write($infoMessage);
        $io->success($successMessage);
    }
}
