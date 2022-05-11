<?php

namespace YaySMTP\Aws3\Aws\Api\Parser;

use YaySMTP\Aws3\Aws\Api\Service;
use YaySMTP\Aws3\Aws\Result;
use YaySMTP\Aws3\Aws\CommandInterface;
use YaySMTP\Aws3\Psr\Http\Message\ResponseInterface;
/**
 * @internal Implements JSON-RPC parsing (e.g., DynamoDB)
 */
class JsonRpcParser extends \YaySMTP\Aws3\Aws\Api\Parser\AbstractParser
{
    use PayloadParserTrait;
    private $parser;
    /**
     * @param Service    $api    Service description
     * @param JsonParser $parser JSON body builder
     */
    public function __construct(\YaySMTP\Aws3\Aws\Api\Service $api, \YaySMTP\Aws3\Aws\Api\Parser\JsonParser $parser = null)
    {
        parent::__construct($api);
        $this->parser = $parser ?: new \YaySMTP\Aws3\Aws\Api\Parser\JsonParser();
    }
    public function __invoke(\YaySMTP\Aws3\Aws\CommandInterface $command, \YaySMTP\Aws3\Psr\Http\Message\ResponseInterface $response)
    {
        $operation = $this->api->getOperation($command->getName());
        $result = null === $operation['output'] ? null : $this->parser->parse($operation->getOutput(), $this->parseJson($response->getBody()));
        return new \YaySMTP\Aws3\Aws\Result($result ?: []);
    }
}
