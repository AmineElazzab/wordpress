<?php

namespace YaySMTP\Aws3\Aws\Api\Parser;

use YaySMTP\Aws3\Aws\Api\Service;
use YaySMTP\Aws3\Aws\Api\StructureShape;
use YaySMTP\Aws3\Psr\Http\Message\ResponseInterface;
/**
 * @internal Implements REST-JSON parsing (e.g., Glacier, Elastic Transcoder)
 */
class RestJsonParser extends \YaySMTP\Aws3\Aws\Api\Parser\AbstractRestParser
{
    use PayloadParserTrait;
    /** @var JsonParser */
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
    protected function payload(\YaySMTP\Aws3\Psr\Http\Message\ResponseInterface $response, \YaySMTP\Aws3\Aws\Api\StructureShape $member, array &$result)
    {
        $jsonBody = $this->parseJson($response->getBody());
        if ($jsonBody) {
            $result += $this->parser->parse($member, $jsonBody);
        }
    }
}
