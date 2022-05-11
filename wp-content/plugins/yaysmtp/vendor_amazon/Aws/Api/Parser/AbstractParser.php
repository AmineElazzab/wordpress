<?php

namespace YaySMTP\Aws3\Aws\Api\Parser;

use YaySMTP\Aws3\Aws\Api\Service;
use YaySMTP\Aws3\Aws\CommandInterface;
use YaySMTP\Aws3\Aws\ResultInterface;
use YaySMTP\Aws3\Psr\Http\Message\ResponseInterface;

/**
 * @internal
 */
abstract class AbstractParser {
  /** @var \Aws\Api\Service Representation of the service API*/
  protected $api;
  /**
   * @param Service $api Service description.
   */
  public function __construct(\YaySMTP\Aws3\Aws\Api\Service $api) {
    $this->api = $api;
  }
  /**
   * @param CommandInterface  $command  Command that was executed.
   * @param ResponseInterface $response Response that was received.
   *
   * @return ResultInterface
   */
  abstract public function __invoke(\YaySMTP\Aws3\Aws\CommandInterface $command, \YaySMTP\Aws3\Psr\Http\Message\ResponseInterface $response);
}
