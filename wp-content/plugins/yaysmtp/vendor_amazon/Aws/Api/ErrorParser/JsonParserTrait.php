<?php

namespace YaySMTP\Aws3\Aws\Api\ErrorParser;

use YaySMTP\Aws3\Aws\Api\Parser\PayloadParserTrait;
use YaySMTP\Aws3\Psr\Http\Message\ResponseInterface;
/**
 * Provides basic JSON error parsing functionality.
 */
trait JsonParserTrait
{
    use PayloadParserTrait;
    private function genericHandler(\YaySMTP\Aws3\Psr\Http\Message\ResponseInterface $response)
    {
        $code = (string) $response->getStatusCode();
        return ['request_id' => (string) $response->getHeaderLine('x-amzn-requestid'), 'code' => null, 'message' => null, 'type' => $code[0] == '4' ? 'client' : 'server', 'parsed' => $this->parseJson($response->getBody())];
    }
}
