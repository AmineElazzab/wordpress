<?php

namespace YaySMTP\Aws3\Aws\Handler\GuzzleV5;

use YaySMTP\Aws3\GuzzleHttp\Stream\StreamDecoratorTrait;
use YaySMTP\Aws3\GuzzleHttp\Stream\StreamInterface as GuzzleStreamInterface;
use YaySMTP\Aws3\Psr\Http\Message\StreamInterface as Psr7StreamInterface;
/**
 * Adapts a PSR-7 Stream to a Guzzle 5 Stream.
 *
 * @codeCoverageIgnore
 */
class GuzzleStream implements \YaySMTP\Aws3\GuzzleHttp\Stream\StreamInterface
{
    use StreamDecoratorTrait;
    /** @var Psr7StreamInterface */
    private $stream;
    public function __construct(\YaySMTP\Aws3\Psr\Http\Message\StreamInterface $stream)
    {
        $this->stream = $stream;
    }
}
