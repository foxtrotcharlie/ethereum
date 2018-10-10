<?php

namespace Drupal\ethereum\Controller;

use Drupal\Core\Controller\ControllerBase;
use Ethereum\Ethereum;
use Drupal\Core\Render\Markup;

/**
 * Controller routines for Ethereum routes.
 */
class EthereumController extends ControllerBase {

  /**
   * The Ethereum JsonRPC client.
   *
   * @var \Ethereum\Ethereum
   */
  protected $web3;

  // @todo Doesn't seem to be propagated to the library anymore.
  private $debug = TRUE;

  /**
   * Constructs a new EthereumController.
   *
   * @param string|NULL $host
   *    Ethereum node url.
   *
   * @throws \Exception
   */
  public function __construct(string $host = null) {
    if (!$host) {
      $host = \Drupal::service('ethereum.manager')->getCurrentServer()->getUrl();
    }
    $this->web3 = new Ethereum($host);
  }

  /**
   * Outputs function call logging as drupal message.
   *
   * This will output logs of all calls if $this->debug = TRUE.
   * Debug log will be emptied after call.
   *
   * @param bool $clear
   *   If empty is set we will empty the debug log.
   *   You may use to debug a single call.
   */
  public function debug($clear = FALSE) {
    $html = $this->web3->debugHtml;
    $this->web3->debugHtml = '';
    if (!$clear && $this->debug) {
      // Remove last HR Tag.
      $html = strrev(implode('', explode(strrev('<hr />'), strrev($html), 2)));
      $this->messenger()->addMessage(Markup::create($html), 'warning');
    }
  }

}
