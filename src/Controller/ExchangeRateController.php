<?php

namespace App\Controller;

use App\Entity\ExchangeRate;
use App\Form\ExchangeRateType;
use App\Repository\ExchangeRateRepository;
use App\Services\ExchangeRateProviderImpl;
use App\Services\Interfaces\ExchangeRateDBOperationsImpl;
use App\Services\Interfaces\ExchangeRateManagerImpl;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package App\Controller
 * @Route("/rates")
 */
class ExchangeRateController extends BaseController
{
   /**
    * Rates index page to display all the exchange rates of base currency.
    * @Route("/", name="exchange_rate_index", methods={"GET"})
    * @param ExchangeRateDBOperationsImpl $exchangeRateDBOperations
    * @return Response
    */
    public function index(ExchangeRateDBOperationsImpl $exchangeRateDBOperations): Response
    {
        return $this->render('exchange_rate/index.html.twig', [
            'exchange_rates' => $exchangeRateDBOperations->getTodayRates($this->getBaseCurrency()),
        ]);
    }

   /**
    * Page to refresh the given or all the exchange rates.
    * @Route("/refresh-rates/{currency}", name="exchange_rate_refresh", methods={"GET"}, defaults={"currency" = ""})
    * @param ExchangeRateManagerImpl $exchangeRateManager
    * @param Request $request
    * @return Response
    */
    public function refreshRates(ExchangeRateManagerImpl $exchangeRateManager, Request $request): Response {
      $currency = $request->get('currency');
      $currencies = array();
      if ($currency != '') {
        $currencies = explode(",", $currency);
      }
      $exchangeRateManager->refreshTodayRates($this->getBaseCurrency(), $currencies);
      $successMessage = 'Exchange rates has been refreshed successfully.';
      if (count($currencies)) {
        $successMessage = 'Exchange rate of '.$currency.' has been refreshed successfully.';
      }
      $this->addFlash(self::$FLASH_SUCCESS_TAG, $successMessage);
      return $this->redirectToRoute('exchange_rate_index');
    }

   /**
    * Page to add new exchange rate manually.
    * @Route("/new", name="exchange_rate_new", methods={"GET","POST"})
    * @param Request $request
    * @param ExchangeRateRepository $exchangeRateRepository
    * @return Response
    */
    public function new(Request $request, ExchangeRateRepository $exchangeRateRepository): Response
    {
        $exchangeRate = new ExchangeRate();
        $form = $this->createForm(ExchangeRateType::class, $exchangeRate);
        $exchangeRate->setBaseCurrency($this->getBaseCurrency());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($exchangeRateRepository->findRate($exchangeRate->getBaseCurrency(), $exchangeRate->getTargetCurrency(), $exchangeRate->getDate()) == NULL) {
              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($exchangeRate);
              $entityManager->flush();
              $this->addFlash(self::$FLASH_SUCCESS_TAG, 'Exchange rate has been added successfully.');
            }
            else {
              $this->addFlash(self::$FLASH_ERROR_TAG, 'Exchange rate already exists in database.');
            }

            return $this->redirectToRoute('exchange_rate_index');
        }

        return $this->render('exchange_rate/new.html.twig', [
            'exchange_rate' => $exchangeRate,
            'form' => $form->createView(),
        ]);
    }

   /**
    * Page to edit the exchange rate manually.
    * @Route("/{id}/edit", name="exchange_rate_edit", methods={"GET","POST"})
    * @param Request $request
    * @param ExchangeRate $exchangeRate
    * @return Response
    */
    public function edit(Request $request, ExchangeRate $exchangeRate): Response
    {
        $form = $this->createForm(ExchangeRateType::class, $exchangeRate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(self::$FLASH_SUCCESS_TAG, 'Exchange rate has been edited successfully.');

            return $this->redirectToRoute('exchange_rate_index');
        }

        return $this->render('exchange_rate/edit.html.twig', [
            'exchange_rate' => $exchangeRate,
            'form' => $form->createView(),
        ]);
    }

   /**
    * Page to delete the exchange rate.
    * @Route("/{id}", name="exchange_rate_delete", methods={"DELETE"})
    * @param Request $request
    * @param ExchangeRate $exchangeRate
    * @return Response
    */
    public function delete(Request $request, ExchangeRate $exchangeRate): Response
    {
        if ($this->isCsrfTokenValid('delete'.$exchangeRate->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($exchangeRate);
            $entityManager->flush();
            $this->addFlash(self::$FLASH_SUCCESS_TAG, 'Exchange rate has been deleted successfully.');
        }

        return $this->redirectToRoute('exchange_rate_index');
    }
}
