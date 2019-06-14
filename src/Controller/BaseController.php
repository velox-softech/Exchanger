<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Base Controller to extend all controller of the app.
 * Class BaseController
 * @package App\Controller
 */
class BaseController extends AbstractController {
  static $FLASH_SUCCESS_TAG = "success"; // Flash success tag
  static $FLASH_ERROR_TAG = "error"; // Flash error tag

  static $DEFAULT_BASE_CURRENCY = "USD"; // Base currency to manage exchange rates.

  /**
   * Get base currency of the app
   * @return string
   */
  public function getBaseCurrency() {
    return self::$DEFAULT_BASE_CURRENCY;
  }
}
