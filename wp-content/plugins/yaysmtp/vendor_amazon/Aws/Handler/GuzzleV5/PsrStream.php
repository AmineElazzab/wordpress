<?php

namespace YaySMTP\Aws3\Aws\Handler\GuzzleV5;

use YaySMTP\Aws3\GuzzleHttp\Stream\StreamDecoratorTrait;
use YaySMTP\Aws3\GuzzleHttp\Stream\StreamInterface as GuzzleStreamInterface;

/**
 * Adapts a Guzzle 5 Stream to a PSR-7 Stream.
 *
 * @codeCoverageIgnore
 */
class PsrStream implements \YaySMTP\Aws3\Psr\Http\Message\StreamInterface {
  use StreamDecoratorTrait;
  /** @var GuzzleStreamInterface */
  private $stream;
  public function __construct(\YaySMTP\Aws3\GuzzleHttp\Stream\StreamInterface $stream) {
    $this->stream = $stream;
  }
  public function rewind() {
    $this->stream->seek(0);
  }
  public function getContents() {
    return $this->stream->getContents();
  }
}
